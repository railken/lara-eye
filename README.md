# Laravel Eye

[![Build Status](https://travis-ci.org/railken/lara-eye.svg?branch=master)](https://travis-ci.org/railken/lara-eye)
[![License](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

Converts a query string into a nested where clauses for a QueryBuilder. This can be pretty usefull when building API.

## Requirements

PHP 7.0.0 or later.

## Usage

```php

use Railken\LaraEye\Filter;
use Railken\SQ\Exceptions\QuerySyntaxException;
use App\Foo;


// Instance of Query\Builder
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
