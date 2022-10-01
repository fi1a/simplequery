<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Отобразить элементы.
 */
class ShowTest extends TestCase
{
    /**
     * Отобразить скрытый параграф.
     */
    public function testShow(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/Show.html');
        $result = file_get_contents(__DIR__ . '/Fixtures/Show-result.html');
        $sq = new SimpleQuery($html);
        $sq('p')->show();
        $this->assertEquals($result, (string) $sq);
    }
}
