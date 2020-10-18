<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Возвращает расходящиеся элементы.
 */
class DiffTest extends TestCase
{
    /**
     * Возвращает расходящиеся элементы.
     */
    public function testDiff(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/Diff.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/Diff-result.html');
        $sq = new SimpleQuery($html);
        $allDivs = $sq('div');
        $divs = $sq('div.not-that');
        $allDivs->diff($divs)->css('border', '3px solid red');
        $this->assertEquals($result, (string) $sq);
    }
}
