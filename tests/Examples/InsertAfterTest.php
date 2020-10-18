<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Вставить каждый элемент после цели.
 */
class InsertAfterTest extends TestCase
{
    /**
     * Вставить все параграфы после элемента с id равным "foo".
     */
    public function testInsertAfter(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/InsertAfter.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/InsertAfter-result.html');
        $sq = new SimpleQuery($html);
        $sq('p')->insertAfter('#foo');
        $this->assertEquals($result, (string) $sq);
    }
}
