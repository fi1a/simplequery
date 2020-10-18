<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Обернуть структуру вокруг содержимого элементов.
 */
class WrapInnerTest extends TestCase
{
    /**
     * Выбрать все параграфы и обернуть тегом b все содержимое.
     */
    public function testWrapInner(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/WrapInner.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/WrapInner-result.html');
        $sq = new SimpleQuery($html);
        $sq('p')->wrapInner('<b/>');
        $this->assertEquals($result, (string) $sq);
    }
}
