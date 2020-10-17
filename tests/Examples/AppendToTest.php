<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Вставить набор элементов к указанной цели.
 */
class AppendToTest extends TestCase
{
    /**
     * Добавить все span'ы к элементу с id="foo".
     */
    public function testAppendTo(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/AppendTo.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/AppendTo-result.html');
        $sq = new SimpleQuery($html);
        $sq('span')->appendTo('#foo');
        $this->assertEquals($result, (string) $sq);
    }
}
