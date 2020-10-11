<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Выбирает элементы, которые имеют указанный атрибут со значением,
 * оканчивающимся точно на заданную строку. При сравнении учитывается регистр.
 */
class AttributeEndsWithSelectorTest extends TestCase
{
    /**
     * Найти все input с атрибутом name, оканчивающимся на "letter" и установить значение.
     */
    public function testAttributeEndsWithSelector(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/AttributeEndsWithSelector.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/AttributeEndsWithSelector-result.html');
        $sq = new SimpleQuery($html);
        $sq('input[name$="letter"]')->val('a letter');
        $this->assertEquals($result, (string) $sq);
    }
}
