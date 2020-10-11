<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Выбирает все элементы button и элементы типа button.
 */
class ButtonSelectorTest extends TestCase
{
    /**
     * Найти все input типа button и отметить их.
     */
    public function testButtonSelector(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/ButtonSelector.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/ButtonSelector-result.html');
        $sq = new SimpleQuery($html);
        $input = $sq(':button')->addClass('marked');
        $sq('div')->text('For this type found ' . count($input) . '.');
        $this->assertEquals($result, (string) $sq);
    }
}
