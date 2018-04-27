<?php

namespace Railken\LaraEye\Query\Visitors;

use Railken\SQ\Languages\BoomTree\Nodes as Nodes;

class NotInVisitor extends InVisitor
{
    /**
     * The node that will trigger the visitor.
     *
     * @var string
     */
    protected $node = Nodes\NotInNode::class;

    /**
     * The string operator for the query.
     *
     * @var string
     */
    protected $operator = 'NOT IN';

    /**
     * Visit the node and update the query.
     *
     * @param mixed $query
     * @param \Railken\SQ\Contracts\NodeContract $node
     * @param string                             $context
     */
    public function visit($query, $node, string $context)
    {
        if ($node instanceof $this->node) {
            $column = null;
            $values = null;

            if ($node->getChildByIndex(0) instanceof Nodes\KeyNode) {
                $column = $this->parseKey($node->getChildByIndex(0)->getValue());
            }

            if ($node->getChildByIndex(1) instanceof Nodes\GroupNode) {
                $values = array_map(function ($node) {
                    return $this->parseValue($node->getValue());
                }, $node->getChildByIndex(1)->getChilds());
            }

            if ($column && $values) {
                $context === Nodes\OrNode::class && $query->orWhereNotIn($column, $values);
                $context === Nodes\AndNode::class && $query->whereNotIn($column, $values);
            }
        }
    }
}
