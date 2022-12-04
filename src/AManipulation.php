<?php

declare(strict_types=1);

namespace Fi1a\SimpleQuery;

use DOMText;

/**
 * Абстрактный класс SimpleQuery
 */
abstract class AManipulation extends AbstractAttribute
{
    /**
     * @inheritDoc
     */
    public function after($html)
    {
        /**
         * @var $html \DOMElement[]
         */
        $html = $this->getInsertion($html);
        if (!count($this) || !count($html)) {
            return $this;
        }
        foreach ($this as $context) {
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
                $context->parentNode->appendChild($html[0]->cloneNode(true));

                continue;
            }
            $context->parentNode->insertBefore($html[0]->cloneNode(true), $node);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function before($html)
    {
        $html = $this->getInsertion($html);
        if (!count($this) || !count($html)) {
            return $this;
        }
        foreach ($this as $context) {
            /**
             * @var $context \DOMNode
             */
            $context->parentNode->insertBefore($html[0]->cloneNode(true), $context);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function __clone()
    {
        $list = [];
        foreach ($this as $context) {
            /**
             * @var $context \DOMElement
             */
            $list[] = $context->cloneNode(true);
        }
        $this->exchangeArray($list);
    }

    /**
     * @inheritDoc
     */
    public function detach($selector = null)
    {
        if (!count($this)) {
            return $this;
        }
        $instance = $this;
        if (!is_null($selector)) {
            $instance = $this->filter($selector);
        }
        $fragment = $this->getDomDocument()->createDocumentFragment();
        $list = [];
        foreach ($instance as $context) {
            $list[] = $fragment->appendChild($context);
        }

        return $this->factory($this, $list, [$fragment]);
    }

    /**
     * @inheritDoc
     */
    public function empty()
    {
        $contexts = $this;
        if (!count($contexts)) {
            $contexts = [$this->getDomDocument()->documentElement];
        }
        foreach ($contexts as $context) {
            /**
             * @var $context \DOMElement
             */
            while ($node = $context->firstChild) {
                $context->removeChild($node);
            }
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function replaceAll($selector)
    {
        $selector = $this->getSelector($selector);
        if (!count($this) || !count($selector)) {
            return $this;
        }
        $list = [];
        foreach ($selector as $node) {
            /**
             * @var $node \DOMElement
             */
            $list[] = $node->parentNode->replaceChild($this[0]->cloneNode(true), $node);
        }

        return $this->factory($selector, $list, $this->getFragmentsClosure($selector));
    }

    /**
     * @inheritDoc
     */
    public function replaceWith($html)
    {
        $html = $this->getInsertion($html);
        if (!count($this) || !count($html)) {
            return $this;
        }
        $list = [];
        foreach ($this as $context) {
            /**
             * @var $context \DOMElement
             */
            $list[] = $context->parentNode->replaceChild($html[0]->cloneNode(true), $context);
        }

        return $this->factory($this, $list, $this->getFragments());
    }

    /**
     * @inheritDoc
     */
    public function remove($selector = null)
    {
        if (!count($this)) {
            return $this;
        }
        $instance = $this;
        if (!is_null($selector)) {
            $instance = $this->filter($selector);
        }
        $list = [];
        foreach ($instance as $context) {
            /**
             * @var $context \DOMElement
             */
            $list[] = $context->parentNode->removeChild($context);
        }

        return $this->factory($this, $list, $this->getFragments());
    }
}
