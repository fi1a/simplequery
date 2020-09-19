# Структуры данных и коллекции

[![Latest Version][badge-release]][packagist]
[![Software License][badge-license]][license]
[![PHP Version][badge-php]][php]
[![Build Status][badge-build]][build]
[![Coverage Status][badge-coverage]][coverage]
[![Total Downloads][badge-downloads]][downloads]

Серверная, цепочная, управляемая селекторами CSS3 объектная модель документа (DOM) API похожая на библиотеку jQuery.
Значительно упрощает такие вещи, как обход и манипуляции с HTML-документами.

## Установка

Установить этот пакет можно как зависимость, используя Composer.

``` bash
composer require fi1a/simplequery
```

## Краткий обзор

```php
use Fi1a\SimpleQuery\SimpleQuery;

$sq = new SimpleQuery('<h1>Title</h1><form></form>');

$sq('h1')->html('Updated title');
$sq('form')->append('<button class="continue">Submit</button>');

$button = $sq('form > button');

$button->html(); // Submit
$button->hasClass('continue'); // true
```

Подробная документация доступна в [wiki проекта](https://github.com/fi1a/simplequery/wiki).

[badge-release]: https://img.shields.io/packagist/v/fi1a/simplequery?label=release
[badge-license]: https://img.shields.io/github/license/fi1a/simplequery?style=flat-square
[badge-php]: https://img.shields.io/packagist/php-v/fi1a/simplequery?style=flat-square
[badge-build]: https://img.shields.io/travis/fi1a/simplequery?style=flat-square
[badge-coverage]: https://img.shields.io/coveralls/github/fi1a/simplequery/master.svg?style=flat-square
[badge-downloads]: https://img.shields.io/packagist/dt/fi1a/simplequery.svg?style=flat-square&colorB=mediumvioletred

[packagist]: https://packagist.org/packages/fi1a/simplequery
[license]: https://github.com/fi1a/simplequery/blob/master/LICENSE
[php]: https://php.net
[build]: https://travis-ci.org/fi1a/simplequery
[coverage]: https://coveralls.io/r/fi1a/simplequery?branch=master
[downloads]: https://packagist.org/packages/fi1a/simplequery