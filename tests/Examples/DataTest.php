<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Сохранить данные, связанные с элементами, или вернуть значение данных для первого элемента.
 */
class DataTest extends TestCase
{
    /**
     * Установить и затем получить данные у div элемента.
     */
    public function testData(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/Data.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/Data-result.html');
        $sq = new SimpleQuery($html);
        $sq('div')->data('test', ['first' => 16, 'last' => 'pizza!']);
        $sq('span')->first()->text((string) $sq('div')->data('test')['first']);
        $sq('span')->last()->text($sq('div')->data('test')['last']);
        $this->assertEquals($result, (string) $sq);
    }

    /**
     * Пример установки и получения значений.
     */
    public function testDataSetAndGet(): void
    {
        $sq = new SimpleQuery();
        $element = $sq('<span/>');
        $this->assertNull($element->data('foo'));
        $this->assertCount(0, $element->data());
        $element->data('foo', 42);
        $this->assertEquals(42, $element->data('foo'));
        $this->assertEquals(['foo' => 42,], $element->data());
    }
}
