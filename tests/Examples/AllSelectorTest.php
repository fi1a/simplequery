<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Селектор позволяет выбрать все элементы.
 */
class AllSelectorTest extends TestCase
{
    /**
     * Найти все элементы включая (html, head, body).
     */
    public function testAllSelector(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/AllSelector.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/AllSelector-result.html');
        $sq = new SimpleQuery($html);
        $elements = $sq('*')->css('border', '3px solid red');
        $elementCount = count($elements);
        $sq('body')->prepend('<h3>' . $elementCount . ' elements found</h3>');
        $this->assertEquals($result, (string) $sq);
    }

    /**
     * Найти все элементы внутри body.
     */
    public function testAllSelectorInBody()
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/AllSelectorInBody.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/AllSelectorInBody-result.html');
        $sq = new SimpleQuery($html);
        $elements = $sq('body')->find('*')->css('border', '3px solid red');
        $elementCount = count($elements);
        $sq('body')->prepend('<h3>' . $elementCount . ' elements found</h3>');
        $this->assertEquals($result, (string) $sq);
    }
}
