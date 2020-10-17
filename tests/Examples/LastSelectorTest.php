<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Выбирает последний совпавший элемент.
 */
class LastSelectorTest extends TestCase
{
    /**
     * Найти последнюю строку таблицы.
     */
    public function testLastSelector(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/LastSelector.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/LastSelector-result.html');
        $sq = new SimpleQuery($html);
        $sq('tr:last')->css(['backgroundColor' => 'yellow', 'fontWeight' => 'bolder']);
        $this->assertEquals($result, (string) $sq);
    }
}
