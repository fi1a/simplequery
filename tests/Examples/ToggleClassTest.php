<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Добавить или удалить класс из каждого элемента, в зависимости от наличия класса.
 */
class ToggleClassTest extends TestCase
{
    /**
     * Поменять класс "highlight" у параграфов.
     */
    public function testToggleClass(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/ToggleClass.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/ToggleClass-result.html');
        $sq = new SimpleQuery($html);
        $sq('p')->toggleClass('highlight');
        $this->assertEquals($result, (string) $sq);
    }
}
