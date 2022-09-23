<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\Collection\DataType\ArrayObject;
use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Удаляет переменную.
 */
class DeleteVariableTest extends TestCase
{
    /**
     * Удаляет переменную.
     */
    public function testDeleteVariable(): void
    {
        $sq = new SimpleQuery();
        $variables = new ArrayObject([
            'news' => 'body > .content > .news',
            'title' => 'h1',
        ]);
        $sq->setVariables($variables);

        $this->assertTrue($sq->hasVariable('news'));
        $sq->deleteVariable('news');
        $this->assertFalse($sq->hasVariable('news'));
    }
}
