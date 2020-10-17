<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Выбирает все "активные" элементы.
 */
class EnabledSelectorTest extends TestCase
{
    /**
     * Найти все "активные" input'ы.
     */
    public function testEnabledSelector(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/EnabledSelector.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/EnabledSelector-result.html');
        $sq = new SimpleQuery($html);
        $sq('input:enabled')->val('this is it');
        $this->assertEquals($result, (string) $sq);
    }
}
