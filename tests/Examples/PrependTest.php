<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Добавление элемента в начало.
 */
class PrependTest extends TestCase
{
    /**
     * Добавить в начало HTML ко всем параграфам.
     */
    public function testPrepend(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/Prepend.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/Prepend-result.html');
        $sq = new SimpleQuery($html);
        $sq('p')->prepend($sq('b'));
        $this->assertEquals($result, (string) $sq);
    }
}
