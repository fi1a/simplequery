<?php

declare(strict_types=1);

namespace Fi1a\SimpleQuery;

use DOMElement;

/**
 * Методы для атрибута класса
 */
abstract class AAttribute extends AInsertion
{
    /**
     * @inheritDoc
     */
    public function attr($attribute, $value = null)
    {
        if (is_array($attribute)) {
            return $this->setAttributes($attribute);
        }

        return func_num_args() === 1 ? $this->getAttr($attribute) : $this->setAttr($attribute, $value);
    }

    /**
     * @inheritDoc
     */
    public function removeAttr(string $attribute): ISimpleQuery
    {
        foreach ($this as $context) {
            /**
             * @var $context \DOMElement
             */
            $context->removeAttribute($attribute);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addClass(string $class): ISimpleQuery
    {
        if (!count($this)) {
            return $this;
        }
        $contexts = $this->getArrayCopy();
        foreach ($contexts as $ind => $node) {
            /**
             * @var $node \DOMElement
             */
            $classes = $this->getAttrClass($ind);
            if (in_array($class, $classes)) {
                continue;
            }
            $classes[] = $class;
            $node->setAttribute('class', implode(' ', $classes));
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function hasClass(string $class): bool
    {
        if (!count($this)) {
            return false;
        }

        return in_array($class, $this->getAttrClass(0));
    }

    /**
     * @inheritDoc
     */
    public function removeClass(string $class): ISimpleQuery
    {
        if (!count($this)) {
            return $this;
        }
        $contexts = $this->getArrayCopy();
        foreach ($contexts as $ind => $node) {
            /**
             * @var $node \DOMElement
             */
            $classes = $this->getAttrClass($ind);
            $ind = array_search($class, $classes);
            if ($ind === false) {
                continue;
            }
            unset($classes[$ind]);
            $node->setAttribute('class', implode(' ', $classes));
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toggleClass(string $class): ISimpleQuery
    {
        if (!count($this)) {
            return $this;
        }
        $contexts = $this->getArrayCopy();
        foreach ($contexts as $ind => $node) {
            /**
             * @var $node \DOMElement
             */
            $classes = $this->getAttrClass($ind);
            $ind = array_search($class, $classes);
            if ($ind === false) {
                $classes[] = $class;
                $node->setAttribute('class', implode(' ', $classes));

                continue;
            }
            unset($classes[$ind]);
            $node->setAttribute('class', implode(' ', $classes));
        }

        return $this;
    }

    /**
     * Возврашает массив с классами
     *
     * @return string[]
     */
    protected function getAttrClass(int $ind): array
    {
        return array_diff(explode(' ', $this[$ind]->getAttribute('class')), ['']);
    }

    /**
     * Устанавливает атрибуты
     *
     * @param string[] $attributes
     *
     * @return $this
     */
    protected function setAttributes(array $attributes): self
    {
        foreach ($attributes as $attribute => $value) {
            $this->setAttr($attribute, $value);
        }

        return $this;
    }

    /**
     * Возвращает значение атрибута
     *
     * @return mixed
     */
    protected function getAttr(string $attribute)
    {
        if (!count($this)) {
            return null;
        }

        return $this[0]->getAttribute($attribute);
    }

    /**
     * Устанавливает значение атрибута
     *
     * @param mixed $value
     *
     * @return $this
     */
    protected function setAttr(string $attribute, $value): self
    {
        if (!count($this)) {
            return $this;
        }
        foreach ($this as $context) {
            $context->setAttribute($attribute, $value);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function val($value = null)
    {
        return func_num_args() === 0 ? $this->getVal() : $this->setVal($value);
    }

    /**
     * Устанавливает значение
     *
     * @param mixed $value
     *
     * @return $this
     */
    protected function setVal($value): self
    {
        if (!count($this)) {
            return $this;
        }
        foreach ($this as $context) {
            /**
             * @var $context \DOMElement
             */
            if ($context->tagName === 'input') {
                $context->setAttribute('value', $this->convertValue($value));

                continue;
            } elseif ($context->tagName === 'textarea') {
                $this->factory($this, [$context])->text($this->convertValue($value));

                continue;
            } elseif ($context->tagName === 'select') {
                $instance = $this->factory($this, [$context]);
                $instance->find('option:selected')->removeAttr('selected');
                if ($context->getAttribute('multiple')) {
                    $values = (array) $value;
                    foreach ($values as $value) {
                        $instance->find(
                            'option[value="' . str_replace('"', '\\"', $this->convertValue($value)) . '"]'
                        )->attr('selected', 'selected');
                    }

                    continue;
                }
                $instance->find('option[value="' . str_replace('"', '\\"', $this->convertValue($value)) . '"]')
                    ->attr('selected', 'selected');
            }
        }

        return $this;
    }

    /**
     * Возвращает значение
     *
     * @return string|mixed[]|null
     */
    protected function getVal()
    {
        if (!count($this)) {
            return null;
        }
        if ($this[0]->tagName === 'input') {
            return $this->getAttr('value');
        } elseif ($this[0]->tagName === 'textarea') {
            return $this->text();
        } elseif ($this[0]->tagName === 'select') {
            if ($this->getAttr('multiple')) {
                $options = $this->factory($this, [$this[0]])->find('option:selected');
                $values = [];
                foreach ($options as $option) {
                    $values[] = $option->getAttribute('value');
                }

                return $values;
            }

            return $this->factory($this, [$this[0]])->find('option:selected:last')->attr('value');
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function css($property, ?string $value = null): ISimpleQuery
    {
        if (!count($this)) {
            return $this;
        }
        if (is_array($property)) {
            foreach ($property as $name => $value) {
                $this->setCss($name, $value);
            }

            return $this;
        }

        return $this->setCss($property, $value);
    }

    /**
     * Установить стиль
     *
     * @return $this
     */
    protected function setCss(string $property, ?string $value = null): self
    {
        foreach ($this as $context) {
            $css = $this->getCssArray($context);
            $css[$property] = $value;
            $this->setCssArray($context, $css);
        }

        return $this;
    }

    /**
     * Устанавливает стили для элемента
     *
     * @param string[] $css
     */
    protected function setCssArray(DOMElement $context, array $css): void
    {
        $style = '';
        foreach ($css as $name => $value) {
            if (is_null($value)) {
                continue;
            }
            $style .= ($style ? ' ' : '') . $name . ': ' . $this->convertValue($value) . ';';
        }
        $context->setAttribute('style', $style);
    }

    /**
     * Возвращает массив стилей элемента
     *
     * @return string[]
     */
    protected function getCssArray(DOMElement $context): array
    {
        $css = [];
        $styles = array_diff(array_map('trim', explode(';', $context->getAttribute('style'))), ['']);
        foreach ($styles as $style) {
            [$name, $value] = explode(':', $style);
            $name = trim($name);
            $value = trim($value);
            $css[$name] = $value;
        }

        return $css;
    }
}
