<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Выбирает элементы с указанными атрибутами.
 */
class MultipleAttributeSelectorTest extends TestCase
{
    /**
     * Найти все input'ы имеющие атрибут id, у которых значение атрибута name заканчивается на "man".
     */
    public function testMultipleAttributeSelector(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/MultipleAttributeSelector.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/MultipleAttributeSelector-result.html');
        $sq = new SimpleQuery($html);
        $sq('input[id][name$="man"]')->val('only this one');
        $this->assertEquals($result, (string) $sq);
    }
}
