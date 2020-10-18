<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Вставить каждый элемент до цели.
 */
class InsertBeforeTest extends TestCase
{
    /**
     * Вставить все параграфы до элемента с id равным "foo".
     */
    public function testInsertBefore(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/InsertBefore.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/InsertBefore-result.html');
        $sq = new SimpleQuery($html);
        $sq('p')->insertBefore('#foo');
        $this->assertEquals($result, (string) $sq);
    }
}
