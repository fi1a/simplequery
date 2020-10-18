<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Вызывает функцию, передавая ключ и значение из коллекции.
 */
class EachTest extends TestCase
{
    /**
     * Вызывает функцию, передавая ключ и значение из коллекции.
     */
    public function testEach(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/Each.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/Each-result.html');
        $sq = new SimpleQuery($html);
        $sq('div')->each(function ($div, $index) use ($sq) {
            $sq($div)->text((string) $index);
        });
        $this->assertEquals($result, (string) $sq);
    }
}
