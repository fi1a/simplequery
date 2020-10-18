<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Поиск используя ХPath селектор.
 */
class XpathTest extends TestCase
{
    /**
     * Поиск используя ХPath селектор.
     */
    public function testXpath(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/Xpath.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/Xpath-result.html');
        $sq = new SimpleQuery($html);
        $sq('p')->xpath('descendant-or-self::p/b')->css('color', 'red');
        $this->assertEquals($result, (string) $sq);
    }
}
