<?php

declare(strict_types=1);

namespace Fi1a\SimpleQuery;

/**
 * Интерфейс компилятора селекторов
 */
interface ICompileSelector
{
    /**
     * Компилирует CSS3 селекторов в XPath
     *
     * @return string|bool
     */
    public static function compile(string $selector, bool $filter = false);
}
