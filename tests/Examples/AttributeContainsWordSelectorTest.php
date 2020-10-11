<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Выбирает элементы, которые имеют указанный атрибут со значением,
 * содержащим заданное слово, разделенное пробелами.
 */
class AttributeContainsWordSelectorTest extends TestCase
{
    /**
     * Найти все input с атрибутом name, содержащим слово «man», и установить значение.
     */
    public function testAttributeContainsWordSelector(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/AttributeContainsWordSelector.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/AttributeContainsWordSelector-result.html');
        $sq = new SimpleQuery($html);
        $sq('input[name~="man"]')->val('mr. man is in it!');
        $this->assertEquals($result, (string) $sq);
    }
}
