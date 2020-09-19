<?php

declare(strict_types=1);

namespace Fi1a\SimpleQuery;

use Fi1a\Tokenizer\CSS3Selector\Token;
use Fi1a\Tokenizer\CSS3Selector\Tokenizer;
use Fi1a\Tokenizer\ITokenizer;

/**
 * Компилятор CSS3 селекторов
 */
class CompileSelector implements ICompileSelector
{
    public const COMPILER_ERROR = false;

    public const COMPILER_NEXT = true;

    /**
     * @var array
     */
    private static $compilers = [];

    /**
     * @var array
     */
    private static $pseudo = [
        ':first-child' => '[1]',
        ':last-child' => '[last()]',
        ':button' => '[translate(@type, "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz")="button"'
            . ' or name(.)="button"]',
        ':checkbox' => '[translate(@type, "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz")='
            . '"checkbox"]',
        ':checked' => '[@checked]',
        ':disabled' => '[@disabled]',
        ':empty' => '[not(descendant::*) and string-length(.) = 0]',
        ':enabled' => '[(name(.)="input" or name(.)="button") and not(@disabled)]',
        ':selected' => '[@selected]',
        ':even' => '[position() mod 2 = 0]',
        ':odd' => '[position() mod 2 = 1]',
        ':hidden' => '[translate(@type, "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz")="hidden"]',
        ':file' => '[translate(@type, "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz")="file"]',
        ':password' => '[translate(@type, "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz")='
            . '"password"]',
        ':radio' => '[translate(@type, "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz")="radio"]',
        ':reset' => '[translate(@type, "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz")="reset"]',
        ':submit' => '[translate(@type, "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz")="submit"]',
        ':text' => '[translate(@type, "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz")="text"]',
        ':input' => '[name(.)="input" or name(.)="button" or name(.)="textarea" or name(.)="select"]',
        ':first' => '[1]',
        ':last' => '[last()]',
        ':parent' => '/..',
    ];

    /**
     * Добавляет метод компиляции
     */
    public static function addCompiler(callable $compiler): bool
    {
        array_unshift(static::$compilers, $compiler);

        return true;
    }

    /**
     * @return mixed
     */
    private static function callCompiler(Token $token, Tokenizer $tokenizer, bool $filter, bool $find)
    {
        foreach (static::$compilers as $compiler) {
            if (($result = call_user_func($compiler, $token, $tokenizer, $filter, $find)) !== self::COMPILER_NEXT) {
                return $result;
            }
        }

        return self::COMPILER_ERROR;
    }

    /**
     * @inheritDoc
     */
    public static function compile(string $selector, bool $filter = false, bool $find = false)
    {
        $tokenizer = new Tokenizer($selector);
        $xpath = '';
        while (($token = $tokenizer->next()) !== ITokenizer::T_EOF) {
            $result = static::callCompiler($token, $tokenizer, $filter, $find);
            if ($result === self::COMPILER_ERROR) {
                return $result;
            }
            $xpath .= $result;
        }

        return $xpath;
    }

    /**
     * Тег
     *
     * Пример:
     * section
     * section > a
     *
     * @return bool|string
     */
    protected static function compileTag(Token $token, Tokenizer $tokenizer, bool $filter, bool $find)
    {
        if ($token->getType() !== Token::T_TAG) {
            return self::COMPILER_NEXT;
        }
        $prevType = $tokenizer->lookAtPrevType(2);
        $prevImage = $tokenizer->lookAtPrevImage(2);
        $xpath = ($tokenizer->getIndex() > 0 ? '/' : '')
            . static::descendant($prevType, $prevImage, $filter, $find, $token)
            . $token->getImage();
        if ($prevType === Token::T_SIBLING_NEXT) {
            $xpath .= '[1]';
        }
        static::descendantNext($tokenizer);

        return $xpath;
    }

    /**
     * Пропускает два следующих элемента
     */
    protected static function descendantNext(Tokenizer $tokenizer): void
    {
        static $types = [Token::T_DIRECT_CHILD, Token::T_SIBLING_NEXT, Token::T_SIBLING_AFTER];
        if (in_array($tokenizer->lookAtNextType(2), $types)) {
            $tokenizer->next(2);
        }
    }

    /**
     * Определяет XPath селектор элемента
     */
    protected static function descendant(int $type, $image, bool $filter, bool $find, Token $token): string
    {
        if (
            $type === Token::T_SIBLING_NEXT
            || $type === Token::T_SIBLING_AFTER
        ) {
            return 'following-sibling::';
        }
        if ($type !== Token::T_DIRECT_CHILD) {
            if ('*' == $token->getImage() && $type > 0 && '*' !== $image) {
                return 'descendant::';
            }

            if ($filter) {
                return 'self::';
            } elseif ($find) {
                return 'descendant::';
            }

            return 'descendant-or-self::';
        }

        return '';
    }

    /**
     * Ид
     *
     * Пример:
     * #article2
     * div#article2
     *
     * @return bool|string
     */
    protected static function compileId(Token $token, Tokenizer $tokenizer, bool $filter, bool $find)
    {
        if ($token->getType() !== Token::T_ID) {
            return self::COMPILER_NEXT;
        }
        $xpath = static::descendantLine($tokenizer, $filter);
        $xpath .= '[@id="' . mb_substr($token->getImage(), 1) . '"]';
        if ($tokenizer->lookAtPrevType(2) === Token::T_SIBLING_NEXT) {
            $xpath .= '[1]';
        }
        static::descendantNext($tokenizer);

        return $xpath;
    }

    /**
     * Определяет XPath селектор элемента
     */
    protected static function descendantLine(Tokenizer $tokenizer, bool $filter): string
    {
        if (
            $tokenizer->lookAtPrevType(2) === Token::T_SIBLING_NEXT
            || $tokenizer->lookAtPrevType(2) === Token::T_SIBLING_AFTER
        ) {
            return ($tokenizer->getIndex() > 0 ? '/' : '') . 'following-sibling::*';
        }
        if (
            $tokenizer->lookAtPrevType() === Token::T_WHITE_SPACE
            || $tokenizer->lookAtPrevType() === ITokenizer::T_BOF
        ) {
            return ($tokenizer->getIndex() > 0 ? '/' : '') . ($filter ? 'self::*' : 'descendant-or-self::*');
        }

        return '';
    }

    /**
     * Класс
     *
     * Пример:
     * .b-news.e-first
     * div.b-news.e-first
     * #id.b-news.e-first
     *
     * @return bool|string
     */
    protected static function compileClass(Token $token, Tokenizer $tokenizer, bool $filter, bool $find)
    {
        if ($token->getType() !== Token::T_CLASS) {
            return self::COMPILER_NEXT;
        }
        $xpath = static::descendantLine($tokenizer, $filter);
        $class = 'contains(concat(" ",normalize-space(@class), " "), " ' . mb_substr($token->getImage(), 1) . ' ")';
        while (($token = $tokenizer->next()) !== ITokenizer::T_EOF) {
            if ($token->getType() !== Token::T_CLASS) {
                $tokenizer->prev();

                break;
            }
            $class .= ' and contains(concat(" ",normalize-space(@class), " "), " '
                . mb_substr($token->getImage(), 1) . ' ")';
        }
        $xpath .= '[' . $class . ']';
        if ($tokenizer->lookAtPrevType(2) === Token::T_SIBLING_NEXT) {
            $xpath .= '[1]';
        }
        static::descendantNext($tokenizer);

        return $xpath;
    }

    /**
     * Пробелы
     *
     * @return bool|string
     */
    protected static function compileWhitespace(Token $token, Tokenizer $tokenizer, bool $filter, bool $find)
    {
        if ($token->getType() !== Token::T_WHITE_SPACE) {
            return self::COMPILER_NEXT;
        }

        return '';
    }

    /**
     * Атрибут
     *
     * @return bool|string
     */
    protected static function compileAttribute(Token $token, Tokenizer $tokenizer, bool $filter, bool $find)
    {
        if ($token->getType() !== Token::T_OPEN_ATTRIBUTE) {
            return self::COMPILER_NEXT;
        }
        $xpath = static::descendantLine($tokenizer, $filter);
        $nextSibling = $tokenizer->lookAtPrevType(2) === Token::T_SIBLING_NEXT;

        $xpath .= '[';
        $action = false;
        $value = null;
        $attribute = false;
        static $operations = [
            Token::T_EQUAL,
            Token::T_NOT_EQUAL,
            Token::T_CONTAINING,
            Token::T_ENDING_EXACTLY,
            Token::T_BEGINNING_EXACTLY,
            Token::T_EITHER_EQUAL,
            Token::T_CONTAINING_WORD,
        ];
        while (($token = $tokenizer->next()) !== ITokenizer::T_EOF) {
            if ($token->getType() === Token::T_CLOSE_ATTRIBUTE) {
                break;
            }
            if (in_array($token->getType(), $operations)) {
                if (!$attribute) {
                    return self::COMPILER_ERROR;
                }
                $action = true;
                $value = false;
            }
            switch ($token->getType()) {
                case Token::T_ATTRIBUTE:
                    $attribute = '@' . $token->getImage();

                    break;
                case Token::T_EQUAL:
                    $xpath .= $attribute . '="%1$s"';

                    break;
                case Token::T_NOT_EQUAL:
                    $xpath .= 'not(' . $attribute . ') or ' . $attribute . '!="%1$s"';

                    break;
                case Token::T_CONTAINING:
                    $xpath .= 'contains(' . $attribute . ', "%1$s")';

                    break;
                case Token::T_ENDING_EXACTLY:
                    $xpath .= 'substring(' . $attribute . ', string-length(' . $attribute . ')';
                    $xpath .= ' - string-length("%1$s") + 1, string-length("%1$s")) = "%1$s"';

                    break;
                case Token::T_BEGINNING_EXACTLY:
                    $xpath .= 'starts-with(' . $attribute . ', "%1$s")';

                    break;
                case Token::T_EITHER_EQUAL:
                    $xpath .= $attribute . '="%1$s" or starts-with(' . $attribute . ', "%1$s-")';

                    break;
                case Token::T_CONTAINING_WORD:
                    $xpath .= 'contains(concat(" ",normalize-space(' . $attribute . '), " "), " %1$s ")';

                    break;
                case Token::T_ATTRIBUTE_VALUE:
                    $value = true;
                    $xpath = sprintf($xpath, str_replace('"', '\\"', $token->getImage()));

                    break;
            }
        }
        if ($value === false || !$attribute) {
            return self::COMPILER_ERROR;
        }
        $xpath .= ($attribute && $action === false ? $attribute : '') . ']' . ($nextSibling ? '[1]' : '');
        static::descendantNext($tokenizer);

        return $xpath;
    }

    /**
     * Псевдокласс
     *
     * @return bool|string
     */
    protected static function compilePseudo(Token $token, Tokenizer $tokenizer, bool $filter, bool $find)
    {
        if ($token->getType() !== Token::T_PSEUDO) {
            return self::COMPILER_NEXT;
        }
        $xpath = static::descendantLine($tokenizer, $filter);
        $nextSibling = $tokenizer->lookAtPrevType(2) === Token::T_SIBLING_NEXT;
        $result = static::getPseudo($token, $tokenizer);
        if ($result === self::COMPILER_NEXT || $result === self::COMPILER_ERROR) {
            return $result;
        }
        $xpath .= $result;
        if ($nextSibling) {
            $xpath .= '[1]';
        }
        static::descendantNext($tokenizer);

        return $xpath;
    }

    /**
     * Возвращает интерпритацию всевдокласса
     *
     * @return bool|string
     */
    protected static function getPseudo(Token $token, Tokenizer $tokenizer)
    {
        if (isset(static::$pseudo[$token->getImage()])) {
            return static::$pseudo[$token->getImage()];
        }
        $xpath = self::COMPILER_NEXT;
        if ($token->getImage() === ':eq') {
            while (($token = $tokenizer->next()) !== ITokenizer::T_EOF) {
                if ($token->getType() === Token::T_CLOSE_BRACKET) {
                    break;
                }
                if ($token->getType() === Token::T_PSEUDO_VALUE) {
                    $xpath = '[' . (int) $token->getImage() . ']';
                }
            }
        } elseif ($token->getImage() === ':gt') {
            while (($token = $tokenizer->next()) !== ITokenizer::T_EOF) {
                if ($token->getType() === Token::T_CLOSE_BRACKET) {
                    break;
                }
                if ($token->getType() === Token::T_PSEUDO_VALUE) {
                    $xpath = '[position() - 1 > ' . (int) $token->getImage() . ']';
                }
            }
        } elseif ($token->getImage() === ':lt') {
            while (($token = $tokenizer->next()) !== ITokenizer::T_EOF) {
                if ($token->getType() === Token::T_CLOSE_BRACKET) {
                    break;
                }
                if ($token->getType() === Token::T_PSEUDO_VALUE) {
                    $xpath = '[position() - 1 < ' . (int) $token->getImage() . ']';
                }
            }
        } elseif ($token->getImage() === ':contains') {
            while (($token = $tokenizer->next()) !== ITokenizer::T_EOF) {
                if ($token->getType() === Token::T_CLOSE_BRACKET) {
                    break;
                }
                if ($token->getType() === Token::T_PSEUDO_VALUE) {
                    $xpath = '[contains(., "' . str_replace('"', '\\"', $token->getImage()) . '")]';
                }
            }
        }

        return $xpath;
    }

    /**
     * Объединение запросов
     *
     * @return bool|string
     */
    protected static function compileMultiple(Token $token, Tokenizer $tokenizer, bool $filter, bool $find)
    {
        if ($token->getType() !== Token::T_MULTIPLE_SELECTOR) {
            return self::COMPILER_NEXT;
        }

        return '|';
    }
}
