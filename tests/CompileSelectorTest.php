<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery;

use Fi1a\SimpleQuery\CompileSelector;
use PHPUnit\Framework\TestCase;

/**
 * Тестируем компилятор CSS3 селекторов
 */
class CompileSelectorTest extends TestCase
{
    /**
     * Добавляем функцию компилятор
     */
    public function testAddCompiler(): void
    {
        $this->assertTrue(CompileSelector::addCompiler(function () {
            return true;
        }));
    }
}
