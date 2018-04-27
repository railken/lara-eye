<?php

namespace Railken\LaraEye\Query\Visitors;

use Railken\SQ\Contracts\NodeContract;
use Railken\SQ\Languages\BoomTree\Nodes as Nodes;

class AndVisitor extends LogicOperatorVisitor
{
    /**
     * Visit the node and update the query.
     *
     * @param mixed $query
     * @param \Railken\SQ\Contracts\NodeContract $node
     * @param string                             $context
     */
    public function visit($query, NodeContract $node, string $context)
    {
        if ($node instanceof Nodes\AndNode) {
            $callback = function ($q) use ($node) {
                foreach ($node->getChilds() as $child) {
                    if ($child instanceof Nodes\KeyNode || $child instanceof Nodes\ValueNode) {
                        throw new \Railken\SQ\Exceptions\QuerySyntaxException();
                    }

                    $this->getBuilder()->build($q, $child, Nodes\AndNode::class);
                }
            };

            $context === Nodes\OrNode::class && $query->orWhere($callback);
            $context === Nodes\AndNode::class && $query->where($callback);
        }
    }
}
