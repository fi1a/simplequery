<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Получите все элементы на текущем уровне. Если передан селектор - фильтрует результат по нему.
 */
class SiblingsTest extends TestCase
{
    /**
     * Получите все элементы на текущем уровне элемента li с классом "hilite".
     */
    public function testSiblings(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/Siblings.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/Siblings-result.html');
        $sq = new SimpleQuery($html);
        $elements = $sq('.hilite')->siblings()->css('color', 'red');
        $sq('b')->text((string) count($elements));
        $this->assertEquals($result, (string) $sq);
    }
}
