<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Выбирает элементы, которые либо не имеют указанного атрибута,
 * либо имеют указанный атрибут, но не имеют определенного значения.
 */
class AttributeNotEqualSelectorTest extends TestCase
{
    /**
     * Найти все input, которые не имеют в атрибуте name значение "newsletter" и добавить текст после span.
     */
    public function testAttributeNotEqualSelector(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/AttributeNotEqualSelector.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/AttributeNotEqualSelector-result.html');
        $sq = new SimpleQuery($html);
        $sq('input[name!="newsletter"]')->next()->append('<b>; not newsletter</b>');
        $this->assertEquals($result, (string) $sq);
    }
}
