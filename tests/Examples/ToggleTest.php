<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Показать или скрыть выбранные элементы
 */
class ToggleTest extends TestCase
{
    /**
     * Показать или скрыть выбранные параграфы.
     */
    public function testEmptySelector(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/Toggle.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/Toggle-result.html');
        $sq = new SimpleQuery($html);
        $sq('p')->toggle();
        $this->assertEquals($result, (string) $sq);
    }
}
