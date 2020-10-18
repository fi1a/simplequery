<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Обернуть структуру вокруг всех элементов.
 */
class WrapAllTest extends TestCase
{
    /**
     * Обернуть все параграфы одним div'м.
     */
    public function testWrapAll(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/WrapAll.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/WrapAll-result.html');
        $sq = new SimpleQuery($html);
        $sq('p')->wrapAll('<div/>');
        $this->assertEquals($result, (string) $sq);
    }
}
