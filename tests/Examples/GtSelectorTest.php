<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Выбрать все элементы с превышающим индексом.
 */
class GtSelectorTest extends TestCase
{
    /**
     * Получить все теги td больше индекса 4.
     */
    public function testGtSelector(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/GtSelector.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/GtSelector-result.html');
        $sq = new SimpleQuery($html);
        $sq('td:gt(4)')->css('backgroundColor', 'yellow');
        $this->assertEquals($result, (string) $sq);
    }
}
