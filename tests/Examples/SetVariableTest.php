<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Устанавливает переменную.
 */
class SetVariableTest extends TestCase
{
    /**
     * Установить переменные с ключами "news" и "title".
     */
    public function testSetVariable(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/SetVariable.html');
        $sq = new SimpleQuery($html);
        $sq->setVariable('news', 'body > .content > .news');
        $sq->setVariable('title', 'h1');
        $this->assertEquals('News title', $sq('{{news|unescape}} > {{title}}')->text());
    }
}
