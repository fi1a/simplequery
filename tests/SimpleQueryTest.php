<?php

declare(strict_types=1);

namespace Fi1a\Unit\SimpleQuery;

use DOMNode;
use Fi1a\Collection\DataType\ArrayObject;
use Fi1a\SimpleQuery\Exception\ErrorException;
use Fi1a\SimpleQuery\Exception\LogicException;
use Fi1a\SimpleQuery\SimpleQuery;
use PHPUnit\Framework\TestCase;

/**
 * Тестирование SimpleQuery
 */
class SimpleQueryTest extends TestCase
{
    /**
     * Создание нового элемента
     */
    public function testConstruct(): void
    {
        $sq = new SimpleQuery();
        /**
         * @var $string SimpleQueryInterface
         * @var $array  SimpleQueryInterface
         * @var $short  SimpleQueryInterface
         * @var $new    SimpleQueryInterface
         */
        $string = $sq('<div>string</div>');
        $array = $sq([
            '__tag' => 'div',
            '__html' => 'array',
        ]);
        $short = $sq('<div/>');
        $this->assertEquals('string', $string->html());
        $this->assertEquals('array', $array->html());
        $this->assertEquals('', $short->html());
        $new = $string('<div>new div</div>');
        $this->assertEquals('string', $string->html());
        $this->assertEquals('new div', $new->html());

        $sq = new SimpleQuery('');
        $string = $sq('<div>string</div>');
        $this->assertEquals('string', $string->html());
    }

    /**
     * Тестирование html
     */
    public function testHtml(): void
    {
        $sq = new SimpleQuery();
        /**
         * @var $string SimpleQuery
         * @var $new    SimpleQuery
         */
        $sq->html('<div/>');
        $this->assertEquals('<div></div>', $sq->html());
        $string = $sq('<div>string</div>');
        $sq->html($string);
        $this->assertEquals('<div>string</div>', $sq->html());
        $new = $sq('<div>new string</div>');
        $this->assertEquals('string', $string->html());
        $this->assertEquals('new string', $new->html());
        $string->html($new->html());
        $this->assertEquals('new string', $new->html());
        $this->assertEquals('new string', $string->html());
        // битый html
        $sq->html('<div>');
        $this->assertEquals('<div></div>', $sq->html());
    }

    /**
     * Тестирование добавления
     */
    public function testAppend(): void
    {
        $sq = new SimpleQuery();
        /**
         * @var $article SimpleQuery
         * @var $date    SimpleQuery
         */
        $article = $sq('<article class="b-article"/>');
        $date = $sq('<div class="e-date">12.12.2016</div>');
        $article->append($date);
        $this->assertEquals('<div class="e-date">12.12.2016</div>', $article->html());
        $this->assertEquals('12.12.2016', $date->html());
        $article->append('<div class="e-date">12.11.2016</div>');
        $this->assertEquals(
            '<div class="e-date">12.12.2016</div><div class="e-date">12.11.2016</div>',
            $article->html()
        );
        $article->append(['__tag' => 'div', '__html' => '12.10.2016', 'class' => 'e-date',]);
        $this->assertEquals(
            '<div class="e-date">12.12.2016</div><div class="e-date">12.11.2016</div>'
            . '<div class="e-date">12.10.2016</div>',
            $article->html()
        );
        $date->html('12.12.2017');
        $this->assertEquals(
            '<div class="e-date">12.12.2017</div><div class="e-date">12.11.2016</div>'
            . '<div class="e-date">12.10.2016</div>',
            $article->html()
        );
        $sq->xpath('descendant-or-self::body')->append($article);
        $this->assertEquals(
            '<head></head>'
            . '<body><article class="b-article"><div class="e-date">12.12.2017</div>'
            . '<div class="e-date">12.11.2016</div>'
            . '<div class="e-date">12.10.2016</div></article></body>',
            $sq->html()
        );
        $this->assertEquals(
            '<!DOCTYPE html>' . "\n" . '<html><head></head>'
            . '<body><article class="b-article"><div class="e-date">12.12.2017</div>'
            . '<div class="e-date">12.11.2016</div>'
            . '<div class="e-date">12.10.2016</div></article></body></html>' . "\n",
            (string) $sq
        );
        $sq->append('<html><body></body></html>');
        $date = $sq('<div class="e-date">12.12.2016</div>');
        $article->append($date[0]);
    }

    /**
     * Формирование из массива
     */
    public function testArray(): void
    {
        /**
         * @var $div  SimpleQuery
         * @var $meta SimpleQuery
         */
        $sq = new SimpleQuery();
        $div = $sq(['__tag' => 'div', '__html' => 'div',]);
        $this->assertEquals('<div>div</div>', (string) $div);
        $div = $sq(['__tag' => 'div',]);
        $this->assertEquals('<div></div>', (string) $div);
        $meta = $sq(['__tag' => 'meta', 'charset' => 'UTF-8',]);
        $this->assertEquals('<meta charset="UTF-8">', (string) $meta);
        $div = $sq(['__tag' => 'div', '__html' => [['__tag' => 'div', '__html' => [['__html' => 'div']]]],]);
        $this->assertEquals('<div><div>div</div></div>', (string) $div);
        $div = $sq([
            '__tag' => 'div',
            '__html' => [['__tag' => 'div', '__html' => [['__html' => '<div></div>']]]],
        ]);
        $this->assertEquals('<div><div><div></div></div></div>', (string) $div);
        $meta = $sq(['__tag' => 'meta', 'charset' => '<">',]);
        $this->assertEquals('<meta charset=\'&lt;"&gt;\'>', (string) $meta);
    }

    /**
     * Провайдер данных для теста testXPath
     *
     * @return mixed[]
     */
    public function dataProviderXPath(): array
    {
        return [
            // section
            [
                'descendant-or-self::section',
                1,
            ],
            // #article2
            [
                'descendant-or-self::*[@id=\'article2\']',
                1,
            ],
            // article#article2
            [
                'descendant-or-self::article[@id=\'article2\']',
                1,
            ],
            // .b-news.e-first
            [
                'descendant-or-self::*['
                . "contains(concat(' ',normalize-space(@class), ' '), ' b-news ') and "
                . "contains(concat(' ',normalize-space(@class), ' '), ' e-first ')]",
                1,
            ],
            // article.b-news.e-first
            [
                'descendant-or-self::article['
                . "contains(concat(' ',normalize-space(@class), ' '), ' b-news e-first ')]",
                1,
            ],
            // section > div > article
            [
                'descendant-or-self::section/div/article',
                3,
            ],
            // section article
            [
                'descendant-or-self::section/descendant-or-self::article',
                3,
            ],
            // [data-fixture="321"]
            [
                'descendant-or-self::*[@data-fixture=\'321\']',
                1,
            ],
            // [data-fixture!="321"]
            [
                'descendant-or-self::*[not(@data-fixture) or @data-fixture!=\'321\']',
                38,
            ],
            // [data-fixture*="1"] содержит 1
            [
                'descendant-or-self::*[contains(@data-fixture, \'2\')]',
                1,
            ],
            // [data-fixture$="1"] заканчивается на 1
            [
                'descendant-or-self::*[substring(@data-fixture, '
                . 'string-length(@data-fixture) - string-length(\'21\') + 1, '
                . 'string-length(\'21\')) = \'21\']',
                1,
            ],
            // [data-fixture^="3"] начинается на 3
            [
                'descendant-or-self::*[starts-with(@data-fixture, \'3\')]',
                1,
            ],
            // [lang|="en"] == en || == en-...
            [
                'descendant-or-self::*[@lang=\'en\' or starts-with(@lang, \'en-\')]',
                2,
            ],
            // * все элементы
            [
                '//*',
                39,
            ],
            // div[data-fixture] с атрибутом
            [
                'descendant-or-self::div[@data-fixture]',
                3,
            ],
            // *[data-fixture][data-some="1"]
            [
                'descendant-or-self::div[@data-fixture][@data-some="1"]',
                1,
            ],
            // *[class~="first"]
            [
                'descendant-or-self::'
                . '*[contains(concat(\' \',normalize-space(@class), \' \'), \' first \')]',
                2,
            ],
            // section > div:first-child
            [
                'descendant-or-self::section/div[1]',
                1,
            ],
            // section > div:first-child, section > div:eq(2)
            [
                'descendant-or-self::section/div[1]|descendant-or-self::section/div[2]',
                2,
            ],
            // h1 + div
            [
                //'descendant-or-self::div[name(preceding-sibling::*[1]) = \'h1\']',
                'descendant-or-self::h1/following-sibling::div[1]',
                3,
            ],
            // h1 ~ p
            [
                //'descendant-or-self::div[name(preceding-sibling::*) = \'h1\']',
                'descendant-or-self::h1/following-sibling::div',
                3,
            ],
        ];
    }

    /**
     * XPath выборка
     *
     * @dataProvider dataProviderXPath
     */
    public function testXPath(string $xpath, int $count): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $this->assertCount(0, $sq);
        $this->assertCount(0, $sq->xpath('article, section'));

        $selected = $sq->xpath($xpath);
        $this->assertCount($count, $selected);
    }

    /**
     * Провайдер данных для теста testCompile
     *
     * @return mixed[]
     */
    public function dataProviderCompile(): array
    {
        return [
            // descendant-or-self::section
            [
                'section',
                1,
            ],

            // descendant-or-self::*[@id='article2']
            [
                '#article2',
                1,
            ],

            // descendant-or-self::article[@id='article2']
            [
                'article#article2',
                1,
            ],

            // "descendant-or-self::*[contains(concat(' ',normalize-space(@class), ' '), ' b-news ')
            // and contains(concat(' ',normalize-space(@class), ' '), ' e-first ')]"
            [
                '.b-news.e-first',
                1,
            ],

            // descendant-or-self::article[contains(concat(' ',normalize-space(@class), ' '),
            //' b-news e-first ')]
            [
                'article.b-news.e-first',
                1,
            ],
            // descendant-or-self::section/div/article
            [
                'section > div > article',
                3,
            ],

            // descendant-or-self::section/descendant-or-self::article
            [
                'section article',
                3,
            ],

            // descendant-or-self::*[@data-fixture='321']
            [
                '[data-fixture="321"]',
                1,
            ],

            // descendant-or-self::*[not(@data-fixture) or @data-fixture!='321']
            [
                '[data-fixture!="321"]',
                38,
            ],

            // descendant-or-self::*[contains(@data-fixture, '2')] содержит 1
            [
                '[data-fixture*="1"]',
                1,
            ],
            // 'descendant-or-self::*[substring(@data-fixture,
            // string-length(@data-fixture) - string-length(\'21\') + 1,
            // string-length(\'21\')) = \'21\']' заканчивается на 1
            [
                '[data-fixture$="1"]',
                1,
            ],
            // descendant-or-self::*[starts-with(@data-fixture, '3')] начинается на 3
            [
                '[data-fixture^="3"]',
                1,
            ],
            // descendant-or-self::*[@lang='en' or starts-with(@lang, 'en-')]
            [
                '[lang|="en"]',
                2,
            ],

            // descendant-or-self::* все элементы
            [
                '*',
                39,
            ],

            // descendant-or-self::div[@data-fixture] с атрибутом
            [
                'div[data-fixture]',
                3,
            ],
            // descendant-or-self::*[@data-fixture][@data-some="1"]
            [
                '*[data-fixture][data-some="1"]',
                1,
            ],

            // descendant-or-self::*[contains(concat(\' \',normalize-space(@class), \' \'), \' first \')]
            [
                '*[class~="first"]',
                2,
            ],
            // descendant-or-self::section/div[1]
            [
                'section > div:first-child',
                1,
            ],
            // descendant-or-self::section/div[1]|descendant-or-self::section/div[2]
            [
                'section > div:first-child, section > div:eq(2)',
                2,
            ],

            // descendant-or-self::h1/following-sibling::div[1]
            [
                'h1 + div',
                3,
            ],
            // descendant-or-self::h1/following-sibling::div
            [
                'h1 ~ p',
                3,
            ],
            // descendant-or-self::h1/following-sibling::*[contains(concat(' ',normalize-space(@class), ' '),
            // ' e-content ')]
            [
                'h1 ~ .e-content',
                3,
            ],
            // descendant-or-self::h1/following-sibling::*[contains(concat(' ',normalize-space(@class), ' '),
            // ' e-content ')][1]
            [
                'h1 + .e-content',
                3,
            ],
            [
                'h1 + [class="e-date"]',
                3,
            ],
            [
                'h1 ~ [class^="e-content"]',
                3,
            ],
            [
                'h1 + #div1',
                1,
            ],
            [
                'h1 ~ #div1',
                1,
            ],
            [
                'section div article #div1',
                1,
            ],
            [
                '.b-news-list [data-fixture="789"] .e-first > div',
                1,
            ],
            [
                '.b-news-list div .e-first > div',
                1,
            ],
            [
                ':button',
                2,
            ],
            [
                ':checkbox',
                1,
            ],
            [
                '.e-date:contains("12.11.2015")',
                1,
            ],
            [
                ':disabled',
                1,
            ],
            [
                ':empty',
                12,
            ],
            [
                ':enabled',
                8,
            ],
            [
                ':even',
                20,
            ],
            [
                ':odd',
                19,
            ],
            [
                ':gt(10) ',
                28,
            ],
            [
                ':hidden',
                0,
            ],
            [
                ':input',
                11,
            ],
            [
                'div:last-child',
                1,
            ],
            [
                'div:last',
                1,
            ],
            [
                ':lt(10)',
                10,
            ],
            [
                'body:parent',
                1,
            ],
            [
                ':selected',
                2,
            ],
            [
                '~',
                0,
            ],
            [
                '[="value"]',
                0,
            ],
            [
                '[]',
                0,
            ],
            [
                '[name "value"]',
                0,
            ],
            [
                ':checked',
                1,
            ],
            [
                ':file',
                1,
            ],
            [
                ':password',
                1,
            ],
            [
                ':radio',
                1,
            ],
            [
                ':reset',
                1,
            ],
            [
                ':submit',
                1,
            ],
            [
                'article:first',
                1,
            ],
            [
                ':not-exist',
                0,
            ],
            [
                'input:text + :button',
                1,
            ],
            [
                ':lang(en-EN)',
                1,
            ],
        ];
    }

    /**
     * Тестируем компиляцию SimpleQuery селектора в XPath
     *
     * @dataProvider dataProviderCompile
     */
    public function testCompile(string $selector, int $count): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $xpath = $sq->compile($selector);
        if ($xpath !== false) {
            $selected = $sq->xpath($xpath);
            $this->assertCount($count, $selected);
        } else {
            $this->assertFalse($xpath);
        }
    }

    /**
     * Обращение к объекту как к функции(контекст документа)
     */
    public function testInvoke(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $article = $sq->find('#article1');
        $this->assertCount(1, $article);
        $this->assertCount(0, $article->find('.b-news-list'));
        $this->assertCount(1, $article('.b-news-list'));
        $this->assertCount(1, $sq('.b-news-list'));
        $this->assertCount(1, $sq->find('.b-news-list'));
        $this->assertCount(0, $sq($article)->find('.b-news-list'));
    }

    /**
     * Обращение к объекту как к функции(контекст документа)
     */
    public function testInvokeSyntaxError(): void
    {
        $this->expectException(ErrorException::class);
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $sq('][');
    }

    /**
     * Провайдер данных для теста testInvokeSelectors
     *
     * @return mixed[]
     */
    public function dataProviderInvokeSelectors(): array
    {
        return [
            [
                '*',
                39,
            ],
            [
                'head *',
                3,
            ],
            [
                'footer > *',
                2,
            ],
            [
                '* *',
                39,
            ],
            [
                '* * *',
                39,
            ],
            [
                '**',
                39,
            ],
            [
                'footer *',
                15,
            ],
            [
                'footer*',
                16,
            ],
            [
                'section article:eq(2)',
                1,
            ],
        ];
    }

    /**
     * Селекторы
     *
     * @dataProvider dataProviderInvokeSelectors
     */
    public function testInvokeSelectors(string $selector, int $count): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $this->assertCount($count, $sq($selector));
    }

    /**
     * Добавляет элементы в текущий контекст
     */
    public function testAdd(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $article1 = $sq->find('#article1');
        $article2 = $sq->find('#article2');
        $article1->add($article2);
        $this->assertCount(2, $article1);
    }

    /**
     * Фильтрация элементов с использованием функции
     */
    public function testCallbackFilter(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $filter = $sq('div')->filter(function (int $index, DOMNode $element) {
            $sq = new SimpleQuery();
            $sq = $sq($element);

            return (bool) $element->attributes->getNamedItem('id');
        });
        $this->assertCount(1, $filter);
    }

    /**
     * Фильтрация элементов на основе селектора
     */
    public function testSelectorFilter(): void
    {
        /**
         * @var $input SimpleQuery
         */
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $input = $sq(':input');
        $this->assertCount(11, $input);
        $this->assertCount(1, $input->filter(':text'));
        $input->filter($sq);
    }

    /**
     * Фильтрация элементов на основе селектора
     */
    public function testSelectorFilterSyntaxError(): void
    {
        $this->expectException(ErrorException::class);
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $input = $sq(':input');
        $input->filter('][');
    }

    /**
     * Фильтрация элементов на основе сравнения элементов
     */
    public function testElementFilter(): void
    {
        /**
         * @var $input SimpleQuery
         * @var $text  SimpleQuery
         */
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $input = $sq(':input');
        $text = $input->filter(':text');
        $this->assertCount(1, $input->filter($text[0]));
        $this->assertCount(1, $input->filter($text));
    }

    /**
     * Добавить предыдущий набор элементов в стек к текущему набору
     */
    public function testAddBack(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $this->assertCount(2, $sq('#article1')->find('p')->addBack());
        $this->assertCount(4, $sq('article')->find('p')->addBack('#article1'));
        $this->assertCount(0, $sq->addBack());
    }

    /**
     * Тестирование атрибутов
     */
    public function testAttr(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $sq->attr('id');
        $sq->attr('id', 'new_id');
        $this->assertEquals('article1', $sq('#article1')->attr('id'));
        $sq('#article1')->attr('id', 'new_id');
        $this->assertEquals('new_id', $sq('#new_id')->attr('id'));
        $sq('#new_id')->attr(['class' => 'new_class', 'data-some' => 'new_data_some']);
        $this->assertEquals('new_class', $sq('#new_id')->attr('class'));
        $this->assertEquals('new_data_some', $sq('#new_id')->attr('data-some'));
        $this->assertEquals('multiple', $sq('select')->attr('multiple'));
    }

    /**
     * Тестирование класса
     */
    public function testClass(): void
    {
        /**
         * @var $article SimpleQuery
         */
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $sq->removeClass('class');
        $sq->addClass('class');
        $sq->hasClass('class');
        $sq->toggleClass('class');

        $article = $sq('#article1');
        $this->assertTrue($article->hasClass('e-first'));
        $article->removeClass('e-first');
        $article->removeClass('e-first');
        $this->assertFalse($article->hasClass('e-first'));
        $article->addClass('e-first');
        $article->addClass('e-first');
        $this->assertTrue($article->hasClass('e-first'));
        $article->toggleClass('e-first');
        $this->assertFalse($article->hasClass('e-first'));
        $article->toggleClass('e-first');
        $this->assertTrue($article->hasClass('e-first'));
    }

    /**
     * Возвращает дочерние элементы
     */
    public function testChildren(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $this->assertCount(3, $sq('[data-fixture="321"] > article')->children());
        $this->assertCount(1, $sq('[data-fixture="321"] > article')->children('h1'));
    }

    /**
     * Для каждого элемента, получить первый элемент,
     * соответствующий селектору для себя и всех родителей
     */
    public function testClosest(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $this->assertCount(3, $sq('h1')->closest('article'));
        $this->assertCount(0, $sq('h1')->closest('not_exist'));
    }

    /**
     * Следующий элемент
     */
    public function testNext(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $this->assertEquals('3', $sq('[data-some="1"]')->next('[data-some="3"]')->attr('data-some'));
        $this->assertEquals('2', $sq('[data-some="1"]')->next()->attr('data-some'));
    }

    /**
     * Все следующие элементы
     */
    public function testNextAll(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $this->assertEquals('3', $sq('[data-some="1"]')->nextAll('[data-some="3"]')->attr('data-some'));
    }

    /**
     * Все следующие элементы до элемента, удовлетворяющего селектору
     */
    public function testNextUntil(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $this->assertEquals('2', $sq('[data-some="1"]')->nextUntil('[data-some="3"]')->attr('data-some'));
    }

    /**
     * Возврашает родительский элемент
     */
    public function testParent(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $this->assertEquals('article1', $sq('h1')->parent('article')->attr('id'));
        $this->assertCount(0, $sq('html')->parent());
    }

    /**
     * Возврашает все родительские элементы
     */
    public function testParents(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $this->assertCount(3, $sq('h1')->parents('div'));
    }

    /**
     * Возврашает родительский элемент
     */
    public function testParentsUntil(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $this->assertCount(3, $sq('h1')->parentsUntil('div'));
    }

    /**
     * Предыдущий элемент
     */
    public function testPrev(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $this->assertEquals('1', $sq('[data-some="2"]')->prev('[data-some="1"]')->attr('data-some'));
    }

    /**
     * Предыдущие элементы
     */
    public function testPrevAll(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $this->assertEquals('1', $sq('[data-some="3"]')->prevAll('[data-some="1"]')->attr('data-some'));
    }

    /**
     * Все предыдущие элементы до элемента, удовлетворяющего селектору
     */
    public function testPrevUntil(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $this->assertEquals('2', $sq('[data-some="3"]')->prevUntil('[data-some="1"]')->attr('data-some'));
    }

    /**
     * Соседние элементы
     */
    public function testSiblings(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $this->assertCount(2, $sq('[data-some="2"]')->siblings());
    }

    /**
     * Добавить после элементов
     */
    public function testAfter(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $sq('h1')->after('<h2>Some h2</h2>');
        $this->assertCount(3, $sq('h2'));
        $sq->after('<h2>Some h2</h2>');
        $this->assertCount(3, $sq('h2'));
        $sq('.e-content')->after('<h2>Some h2</h2>');
        $this->assertCount(6, $sq('h2'));
    }

    /**
     * Добавить после элементов
     */
    public function testInsertAfter(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $sq('<h2>Some h2</h2>')->insertAfter('h1');
        $this->assertCount(3, $sq('h2'));
        $sq->insertAfter('h1');
        $this->assertCount(3, $sq('h2'));
        $sq('<h2>Some h2</h2>')->insertAfter('.e-content');
        $this->assertCount(6, $sq('h2'));
        $p = $sq('.e-content');
        $sq('<h2>Some h2</h2>')->insertAfter($p[0]);
        $this->assertCount(7, $sq('h2'));
    }

    /**
     * Добавить после элементов
     */
    public function testInsertAfterSyntaxError(): void
    {
        $this->expectException(ErrorException::class);
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $sq('<h2>Some h2</h2>')->insertAfter('][');
    }

    /**
     * Добавить до элементов
     */
    public function testBefore(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $sq('h1')->before('<h2>Some h2</h2>');
        $this->assertCount(3, $sq('h2'));
        $sq->before('<h2>Some h2</h2>');
        $this->assertCount(3, $sq('h2'));
    }

    /**
     * Добавить до элементов
     */
    public function testInsertBefore(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $sq('<h2>Some h2</h2>')->insertBefore('h1');
        $this->assertCount(3, $sq('h2'));
        $sq->insertBefore('h1');
        $this->assertCount(3, $sq('h2'));
    }

    /**
     * Возвращает дочерние элементы, в том числе и текст
     */
    public function testContents(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $this->assertCount(3, $sq('#article1')->contents());
    }

    /**
     * Убрать элементы, соответствующие селектору
     */
    public function testNot(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $this->assertCount(2, $sq('div > article')->not('.e-first'));
        $this->assertCount(0, $sq->not('.e-first'));
    }

    /**
     * Конец цепочки поиска
     */
    public function testEnd(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $this->assertCount(6, $sq('div')->find('article:first')->end());
        $this->assertCount(0, $sq->end());
    }

    /**
     * Добавление элемента в переданный
     */
    public function testAppendTo(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $sq('<div/>')->appendTo('div > article');
        $this->assertCount(6, $sq('article > div'));
        $sq->appendTo('div > article');
    }

    /**
     * Добавление элемента в начало
     */
    public function testPrepend(): void
    {
        /**
         * @var $date    SimpleQuery
         * @var $article SimpleQuery
         */
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $article = $sq('div article');
        $date = $sq('<span class="datetime">12.12.2013</span>');
        $article->prepend($date);
        $this->assertCount(3, $article->find('span.datetime'));
        $sq->prepend($date);
        $footerArticle = $sq('footer article');
        $footerArticle->prepend($date);
        $this->assertCount(1, $footerArticle->find('span.datetime'));
        $header = $sq('header');
        $header->prepend($date);
        $this->assertCount(1, $header->find('span.datetime'));
    }

    /**
     * Добавление элемента в начало переданного
     */
    public function testPrependTo(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $sq('<span class="datetime">12.12.2013</span>')->prependTo('div article');
        $this->assertCount(3, $sq('div article span.datetime'));
        $sq->prependTo('div article');
        $sq('<span class="datetime">12.12.2013</span>')->prependTo('footer article');
        $this->assertCount(1, $sq('footer article span.datetime'));
        $sq('<span class="datetime">12.12.2013</span>')->prependTo('header');
        $this->assertCount(1, $sq('header span.datetime'));
    }

    /**
     * Возвращает или устанавливает текст
     */
    public function testText(): void
    {
        /**
         * @var $date SimpleQuery
         */
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $date = $sq('.b-news .e-date:first');
        $this->assertEquals('12.12.2015', $date->text());
        $date->text('13.12.2016');
        $this->assertEquals('13.12.2016', $date->text());
        $sq->text('new text node');
        $this->assertEquals('new text node', $sq->text());
    }

    /**
     * Удаляет атрибут
     */
    public function testRemoveAttr(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $sq('#article1')->removeAttr('id');
        $this->assertCount(0, $sq('#article1'));
    }

    /**
     * Тестирование значения
     */
    public function testVal(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $this->assertEquals('1', $sq('input:text')->val());
        $this->assertEquals('1', $sq('textarea')->val());
        $this->assertEquals(['0', '1'], $sq('select')->val());
        $sq('select')->removeAttr('multiple');
        $this->assertEquals('1', $sq('select')->val());
        $this->assertNull($sq->val());
        $this->assertNull($sq('div')->val());
        $sq('select')->attr('multiple', 'multiple');
        $sq('input:text')->val(2);
        $this->assertEquals('2', $sq('input:text')->val());
        $sq('textarea')->val(2);
        $this->assertEquals('2', $sq('textarea')->val());
        $sq('select')->val(1);
        $this->assertEquals(['1'], $sq('select')->val());
        $sq('select')->removeAttr('multiple');
        $sq('select')->val(0);
        $this->assertEquals('0', $sq('select')->val());
        $sq->val(1);
    }

    /**
     * Тестирование установки стиля
     */
    public function testCss(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $sq('#article1')->css('background', '#000');
        $this->assertEquals('color: red; background: #000;', $sq('#article1')->attr('style'));
        $sq('#article1')->css('background', false);
        $this->assertEquals('color: red;', $sq('#article1')->attr('style'));
        $sq('#article1')->css(['color' => 'black']);
        $this->assertEquals('color: black;', $sq('#article1')->attr('style'));
        $sq->css('color', 'black');
        $sq('#article1')->css(['backgroundColor' => 'white']);
        $this->assertEquals('color: black; background-color: white;', $sq('#article1')->attr('style'));
        $this->assertEquals([
            'color' => 'black',
            'backgroundColor' => 'white',
        ], $sq('#article1')->css());
    }

    /**
     * Тестирование данных
     */
    public function testData(): void
    {
        /**
         * @var $div SimpleQuery
         */
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $div = $sq('[data-fixture="789"]');
        $this->assertEquals('789', $div->data('fixture'));
        $div->data('fixture', '123');
        $this->assertEquals('123', $div->data('fixture'));
        $div->data('fixture', null);
        $this->assertEquals('', $div->data('fixture'));
        $div->removeData('fixture');
        $this->assertEquals('', $div->data('fixture'));
        $sq->data('fixture');
        $this->assertEquals(['some' => 1,], $div->data());
        $div->data(['some' => [1, 2, 3]]);
        $this->assertEquals([1, 2, 3], $div->data('some'));
        $div->removeData(['fixture', 'some',]);

        $div->data(['someParam' => '1']);
        $this->assertEquals('1', $div->data('someParam'));
        $this->assertEquals(['someParam' => '1'], $div->data());
        $div->removeData('someParam');
        $this->assertEquals('', $div->data('someParam'));

        $div->data(['someParam' => true]);
        $this->assertEquals(true, $div->data('someParam'));

        $div->data('someParam', new SimpleQueryTest());
        $this->assertEquals('Fi1a\Unit\SimpleQuery\SimpleQueryTest', $div->data('someParam'));

        $div->data('someParam', '"string\\"');
        $this->assertEquals('"string\\"', $div->data('someParam'));

        $div->data('someParam', '\'"string\\"\'');
        $this->assertEquals('\'"string\\"\'', $div->data('someParam'));
    }

    /**
     * Клонирование
     */
    public function testClone(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $article = $sq('#article1');
        $clone = clone $article;
        $this->assertTrue($clone[0] !== $article[0]);
    }

    /**
     * Обернуть структуру вокруг каждого элемента
     * Удаляет родительский элемент и помещает на его место
     */
    public function testWrapUnwrap(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $sq('div > article')->wrap('<div>wrap</div>');
        $sq->wrap('<div>wrap</div>');
        $this->assertCount(3, $sq('div > div > article'));
        $sq('div > div > article')->unwrap();
        $this->assertCount(0, $sq('div > div > article'));
        $this->assertCount(3, $sq('div > article'));
        $sq->unwrap();
        $sq('html')->unwrap();
    }

    /**
     * Обернуть структуру вокруг всех элементов
     */
    public function testWrapAll(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $sq('section > div > article')->wrapAll('<div>wrap</div>');
        $this->assertCount(1, $sq('section > div > div'));
        $sq->wrapAll('<div>wrap</div>');
    }

    /**
     * Обернуть структуру вокруг содержимого элементов
     */
    public function testWrapInner(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $sq('section > div > article')->wrapInner('<div>wrap</div>');
        $this->assertCount(3, $sq('section > div > article > div'));
        $sq->wrapInner('<div>wrap</div>');
    }

    /**
     * Удаляет элементы
     */
    public function testDetach(): void
    {
        /**
         * @var $articles SimpleQuery
         */
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $articles = $sq('section > div > article')->detach('article');
        $this->assertCount(0, $sq('section > div > article'));
        $articles->appendTo('section > div');
        $this->assertCount(9, $sq('section > div > article'));
        $sq->detach();
    }

    /**
     * Удаляет все дочерние элементы
     */
    public function testEmpty(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $sq('section > div')->empty();
        $this->assertCount(0, $sq('section > div > article'));
        $sq->empty();
    }

    /**
     * Удаляет элементы
     */
    public function testRemove(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $sq('section > div > article')->remove('article');
        $this->assertCount(0, $sq('section > div > article'));
        $sq->remove();
    }

    /**
     * Заменяет
     */
    public function testReplaceAll(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $sq('<div>replace</div>')->replaceAll($sq('section > div > article'));
        $this->assertCount(0, $sq('section > div > article'));
        $this->assertCount(3, $sq('section > div > div'));
        $sq->replaceAll($sq('section > div > article'));
    }

    /**
     * Заменить каждый элемент с помощью нового содержимого
     */
    public function testReplaceWith(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $sq('section > div > article')->replaceWith('<div>replace</div>');
        $this->assertCount(0, $sq('section > div > article'));
        $this->assertCount(3, $sq('section > div > div'));
        $sq->replaceWith('<div>replace</div>');
    }

    /**
     * Обход с использованием метода коллекции
     */
    public function testEach(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $sq('section > div > article')->each(function ($node) use ($sq) {
            $sq($node)->remove();
        });
        $this->assertCount(0, $sq('section > div > article'));
    }

    /**
     * Возвращает элемент с определенным индексом
     */
    public function testEq(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $this->assertCount(1, $sq('section > div > article')->eq(1));
    }

    /**
     * Возвращает первый элемент
     */
    public function testFirst(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $this->assertCount(1, $sq('section > div > article')->first());
    }

    /**
     * Имеет дочерние элементы, удовлетвряющие селектору
     */
    public function testHave(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $this->assertCount(3, $sq('section > div')->have('article'));
        $sq->have('article');
    }

    /**
     * Проверяет на соответствие селектору
     */
    public function testIs(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $this->assertTrue($sq('section > div > article')->is('article'));
        $this->assertFalse($sq->is('body'));
    }

    /**
     * Возвращает последний элемент
     */
    public function testLast(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $this->assertCount(1, $sq('section > div > article')->last());
    }

    /**
     * Вызывает функцию, передавая ключ и значение из коллекции и заменяет элемент результатом
     */
    public function testMap(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $this->assertEquals(
            ['article1', 'article2', 'article3'],
            $sq('section > div > article')->map(function ($element) use ($sq) {
                return $sq($element)->attr('id');
            })->getArrayCopy()
        );
    }

    /**
     * Возвращает подмножество заданных диапазонов индексов
     */
    public function testSlice(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $this->assertCount(1, $sq('section > div > article')->slice(2));
        $sq->slice(1);
    }

    /**
     * Поиск значения
     */
    public function testFind(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $this->assertCount(15, $sq('body section')->find('*'));
        $this->assertCount(3, $sq('body section')->find('article h1'));
        $this->assertCount(3, $sq('body section')->find('div h1'));
        $this->assertCount(3, $sq('body section')->find('article > h1'));
        $this->assertCount(0, $sq('body section')->find('div > h1'));
    }

    /**
     * Поиск значения
     */
    public function testFindSyntaxError(): void
    {
        $this->expectException(ErrorException::class);
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $sq('body section')->find('][');
    }

    /**
     * Переменные
     */
    public function testVariables(): void
    {
        /**
         * @var $div \Fi1a\SimpleQuery\SimpleQuery
         */
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $sq->setVariables(new ArrayObject([
            'article' => 'section > div > article',
            'h1' => 'section > div > article > h1',
            'body' => 'body',
        ]));
        $this->assertTrue($sq->hasVariable('body'));
        $this->assertEquals('body', $sq->getVariable('body'));
        $this->assertTrue($sq->deleteVariable('body'));
        $this->assertFalse($sq->deleteVariable('body'));
        $this->assertFalse($sq->getVariable('body'));
        $this->assertEquals(
            [
                'article' => 'section > div > article',
                'h1' => 'section > div > article > h1',
            ],
            $sq->getVariables()->getArrayCopy()
        );
        $div = $sq('div');
        $this->assertEquals('descendant-or-self::section/div/article', $sq->compile('{{article|unescape}}'));
        $this->assertEquals('descendant-or-self::section/div/article', $div->compile('{{article|unescape}}'));
        $div->setVariable('new', 'div');
        $this->assertTrue($sq->hasVariable('new'));
    }

    /**
     * Не поддерживаемый метод where из коллекции
     */
    public function testWhere(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $this->expectException(LogicException::class);
        $sq->where('key', 'value');
    }

    /**
     * Возвращает расходящиеся элементы
     */
    public function testDiff(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $sectionArticle = $sq('section > div > article');
        $article = $sq('article');
        $this->assertCount(1, $article->diff($sectionArticle));
    }

    /**
     * Показать элемент
     */
    public function testShow(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $article = $sq('.b-some-footer');
        $this->assertEquals('none', $article->css('display'));
        $article->show();
        $this->assertNull($article->css('display'));
    }

    /**
     * Скрыть элемент
     */
    public function testHide(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $article = $sq('.b-news-list');
        $this->assertNull($article->css('display'));
        $article->hide();
        $this->assertEquals('none', $article->css('display'));
    }

    /**
     * Показать или скрыть выбранные элементы
     */
    public function testToggle(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $article = $sq('.b-news-list, .b-some-footer');
        $this->assertNull($article->eq(0)->css('display'));
        $this->assertEquals('none', $article->eq(1)->css('display'));
        $article->toggle();
        $this->assertNull($article->eq(1)->css('display'));
        $this->assertEquals('none', $article->eq(0)->css('display'));
    }

    /**
     * Получить набор элементов формы как массив с именами и значениями.
     */
    public function testSerializeArray(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $serialized = $sq('form')->serializeArray();
        $this->assertCount(5, $serialized);
        $this->assertEquals([
            [
                'name' => 'form[name3]',
                'value' => 'Y',
            ],
            [
                'name' => 'form[name8]',
                'value' => 'pass',
            ],
            [
                'name' => 'form[name10]',
                'value' => '1',
            ],
            [
                'name' => 'form[name11][]',
                'value' => '0',
            ],
            [
                'name' => 'form[name11][]',
                'value' => '1',
            ],
        ], $serialized);
        $this->assertCount(0, $sq('#article3')->serializeArray());
        $this->assertCount(0, $sq('#article100500')->serializeArray());
    }

    /**
     * Получить набор элементов формы как массив с именами и значениями.
     */
    public function testSerializeNested(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $serialized = $sq('form')->serializeNested();
        $this->assertEquals([
            'form' => [
                'name3' => 'Y',
                'name8' => 'pass',
                'name10' => '1',
                'name11' => ['0', '1'],
            ],
        ], $serialized);
        $this->assertCount(0, $sq('#article3')->serializeNested());
        $this->assertCount(0, $sq('#article100500')->serializeNested());
    }

    /**
     * ППолучить набор элементов формы в виде строки.
     */
    public function testSerialize(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $serialized = $sq('form')->serialize();
        $this->assertEquals(
            'form%5Bname3%5D=Y&form%5Bname8%5D=pass&form%5Bname'
            . '10%5D=1&form%5Bname11%5D%5B0%5D=0&form%5Bname11%5D%5B1%5D=1',
            $serialized
        );
        $this->assertEquals('', $sq('#article3')->serialize());
        $this->assertEquals('', $sq('#article100500')->serialize());
    }

    /**
     * Возвращает индекс элемента среди выбранных
     */
    public function testIndex(): void
    {
        $sq = new SimpleQuery(file_get_contents(__DIR__ . '/Fixtures/fixture.html'));
        $this->assertEquals(2, $sq('.b-news')->index('.b-news.e-last'));
        $this->assertEquals(0, $sq('.b-news')->index($sq('.b-news.e-first')));
        $elements = $sq('#article2')->getArrayCopy();
        $this->assertEquals(1, $sq('.b-news')->index(reset($elements)));
        $this->assertFalse($sq('.not-exists')->index(reset($elements)));
        $this->assertFalse($sq('.b-news')->index('h1'));
    }
}
