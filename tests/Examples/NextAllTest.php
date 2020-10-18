<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Выбрать все следующие элементы.
 */
class NextAllTest extends TestCase
{
    /**
     * Получить все div'ы после первого и добавить им класс.
     */
    public function testNextAll(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/NextAll.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/NextAll-result.html');
        $sq = new SimpleQuery($html);
        $sq('div')->first()->nextAll()->addClass('after');
        $this->assertEquals($result, (string) $sq);
    }
}
