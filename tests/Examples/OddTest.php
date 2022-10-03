<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Выбирает нечетные элементы.
 */
class OddTest extends TestCase
{
    /**
     * Найти нечетные строки таблиц(индекс 1, 3, 5 и т.д.).
     */
    public function testOdd(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/Odd.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/Odd-result.html');
        $sq = new SimpleQuery($html);
        $sq('tr')->odd()->css('background-color', '#bbbbff');
        $this->assertEquals($result, (string) $sq);
        $this->assertCount(0, $sq('div')->odd());
    }
}
