<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Выбирает элементы, которые имеют указанный атрибут со значением,
 * равным заданной строке или начинающимся с этой строки.
 */
class AttributeContainsPrefixSelectorTest extends TestCase
{
    /**
     * Найти ссылки у которых атрибут hreflang равный значению en или начинается с него.
     */
    public function testAttributeContainsPrefixSelector(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/AttributeContainsPrefixSelector.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/AttributeContainsPrefixSelector-result.html');
        $sq = new SimpleQuery($html);
        $sq('a[hreflang|="en"]')->css('border', '3px dotted green');
        $this->assertEquals($result, (string) $sq);
    }
}
