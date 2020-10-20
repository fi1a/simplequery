<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Объединяет элементы.
 */
class MergeTest extends TestCase
{
    /**
     * Объединяет элементы.
     */
    public function testMerge(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/Merge.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/Merge-result.html');
        $sq = new SimpleQuery($html);
        $fooDivs = $sq('.foo:first');
        $barDivs = $sq('.bar:last');
        $fooDivs->merge($barDivs)->css('border', '3px solid red');
        $this->assertEquals($result, (string) $sq);
    }
}
