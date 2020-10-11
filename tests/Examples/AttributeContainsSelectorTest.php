<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Выбирает элементы, которые имеют указанный атрибут со значением, содержащим данную подстроку.
 */
class AttributeContainsSelectorTest extends TestCase
{
    /**
     * Найти все input с атрибутом name, содержащим «man», и установить значение.
     */
    public function testAttributeContainsSelector(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/AttributeContainsSelector.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/AttributeContainsSelector-result.html');
        $sq = new SimpleQuery($html);
        $sq('input[name*="man"]')->val('has man in it!');
        $this->assertEquals($result, (string) $sq);
    }
}
