<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Добавить перед каждым элементом в наборе.
 */
class BeforeTest extends TestCase
{
    /**
     * Добавить HTML до всех параграфов.
     */
    public function testBefore(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/Before.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/Before-result.html');
        $sq = new SimpleQuery($html);
        $sq('p')->before('<b>Hello</b>');
        $this->assertEquals($result, (string) $sq);
    }
}
