<?php

namespace Railken\LaraEye\Query\Functions;

use Railken\SQ\Languages\BoomTree\Nodes as Nodes;

class ConcatFunction
{
    /**
     * The node that will trigger the visitor.
     *
     * @var string
     */
    public $node = Nodes\ConcatFunctionNode::class;

    /**
     * The string function for the query.
     *
     * @var string
     */
    public $function = 'CONCAT';

}
