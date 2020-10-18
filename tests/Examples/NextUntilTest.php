<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Все следующие элементы до элемента удовлетворяющего селектору.
 */
class NextUntilTest extends TestCase
{
    /**
     * Все следующие элементы до элемента удовлетворяющего селектору.
     */
    public function testNextUntil(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/NextUntil.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/NextUntil-result.html');
        $sq = new SimpleQuery($html);
        $sq('#term-2')->nextUntil('dt')->css('background-color', 'red');
        $sq('#term-1')->nextUntil($sq('#term-2'))->css('color', 'green');
        $this->assertEquals($result, (string) $sq);
    }
}
