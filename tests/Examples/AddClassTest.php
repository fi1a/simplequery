<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Добавляет указанный класс (классы) к каждому элементу в наборе элементов.
 */
class AddClassTest extends TestCase
{
    /**
     * Добавить последнему выбранному p класс "selected".
     */
    public function testAddClass(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/AddClass.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/AddClass-result.html');
        $sq = new SimpleQuery($html);
        $sq('p')->last()->addClass('selected');
        $this->assertEquals($result, (string) $sq);
    }
}
