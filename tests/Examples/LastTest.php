<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Вернуть последний элемент.
 */
class LastTest extends TestCase
{
    /**
     * Выделить последний элемент в списке.
     */
    public function testLast(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/Last.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/Last-result.html');
        $sq = new SimpleQuery($html);
        $sq('ul li')->last()->css('background-color', 'yellow');
        $this->assertEquals($result, (string) $sq);
    }
}
