<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Выбирает все одноуровневые элементы, которые следуют после элемента «prev»,
 * имеют одного и того же родителя и соответствуют фильтрующему селектору.
 */
class NextSiblingsSelectorTest extends TestCase
{
    /**
     * Выбирает все div'ы, которые следуют после элемента с id равным "prev".
     */
    public function testNextSiblingsSelector(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/NextSiblingsSelector.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/NextSiblingsSelector-result.html');
        $sq = new SimpleQuery($html);
        $sq('#prev ~ div')->css('border', '3px groove blue');
        $this->assertEquals($result, (string) $sq);
    }
}
