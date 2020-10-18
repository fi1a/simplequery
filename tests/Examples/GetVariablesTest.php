<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Возвращает все переменные.
 */
class GetVariablesTest extends TestCase
{
    /**
     * Получить коллекцию переменных и затем установить ее.
     */
    public function testGetVariables(): void
    {
        $sq = new SimpleQuery();
        $variables = $sq->getVariables();
        $variables->set('body', '.body');
        $sq->setVariables($variables);
        $this->assertTrue($sq->hasVariable('body'));
    }
}
