<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Преобразует CSS3 селектор в xpath.
 */
class CompileTest extends TestCase
{
    /**
     * Преобразует CSS3 селектор в xpath.
     */
    public function testCompile(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/Compile.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/Compile-result.html');
        $sq = new SimpleQuery($html);
        $sq('p')->xpath($sq->compile('p > b'))->css('color', 'red');
        $this->assertEquals($result, (string) $sq);
    }
}
