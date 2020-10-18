<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Удаляет родительский элемент и помещает на его место.
 */
class UnwrapTest extends TestCase
{
    /**
     * Удаляет родительский элемент и помещает на его место.
     */
    public function testUnwrap(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/Unwrap.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/Unwrap-result.html');
        $sq = new SimpleQuery($html);
        $sq('p')->unwrap();
        $this->assertEquals($result, (string) $sq);
    }
}
