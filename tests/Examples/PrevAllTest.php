<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Выбрать все предыдущие элементы.
 */
class PrevAllTest extends TestCase
{
    /**
     * Найти все предыдущие элементы последнего div'а.
     */
    public function testPrevAll(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/PrevAll.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/PrevAll-result.html');
        $sq = new SimpleQuery($html);
        $sq('div')->last()->prevAll()->addClass('before');
        $this->assertEquals($result, (string) $sq);
    }
}
