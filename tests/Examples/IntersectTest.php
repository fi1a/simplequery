<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Возвращает пересечение элементов.
 */
class IntersectTest extends TestCase
{
    /**
     *  Возвращает пересечение элементов.
     */
    public function testIntersect(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/Intersect.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/Intersect-result.html');
        $sq = new SimpleQuery($html);
        $fooDivs = $sq('.foo');
        $barDivs = $sq('.bar');
        $fooDivs->intersect($barDivs)->css('border', '3px solid red');
        $this->assertEquals($result, (string) $sq);
    }
}
