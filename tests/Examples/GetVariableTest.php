<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Возврашает переменную.
 */
class GetVariableTest extends TestCase
{
    /**
     * Получить переменную с ключом "news" предварительно установленную.
     */
    public function testGetVariable(): void
    {
        $sq = new SimpleQuery();
        $sq->setVariable('news', 'body > .content > .news');
        $sq->setVariable('title', 'h1');
        $this->assertEquals('body > .content > .news', $sq->getVariable('news'));
    }
}
