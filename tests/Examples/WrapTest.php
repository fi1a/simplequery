<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Обернуть структуру вокруг каждого элемента.
 */
class WrapTest extends TestCase
{
    /**
     * Обернуть параграфы тегами div.
     */
    public function testWrap(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/Wrap.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/Wrap-result.html');
        $sq = new SimpleQuery($html);
        $sq('p')->wrap('<div/>');
        $this->assertEquals($result, (string) $sq);
    }
}
