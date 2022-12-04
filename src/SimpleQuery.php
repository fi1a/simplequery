<?php

declare(strict_types=1);

namespace Fi1a\SimpleQuery;

use DOMDocument;
use DOMText;

/**
 * Класс SimpleQuery
 */
class SimpleQuery extends AbstractFiltering
{
    /**
     * @inheritDoc
     */
    public function data($key = null, $value = null)
    {
        if (!count($this)) {
            return $this;
        }
        if (func_num_args() === 0) {
            return $this->getAllData();
        }
        if (func_num_args() === 1) {
            if (is_array($key)) {
                foreach ($key as $name => $value) {
                    $this->setData($name, $value);
                }

                return $this;
            }

            return $this->getData($key);
        }

        return $this->setData($key, $value);
    }

    /**
     * @inheritDoc
     */
    public function removeData($key)
    {
        if (is_array($key)) {
            foreach ($this as $context) {
                /**
                 * @var $context \DOMElement
                 */
                foreach ($key as $name) {
                    $name = $this->humanize($name, '-');
                    $context->removeAttribute('data-' . $name);
                }
            }

            return $this;
        }
        $key = $this->humanize($key, '-');
        foreach ($this as $context) {
            /**
             * @var $context \DOMElement
             */
            $context->removeAttribute('data-' . $key);
        }

        return $this;
    }

    /**
     * Устанавливает данные элементам
     *
     * @param mixed  $value
     *
     * @return $this
     */
    protected function setData(string $key, $value): self
    {
        $key = $this->humanize($key, '-');
        $value = $this->convertValue($value);
        foreach ($this as $context) {
            /**
             * @var $context \DOMElement
             */
            $context->setAttribute('data-' . $key, $value);
        }

        return $this;
    }

    /**
     * Возвращает все данные у элемента
     *
     * @return string[]
     */
    protected function getAllData(): array
    {
        /**
         * @var $context \DOMElement
         */
        $data = [];
        $context = $this[0];
        for ($ind = 0; $ind < $context->attributes->length; $ind++) {
            $name = $context->attributes->item($ind)->nodeName;
            if (mb_substr($name, 0, 5) !== 'data-') {
                continue;
            }
            $dataName = mb_substr($name, 5);
            $dataName = $this->camelize($dataName);
            $data[$dataName] = $context->getAttribute($name);
        }

        return $data;
    }

    /**
     * Возвращает значение данных по ключу
     *
     * @return string|null
     */
    protected function getData(string $key)
    {
        $key = $this->humanize($key, '-');
        $value = $this[0]->getAttribute('data-' . $key);

        return $this->restoreValue($value);
    }

    /**
     * @inheritDoc
     */
    public function children($selector = null)
    {
        $list = [];
        foreach ($this as $context) {
            /**
             * @var $context \DOMElement
             */
            for ($ind = 0; $ind < $context->childNodes->length; $ind++) {
                $node = $context->childNodes->item($ind);
                if ($node instanceof DOMText) {
                    continue;
                }
                $list[] = $node;
            }
        }

        $instance = $this->factory($this, $list, $this->getFragments());
        if (!is_null($selector)) {
            $instance = $instance->filter($selector);
            $this->setSourceClosure($instance, $this);
        }

        return $instance;
    }

    /**
     * @inheritDoc
     */
    public function siblings($selector = null)
    {
        $lists = array_merge($this->prevAll($selector)->getArrayCopy(), $this->nextAll($selector)->getArrayCopy());
        for ($i = 0; $i < count($lists); $i++) {
            if (!isset($lists[$i])) {
                continue;
            }
            for ($j = $i + 1; $j < count($lists); $j++) {
                if (!isset($lists[$j])) {
                    continue;
                }
                if ($lists[$i] === $lists[$j]) {
                    unset($lists[$j]);
                }
            }
        }

        return $this->factory(
            $this,
            array_values($lists),
            $this->getFragments()
        );
    }

    /**
     * @inheritDoc
     */
    public function parents($selector = null)
    {
        $list = [];
        foreach ($this as $context) {
            /**
             * @var $context \DOMElement
             */
            while (!(($context = $context->parentNode) instanceof DOMDocument)) {
                $list[] = $context;
            }
        }
        $instance = $this->factory($this, $list, $this->getFragments());
        if (!is_null($selector)) {
            $instance = $instance->filter($selector);
            $this->setSourceClosure($instance, $this);
        }

        return $instance;
    }

    /**
     * @inheritDoc
     */
    public function parentsUntil($selector)
    {
        $list = [];
        foreach ($this as $context) {
            /**
             * @var $context \DOMElement
             */
            while (!(($context = $context->parentNode) instanceof DOMDocument)) {
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
    public function contents(): SimpleQueryInterface
    {
        $list = [];
        foreach ($this as $context) {
            /**
             * @var $context \DOMElement
             */
            for ($ind = 0; $ind < $context->childNodes->length; $ind++) {
                $list[] = $context->childNodes->item($ind);
            }
        }

        return $this->factory($this, $list, $this->getFragments());
    }
}
