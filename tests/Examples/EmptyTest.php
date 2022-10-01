<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Удалите все дочерние узлы элементов из DOM.
 */
class EmptyTest extends TestCase
{
    /**
     * Удалите все дочерние узлы элементов из DOM.
     */
    public function testEmptySelector(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/Empty.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/Empty-result.html');
        $sq = new SimpleQuery($html);
        $sq('p')->empty();
        $this->assertEquals($result, (string) $sq);
    }
}
