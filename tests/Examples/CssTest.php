<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Получите значение свойства стиля для первого элемента или
 * установить одно или несколько свойств CSS для каждого элемента.
 */
class CssTest extends TestCase
{
    /**
     * Установить цвет всем параграфам.
     */
    public function testCss(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/Css.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/Css-result.html');
        $sq = new SimpleQuery($html);
        $sq('p')->css('color', 'red');
        $this->assertEquals($result, (string) $sq);
    }
}
