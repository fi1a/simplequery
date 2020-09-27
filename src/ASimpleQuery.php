<?php

declare(strict_types=1);

namespace Fi1a\SimpleQuery;

use Closure;
use DOMDocument;
use DOMDocumentFragment;
use DOMNode;
use DOMXPath;
use Fi1a\Collection\Collection;
use Fi1a\Collection\DataType\TArrayObject;
use Fi1a\Collection\ICollection;
use Fi1a\Collection\TCollection;
use Fi1a\Format\Formatter;
use Fi1a\SimpleQuery\Exception\ErrorException;
use tidy;

use const ENT_HTML5;
use const ENT_QUOTES;

/**
 * Абстрактный класс SimpleQuery
 */
abstract class ASimpleQuery implements ISimpleQuery
{
    use TArrayObject;
    use TCollection;

    /**
     * @var array
     */
    private $variables = [];

    /**
     * @var string
     */
    private $encoding = 'UTF-8';

    /**
     * @var \DOMDocument
     */
    private $domDocument = null;

    /**
     * @var \DOMXPath
     */
    private $xpath = null;

    /**
     * @var \DOMDocumentFragment[]
     */
    private $fragments = [];

    /**
     * @var ISimpleQuery|null
     */
    private $source = null;

    /**
     * @var ISimpleQuery|null
     */
    private $end = null;

    /**
     * @var \tidy
     */
    private $tidy = null;

    /**
     * @inheritDoc
     */
    public function __construct($document = null, string $encoding = 'UTF-8')
    {
        $this->setEncoding($encoding);
        $this->variables = new Collection();
        $this->tidy = new tidy();
        if ($document instanceof DOMDocument) {
            $this->setDomDocument($document);

            return;
        }
        $this->setDomDocument(new DOMDocument('1.0', $this->getEncoding()));
        if (is_null($document) || !$document) {
            $document = '<!DOCTYPE html><html><head></head><body></body></html>';
        }
        libxml_use_internal_errors(true);
        $dom = $this->getDomDocument();
        $dom->loadHTML($document);
        $this->setDomDocument($dom);
        libxml_clear_errors();
    }

    /**
     * Устанавдивает кодировку
     *
     * @return $this
     */
    private function setEncoding(string $encoding): self
    {
        $this->encoding = $encoding;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getEncoding(): string
    {
        return $this->encoding;
    }

    /**
     * @inheritDoc
     */
    public function setDomDocument(DOMDocument $domDocument): ISimpleQuery
    {
        $this->domDocument = $domDocument;
        $xpath = new DOMXPath($domDocument);
        $this->setXpath($xpath);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getDomDocument(): DOMDocument
    {
        return $this->domDocument;
    }

    /**
     * Вернуть экземпляр класса \DOMXPath
     */
    private function getXpath(): DOMXPath
    {
        return $this->xpath;
    }

    /**
     * Установить экземпляр класса \DOMXPath
     *
     * @return $this
     */
    private function setXpath(DOMXPath $xpath): self
    {
        $this->xpath = $xpath;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function __invoke(): ISimpleQuery
    {
        $instance = $this->getArgument(func_get_arg(0), [$this->getDomDocument()->documentElement]);
        $this->setEndClosure($instance, $instance);

        return $instance;
    }

    /**
     * Возвращает переданный контекст относительно документа
     *
     * @param string|ISimpleQuery $selector
     * @param mixed[]               $contexts
     */
    protected function getArgument($selector, array $contexts): ISimpleQuery
    {
        if ($selector instanceof DOMNode) {
            return $this->factory($this, [$selector], $this->getFragments());
        }
        if ($selector instanceof ISimpleQuery) {
            return clone $selector;
        }
        if (is_array($selector)) {
            $selector = $this->getHtmlUsingArray($selector);
        }
        if ($selector && $selector[0] === '<') {
            return $this->createFromHtml($this->factory($this, []), $selector);
        }
        $xpath = $this->compile($selector);
        if ($xpath === false) {
            throw new ErrorException('CSS3 Selector syntax error');
        }
        $lists = $this->contextQueryXPath($xpath, $contexts);

        return $this->factory($this, $lists, $this->getFragments());
    }

    /**
     * @inheritDoc
     */
    public function xpath(): ISimpleQuery
    {
        $selectors = func_get_args();
        $lists = [];
        $contexts = $this;
        if (!count($contexts)) {
            $contexts = [$this->getDomDocument()->documentElement];
        }
        foreach ($selectors as $selector) {
            $lists = array_merge($lists, $this->contextQueryXPath($selector, $contexts));
        }

        return $this->factory($this, $lists, $this->getFragments());
    }

    /**
     * Выполняет xpath запрос для контекста
     *
     * @param mixed[]|ISimpleQuery $contexts
     *
     * @return DOMNode[]
     */
    protected function contextQueryXPath(string $selector, $contexts): array
    {
        $lists = [];
        foreach ($contexts as $context) {
            $lists = array_merge($lists, $this->queryXPath($selector, $context));
        }

        return $lists;
    }

    /**
     * Выполняет xpath запрос
     *
     * @return DOMNode[]
     *
     * @throws ErrorException
     */
    protected function queryXPath(string $selector, ?DOMNode $context = null): array
    {
        $list = $this->getXpath()->query($selector, $context);
        if ($list === false) {
            return [];
        }
        $lists = [];
        for ($ind = 0; $ind < $list->length; $ind++) {
            $lists[] = $list->item($ind);
        }

        return $lists;
    }

    /**
     * Восстановление html
     */
    protected function repairString(string $html): string
    {
        static $encodings = [
            'UTF-8' => 'utf8',
            'UTF-16' => 'utf16',
        ];
        $encoding = $encodings[$this->getEncoding()] ?? $this->getEncoding();
        $encoding = mb_strtolower($encoding);

        return $this->tidy->repairString(
            $html,
            [
                'output-xml' => true,
                'input-xml' => true,
                'wrap' => 0,
            ],
            $encoding
        );
    }

    /**
     * Создание на основе html
     */
    protected function createFromHtml(ISimpleQuery $sqInstance, string $html): ISimpleQuery
    {
        $fragment = $sqInstance->getDomDocument()->createDocumentFragment();
        if ($fragment->appendXML($html) === false) {
            $fragment->appendXML($this->repairString($html));
        }

        $lists = [];
        for ($ind = 0; $ind < $fragment->childNodes->length; $ind++) {
            $lists[] = $fragment->childNodes->item($ind);
        }
        $sqInstance->exchangeArray($lists);
        $func = Closure::bind(
            function (DOMDocumentFragment $fragment) {
                $fragments = $this->getFragments();
                $fragments[] = $fragment;
                $this->setFragments($fragments);
            },
            $sqInstance,
            SimpleQuery::class
        );
        $func($fragment);

        return $sqInstance;
    }

    /**
     * Формирует html на основе массива
     *
     * @param string[] $document
     *
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function getHtmlUsingArray(array $document): string
    {
        $html = $this->getTagOpen($document, $this->getTagAttributes($document));
        $html .= $this->getTagBody($document);
        $html .= $this->getTagClose($document);

        return $html;
    }

    /**
     * Формирует закрывающийся тег
     *
     * @param string[] $document
     */
    protected function getTagClose(array $document): string
    {
        if (!isset($document['__tag']) || !isset($document['__html'])) {
            return '';
        }

        return sprintf('</%s>', $document['__tag']);
    }

    /**
     * Формирует содержание тега
     *
     * @param string[] $document
     */
    protected function getTagBody(array $document): string
    {
        if (!isset($document['__html'])) {
            return '';
        }
        if (!is_array($document['__html'])) {
            return $this->convertValue($document['__html']);
        }
        $html = '';
        foreach ($document['__html'] as $value) {
            $html .= $this->getHtmlUsingArray($value);
        }

        return $html;
    }

    /**
     * Формирует открывающий тег
     *
     * @param string[]  $document
     */
    protected function getTagOpen(array $document, string $attributes): string
    {
        if (!isset($document['__tag'])) {
            return '';
        }
        $html = sprintf('<%s %s', $document['__tag'], $attributes);
        if (!isset($document['__html'])) {
            return $html . ' />';
        }

        return $html . '>';
    }

    /**
     * Формирует атрибуты тега из массива
     *
     * @param string[] $document
     */
    protected function getTagAttributes(array $document): string
    {
        $attributes = '';
        foreach ($document as $name => $value) {
            if ($name === '__tag' || $name === '__html') {
                continue;
            }
            $attributes .= ($attributes ? ' ' : '') . $name . '="'
                . htmlspecialchars($this->convertValue($value), ENT_QUOTES | ENT_HTML5) . '"';
        }

        return $attributes;
    }

    /**
     * @param string|mixed[]|ISimpleQuery|\DOMNode $html
     */
    protected function getInsertion($html): ISimpleQuery
    {
        if (is_array($html)) {
            $html = $this->getHtmlUsingArray($html);
        }
        if (is_string($html)) {
            $html = $this->createFromHtml($this->factory($this, []), $html);
        }
        if ($html instanceof DOMNode) {
            $html = $this->factory($this, [$html]);
        }

        return $html;
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        $contexts = $this;
        if (!count($contexts)) {
            return $this->getDomDocument()->saveHTML();
        }
        $html = '';
        foreach ($contexts as $context) {
            $html .= $this->getDomDocument()->saveHTML($context);
        }

        return $html;
    }

    /**
     * Фабричный метод
     *
     * @param \DOMNode[] $contexts
     * @param \DOMDocumentFragment[] $fragments
     */
    protected function factory(ISimpleQuery $source, array $contexts, array $fragments = []): ISimpleQuery
    {
        $instance = new static($source->getDomDocument(), $source->getEncoding());
        $instance->exchangeArray($contexts);
        $func = Closure::bind(
            function (ISimpleQuery $source, $end, array $fragments = []) {
                $this->setFragments($fragments);
                $this->setSource($source);
                $this->setEnd($end);
            },
            $instance,
            SimpleQuery::class
        );
        $func($source, $this->getEndClosure($source), $fragments);
        $instance->addVariables($source->getVariables());

        return $instance;
    }

    /**
     * Возвращает фрагменты
     *
     * @return \DOMDocumentFragment[]
     */
    protected function getFragments(): array
    {
        return $this->fragments;
    }

    /**
     * Устанавливает фрагменты
     *
     * @param \DOMDocumentFragment[] $fragments
     *
     * @return $this
     */
    protected function setFragments(array $fragments): self
    {
        $this->fragments = $fragments;

        return $this;
    }

    /**
     * Вернуть предыдущий экземпляр класса
     */
    protected function getSource(): ?ISimpleQuery
    {
        return $this->source;
    }

    /**
     * Установить предыдущий
     *
     * @return $this
     */
    protected function setSource(?ISimpleQuery $source = null): self
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Вернуть экземпляр кдасса для метода end
     */
    protected function getEnd(): ?ISimpleQuery
    {
        return $this->end;
    }

    /**
     * Установить экземпляр кдасса для метода end
     *
     * @return $this
     */
    protected function setEnd(?ISimpleQuery $end): self
    {
        $this->end = $end;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setVariable(string $name, string $selector): ISimpleQuery
    {
        $this->variables[$name] = $selector;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getVariable(string $name)
    {
        if (!$this->hasVariable($name)) {
            return false;
        }

        return $this->variables[$name];
    }

    /**
     * @inheritDoc
     */
    public function hasVariable(string $name): bool
    {
        return $this->variables->has($name);
    }

    /**
     * @inheritDoc
     */
    public function addVariables(ICollection $variables): ISimpleQuery
    {
        $this->variables = $variables;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function deleteVariable(string $name): bool
    {
        if (!$this->hasVariable($name)) {
            return false;
        }
        unset($this->variables[$name]);

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getVariables(): ICollection
    {
        return $this->variables;
    }

    /**
     * Установить экземпляр класса для метода end
     */
    protected function setEndClosure(ISimpleQuery $instance, ISimpleQuery $end): void
    {
        $func = Closure::bind(
            function (ISimpleQuery $end) {
                $this->setEnd($end);
            },
            $instance,
            SimpleQuery::class
        );
        $func($end);
    }

    /**
     * Устанавливает экземпляр класса для метода addBack
     */
    protected function setSourceClosure(ISimpleQuery $instance, ISimpleQuery $source): void
    {
        $func = Closure::bind(
            function (ISimpleQuery $source) {
                $this->setSource($source);
            },
            $instance,
            SimpleQuery::class
        );
        $func($source);
    }

    /**
     * Возвращает экземпляр класса для метода end
     */
    protected function getEndClosure(ISimpleQuery $instance): ?ISimpleQuery
    {
        $func = Closure::bind(
            function () {
                return $this->getEnd();
            },
            $instance,
            SimpleQuery::class
        );

        return $func();
    }

    /**
     * Возвращает фрагменты
     *
     * @return \DOMDocumentFragment[]
     */
    protected function getFragmentsClosure(ISimpleQuery $instance): array
    {
        $getFragments = Closure::bind(
            function () {
                return $this->getFragments();
            },
            $instance,
            SimpleQuery::class
        );

        return $getFragments();
    }

    /**
     * Конвертирует значения в строку
     *
     * @param mixed $value
     */
    protected function convertValue($value): string
    {
        if (is_null($value)) {
            return '';
        }
        if (is_array($value)) {
            return json_encode($value);
        }
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }
        if (is_object($value) && !method_exists($value, '__toString')) {
            return get_class($value);
        }
        if ($value === 0) {
            return '0';
        }

        return (string) $value;
    }

    /**
     * Восстанавливает значение из строки
     *
     * @param mixed $value
     *
     * @return mixed
     */
    protected function restoreValue($value)
    {
        $restore = json_decode($value, true);
        if (is_null($restore) && $value) {
            $restore = $value;
        }

        return $restore;
    }

    /**
     * @inheritDoc
     */
    public function compile(string $selector)
    {
        return CompileSelector::compile(Formatter::format($selector, $this->getVariables()->getArrayCopy()));
    }

    /**
     * @inheritDoc
     */
    public function add($selector): ISimpleQuery
    {
        $sqInstance = $this->getArgument($selector, [$this->getDomDocument()->documentElement]);
        $this->exchangeArray(array_merge($this->getArrayCopy(), $sqInstance->getArrayCopy()));

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function filter($selector): ISimpleQuery
    {
        if (is_callable($selector) && !($selector instanceof ISimpleQuery)) {
            return $this->callbackFilter($selector);
        }
        if (is_string($selector)) {
            return $this->selectorFilter($selector);
        }
        $filter = [];
        if ($selector instanceof DOMNode) {
            $filter = [$selector];
        }
        if ($selector instanceof ISimpleQuery) {
            $filter = $selector->getArrayCopy();
        }

        return $this->elementFilter($filter);
    }

    /**
     * Фильтрация на основе элементов
     *
     * @param DOMNode[] $filter
     */
    protected function elementFilter(array $filter): ISimpleQuery
    {
        $lists = [];
        foreach ($this as $context) {
            foreach ($filter as $node) {
                if ($context !== $node) {
                    continue;
                }
                $lists[] = $context;

                break;
            }
        }

        return $this->factory($this, $lists, $this->getFragments());
    }

    /**
     * Фильтрация элементов на основе селектора
     */
    protected function selectorFilter(string $selector): ISimpleQuery
    {
        $xpath = CompileSelector::compile(Formatter::format($selector, $this->getVariables()->getArrayCopy()), true);
        if ($xpath === false) {
            throw new ErrorException('CSS3 Selector syntax error');
        }

        $lists = [];
        foreach ($this as $context) {
            $query = $this->queryXPath($xpath, $context);
            if (!count($query)) {
                continue;
            }
            $lists[] = $context;
        }

        return $this->factory($this, $lists, $this->getFragments());
    }

    /**
     * Фильтрация элементов на основе callback функции
     */
    protected function callbackFilter(callable $callback): ISimpleQuery
    {
        $lists = [];
        foreach ($this as $ind => $node) {
            if (call_user_func($callback, $ind, $node) === true) {
                $lists[] = $node;
            }
        }

        return $this->factory($this, $lists, $this->getFragments());
    }

    /**
     * @inheritDoc
     */
    public function find(string $selector): ISimpleQuery
    {
        $contexts = $this;
        if (!count($contexts)) {
            $contexts = [$this->getDomDocument()->documentElement];
        }
        $xpath = CompileSelector::compile(
            Formatter::format($selector, $this->getVariables()->getArrayCopy()),
            false,
            true
        );
        if ($xpath === false) {
            throw new ErrorException('CSS3 Selector syntax error');
        }
        $lists = $this->contextQueryXPath($xpath, $contexts);

        return $this->factory($this, $lists, $this->getFragments());
    }

    /**
     * @inheritDoc
     */
    public function addBack($selector = null): self
    {
        $source = $this->getSource();
        if (!$source) {
            return $this;
        }
        if (!is_null($selector)) {
            $source = $source->filter($selector);
            $this->setSourceClosure($source, $this);
        }
        $this->exchangeArray(array_merge($source->getArrayCopy(), $this->getArrayCopy()));
        $this->setFragments(array_merge($this->getFragments(), $this->getFragmentsClosure($source)));

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function end(): ISimpleQuery
    {
        $end = $this->getEnd();
        if ($end) {
            return $this->factory($end, $end->getArrayCopy(), $this->getFragmentsClosure($end));
        }

        return $this->factory($this, []);
    }

    /**
     * Преобразует строку из "StringHelper" в "string_helper"
     *
     * @param string $value     значение для преобразования
     * @param string $delimiter разделитель между словами
     */
    protected function humanize(string $value, string $delimiter = '_'): string
    {
        $result = mb_strtolower(preg_replace('/(?<=\w)([A-Z])/m', '_\\1', (string) $value));
        $search = '\\';
        if (strpos($search, $result) === false) {
            $search = '_';
        }

        return str_replace($search, $delimiter, $result);
    }

    /**
     * Преобразует строку из ("string_helper" или "string.helper" или "string-helper") в "stringHelper"
     *
     * @param string $value значение для преобразования
     */
    protected function camelize(string $value, string $delimiter = ''): string
    {
        return lcfirst($this->classify($value, $delimiter));
    }

    /**
     * Преобразует строку из ("string_helper" или "string.helper" или "string-helper") в "StringHelper"
     *
     * @param string $value значение для преобразования
     */
    protected function classify(string $value, string $delimiter = ''): string
    {
        return trim(preg_replace_callback('/(^|_|\.|\-|\/)([a-z ]+)/im', function ($matches) use ($delimiter) {
            return ucfirst(mb_strtolower($matches[2])) . $delimiter;
        }, $value . ' '), ' ' . $delimiter);
    }
}
