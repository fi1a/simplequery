<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Выбирает один элемент с заданным атрибутом id.
 */
class IDSelectorTest extends TestCase
{
    /**
     * Выбрать элемент с идентификатором "myDiv".
     */
    public function testIDSelector(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/IDSelector.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/IDSelector-result.html');
        $sq = new SimpleQuery($html);
        $sq('#myDiv')->css('border', '3px solid red');
        $this->assertEquals($result, (string) $sq);
    }

    /**
     * Выбрать элемент с идентификатором равным "myID.entry[1]".
     */
    public function testIDSelectorWithEscape(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/IDSelectorWithEscape.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/IDSelectorWithEscape-result.html');
        $sq = new SimpleQuery($html);
        $sq('#myID\.entry\[1\]')->css('border', '3px solid red');
        $this->assertEquals($result, (string) $sq);
    }
}
