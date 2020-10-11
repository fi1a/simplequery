<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Все отмеченные или выбранные элементы.
 */
class CheckedSelectorTest extends TestCase
{
    /**
     * Определить сколько input имеют атрибут checked (отмечены).
     */
    public function testCheckedSelector(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/CheckedSelector.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/CheckedSelector-result.html');
        $sq = new SimpleQuery($html);
        $input = $sq('input:checked');
        $sq('div')->text(count($input) . (count($input) === 1 ? ' is' : ' are') . ' checked!');
        $this->assertEquals($result, (string) $sq);
    }
}
