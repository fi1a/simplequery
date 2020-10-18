<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Устанавливает значение по ключу.
 */
class SetTest extends TestCase
{
    /**
     * Устанавливает значение по ключу.
     */
    public function testSet(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/Set.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/Set-result.html');
        $sq = new SimpleQuery($html);
        $p = $sq('p');
        $p->set(1, $sq('div')->get(0));
        $p->css('color', 'red');
        $this->assertEquals($result, (string) $sq);
    }
}
