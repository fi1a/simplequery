# PHP библиотека для обхода и манипуляции с HTML-документами

[![Latest Version][badge-release]][packagist]
[![Software License][badge-license]][license]
[![PHP Version][badge-php]][php]
![Coverage Status][badge-coverage]
[![Total Downloads][badge-downloads]][downloads]

Серверная, цепочная, управляемая селекторами CSS3 объектная модель документа (DOM), похожая на библиотеку jQuery.
Значительно упрощает такие вещи, как обход и манипуляции с HTML-документами.

## Установка

Установить этот пакет можно как зависимость, используя Composer.

``` bash
composer require fi1a/simplequery ">=1.1.0 <1.2.0"
```

**Внимание!** Совместимость версий не гарантируется при переходе major или minor версии.
Указывайте допустимую версию пакета в своем проекте следующим образом: ```"fi1a/collection": ">=1.1.0 <1.2.0"```.

## Краткий обзор

```php
use Fi1a\SimpleQuery\SimpleQuery;

$sq = new SimpleQuery('<h1>Title</h1><form></form>');

$sq('h1')->html('Updated title');
$sq('form')->append('<button class="continue">Submit</button>');

$button = $sq('form > button');

$button->html(); // Submit
$button->hasClass('continue'); // true

echo (string) $sq;
```

Результат вывода `echo (string) $sq;`:

```html
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">
<html><body><h1>Updated title</h1><form><button class="continue">Submit</button></form></body></html>
```

Подробная документация доступна в [wiki проекта](https://github.com/fi1a/simplequery/wiki).

[badge-release]: https://img.shields.io/packagist/v/fi1a/simplequery?label=release
[badge-license]: https://img.shields.io/github/license/fi1a/simplequery?style=flat-square
[badge-php]: https://img.shields.io/packagist/php-v/fi1a/simplequery?style=flat-square
[badge-coverage]: https://img.shields.io/badge/coverage-100%25-green
[badge-downloads]: https://img.shields.io/packagist/dt/fi1a/simplequery.svg?style=flat-square&colorB=mediumvioletred

[packagist]: https://packagist.org/packages/fi1a/simplequery
[license]: https://github.com/fi1a/simplequery/blob/master/LICENSE
[php]: https://php.net
[downloads]: https://packagist.org/packages/fi1a/simplequery