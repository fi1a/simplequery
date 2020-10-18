<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Заменить каждый элемент на новое содержимое.
 */
class ReplaceWithTest extends TestCase
{
    /**
     * Заменить кнопку на div.
     */
    public function testReplaceWith(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/ReplaceWith.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/ReplaceWith-result.html');
        $sq = new SimpleQuery($html);
        $sq('button')->each(function ($button) use ($sq) {
            $sq($button)->replaceWith('<div>' . $sq($button)->text() . '</div>');
        });
        $this->assertEquals($result, (string) $sq);
    }
}
