<?php

declare(strict_types=1);

namespace Fi1a\SimpleQuery;

use DOMNode;
use Fi1a\SimpleQuery\Exception\ErrorException;

use const ENT_HTML5;
use const ENT_QUOTES;

/**
 * Работа с элементами
 */
abstract class AInsertion extends ASimpleQuery
{
    /**
     * @inheritDoc
     */
    public function unwrap(): ISimpleQuery
    {
        if (!count($this)) {
            return $this;
        }
        foreach ($this as $ind => $context) {
            /**
             * @var $context \DOMElement
             */
            if (!$context->parentNode->parentNode) {
                continue;
            }
            $this[$ind] = $context->parentNode->parentNode->replaceChild($context, $context->parentNode);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function wrapAll($html): ISimpleQuery
    {
        $html = $this->getInsertion($html);
        if (!count($this) || !count($html)) {
            return $this;
        }
        /**
         * @var $node \DOMElement
         */
        $node = $this[0]->parentNode->insertBefore($html[0], $this[0]);
        foreach ($this as $ind => $context) {
            $this[$ind] = $node->appendChild($context);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function wrapInner($html): ISimpleQuery
    {
        $html = $this->getInsertion($html);
        if (!count($this) || !count($html)) {
            return $this;
        }
        foreach ($this as $context) {
            /**
             * @var $context \DOMElement
             * @var $node    \DOMElement
             */
            $node = $html[0]->cloneNode(true);
            while ($sibling = $context->firstChild) {
                $node->appendChild($sibling);
            }
            $context->appendChild($node);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function wrap($html): ISimpleQuery
    {
        $html = $this->getInsertion($html);
        if (!count($this) || !count($html)) {
            return $this;
        }
        foreach ($this as $ind => $context) {
            /**
             * @var $context \DOMElement
             * @var $node    \DOMElement
             */
            $node = $html[0]->cloneNode(true);
            $this[$ind] = $node->appendChild($context->cloneNode(true));
            $context->parentNode->replaceChild($node, $context);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function append($html): ISimpleQuery
    {
        /**
         * @var $html \DOMNode[]
         */
        $html = $this->getInsertion($html);
        $contexts = $this;
        if (!count($contexts)) {
            $contexts = [$this->getDomDocument()->documentElement];
        }
        foreach ($contexts as $context) {
            /**
             * @var $context \DOMNode
             */
            foreach ($html as $ind => $node) {
                $html[$ind] = $context->appendChild($node->cloneNode(true));
            }
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function appendTo($selector): ISimpleQuery
    {
        $selector = $this->getSelector($selector);
        if (!count($this) || !count($selector)) {
            return $this;
        }
        foreach ($selector as $context) {
            /**
             * @var $context \DOMNode
             */
            foreach ($this as $insert) {
                $context->appendChild($insert->cloneNode(true));
            }
        }

        return $this;
    }

    /**
     * Возвращает экземпляр класса SimpleQuery на основе селектора или элемента
     *
     * @param string|ISimpleQuery|\DOMNode $selector
     */
    protected function getSelector($selector): ISimpleQuery
    {
        if (is_string($selector)) {
            $xpath = $this->compile($selector);
            if ($xpath === false) {
                throw new ErrorException('CSS3 Selector syntax error');
            }
            $lists = $this->contextQueryXPath(
                $xpath,
                [$this->getDomDocument()->documentElement]
            );
            $selector = $this->factory($this, $lists);
        }
        if ($selector instanceof DOMNode) {
            $selector = $this->factory($this, [$selector]);
        }

        return $selector;
    }

    /**
     * @inheritDoc
     */
    public function prependTo($selector): ISimpleQuery
    {
        $selector = $this->getSelector($selector);
        if (!count($this) || !count($selector)) {
            return $this;
        }
        foreach ($selector as $context) {
            /**
             * @var $context \DOMNode
             */
            foreach ($this as $insert) {
                if ($context->firstChild) {
                    $context->insertBefore($insert->cloneNode(true), $context->firstChild);

                    continue;
                }
                $context->appendChild($insert->cloneNode(true));
            }
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function prepend($html): ISimpleQuery
    {
        $html = $this->getInsertion($html);
        $contexts = $this;
        if (!count($contexts)) {
            $contexts = [$this->getDomDocument()->documentElement];
        }
        foreach ($contexts as $context) {
            /**
             * @var $context \DOMNode
             */
            foreach ($html as $ind => $node) {
                if ($context->firstChild) {
                    $html[$ind] = $context->insertBefore($node->cloneNode(true), $context->firstChild);

                    continue;
                }
                $html[$ind] = $context->appendChild($node->cloneNode(true));
            }
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function html($html = null)
    {
        return is_null($html) ? $this->getHtml() : $this->setHtml($html);
    }

    /**
     * @inheritDoc
     */
    public function text(?string $text = null)
    {
        return is_null($text) ? $this->getText() : $this->setText($text);
    }

    /**
     * Возвращает html контекстов
     *
     * @return string
     */
    protected function getHtml()
    {
        /**
         * @var $context \DOMNode
         */
        $context = $this->getDomDocument()->documentElement;
        if (isset($this[0])) {
            $context = $this[0];
        }
        $html = '';
        for ($ind = 0; $ind < $context->childNodes->length; $ind++) {
            $html .= $this->getDomDocument()->saveHTML($context->childNodes->item($ind));
        }

        return $html;
    }

    /**
     * Устанавливает html для контекстов
     *
     * @param string|ISimpleQuery $html
     *
     * @return $this
     */
    protected function setHtml($html): self
    {
        $html = $this->getInsertion($html);
        $contexts = $this;
        if (!count($contexts)) {
            $contexts = [$this->getDomDocument()->documentElement];
        }
        foreach ($contexts as $context) {
            /**
             * @var $context \DOMNode
             */
            while ($sibling = $context->firstChild) {
                $context->removeChild($sibling);
            }
            foreach ($html as $node) {
                $context->appendChild($node->cloneNode(true));
            }
        }

        return $this;
    }

    /**
     * Возвращает текст
     */
    protected function getText(): string
    {
        /**
         * @var $context \DOMNode
         */
        $context = $this->getDomDocument()->documentElement;
        if (isset($this[0])) {
            $context = $this[0];
        }

        return $context->textContent;
    }

    /**
     * Устанавливает текст
     *
     * @return $this
     */
    protected function setText(string $text): self
    {
        $fragment = $this->getDomDocument()->createDocumentFragment();
        $fragment->appendXML(htmlspecialchars($text, ENT_QUOTES | ENT_HTML5));
        $contexts = $this;
        if (!count($contexts)) {
            $contexts = [$this->getDomDocument()->documentElement];
        }
        foreach ($contexts as $context) {
            /**
             * @var $context \DOMNode
             */
            while ($context->childNodes->length) {
                $context->removeChild($context->childNodes->item(0));
            }
            for ($ind = 0; $ind < $fragment->childNodes->length; $ind++) {
                $context->appendChild($fragment->childNodes->item($ind)->cloneNode(true));
            }
        }

        return $this;
    }
}
