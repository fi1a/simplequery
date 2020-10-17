<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Выбирает все элементы с заданным именем тега.
 */
class ElementSelectorTest extends TestCase
{
    /**
     * Найти все теги div.
     */
    public function testElementSelector(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/ElementSelector.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/ElementSelector-result.html');
        $sq = new SimpleQuery($html);
        $sq('div')->css('border', '9px solid red');
        $this->assertEquals($result, (string) $sq);
    }
}
