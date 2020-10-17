<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Выбирает все input, textarea, select и button элементы.
 */
class InputSelectorTest extends TestCase
{
    /**
     * Найти все элементы (input, textarea, select и button).
     */
    public function testInputSelector(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/InputSelector.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/InputSelector-result.html');
        $sq = new SimpleQuery($html);
        $allInputs = $sq(':input');
        $formChildren = $sq('form > *');
        $sq('#messages')->text(
            'Found ' . count($allInputs) . ' inputs and the form has ' . count($formChildren) . ' children.'
        );
        $this->assertEquals($result, (string) $sq);
    }
}
