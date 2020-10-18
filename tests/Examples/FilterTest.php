<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Сократите набор элементов до тех, которые соответствуют селектору.
 */
class FilterTest extends TestCase
{
    /**
     * Поменять цвет всем div'м, затем добавить border тем которые имеют класс "middle".
     */
    public function testFilter(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/Filter.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/Filter-result.html');
        $sq = new SimpleQuery($html);
        $sq('div')
            ->css('background', '#c8ebcc')
            ->filter('.middle')
            ->css('border-color', 'red');
        $this->assertEquals($result, (string) $sq);
    }
}
