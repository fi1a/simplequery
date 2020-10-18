<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Добавление элемента в начало к указанной цели.
 */
class PrependToTest extends TestCase
{
    /**
     * Добавить в начало все span'ы к элементу с идентификатором "foo".
     */
    public function testPrependTo(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/PrependTo.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/PrependTo-result.html');
        $sq = new SimpleQuery($html);
        $sq('span')->prependTo('#foo');
        $this->assertEquals($result, (string) $sq);
    }
}
