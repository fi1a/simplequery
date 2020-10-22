<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Есть ли элемент с таким ключом.
 */
class HasTest extends TestCase
{
    /**
     * Есть ли элемент с таким ключом.
     */
    public function testHas(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/Has.html');
        $sq = new SimpleQuery($html);
        $this->assertTrue($sq('div')->has(0));
    }
}
