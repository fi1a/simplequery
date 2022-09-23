<?php

declare(strict_types=1);

namespace Fi1a\SimpleQuery;

use DOMDocument;
use Fi1a\Collection\DataType\IArrayObject;
use Fi1a\Collection\DataType\IMapArrayObject;

/**
 * Интерфейс SimpleQuery
 */
interface ISimpleQuery extends IMapArrayObject
{
    /**
     * Конструктор
     *
     * @param null   $document
     */
    public function __construct($document = null, string $encoding = 'UTF-8');

    /**
     * Возвращает кодировку
     */
    public function getEncoding(): string;

    /**
     * Возвращает контекст
     */
    public function getDomDocument(): DOMDocument;

    /**
     * Устанавливает контекст
     *
     * @return self
     */
    public function setDomDocument(DOMDocument $context);

    /**
     * Поиск, используя ХPath
     *
     * @return static
     */
    public function xpath(string ...$selectors);

    /**
     * Обращение к объекту как к функции
     *
     * Примеры:
     *
     * $sq = new SimpleQuery();
     *
     * $sq('<div/>');
     * $sq(['__tag' => 'div', '__html' => 'html',]);
     *
     * @return static
     */
    public function __invoke();

    /**
     * Возвращает скомпилированный XPath на основе CSS3 селектора
     *
     * @return string|bool
     */
    public function compile(string $selector);

    /**
     * Добавляет элементы в текущий контекст
     *
     * @param string|ISimpleQuery|\DOMNode $selector
     *
     * @return self
     */
    public function add($selector);

    /**
     * Добавить предыдущий набор элементов в стек к текущему набору
     *
     * @param ISimpleQuery|string|callable|\DOMNode|null $selector
     *
     * @return self
     */
    public function addBack($selector = null);

    /**
     * Конец операции фильтрации в текущей цепочке
     *
     * @return static
     */
    public function end();

    /**
     * Фильтрация
     *
     * @param ISimpleQuery|string|callable|\DOMNode $selector
     *
     * @return static
     */
    public function filter($selector);

    /**
     * Добавляет класс
     *
     * @return self
     */
    public function addClass(string $class);

    /**
     * Возвращает true, если класс есть
     */
    public function hasClass(string $class): bool;

    /**
     * Удаляет класс
     *
     * @return self
     */
    public function removeClass(string $class);

    /**
     * Если класс есть - удаляет, если класса нет - добавляет
     *
     * @return self
     */
    public function toggleClass(string $class);

    /**
     * Поиск
     *
     * @return static
     */
    public function find(string $selector);

    /**
     * Для каждого элемента, получить первый элемент,
     * соответствующий селектору для себя и всех родителей
     *
     * @param ISimpleQuery|string|callable|\DOMNode $selector
     *
     * @return static
     */
    public function closest($selector);

    /**
     * Следующий элемент
     *
     * @param ISimpleQuery|string|callable|\DOMNode|null $selector
     *
     * @return static
     */
    public function next($selector = null);

    /**
     * Все следующие элементы
     *
     * @param ISimpleQuery|string|callable|\DOMNode|null $selector
     *
     * @return static
     */
    public function nextAll($selector = null);

    /**
     * Все следующие элементы до элемента, удовлетворяющего селектору
     *
     * @param ISimpleQuery|string|callable|\DOMNode $selector
     *
     * @return static
     */
    public function nextUntil($selector);

    /**
     * Возврашает родительский элемент
     *
     * @param ISimpleQuery|string|callable|\DOMNode|null $selector
     *
     * @return static
     */
    public function parent($selector = null);

    /**
     * Возврашает все родительские элементы
     *
     * @param ISimpleQuery|string|callable|\DOMNode|null $selector
     *
     * @return static
     */
    public function parents($selector = null);

    /**
     * Возврашает все родительские элементы до элемента, удовлетворяющего селектору
     *
     * @param ISimpleQuery|string|callable|\DOMNode $selector
     *
     * @return static
     */
    public function parentsUntil($selector);

    /**
     * Предыдущий элемент
     *
     * @param ISimpleQuery|string|callable|\DOMNode|null $selector
     *
     * @return static
     */
    public function prev($selector = null);

    /**
     * Все предыдущие элементы
     *
     * @param ISimpleQuery|string|callable|\DOMNode|null $selector
     *
     * @return static
     */
    public function prevAll($selector = null);

    /**
     * Все предыдущие элементы до элемента, удовлетворяющего селектору
     *
     * @param ISimpleQuery|string|callable|\DOMNode $selector
     *
     * @return static
     */
    public function prevUntil($selector);

    /**
     * Соседние элементы
     *
     * @param ISimpleQuery|string|callable|\DOMNode|null $selector
     *
     * @return static
     */
    public function siblings($selector = null);

    /**
     * Добавить после элементов
     *
     * @param string|mixed[]|ISimpleQuery|\DOMNode $html
     *
     * @return self
     */
    public function after($html);

    /**
     * Добавить до элементов
     *
     * @param string|mixed[]|ISimpleQuery|\DOMNode $html
     *
     * @return self
     */
    public function before($html);

    /**
     * Добавить после элементов
     *
     * @param string|ISimpleQuery|\DOMNode $selector
     *
     * @return static
     */
    public function insertAfter($selector);

    /**
     * Добавить до элементов
     *
     * @param string|ISimpleQuery|\DOMNode $selector
     *
     * @return static
     */
    public function insertBefore($selector);

    /**
     * Возвращает дочерние элементы
     *
     * @param ISimpleQuery|string|callable|\DOMNode $selector
     *
     * @return static
     */
    public function children($selector = null);

    /**
     * Возвращает дочерние элементы, в том числе и текст
     *
     * @return static
     */
    public function contents();

    /**
     * Убрать элементы, соответствующие селектору
     *
     * @param ISimpleQuery|string|callable|\DOMNode $selector
     *
     * @return static
     */
    public function not($selector);

    /**
     * Добавление элемента
     *
     * Примеры:
     *
     * $sq = new SimpleQuery();
     *
     * $sq('<div/>').append('<div/>');
     * $sq('<div/>').append(['__tag' => 'div', '__html' => 'html',]);
     * $sq('<div/>').append($pq('<div/>'));
     *
     * @param string|mixed[]|ISimpleQuery|\DOMNode $html
     *
     * @return self
     */
    public function append($html);

    /**
     * Добавление элемента в переданный
     *
     * @param string|ISimpleQuery|\DOMNode $selector
     *
     * @return static
     */
    public function appendTo($selector);

    /**
     * Возвращает или устанавливает html
     *
     * @param string|ISimpleQuery|mixed[]|null $html
     *
     * @return string|static
     */
    public function html($html = null);

    /**
     * Возвращает или устанавливает текст
     *
     * @return string|static
     */
    public function text(?string $text = null);

    /**
     * Добавление элемента в начало
     *
     * @param string|mixed[]|ISimpleQuery|\DOMNode $html
     *
     * @return self
     */
    public function prepend($html);

    /**
     * Добавление элемента в начало переданного
     *
     * @param string|ISimpleQuery|\DOMNode $selector
     *
     * @return static
     */
    public function prependTo($selector);

    /**
     * Возвращает или устанавливает значение атрибута
     *
     * @param string|string[] $attribute
     * @param null         $value
     *
     * @return string|static|null
     */
    public function attr($attribute, $value = null);

    /**
     * Удаляет атрибут
     *
     * @param $attribute
     *
     * @return self
     */
    public function removeAttr(string $attribute);

    /**
     * Возвращает или устанавливает значение
     *
     * @param mixed $value
     *
     * @return string|static
     */
    public function val($value = null);

    /**
     * Установить стиль
     *
     * @param string|string[] $property
     *
     * @return self
     */
    public function css($property, ?string $value = null);

    /**
     * Установить или вернуть данные
     *
     * @param string|string[] $key
     * @param mixed|null   $value
     *
     * @return mixed|static
     */
    public function data($key = null, $value = null);

    /**
     * Удаляет данные
     *
     * @param string|string[] $key
     *
     * @return self
     */
    public function removeData($key);

    /**
     * Обернуть структуру вокруг каждого элемента
     *
     * @param string|ISimpleQuery|\DOMNode $html
     *
     * @return self
     */
    public function wrap($html);

    /**
     * Удаляет родительский элемент и помещает на его место
     *
     * @return self
     */
    public function unwrap();

    /**
     * Обернуть структуру вокруг всех элементов
     *
     * @param string|ISimpleQuery|\DOMNode $html
     *
     * @return self
     */
    public function wrapAll($html);

    /**
     * Обернуть структуру вокруг содержимого элементов
     *
     * @param string|ISimpleQuery|\DOMNode $html
     *
     * @return self
     */
    public function wrapInner($html);

    /**
     * Удаляет элементы
     *
     * @param ISimpleQuery|string|callable|\DOMNode|null $selector
     *
     * @return static
     */
    public function detach($selector = null);

    /**
     * Удаляет все дочерние элементы
     *
     * @return self
     */
    public function clear();

    /**
     * Удаляет элементы
     *
     * @param ISimpleQuery|string|callable|\DOMNode|null $selector
     *
     * @return static
     */
    public function remove($selector = null);

    /**
     * Заменяет
     *
     * @param string|ISimpleQuery|\DOMNode $selector
     *
     * @return static
     */
    public function replaceAll($selector);

    /**
     * Заменить каждый элемент с помощью нового содержимого
     *
     * @param string|ISimpleQuery|\DOMNode $html
     *
     * @return static
     */
    public function replaceWith($html);

    /**
     * Возвращает элемент с определенным индексом
     *
     * @return static
     */
    public function eq(int $index);

    /**
     * Возвращает первый элемент
     *
     * @return self
     */
    public function first();

    /**
     * Имеет дочерние элементы, удовлетвряющие селектору
     *
     * @param ISimpleQuery|string|callable|\DOMNode $selector
     *
     * @return static
     */
    public function have($selector);

    /**
     * Проверяет на соответствие селектору
     *
     * @param ISimpleQuery|string|callable|\DOMNode $selector
     *
     * @SuppressWarnings(PHPMD.ShortMethodName)
     */
    public function is($selector): bool;

    /**
     * Возвращает последний элемент
     *
     * @return self
     */
    public function last();

    /**
     * Возвращает подмножество заданных диапазонов индексов
     *
     * @return static
     */
    public function slice(int $start, ?int $end = null);

    /**
     * Устанавливает переменную
     *
     * @return self
     */
    public function setVariable(string $name, string $selector);

    /**
     * Возврашает переменную
     *
     * @return string|bool
     */
    public function getVariable(string $name);

    /**
     * Возвращает true, если переменная есть
     */
    public function hasVariable(string $name): bool;

    /**
     * Добавляет переменные
     *
     * @return self
     */
    public function setVariables(IArrayObject $variables);

    /**
     * Удаляет переменную
     */
    public function deleteVariable(string $name): bool;

    /**
     * Возвращает все переменные
     */
    public function getVariables(): IArrayObject;

    /**
     * Возвращает html документа или общий html наборов
     */
    public function getDocumentHtml(): string;
}
