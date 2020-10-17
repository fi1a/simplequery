<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Выбрать все элементы индексом меньшим заданного.
 */
class LtSelectorTest extends TestCase
{
    /**
     * Получить колонки таблицы меньше 4-го индекса.
     */
    public function testLtSelector(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/LtSelector.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/LtSelector-result.html');
        $sq = new SimpleQuery($html);
        $sq('td:lt(4)')->css('backgroundColor', 'yellow');
        $this->assertEquals($result, (string) $sq);
    }
}
