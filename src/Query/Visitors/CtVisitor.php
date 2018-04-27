<?php

namespace Railken\LaraEye\Query\Visitors;

use Railken\SQ\Languages\BoomTree\Nodes as Nodes;

class CtVisitor extends LikeVisitor
{
    /**
     * The node that will trigger the visitor.
     *
     * @var string
     */
    protected $node = Nodes\CtNode::class;

    /**
     * The string operator for the query.
     *
     * @var string
     */
    protected $operator = 'like';

    /**
     * Parse the value before putting in the query.
     *
     * @param string $value
     *
     * @return string
     */
    public function parseValue($value)
    {
        return '%'.$value.'%';
    }
}
