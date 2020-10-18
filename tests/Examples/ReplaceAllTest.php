<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Заменить каждый элемент в наборе.
 */
class ReplaceAllTest extends TestCase
{
    /**
     * Заменить все параграфы.
     */
    public function testReplaceAll(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/ReplaceAll.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/ReplaceAll-result.html');
        $sq = new SimpleQuery($html);
        $sq('<b>Paragraph. </b>')->replaceAll('p');
        $this->assertEquals($result, (string) $sq);
    }
}
