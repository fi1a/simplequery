<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Определяет наличие класса у элемента.
 */
class HasClassTest extends TestCase
{
    /**
     * Проверить наличие класса "selected" у параграфов.
     */
    public function testHasClass(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/HasClass.html');
        $sq = new SimpleQuery($html);
        $this->assertEquals(
            [false, true,],
            $sq('p')
            ->map(function ($element) use ($sq) {
                return $sq($element)->hasClass('selected');
            })->getArrayCopy()
        );
    }
}
