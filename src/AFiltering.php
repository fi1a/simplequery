<?php

declare(strict_types=1);

namespace Fi1a\SimpleQuery;

/**
 * Абстрактный класс с методами фильтрации
 */
abstract class AFiltering extends ATreeTraversal
{
    /**
     * @inheritDoc
     * @SuppressWarnings(PHPMD.ShortMethodName)
     */
    public function eq(int $index): ISimpleQuery
    {
        $list = [];
        if ($index >= 0 && $index < count($this)) {
            $list[] = $this[$index];
        }

        return $this->factory($this, $list, $this->getFragments());
    }

    /**
     * @inheritDoc
     */
    public function first(): ISimpleQuery
    {
        return $this->eq(0);
    }

    /**
     * @inheritDoc
     */
    public function have($selector): ISimpleQuery
    {
        if (!count($this)) {
            return $this;
        }
        $list = [];
        foreach ($this as $context) {
            /**
             * @var $context \DOMElement
             */
            $instance = $this->factory($this, [$context], $this->getFragments());
            if (count($instance->find($selector))) {
                $list[] = $context;
            }
        }

        return $this->factory($this, $list, $this->getFragments());
    }

    /**
     * @inheritDoc
     * @SuppressWarnings(PHPMD.ShortMethodName)
     */
    public function is($selector): bool
    {
        if (!count($this)) {
            return false;
        }
        $exist = false;
        if (count($this->filter($selector))) {
            $exist = true;
        }

        return $exist;
    }

    /**
     * @inheritDoc
     */
    public function last(): ISimpleQuery
    {
        return $this->eq(count($this) - 1);
    }

    /**
     * @inheritDoc
     */
    public function slice(int $start, ?int $end = null): ISimpleQuery
    {
        $count = count($this);
        if (!$count || $start >= $count) {
            return $this;
        }
        if (is_null($end) || $end > $count) {
            $end = $count;
        }
        $list = array_slice($this->getArrayCopy(), $start, $end - $start);

        return $this->factory($this, $list, $this->getFragments());
    }

    /**
     * @inheritDoc
     */
    public function not($selector): ISimpleQuery
    {
        if (!count($this)) {
            return $this;
        }
        $list = [];
        foreach ($this as $context) {
            if (count($this->factory($this, [$context])->filter($selector))) {
                continue;
            }
            $list[] = $context;
        }

        return $this->factory($this, $list, $this->getFragments());
    }
}
