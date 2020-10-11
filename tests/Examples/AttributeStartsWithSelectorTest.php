<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Выбирает элементы, которые имеют указанный атрибут со значением, начинающимся точно с заданной строки.
 */
class AttributeStartsWithSelectorTest extends TestCase
{
    /**
     * Найти все input у которых атрибут name начинается с "news" и установить значение у них.
     */
    public function testAttributeStartsWithSelector(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/AttributeStartsWithSelector.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/AttributeStartsWithSelector-result.html');
        $sq = new SimpleQuery($html);
        $sq('input[name^="news"]')->val('news here!');
        $this->assertEquals($result, (string) $sq);
    }
}
