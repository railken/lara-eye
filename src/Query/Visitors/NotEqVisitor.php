<?php

namespace Railken\LaraEye\Query\Visitors;

use Railken\SQ\Languages\BoomTree\Nodes as Nodes;

class NotEqVisitor extends BaseOperatorVisitor
{
    /**
     * The node that will trigger the visitor.
     *
     * @var string
     */
    protected $node = Nodes\NotEqNode::class;

    /**
     * The string operator for the query.
     *
     * @var string
     */
    protected $operator = '!=';
}
