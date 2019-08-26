<?php

namespace Railken\LaraEye\Query\Functions;

use Railken\SQ\Languages\BoomTree\Nodes as Nodes;

class DateFormatFunction extends BaseFunction
{
    /**
     * The node that will trigger the visitor.
     *
     * @var string
     */
    protected $node = Nodes\DateFormatFunctionNode::class;

    /**
     * The string function for the query.
     *
     * @var string
     */
    protected $name = 'DATE_FORMAT';
}
