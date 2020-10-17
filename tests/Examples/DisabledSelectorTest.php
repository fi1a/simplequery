<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Выбирает все "не активные" элементы.
 */
class DisabledSelectorTest extends TestCase
{
    /**
     * Найти все "не активные" input'ы.
     */
    public function testDisabledSelector(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/DisabledSelector.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/DisabledSelector-result.html');
        $sq = new SimpleQuery($html);
        $sq('input:disabled')->val('this is it');
        $this->assertEquals($result, (string) $sq);
    }
}
