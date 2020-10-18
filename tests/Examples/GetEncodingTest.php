<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Возвращает кодировку документа.
 */
class GetEncodingTest extends TestCase
{
    /**
     * Получить установленную кодировку документа.
     */
    public function testGetEncoding(): void
    {
        $sq = new SimpleQuery();
        $this->assertEquals('UTF-8', $sq->getEncoding());
        $sq = new SimpleQuery(null, 'Windows-1251');
        $this->assertEquals('Windows-1251', $sq->getEncoding());
    }
}
