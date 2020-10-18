<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Получить предыдущий элемент. Если предоставлен селектор,
 * он извлекает предыдущий элемент только в том случае, если он соответствует этому селектору.
 */
class PrevTest extends TestCase
{
    /**
     * Найти предыдущий элемент div'а.
     */
    public function testPrev(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/Prev.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/Prev-result.html');
        $sq = new SimpleQuery($html);
        $sq('#start')->prev()->css('background', 'red');
        $this->assertEquals($result, (string) $sq);
    }
}
