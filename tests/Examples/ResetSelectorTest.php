<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Выбрать все элементы типа reset.
 */
class ResetSelectorTest extends TestCase
{
    /**
     * Найти все input'ы с типом "reset".
     */
    public function testResetSelector(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/ResetSelector.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/ResetSelector-result.html');
        $sq = new SimpleQuery($html);
        $inputs = $sq('input:reset')->css(['background' => 'yellow', 'border' => '3px red solid']);
        $sq('div')->text('For this type found ' . count($inputs) . '.');
        $this->assertEquals($result, (string) $sq);
    }
}
