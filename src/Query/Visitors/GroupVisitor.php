<?php

namespace Railken\LaraEye\Query\Visitors;

use Illuminate\Database\Query\Builder;
use Railken\SQ\Contracts\NodeContract;
use Railken\SQ\Languages\BoomTree\Nodes as Nodes;

class GroupVisitor extends BaseVisitor
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
        if ($node instanceof Nodes\GroupNode) {

            $callback = function ($q) use ($node,$context) {
                foreach ($node->getChilds() as $child) {
                    $this->getBuilder()->build($q, $child, $context);
                }
            };

            $context === Nodes\OrNode::class && $query->orWhere($callback);
            $context === Nodes\AndNode::class && $query->where($callback);
        }
    }
}
