<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Получить набор элементов формы как массив с именами и значениями.
 */
class SerializeArrayTest extends TestCase
{
    /**
     * Получить набор элементов формы как массив с именами и значениями.
     */
    public function testSerializeArray(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/SerializeArray.html');
        $sq = new SimpleQuery($html);
        $this->assertEquals([
            [
                'name' => 'single',
                'value' => null,
            ],
            [
                'name' => 'multiple',
                'value' => 'Multiple',
            ],
            [
                'name' => 'multiple',
                'value' => 'Multiple3',
            ],
            [
                'name' => 'check',
                'value' => 'check2',
            ],
            [
                'name' => 'radio',
                'value' => 'radio1',
            ],
        ], $sq(':input')->serializeArray());
    }
}
