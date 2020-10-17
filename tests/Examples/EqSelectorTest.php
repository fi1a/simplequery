<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Выбрать элемент с указанным индексом.
 */
class EqSelectorTest extends TestCase
{
    /**
     * Найти тег td с индексом равным 2-м.
     */
    public function testEqSelector(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/EqSelector.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/EqSelector-result.html');
        $sq = new SimpleQuery($html);
        $sq('td:eq(2)')->css('color', 'red');
        $this->assertEquals($result, (string) $sq);
    }
}
