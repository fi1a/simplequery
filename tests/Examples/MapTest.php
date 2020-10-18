<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Вызывает функцию, передавая ключ и значение из коллекции и заменяет элемент результатом.
 */
class MapTest extends TestCase
{
    /**
     * Вызывает функцию, передавая ключ и значение из коллекции и заменяет элемент результатом.
     */
    public function testMap(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/Map.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/Map-result.html');
        $sq = new SimpleQuery($html);
        $sq('p')->map(function ($div, $index) use ($sq) {
            $parent = $sq($div)->parent();
            if ($parent->hasClass('target')) {
                return $parent->get(0);
            }

            return $div;
        })->css('color', 'red');
        $this->assertEquals($result, (string) $sq);
    }
}
