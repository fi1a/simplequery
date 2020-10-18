<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Удалить атрибут у набора элементов.
 */
class RemoveAttrTest extends TestCase
{
    /**
     * Удалить атрибут "title".
     */
    public function testRemoveAttr(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/RemoveAttr.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/RemoveAttr-result.html');
        $sq = new SimpleQuery($html);
        $sq('input')->removeAttr('title');
        $this->assertEquals($result, (string) $sq);
    }
}
