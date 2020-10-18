<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Конец операции фильтрации в текущей цепочке.
 */
class EndTest extends TestCase
{
    /**
     * Выбрать все параграфы, в них найти все span и вернуть контекст обратно к параграфам.
     */
    public function testEnd(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/End.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/End-result.html');
        $sq = new SimpleQuery($html);
        $sq('p')
            ->css('background', 'yellow')
            ->find('span')
            ->css('font-style', 'italic')
            ->end()
            ->css('color', 'red');
        $this->assertEquals($result, (string) $sq);
    }
}
