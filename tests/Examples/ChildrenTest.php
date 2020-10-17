<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Получить дочерние элементы каждого элемента в наборе элементов,
 * при необходимости отфильтрованных с помощью селектора.
 */
class ChildrenTest extends TestCase
{
    /**
     * Найти все дочерние элементы родителя с атрибутом id="foo".
     */
    public function testChildren(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/Children.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/Children-result.html');
        $sq = new SimpleQuery($html);
        $sq('#foo')->children()->css('background', 'yellow');
        $this->assertEquals($result, (string) $sq);
    }
}
