<?php

declare(strict_types=1);

namespace Fi1a\SimpleQuery;

/**
 * Интерфейс компилятора селекторов
 */
interface CompileSelectorInterface
{
    /**
     * Компилирует CSS3 селекторы в XPath
     *
     * @return string|bool
     */
    public static function compile(string $selector, bool $filter = false, bool $find = false);
}
