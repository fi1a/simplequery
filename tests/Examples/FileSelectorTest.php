<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Выбрать все элементы типа файл.
 */
class FileSelectorTest extends TestCase
{
    /**
     * Найти все input'ы типа file.
     */
    public function testFileSelector(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/FileSelector.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/FileSelector-result.html');
        $sq = new SimpleQuery($html);
        $input = $sq('input:file')->css([
            'background' => 'yellow',
            'border' => '3px red solid',
        ]);
        $sq('div')->text('For this type found ' . count($input) . '.');
        $this->assertEquals($result, (string) $sq);
    }
}
