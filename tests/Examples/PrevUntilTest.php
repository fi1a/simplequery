<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Все предыдущие элементы до элемента, удовлетворяющего селектору
 */
class PrevUntilTest extends TestCase
{
    /**
     * Все предыдущие элементы до элемента, удовлетворяющего селектору
     */
    public function testPrevUntil(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/PrevUntil.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/PrevUntil-result.html');
        $sq = new SimpleQuery($html);
        $sq('#term-2')->prevUntil('dt')->css('background-color', 'red');
        $sq('#term-3')->prevUntil($sq('term-1'))->css('color', 'green');
        $this->assertEquals($result, (string) $sq);
    }
}
