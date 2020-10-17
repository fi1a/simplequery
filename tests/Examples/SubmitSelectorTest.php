<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Выбрать все элементы типа submit.
 */
class SubmitSelectorTest extends TestCase
{
    /**
     * Найти все input'ы с типом "submit".
     */
    public function testSubmitSelector(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/SubmitSelector.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/SubmitSelector-result.html');
        $sq = new SimpleQuery($html);
        $inputs = $sq(':submit')->css(['background' => 'yellow', 'border' => '3px red solid']);
        $sq('div')->text('For this type found ' . count($inputs) . '.');
        $this->assertEquals($result, (string) $sq);
    }
}
