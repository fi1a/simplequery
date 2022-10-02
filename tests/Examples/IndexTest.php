<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Возвращает индекс элемента среди выбранных
 */
class IndexTest extends TestCase
{
    /**
     * Возвращает индекс элемента среди выбранных
     */
    public function testIndex(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/Index.html');
        $sq = new SimpleQuery($html);
        $this->assertEquals(1, $sq('li')->index('#bar'));
    }
}
