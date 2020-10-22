<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Возвращает true, если переменная есть.
 */
class HasVariableTest extends TestCase
{
    /**
     * Проверить наличие переменной с ключом "news", предварительно установленный.
     */
    public function testHasVariable(): void
    {
        $sq = new SimpleQuery();
        $sq->setVariable('news', 'body > .content > .news');
        $sq->setVariable('title', 'h1');
        $this->assertTrue($sq->hasVariable('news'));
        $this->assertFalse($sq->hasVariable('unknown'));
    }
}
