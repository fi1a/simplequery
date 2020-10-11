<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Выбирает элементы, которые имеют указанный атрибут, точно равный определенному значению.
 */
class AttributeEqualsSelectorTest extends TestCase
{
    /**
     * Найти все input со значение "Hot Fuzz" и изменить текст у следующего тега span.
     */
    public function testAttributeEqualsSelector(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/AttributeEqualsSelector.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/AttributeEqualsSelector-result.html');
        $sq = new SimpleQuery($html);
        $sq('input[value="Hot Fuzz"]')->next()->text('Hot Fuzz');
        $this->assertEquals($result, (string) $sq);
    }
}
