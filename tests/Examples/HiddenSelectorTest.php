<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Выбирает все элементы с типом hidden. Не выбирает элементы со style="display: none;" и т.д.
 */
class HiddenSelectorTest extends TestCase
{
    /**
     * Выбрать все input'ы с типом hidden и задать значение.
     */
    public function testHiddenSelector(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/HiddenSelector.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/HiddenSelector-result.html');
        $sq = new SimpleQuery($html);
        $sq(':hidden')->val('Type is hidden');
        $this->assertEquals($result, (string) $sq);
    }
}
