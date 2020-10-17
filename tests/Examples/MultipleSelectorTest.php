<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Выбирает результаты всех указанных селекторов.
 */
class MultipleSelectorTest extends TestCase
{
    /**
     * Найти элементы подходящие под все три селектора.
     */
    public function testMultipleSelector(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/MultipleSelector.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/MultipleSelector-result.html');
        $sq = new SimpleQuery($html);
        $sq('div, span, p.myClass')->css('border', '3px solid red');
        $this->assertEquals($result, (string) $sq);
    }
}
