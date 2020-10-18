<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Получите потомков каждого элемента, отфильтрованных с помощью селектора.
 */
class FindTest extends TestCase
{
    /**
     * Найти все параграфы и в них найти все span.
     */
    public function testFind(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/Find.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/Find-result.html');
        $sq = new SimpleQuery($html);
        $sq('p')
            ->find('span')
            ->css('color', 'red');
        $this->assertEquals($result, (string) $sq);
    }
}
