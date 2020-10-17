<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Выбрать все элементы типа radio.
 */
class RadioSelectorTest extends TestCase
{
    /**
     * Найти все input'ы с типом "radio".
     */
    public function testRadioSelector(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/RadioSelector.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/RadioSelector-result.html');
        $sq = new SimpleQuery($html);
        $inputs = $sq('input:radio')->css(['background' => 'yellow', 'border' => '3px red solid']);
        $sq('div')->text('For this type found ' . count($inputs) . '.');
        $this->assertEquals($result, (string) $sq);
    }
}
