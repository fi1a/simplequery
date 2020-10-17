<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Выбирает все элементы, у которых нет дочерних элементов (включая текстовые узлы).
 */
class EmptySelectorTest extends TestCase
{
    /**
     * Найти все пустые элементы (не имеющих дочерних элементов или текста).
     */
    public function testEmptySelector(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/EmptySelector.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/EmptySelector-result.html');
        $sq = new SimpleQuery($html);
        $sq('td:empty')->text('Was empty!')->css('background', 'rgb(255,220,200)');
        $this->assertEquals($result, (string) $sq);
    }
}
