<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Выбрать все элементы, содержащие текст.
 */
class ContainsSelectorTest extends TestCase
{
    /**
     * Найти все теги div, содержащие "John".
     */
    public function testContainsSelector(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/ContainsSelector.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/ContainsSelector-result.html');
        $sq = new SimpleQuery($html);
        $sq('div:contains("John")')->css('text-decoration', 'underline');
        $this->assertEquals($result, (string) $sq);
    }
}
