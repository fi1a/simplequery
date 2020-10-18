<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Возвращает элемент по ключу.
 */
class GetTest extends TestCase
{
    /**
     * Возвращает элемент по ключу.
     */
    public function testGet(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/Get.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/Get-result.html');
        $sq = new SimpleQuery($html);
        $sq($sq('div')->get(0))->css('color', 'red');
        $this->assertEquals($result, (string) $sq);
    }
}
