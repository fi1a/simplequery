<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Вернуть все выбранные элементы (selected="selected").
 */
class SelectedSelectorTest extends TestCase
{
    /**
     * Найти все выбранные option.
     */
    public function testSelectedSelector(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/SelectedSelector.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/SelectedSelector-result.html');
        $sq = new SimpleQuery($html);
        $string = '';
        foreach ($sq('select option:selected') as $option) {
            $string .= ($string ? ' ' : '') . $sq($option)->text();
        }
        $sq('div')->text($string);
        $this->assertEquals($result, (string) $sq);
    }
}
