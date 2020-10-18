<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Удалить набор элементов из DOM.
 */
class RemoveTest extends TestCase
{
    /**
     * Удалить все параграфы.
     */
    public function testRemove(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/Remove.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/Remove-result.html');
        $sq = new SimpleQuery($html);
        $sq('p')->remove();
        $this->assertEquals($result, (string) $sq);
    }

    /**
     * Удалить параграфы с текстом "Hello".
     */
    public function testRemoveWithTextHello(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/RemoveWithTextHello.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/RemoveWithTextHello-result.html');
        $sq = new SimpleQuery($html);
        $sq('p')->remove(':contains("Hello")');
        $this->assertEquals($result, (string) $sq);
    }
}
