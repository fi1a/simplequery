<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Удалить класс у набора элементов.
 */
class RemoveClassTest extends TestCase
{
    /**
     * Удалить класс "blue" у выбранных элементов.
     */
    public function testRemoveClass(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/RemoveClass.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/RemoveClass-result.html');
        $sq = new SimpleQuery($html);
        $sq('p:even')->removeClass('blue');
        $this->assertEquals($result, (string) $sq);
    }
}
