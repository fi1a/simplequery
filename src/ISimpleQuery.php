<?php

declare(strict_types=1);

namespace Fi1a\SimpleQuery;

use DOMDocument;
use Fi1a\Collection\ICollection;

/**
 * Интерфейс SimpleQuery
 */
interface ISimpleQuery extends ICollection
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
     * @return static
     */
    public function setDomDocument(DOMDocument $context): ISimpleQuery;

    /**
     * Поиск используя ХPath
     */
    public function xpath(string ...$selectors): ISimpleQuery;

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
    public function __invoke(): ISimpleQuery;

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
     * @return static
     */
    public function add($selector): ISimpleQuery;

    /**
     * Добавить предыдущий набор элементов в стек к текущему набору
     *
     * @param ISimpleQuery|string|callable|\DOMNode|null $selector
     *
     * @return mixed
     */
    public function addBack($selector = null);

    /**
     * Конец операции фильтрации в текущей цепочке
     */
    public function end(): ISimpleQuery;

    /**
     * Фильтрация
     *
     * @param ISimpleQuery|string|callable|\DOMNode $selector
     *
     * @return static
     */
    public function filter($selector): ISimpleQuery;

    /**
     * Добавляет класс
     *
     * @return static
     */
    public function addClass(string $class): ISimpleQuery;

    /**
     * Возвращает true, если класс есть
     */
    public function hasClass(string $class): bool;

    /**
     * Удаляет класс
     *
     * @return static
     */
    public function removeClass(string $class): ISimpleQuery;

    /**
     * Если класс есть - удаляет, если класса нет - добавляет
     *
     * @return static
     */
    public function toggleClass(string $class): ISimpleQuery;

    /**
     * Поиск
     */
    public function find(string $selector): ISimpleQuery;

    /**
     * Для каждого элемента, получить первый элемент,
     * соответствующий селектору для себя и всех родителей
     *
     * @param ISimpleQuery|string|callable|\DOMNode $selector
     */
    public function closest($selector): ISimpleQuery;

    /**
     * Следующий элемент
     *
     * @param ISimpleQuery|string|callable|\DOMNode|null $selector
     */
    public function next($selector = null): ISimpleQuery;

    /**
     * Все следующие элементы
     *
     * @param ISimpleQuery|string|callable|\DOMNode|null $selector
     */
    public function nextAll($selector = null): ISimpleQuery;

    /**
     * Все следующие элементы до элемента удовлетворяющего селектору
     *
     * @param ISimpleQuery|string|callable|\DOMNode $selector
     */
    public function nextUntil($selector): ISimpleQuery;

    /**
     * Возврашает родительский элемент
     *
     * @param ISimpleQuery|string|callable|\DOMNode|null $selector
     */
    public function parent($selector = null): ISimpleQuery;

    /**
     * Возврашает все родительские элементы
     *
     * @param ISimpleQuery|string|callable|\DOMNode|null $selector
     */
    public function parents($selector = null): ISimpleQuery;

    /**
     * Возврашает все родительские элементы до элемента удовлетворяющего селектору
     *
     * @param ISimpleQuery|string|callable|\DOMNode $selector
     */
    public function parentsUntil($selector): ISimpleQuery;

    /**
     * Предыдущий элемент
     *
     * @param ISimpleQuery|string|callable|\DOMNode|null $selector
     */
    public function prev($selector = null): ISimpleQuery;

    /**
     * Все предыдущие элементы
     *
     * @param ISimpleQuery|string|callable|\DOMNode|null $selector
     */
    public function prevAll($selector = null): ISimpleQuery;

    /**
     * Все предыдущие элементы до элемента удовлетворяющего селектору
     *
     * @param ISimpleQuery|string|callable|\DOMNode $selector
     */
    public function prevUntil($selector): ISimpleQuery;

    /**
     * Соседние элементы
     *
     * @param ISimpleQuery|string|callable|\DOMNode|null $selector
     */
    public function siblings($selector = null): ISimpleQuery;

    /**
     * Добавить после элементов
     *
     * @param string|mixed[]|ISimpleQuery|\DOMNode $html
     *
     * @return static
     */
    public function after($html): ISimpleQuery;

    /**
     * Добавить до элементов
     *
     * @param string|mixed[]|ISimpleQuery|\DOMNode $html
     *
     * @return static
     */
    public function before($html): ISimpleQuery;

    /**
     * Добавить после элементов
     *
     * @param string|ISimpleQuery|\DOMNode $selector
     *
     * @return static
     */
    public function insertAfter($selector): ISimpleQuery;

    /**
     * Добавить до элементов
     *
     * @param string|ISimpleQuery|\DOMNode $selector
     *
     * @return static
     */
    public function insertBefore($selector): ISimpleQuery;

    /**
     * Возвращает дочерние элементы
     *
     * @param ISimpleQuery|string|callable|\DOMNode $selector
     */
    public function children($selector = null): ISimpleQuery;

    /**
     * Возвращает дочерние элементы в том числе и текст
     */
    public function contents(): ISimpleQuery;

    /**
     * Убрать элементы соответствующие селектору
     *
     * @param ISimpleQuery|string|callable|\DOMNode $selector
     */
    public function not($selector): ISimpleQuery;

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
     * @return static
     */
    public function append($html): ISimpleQuery;

    /**
     * Добавление элемента в переданный
     *
     * @param string|ISimpleQuery|\DOMNode $selector
     *
     * @return static
     */
    public function appendTo($selector): ISimpleQuery;

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
     * @return static
     */
    public function prepend($html): ISimpleQuery;

    /**
     * Добавление элемента в начало переданного
     *
     * @param string|ISimpleQuery|\DOMNode $selector
     *
     * @return static
     */
    public function prependTo($selector): ISimpleQuery;

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
     * @return static
     */
    public function removeAttr(string $attribute): ISimpleQuery;

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
     * @return static
     */
    public function css($property, ?string $value = null): ISimpleQuery;

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
     * @return static
     */
    public function removeData($key): ISimpleQuery;

    /**
     * Обернуть структуру вокруг каждого элемента
     *
     * @param string|ISimpleQuery|\DOMNode $html
     *
     * @return static
     */
    public function wrap($html): ISimpleQuery;

    /**
     * Удаляет родительский элемент и помещает на его место
     *
     * @return static
     */
    public function unwrap(): ISimpleQuery;

    /**
     * Обернуть структуру вокруг всех элементов
     *
     * @param string|ISimpleQuery|\DOMNode $html
     *
     * @return static
     */
    public function wrapAll($html): ISimpleQuery;

    /**
     * Обернуть структуру вокруг содержимого элементов
     *
     * @param string|ISimpleQuery|\DOMNode $html
     *
     * @return static
     */
    public function wrapInner($html): ISimpleQuery;

    /**
     * Удаляет элементы
     *
     * @param ISimpleQuery|string|callable|\DOMNode|null $selector
     */
    public function detach($selector = null): ISimpleQuery;

    /**
     * Удаляет все дочерние элементы
     *
     * @return static
     */
    public function clear(): ISimpleQuery;

    /**
     * Удаляет элементы
     *
     * @param ISimpleQuery|string|callable|\DOMNode|null $selector
     */
    public function remove($selector = null): ISimpleQuery;

    /**
     * Заменяет
     *
     * @param string|ISimpleQuery|\DOMNode $selector
     */
    public function replaceAll($selector): ISimpleQuery;

    /**
     * Заменить каждый элемент с помощью нового содержимого
     *
     * @param string|ISimpleQuery|\DOMNode $html
     */
    public function replaceWith($html): ISimpleQuery;

    /**
     * Возвращает элемент с определнным индексом
     */
    public function eq(int $index): ISimpleQuery;

    /**
     * Возвращает первый элемент
     */
    public function first(): ISimpleQuery;

    /**
     * Имеет дочерние элементы удовлетвряющие селектору
     *
     * @param ISimpleQuery|string|callable|\DOMNode $selector
     */
    public function have($selector): ISimpleQuery;

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
     */
    public function last(): ISimpleQuery;

    /**
     * Возвращает подмножество заданных диапазонов индексов
     */
    public function slice(int $start, ?int $end = null): ISimpleQuery;

    /**
     * Устанавливает переменную
     */
    public function setVariable(string $name, string $selector): ISimpleQuery;

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
     */
    public function addVariables(ICollection $variables): ISimpleQuery;

    /**
     * Удаляет переменную
     */
    public function deleteVariable(string $name): bool;

    /**
     * Возвращает все переменные
     */
    public function getVariables(): ICollection;
}
