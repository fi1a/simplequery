<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Возвращает текстовое содержимое первого элемента или установливает текстовое содержимое для каждого элемента.
 */
class TextTest extends TestCase
{
    /**
     * Получить текст из первого параграфа, затем установить текст как html в последний параграф.
     */
    public function testText(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/Text.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/Text-result.html');
        $sq = new SimpleQuery($html);
        $text = $sq('p')->first()->text();
        $sq('p')->last()->html($text);
        $this->assertEquals($result, (string) $sq);
    }
}
