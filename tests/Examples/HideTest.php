<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Скрыть выбранные элементы.
 */
class HideTest extends TestCase
{
    /**
     * Скрыть параграф.
     */
    public function testEmptySelector(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/Hide.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/Hide-result.html');
        $sq = new SimpleQuery($html);
        $sq('p')->hide();
        $this->assertEquals($result, (string) $sq);
    }
}
