<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Убрать элементы, соответствующие селектору.
 */
class NotTest extends TestCase
{
    /**
     * Добавить border к div'ам не имеющим класс "green" и не имеющим идентификатор "blueone".
     */
    public function testNot(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/Not.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/Not-result.html');
        $sq = new SimpleQuery($html);
        $sq('div')->not('.green, #blueone')->css('border-color', 'red');
        $this->assertEquals($result, (string) $sq);
    }
}
