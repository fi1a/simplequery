<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Возвращает все родительские элементы до элемента, удовлетворяющего селектору
 */
class ParentsUntilTest extends TestCase
{
    /**
     * Возвращает все родительские элементы до элемента, удовлетворяющего селектору
     */
    public function testParentsUntil(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/ParentsUntil.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/ParentsUntil-result.html');
        $sq = new SimpleQuery($html);
        $sq('li.item-a')->parentsUntil('.level-1')->css('background-color', 'red');
        $sq('li.item-2')->parentsUntil($sq('ul.level-1'))->css('border', '3px solid green');
        $this->assertEquals($result, (string) $sq);
    }
}
