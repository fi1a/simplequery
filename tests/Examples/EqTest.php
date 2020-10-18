<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Получить элемент с указанным индексом.
 */
class EqTest extends TestCase
{
    /**
     * Получить div с индексом 2.
     */
    public function testEq(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/Eq.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/Eq-result.html');
        $sq = new SimpleQuery($html);
        $sq('div')
            ->eq(2)
            ->css('background', 'yellow');
        $this->assertEquals($result, (string) $sq);
    }
}
