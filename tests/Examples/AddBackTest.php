<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Добавить предыдущий набор элементов в стек к текущему набору.
 */
class AddBackTest extends TestCase
{
    /**
     * Добавить к выбранным элементам find('p') предыдущий набор элементов $sq('div.after-addback').
     */
    public function testAddBack(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/AddBack.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/AddBack-result.html');
        $sq = new SimpleQuery($html);
        $sq('div.before-addback')
            ->find('p')
            ->addClass('background');
        $sq('div.after-addback')
            ->find('p')
            ->addBack()
            ->addClass('background');
        $this->assertEquals($result, (string) $sq);
    }
}
