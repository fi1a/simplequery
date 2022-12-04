<?php

declare(strict_types=1);

namespace Fi1a\SimpleQuery;

use DOMNode;
use DOMText;
use Fi1a\SimpleQuery\Exception\ErrorException;

use const ENT_HTML5;
use const ENT_QUOTES;

/**
 * Работа с элементами
 */
abstract class AbstractInsertion extends AbstractSimpleQuery
{
    /**
     * @inheritDoc
     */
    public function unwrap()
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
    public function wrapAll($html)
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
    public function wrapInner($html)
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
    public function wrap($html)
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
    public function append($html)
    {
        /**
         * @var $html \DOMNode[]
         */
        $html = $this->getInsertion($html);
        $contexts = $this;
        if (!count($contexts)) {
            $contexts = [$this->getDomDocument()->documentElement];
        }
        $forRemove = [];
        foreach ($html as $node) {
            /**
             * @var $node \DOMNode
             */
            $forRemove[] = $node;
        }
        foreach ($contexts as $context) {
            /**
             * @var $context \DOMNode
             */
            foreach ($html as $ind => $node) {
                $html[$ind] = $context->appendChild($node->cloneNode(true));
            }
        }
        foreach ($forRemove as $node) {
            /**
             * @var $node \DOMNode
             */
            $node->parentNode->removeChild($node);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function appendTo($selector)
    {
        $selector = $this->getSelector($selector);
        if (!count($this) || !count($selector)) {
            return $this;
        }
        $list = [];
        foreach ($selector as $context) {
            /**
             * @var $context \DOMNode
             */
            foreach ($this as $insert) {
                $list[] = $context->appendChild($insert->cloneNode(true));
            }
        }
        foreach ($this as $insert) {
            /**
             * @var $insert \DOMNode
             */
            $insert->parentNode->removeChild($insert);
        }

        return $this->factory($this, $list);
    }

    /**
     * @inheritDoc
     */
    public function insertAfter($selector)
    {
        $selector = $this->getSelector($selector);
        if (!count($this) || !count($selector)) {
            return $this;
        }
        $forRemove = [];
        foreach ($this as $insert) {
            /**
             * @var $node \DOMNode
             */
            $forRemove[] = $insert;
        }
        $list = [];
        foreach ($selector as $context) {
            /**
             * @var $context \DOMNode
             */
            $node = $context;
            while ($node = $node->nextSibling) {
                if ($node instanceof DOMText) {
                    continue;
                }

                break;
            }
            if (!$node) {
                foreach ($this as $insert) {
                    $list[] = $context->parentNode->appendChild($insert->cloneNode(true));
                }

                continue;
            }
            foreach ($this as $insert) {
                $list[] = $context->parentNode->insertBefore($insert->cloneNode(true), $node);
            }
        }
        foreach ($forRemove as $insert) {
            /**
             * @var $insert \DOMNode
             */
            $insert->parentNode->removeChild($insert);
        }

        return $this->factory($this, $list);
    }

    /**
     * @inheritDoc
     */
    public function insertBefore($selector)
    {
        $selector = $this->getSelector($selector);
        if (!count($this) || !count($selector)) {
            return $this;
        }
        $forRemove = [];
        foreach ($this as $insert) {
            /**
             * @var $node \DOMNode
             */
            $forRemove[] = $insert;
        }
        $list = [];
        foreach ($selector as $context) {
            /**
             * @var $context \DOMNode
             */
            foreach ($this as $insert) {
                $list[] = $context->parentNode->insertBefore($insert->cloneNode(true), $context);
            }
        }
        foreach ($forRemove as $insert) {
            /**
             * @var $insert \DOMNode
             */
            $insert->parentNode->removeChild($insert);
        }

        return $this->factory($this, $list);
    }

    /**
     * Возвращает экземпляр класса SimpleQuery на основе селектора или элемента
     *
     * @param string|SimpleQueryInterface|\DOMNode $selector
     *
     * @return static
     */
    protected function getSelector($selector)
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
    public function prependTo($selector)
    {
        $selector = $this->getSelector($selector);
        if (!count($this) || !count($selector)) {
            return $this;
        }
        $list = [];
        foreach ($selector as $context) {
            /**
             * @var $context \DOMNode
             */
            foreach ($this as $insert) {
                if ($context->firstChild) {
                    $list[] = $context->insertBefore($insert->cloneNode(true), $context->firstChild);

                    continue;
                }
                $list[] = $context->appendChild($insert->cloneNode(true));
            }
        }
        foreach ($this as $insert) {
            /**
             * @var $insert \DOMNode
             */
            $insert->parentNode->removeChild($insert);
        }

        return $this->factory($this, $list);
    }

    /**
     * @inheritDoc
     */
    public function prepend($html)
    {
        $html = $this->getInsertion($html);
        $contexts = $this;
        if (!count($contexts)) {
            $contexts = [$this->getDomDocument()->documentElement];
        }
        $forRemove = [];
        foreach ($html as $node) {
            /**
             * @var $node \DOMNode
             */
            $forRemove[] = $node;
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
        foreach ($forRemove as $node) {
            /**
             * @var $node \DOMNode
             */
            $node->parentNode->removeChild($node);
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
     * @param string|SimpleQueryInterface $html
     *
     * @return self
     */
    protected function setHtml($html)
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
     * @return self
     */
    protected function setText(string $text)
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
