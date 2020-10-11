<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Выбирает все элементы типа checkbox.
 */
class CheckboxSelectorTest extends TestCase
{
    /**
     * Найти все checkbox.
     */
    public function testCheckboxSelector(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/CheckboxSelector.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/CheckboxSelector-result.html');
        $sq = new SimpleQuery($html);
        $input = $sq('form input:checkbox')
            ->wrap('<span></span>')
            ->parent()
            ->css(['background' => 'yellow', 'border' => '3px red solid']);
        $sq('div')->text('For this type found ' . count($input) . '.');
        $this->assertEquals($result, (string) $sq);
    }
}
