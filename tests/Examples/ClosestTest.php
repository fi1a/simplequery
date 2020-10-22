<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Для каждого элемента получить первый элемент, соответствующий селектору для самого элемента и его предков.
 */
class ClosestTest extends TestCase
{
    /**
     * Найти элементы с классом "bar" и получить первый элемент с классом "foo",
     * соответсвующий самому элементу или его предку.
     */
    public function testClosest(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/Closest.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/Closest-result.html');
        $sq = new SimpleQuery($html);
        $sq('.bar')->closest('.foo')->css('background', 'yellow');
        $this->assertEquals($result, (string) $sq);
    }
}
