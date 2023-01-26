<?php

declare(strict_types=1);

namespace Fi1a\SimpleQuery;

use DOMDocument;
use Fi1a\Collection\DataType\ArrayObjectInterface;
use Fi1a\Collection\DataType\MapArrayObjectInterface;

/**
 * Интерфейс SimpleQuery
 */
interface SimpleQueryInterface extends MapArrayObjectInterface
{
    /**
     * Конструктор
     *
     * @param mixed $document
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
     * @param string|SimpleQueryInterface|\DOMNode $selector
     *
     * @return static
     */
    public function add($selector);

    /**
     * Добавить предыдущий набор элементов в стек к текущему набору
     *
     * @param SimpleQueryInterface|string|callable|\DOMNode|null $selector
     *
     * @return static
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
     * @param SimpleQueryInterface|string|callable|\DOMNode $selector
     *
     * @return static
     */
    public function filter($selector);

    /**
     * Добавляет класс
     *
     * @return static
     */
    public function addClass(string $class);

    /**
     * Возвращает true, если класс есть
     */
    public function hasClass(string $class): bool;

    /**
     * Удаляет класс
     *
     * @return static
     */
    public function removeClass(string $class);

    /**
     * Если класс есть - удаляет, если класса нет - добавляет
     *
     * @return static
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
     * @param SimpleQueryInterface|string|callable|\DOMNode $selector
     *
     * @return static
     */
    public function closest($selector);

    /**
     * Следующий элемент
     *
     * @param SimpleQueryInterface|string|callable|\DOMNode|null $selector
     *
     * @return static
     */
    public function next($selector = null);

    /**
     * Все следующие элементы
     *
     * @param SimpleQueryInterface|string|callable|\DOMNode|null $selector
     *
     * @return static
     */
    public function nextAll($selector = null);

    /**
     * Все следующие элементы до элемента, удовлетворяющего селектору
     *
     * @param SimpleQueryInterface|string|callable|\DOMNode $selector
     *
     * @return static
     */
    public function nextUntil($selector);

    /**
     * Возврашает родительский элемент
     *
     * @param SimpleQueryInterface|string|callable|\DOMNode|null $selector
     *
     * @return static
     */
    public function parent($selector = null);

    /**
     * Возврашает все родительские элементы
     *
     * @param SimpleQueryInterface|string|callable|\DOMNode|null $selector
     *
     * @return static
     */
    public function parents($selector = null);

    /**
     * Возвращает все родительские элементы до элемента, удовлетворяющего селектору
     *
     * @param SimpleQueryInterface|string|callable|\DOMNode $selector
     *
     * @return static
     */
    public function parentsUntil($selector);

    /**
     * Предыдущий элемент
     *
     * @param SimpleQueryInterface|string|callable|\DOMNode|null $selector
     *
     * @return static
     */
    public function prev($selector = null);

    /**
     * Все предыдущие элементы
     *
     * @param SimpleQueryInterface|string|callable|\DOMNode|null $selector
     *
     * @return static
     */
    public function prevAll($selector = null);

    /**
     * Все предыдущие элементы до элемента, удовлетворяющего селектору
     *
     * @param SimpleQueryInterface|string|callable|\DOMNode $selector
     *
     * @return static
     */
    public function prevUntil($selector);

    /**
     * Соседние элементы
     *
     * @param SimpleQueryInterface|string|callable|\DOMNode|null $selector
     *
     * @return static
     */
    public function siblings($selector = null);

    /**
     * Добавить после элементов
     *
     * @param string|mixed[]|SimpleQueryInterface|\DOMNode $html
     *
     * @return static
     */
    public function after($html);

    /**
     * Добавить до элементов
     *
     * @param string|mixed[]|SimpleQueryInterface|\DOMNode $html
     *
     * @return static
     */
    public function before($html);

    /**
     * Добавить после элементов
     *
     * @param string|SimpleQueryInterface|\DOMNode $selector
     *
     * @return static
     */
    public function insertAfter($selector);

    /**
     * Добавить до элементов
     *
     * @param string|SimpleQueryInterface|\DOMNode $selector
     *
     * @return static
     */
    public function insertBefore($selector);

    /**
     * Возвращает дочерние элементы
     *
     * @param SimpleQueryInterface|string|callable|\DOMNode $selector
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
     * @param SimpleQueryInterface|string|callable|\DOMNode $selector
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
     * @param string|mixed[]|SimpleQueryInterface|\DOMNode $html
     *
     * @return self
     */
    public function append($html);

    /**
     * Добавление элемента в переданный
     *
     * @param string|SimpleQueryInterface|\DOMNode $selector
     *
     * @return static
     */
    public function appendTo($selector);

    /**
     * Возвращает или устанавливает html
     *
     * @param string|SimpleQueryInterface|mixed[]|null $html
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
     * @param string|mixed[]|SimpleQueryInterface|\DOMNode $html
     *
     * @return static
     */
    public function prepend($html);

    /**
     * Добавление элемента в начало переданного
     *
     * @param string|SimpleQueryInterface|\DOMNode $selector
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
     * @return static
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
     * @param string|string[]|null $property
     * @param string|bool|null $value
     *
     * @return static
     */
    public function css($property = null, $value = null);

    /**
     * Показать элементы
     *
     * @return static
     */
    public function show();

    /**
     * Скрыть элементы
     *
     * @return static
     */
    public function hide();

    /**
     * Показать или скрыть выбранные элементы
     *
     * @return static
     */
    public function toggle();

    /**
     * Получить набор элементов формы как массив с именами и значениями.
     *
     * @return string[]
     */
    public function serializeArray(): array;

    /**
     * Получить набор элементов формы в виде вложенного массива
     *
     * @return mixed[]
     */
    public function serializeNested(): array;

    /**
     * Получить набор элементов формы в виде строки.
     */
    public function serialize(): string;

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
    public function removeData($key);

    /**
     * Обернуть структуру вокруг каждого элемента
     *
     * @param string|SimpleQueryInterface|\DOMNode $html
     *
     * @return static
     */
    public function wrap($html);

    /**
     * Удаляет родительский элемент и помещает на его место
     *
     * @return static
     */
    public function unwrap();

    /**
     * Обернуть структуру вокруг всех элементов
     *
     * @param string|SimpleQueryInterface|\DOMNode $html
     *
     * @return static
     */
    public function wrapAll($html);

    /**
     * Обернуть структуру вокруг содержимого элементов
     *
     * @param string|SimpleQueryInterface|\DOMNode $html
     *
     * @return static
     */
    public function wrapInner($html);

    /**
     * Удаляет элементы
     *
     * @param SimpleQueryInterface|string|callable|\DOMNode|null $selector
     *
     * @return static
     */
    public function detach($selector = null);

    /**
     * Удаляет элементы
     *
     * @param SimpleQueryInterface|string|callable|\DOMNode|null $selector
     *
     * @return static
     */
    public function remove($selector = null);

    /**
     * Удалите все дочерние узлы элементов из DOM.
     *
     * @return static
     */
    public function empty();

    /**
     * Заменяет
     *
     * @param string|SimpleQueryInterface|\DOMNode $selector
     *
     * @return static
     */
    public function replaceAll($selector);

    /**
     * Заменить каждый элемент с помощью нового содержимого
     *
     * @param string|SimpleQueryInterface|\DOMNode $html
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
     * Возвращает индекс элемента среди выбранных
     *
     * @param string|SimpleQueryInterface|\DOMNode $selector
     *
     * @return int|bool
     */
    public function index($selector);

    /**
     * Возвращает первый элемент
     *
     * @return self
     */
    public function first();

    /**
     * Имеет дочерние элементы, удовлетвряющие селектору
     *
     * @param SimpleQueryInterface|string|callable|\DOMNode $selector
     *
     * @return static
     */
    public function have($selector);

    /**
     * Проверяет на соответствие селектору
     *
     * @param SimpleQueryInterface|string|callable|\DOMNode $selector
     *
     * @SuppressWarnings(PHPMD.ShortMethodName)
     */
    public function is($selector): bool;

    /**
     * Возвращает последний элемент
     *
     * @return static
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
    public function setVariables(ArrayObjectInterface $variables);

    /**
     * Удаляет переменную
     */
    public function deleteVariable(string $name): bool;

    /**
     * Возвращает все переменные
     */
    public function getVariables(): ArrayObjectInterface;

    /**
     * Возвращает html документа или общий html наборов
     */
    public function getDocumentHtml(): string;

    /**
     * Выбрать четные элементы
     *
     * @return static
     */
    public function even();

    /**
     * Выбрать нечетные элементы
     *
     * @return static
     */
    public function odd();
}
