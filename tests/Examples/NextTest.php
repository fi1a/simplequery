<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Получить следующий элемент. Если предоставлен селектор, возвращается следующий элемент
 * в случае соответствия этому селектору.
 */
class NextTest extends TestCase
{
    /**
     * Найти следующий элемент каждой не активной кнопки.
     */
    public function testNext(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/Next.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/Next-result.html');
        $sq = new SimpleQuery($html);
        $sq('button[disabled]')->next()->text('this button is disabled');
        $this->assertEquals($result, (string) $sq);
    }
}
