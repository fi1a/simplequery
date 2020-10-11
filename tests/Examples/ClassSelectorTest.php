<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Выбирает все элементы с указанным классом.
 */
class ClassSelectorTest extends TestCase
{
    /**
     * Найти все элементы с классом "myClass".
     */
    public function testClassSelector(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/ClassSelector.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/ClassSelector-result.html');
        $sq = new SimpleQuery($html);
        $sq('.myClass')->css('border', '3px solid red');
        $this->assertEquals($result, (string) $sq);
    }

    /**
     * Найти все элементы с классом "myClass" и "otherclass".
     */
    public function testClassSelectorTwoClasses(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/ClassSelectorTwoClasses.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/ClassSelectorTwoClasses-result.html');
        $sq = new SimpleQuery($html);
        $sq('.myClass.otherclass')->css('border', '3px solid red');
        $this->assertEquals($result, (string) $sq);
    }
}
