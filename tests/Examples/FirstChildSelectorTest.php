<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Выбирает все элементы, которые являются первыми дочерними элементами родителя.
 */
class FirstChildSelectorTest extends TestCase
{
    /**
     * Найти первый тег span в каждом div.
     */
    public function testFirstChildSelector(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/FirstChildSelector.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/FirstChildSelector-result.html');
        $sq = new SimpleQuery($html);
        $sq('div span:first-child')->css('text-decoration', 'underline');
        $this->assertEquals($result, (string) $sq);
    }
}
