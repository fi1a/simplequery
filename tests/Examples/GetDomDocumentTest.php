<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Возвращает контекст (\DOMDocument).
 */
class GetDomDocumentTest extends TestCase
{
    /**
     * Возвращает контекст (\DOMDocument).
     */
    public function testGetDomDocument(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/GetDomDocument.html');
        $sq = new SimpleQuery($html);
        $this->assertEquals('html', $sq->getDomDocument()->doctype->name);
    }
}
