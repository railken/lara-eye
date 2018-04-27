<?php

namespace Railken\LaraEye\Query\Visitors;

use Railken\SQ\Languages\BoomTree\Nodes as Nodes;

class LtVisitor extends BaseOperatorVisitor
{
    /**
     * The node that will trigger the visitor.
     *
     * @var string
     */
    protected $node = Nodes\LtNode::class;

    /**
     * The string operator for the query.
     *
     * @var string
     */
    protected $operator = '<';
}
