<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Получить первый элемент.
 */
class FirstTest extends TestCase
{
    /**
     * Задать стили первому элементу в списке.
     */
    public function testFirst(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/First.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/First-result.html');
        $sq = new SimpleQuery($html);
        $sq('ul li')
            ->first()
            ->css('color', 'red');
        $this->assertEquals($result, (string) $sq);
    }
}
