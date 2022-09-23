<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\Collection\DataType\ArrayObject;
use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Устанавливает переменные.
 */
class SetVariablesTest extends TestCase
{
    /**
     * Задать переменные новой коллекцией.
     */
    public function testSetVariables(): void
    {
        $sq = new SimpleQuery();
        $variables = new ArrayObject([
            'news' => 'body > .content > .news',
            'title' => 'h1',
        ]);
        $sq->setVariables($variables);
        $this->assertTrue($sq->hasVariable('news'));
    }
}
