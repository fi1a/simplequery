<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Возвращает или устанавливает значение атрибута.
 */
class AttrTest extends TestCase
{
    /**
     * Получить и установить значение атрибута title.
     */
    public function testAttr(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/Attr.html');
        $sq = new SimpleQuery($html);
        $this->assertEquals('Foo title', $sq('.foo')->attr('title'));
        $sq('.foo')->attr('title', 'Change Foo title');
        $this->assertEquals('Change Foo title', $sq('.foo')->attr('title'));
    }
}
