<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Возвращает HTML первого элемента или установит содержимое HTML для каждого элемента.
 */
class HtmlTest extends TestCase
{
    /**
     * Получить html из div'а с id равным "source" и задать html div'у с id равным "target".
     */
    public function testHtml(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/Html.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/Html-result.html');
        $sq = new SimpleQuery($html);
        $sq('#target')->html($sq('#source')->html());
        $this->assertEquals($result, (string) $sq);
    }
}
