<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Удаляет элемент по ключу.
 */
class DeleteTest extends TestCase
{
    /**
     * Удаляет элемент по ключу.
     */
    public function testDelete(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/Delete.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/Delete-result.html');
        $sq = new SimpleQuery($html);
        $div = $sq('div');
        $div->delete(1);
        $div->css('color', 'red');
        $this->assertEquals($result, (string) $sq);
    }
}
