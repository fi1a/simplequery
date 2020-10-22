<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Выбирает все элементы, которые являются потомками.
 */
class DescendantSelectorTest extends TestCase
{
    /**
     * Отметить все input, являющиеся потомками form, синей рамкой.
     * Отметить желтым фоном input, которые являются потомками fieldset, являющегося потомком form.
     */
    public function testDescendantSelector(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/DescendantSelector.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/DescendantSelector-result.html');
        $sq = new SimpleQuery($html);
        $sq('form input')->css('border', '2px dotted blue');
        $sq('form fieldset input')->css('backgroundColor', 'yellow');
        $this->assertEquals($result, (string) $sq);
    }
}
