<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Получить набор элементов формы в виде вложенного массива
 */
class SerializeNestedTest extends TestCase
{
    /**
     * Получить набор элементов формы в виде вложенного массива
     */
    public function testSerializeNested(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/SerializeNested.html');
        $sq = new SimpleQuery($html);
        $this->assertEquals([
            'single' => null,
            'multiple' => ['Multiple', 'Multiple3'],
            'check' => 'check2',
            'radio' => 'radio1',
        ], $sq(':input')->serializeNested());
    }
}
