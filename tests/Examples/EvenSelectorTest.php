<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Выбирает четные элементы.
 */
class EvenSelectorTest extends TestCase
{
    /**
     * Найти четные строки таблицы (индекс 0, 2, 4 и т.д.).
     */
    public function testEvenSelector(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/EvenSelector.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/EvenSelector-result.html');
        $sq = new SimpleQuery($html);
        $sq('tr:even')->css('background-color', '#bbf');
        $this->assertEquals($result, (string) $sq);
    }
}
