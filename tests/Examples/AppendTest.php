<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Вставить в конец каждого элемента в наборе элементов.
 */
class AppendTest extends TestCase
{
    /**
     * Добавить HTML ко всем параграфам.
     */
    public function testAppend(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/Append.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/Append-result.html');
        $sq = new SimpleQuery($html);
        $sq('p')->append('<strong>Hello</strong>');
        $this->assertEquals($result, (string) $sq);
    }
}
