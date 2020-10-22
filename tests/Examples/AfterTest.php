<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Добавить после каждого элемента в наборе элементов.
 */
class AfterTest extends TestCase
{
    /**
     * Добавить HTML после всех тегов p.
     */
    public function testAfter(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/After.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/After-result.html');
        $sq = new SimpleQuery($html);
        $sq('p')->after('<b>Hello</b>');
        $this->assertEquals($result, (string) $sq);
    }
}
