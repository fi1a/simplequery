<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Возвращает родителей набора элементов.
 */
class ParentTest extends TestCase
{
    /**
     * Отобразить родителя каждого тега в body.
     */
    public function testParent(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/Parent.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/Parent-result.html');
        $sq = new SimpleQuery($html);
        $sq('html > body > *, body')->each(function ($element) use ($sq) {
            /**
             * @var \DOMNode $element
             */
            $parentTag = $sq($element)->parent()->get(0)->nodeName;
            $sq($element)->prepend($parentTag . ' > ');
        });
        $this->assertEquals($result, (string) $sq);
    }
}
