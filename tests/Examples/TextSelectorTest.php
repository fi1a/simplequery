<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Выбрать все элементы типа text.
 */
class TextSelectorTest extends TestCase
{
    /**
     * Найти все input'ы с типом "text".
     */
    public function testTextSelector(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/TextSelector.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/TextSelector-result.html');
        $sq = new SimpleQuery($html);
        $inputs = $sq(':text')->css(['background' => 'yellow', 'border' => '3px red solid']);
        $sq('div')->text('For this type found ' . count($inputs) . '.');
        $this->assertEquals($result, (string) $sq);
    }
}
