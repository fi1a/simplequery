<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Выбирает все следующие элементы, соответствующие «next», которым непосредственно предшествует элемент «prev».
 */
class NextAdjacentSelectorTest extends TestCase
{
    /**
     * Найти все input'ы следующие за label.
     */
    public function testNextAdjacentSelector(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/NextAdjacentSelector.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/NextAdjacentSelector-result.html');
        $sq = new SimpleQuery($html);
        $sq('label + input')->val('Labeled!');
        $this->assertEquals($result, (string) $sq);
    }
}
