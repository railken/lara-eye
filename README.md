# Laravel Eye

[![Actions Status](https://github.com/railken/lara-eye/workflows/Test/badge.svg)](https://github.com/railken/lara-eye/actions)

Filter your ```Illuminate\DataBase\Query\Builder``` using a structured query language.
This can be pretty usefull when you're building an API and you don't want to waste hours of your time creating predefined filters that may change at any time.

## Requirements

PHP 7.1 or later.

## Usage

```php

use Railken\LaraEye\Filter;
use Railken\SQ\Exceptions\QuerySyntaxException;
use App\Foo;


// Instance of Illuminate\DataBase\Query\Builder
$query = (new Foo())->newQuery()->getQuery();

$str_filter = "x > 5 or y < z";

$filter = new Filter("foo", ['id', 'x', 'y', 'z', 'created_at', 'updated_at']);

try {
    $filter->build($query, $str_filter);
} catch (QuerySyntaxException $e) {
    // handle syntax error
}


```

Syntax [here](https://github.com/railken/search-query)

## Composer

You can install it via [Composer](https://getcomposer.org/) by typing the following command:

```bash
composer require railken/lara-eye
```

## Demo

![demo](https://raw.githubusercontent.com/railken/lara-eye/master/demo/demo.gif)

## License

Open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
