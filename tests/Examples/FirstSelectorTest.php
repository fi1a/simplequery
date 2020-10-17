<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Выбирает первый совпавший элемент.
 */
class FirstSelectorTest extends TestCase
{
    /**
     * Найти первую строку таблицы.
     */
    public function testFirstSelector(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/FirstSelector.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/FirstSelector-result.html');
        $sq = new SimpleQuery($html);
        $sq('tr:first')->css('font-style', 'italic');
        $this->assertEquals($result, (string) $sq);
    }
}
