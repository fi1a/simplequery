<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use DOMDocument;
use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Устанавливает контекст (\DOMDocument).
 */
class SetDomDocumentTest extends TestCase
{
    /**
     * Устанавливает контекст (\DOMDocument).
     */
    public function testSetDomDocument(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/SetDomDocument.html');
        $dom = new DOMDocument('1.0', 'Windows-1251');
        $dom->loadHTML($html);
        $sq = new SimpleQuery();
        $sq->setDomDocument($dom);
        $this->assertEquals('Test', $sq('b')->text());
    }
}
