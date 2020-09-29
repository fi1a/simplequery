<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Получите все элементы на текущим уровне. Если передан селектор - фильтрует результат по нему.
 */
class SiblingsTest extends TestCase
{
    /**
     * Получите все элементы на текущим уровне элемента li с классом "hilite".
     */
    public function testExample1(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/siblings-example-1.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/siblings-example-result-1.html');
        $sq = new SimpleQuery($html);
        $elements = $sq('.hilite')->siblings()->css('color', 'red');
        $sq('b')->text(count($elements));
        $this->assertEquals($result, (string) $sq);
    }
}
