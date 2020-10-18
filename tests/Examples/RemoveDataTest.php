<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Удалить данные у набора элементов.
 */
class RemoveDataTest extends TestCase
{
    /**
     * Удалить данные у набора элементов.
     */
    public function testRemoveData(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/RemoveData.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/RemoveData-result.html');
        $sq = new SimpleQuery($html);
        $sq('span')->eq(0)->text($sq('div')->data('test1'));
        $sq('div')->data('test1', 'VALUE-1');
        $sq('div')->data('test2', 'VALUE-2');
        $sq('span')->eq(1)->text($sq('div')->data('test1'));
        $sq('div')->removeData('test1');
        $sq('span')->eq(2)->text($sq('div')->data('test1'));
        $sq('span')->eq(3)->text($sq('div')->data('test2'));
        $this->assertEquals($result, (string) $sq);
    }
}
