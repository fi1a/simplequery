<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Добавляет элементы в текущий контекст.
 */
class AddTest extends TestCase
{
    /**
     * Найти все div'ы затем добавить p к выбранным div'м.
     */
    public function testAdd(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/Add.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/Add-result.html');
        $sq = new SimpleQuery($html);
        $sq('div')
            ->css('border', '2px solid red')
            ->add('p')
            ->css('background', 'yellow');
        $this->assertEquals($result, (string) $sq);
    }
}
