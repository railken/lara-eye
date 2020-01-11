<?php

namespace Railken\LaraEye\Exceptions;

use Exception;

class FilterSyntaxException extends Exception
{
    public function __construct(string $filter, string $error)
    {
        parent::__construct(sprintf("Error `%s`, in query `%s`", $error, $filter));
    }
}
