<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Выбирает все элементы указанного языка.
 */
class LangSelectorTest extends TestCase
{
    /**
     * Задать классы элементам с определённым языком.
     */
    public function testEmptySelector(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/LangSelector.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/LangSelector-result.html');
        $sq = new SimpleQuery($html);
        $sq('div:lang(en-us)')->addClass('usa');
        $sq('div:lang(es-es)')->addClass('spain');
        $this->assertEquals($result, (string) $sq);
    }
}
