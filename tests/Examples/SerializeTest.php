<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery\Examples;

use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Получить набор элементов формы в виде строки.
 */
class SerializeTest extends TestCase
{
    /**
     * Получить набор элементов формы в виде строки.
     */
    public function testSerialize(): void
    {
        $html = file_get_contents(__DIR__ . '/Fixtures/Serialize.html');
        $sq = new SimpleQuery($html);
        $this->assertEquals(
            'multiple%5B0%5D=Multiple&multiple%5B1%5D=Multiple3&check=check2&radio=radio1',
            $sq(':input')->serialize()
        );
    }
}
