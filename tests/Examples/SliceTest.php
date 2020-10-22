<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Возвращает набор элементов, заданного диапазоном индексов.
 */
class SliceTest extends TestCase
{
    /**
     * Выбрать div'ы со 2-го по 5-ый.
     */
    public function testSlice(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/Slice.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/Slice-result.html');
        $sq = new SimpleQuery($html);
        $sq('div')->slice(2, 5)->css('color', 'red');
        $this->assertEquals($result, (string) $sq);
    }
}
