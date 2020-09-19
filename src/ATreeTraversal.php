<?php

declare(strict_types=1);

namespace Fi1a\SimpleQuery;

use DOMDocument;
use DOMText;

/**
 * Абстрактный класс дерева
 */
abstract class ATreeTraversal extends AManipulation
{
    /**
     * @inheritDoc
     */
    public function closest($selector): ISimpleQuery
    {
        $list = [];
        foreach ($this as $context) {
            /**
             * @var $context \DOMElement
             */
            do {
                if ($context instanceof DOMDocument) {
                    break;
                }
                $instance = $this->factory($this, [$context], $this->getFragments());
                if (count($instance->filter($selector))) {
                    $list[] = $context;

                    break;
                }
            } while ($context = $context->parentNode);
        }

        return $this->factory($this, $list, $this->getFragments());
    }

    /**
     * @inheritDoc
     */
    public function next($selector = null): ISimpleQuery
    {
        return $this->walk(true, $selector);
    }

    /**
     * @inheritDoc
     */
    public function prev($selector = null): ISimpleQuery
    {
        return $this->walk(false, $selector);
    }

    /**
     * Обход элементов
     *
     * @param null $selector
     */
    protected function walk(bool $next, $selector = null): ISimpleQuery
    {
        $list = [];
        foreach ($this as $context) {
            /**
             * @var $context \DOMElement
             */
            while ($context = ($next ? $context->nextSibling : $context->previousSibling)) {
                if ($context instanceof DOMText) {
                    continue;
                }
                if (is_null($selector)) {
                    $list[] = $context;

                    break;
                }
                $instance = $this->factory($this, [$context], $this->getFragments());
                if (count($instance->filter($selector))) {
                    $list[] = $context;

                    break;
                }
            }
        }

        return $this->factory($this, $list, $this->getFragments());
    }

    /**
     * @inheritDoc
     */
    public function nextAll($selector = null): ISimpleQuery
    {
        return $this->walkAll(true, $selector);
    }

    /**
     * @inheritDoc
     */
    public function prevAll($selector = null): ISimpleQuery
    {
        return $this->walkAll(false, $selector);
    }

    /**
     * Обход всех элементов
     *
     * @param null $selector
     */
    protected function walkAll(bool $next, $selector = null): ISimpleQuery
    {
        $list = [];
        foreach ($this as $context) {
            /**
             * @var $context \DOMElement
             */
            while ($context = ($next ? $context->nextSibling : $context->previousSibling)) {
                if ($context instanceof DOMText) {
                    continue;
                }
                if (!is_null($selector)) {
                    $instance = $this->factory($this, [$context], $this->getFragments());
                    if (count($instance->filter($selector))) {
                        $list[] = $context;
                    }

                    continue;
                }
                $list[] = $context;
            }
        }

        return $this->factory($this, $list, $this->getFragments());
    }

    /**
     * @inheritDoc
     */
    public function nextUntil($selector): ISimpleQuery
    {
        return $this->walkUntil(true, $selector);
    }

    /**
     * @inheritDoc
     */
    public function prevUntil($selector): ISimpleQuery
    {
        return $this->walkUntil(false, $selector);
    }

    /**
     * Обход всех элементов до переданного
     *
     * @param mixed $selector
     */
    protected function walkUntil(bool $next, $selector): ISimpleQuery
    {
        $list = [];
        foreach ($this as $context) {
            /**
             * @var $context \DOMElement
             */
            while ($context = ($next ? $context->nextSibling : $context->previousSibling)) {
                if ($context instanceof DOMText) {
                    continue;
                }
                $instance = $this->factory($this, [$context], $this->getFragments());
                if (count($instance->filter($selector))) {
                    break;
                }
                $list[] = $context;
            }
        }

        return $this->factory($this, $list, $this->getFragments());
    }

    /**
     * @inheritDoc
     */
    public function parent($selector = null): ISimpleQuery
    {
        $list = [];
        foreach ($this as $context) {
            /**
             * @var $context \DOMElement
             */
            if ($context->parentNode instanceof DOMDocument) {
                continue;
            }
            $list[] = $context->parentNode;
        }
        $instance = $this->factory($this, $list, $this->getFragments());
        if (!is_null($selector)) {
            $instance = $instance->filter($selector);
            $this->setSourceClosure($instance, $this);
        }

        return $instance;
    }
}
