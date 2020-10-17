<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Выбирает элемент имеющий дочерние элементы.
 */
class ParentSelectorTest extends TestCase
{
    /**
     * Выбирает элемент имеющий дочерние элементы.
     */
    public function testParentSelector(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/ParentSelector.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/ParentSelector-result.html');
        $sq = new SimpleQuery($html);
        $sq('td:parent')->css('background-color', '#bbbbff');
        $this->assertEquals($result, (string) $sq);
    }
}
