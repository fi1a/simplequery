<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Проверить элементы по селектору, элементу или объекту SimpleQuery и вернуть true,
 * если хотя бы один из этих элементов соответствует заданным условиям.
 */
class IsTest extends TestCase
{
    /**
     * Выбрать div'ы и проверить на соответствие селектору.
     */
    public function testIs(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/Is.html');
        $sq = new SimpleQuery($html);
        $this->assertFalse($sq('div')->eq(0)->is('.blue'));
        $this->assertTrue($sq('div')->eq(1)->is('.blue'));
        $this->assertFalse($sq('div')->eq(0)->is('.blue, .red'));
        $this->assertTrue($sq('div')->eq(3)->is('.blue, .red'));
        $this->assertTrue($sq('div')->eq(3)->is('.blue,.red'));
        $this->assertFalse($sq('div')->eq(0)->is(':contains("Peter")'));
        $this->assertTrue($sq('div')->eq(4)->is(':contains("Peter")'));
    }
}
