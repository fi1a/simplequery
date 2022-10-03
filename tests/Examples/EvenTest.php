<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Выбирает четные элементы.
 */
class EvenTest extends TestCase
{
    /**
     * Найти четные строки таблицы (индекс 0, 2, 4 и т.д.).
     */
    public function testEven(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/Even.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/Even-result.html');
        $sq = new SimpleQuery($html);
        $sq('tr')->even()->css('background-color', '#bbf');
        $this->assertEquals($result, (string) $sq);
        $this->assertCount(0, $sq('div')->even());
    }
}
