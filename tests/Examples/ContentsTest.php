<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Возвращает все дочерние элементы, в том числе и текст.
 */
class ContentsTest extends TestCase
{
    /**
     * Обернуть все содержимое тега "p" тегами "b".
     */
    public function testContents(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/Contents.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/Contents-result.html');
        $sq = new SimpleQuery($html);
        $sq('p')->contents()->wrap('<b></b>');
        $this->assertEquals($result, (string) $sq);
    }
}
