<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Выбирает всех родителей набора элементов.
 */
class ParentsTest extends TestCase
{
    /**
     * Найти все родительские элементы тега b.
     */
    public function testParents(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/Parents.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/Parents-result.html');
        $sq = new SimpleQuery($html);
        $parentEls = implode(', ', $sq('b')->parents()->map(function ($element) {
            /**
             * @var \DOMNode $element
             */
            return $element->nodeName;
        })->getArrayCopy());
        $sq('b')->append('<strong>' . $parentEls . '</strong>');
        $this->assertEquals($result, (string) $sq);
    }
}
