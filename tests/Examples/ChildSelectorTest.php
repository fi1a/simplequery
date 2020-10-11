<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Выбирает все "прямые" дочерние элементы.
 */
class ChildSelectorTest extends TestCase
{
    /**
     * Отметить все дочерние элементы тега <ul class="topnav">.
     */
    public function testChildSelector(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/ChildSelector.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/ChildSelector-result.html');
        $sq = new SimpleQuery($html);
        $sq('ul.topnav > li')->css('border', '3px double red');
        $this->assertEquals($result, (string) $sq);
    }
}
