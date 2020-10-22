<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Выбрать элементы с указанным атрибутом и любым значением.
 */
class HasAttributeSelectorTest extends TestCase
{
    /**
     * Отметить все div'ы имеющие атрибут id.
     */
    public function testHasAttributeSelector(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/HasAttributeSelector.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/HasAttributeSelector-result.html');
        $sq = new SimpleQuery($html);
        $sq('div[id]')->css('border', '2px dotted blue');
        $this->assertEquals($result, (string) $sq);
    }
}
