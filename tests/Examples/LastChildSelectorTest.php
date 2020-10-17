<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Выбирает все элементы, которые являются последними дочерними элементами родителя.
 */
class LastChildSelectorTest extends TestCase
{
    /**
     * Выбирает последний span в каждом div'е.
     */
    public function testLastChildSelector(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/LastChildSelector.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/LastChildSelector-result.html');
        $sq = new SimpleQuery($html);
        $sq('div span:last-child')->css(['color' => 'red', 'fontSize' => '80%']);
        $this->assertEquals($result, (string) $sq);
    }
}
