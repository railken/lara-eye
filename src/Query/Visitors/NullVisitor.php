<?php

namespace Railken\LaraEye\Query\Visitors;

use Railken\SQ\Languages\BoomTree\Nodes as Nodes;

class NullVisitor extends BaseOperatorVisitor
{
    /**
     * The node that will trigger the visitor.
     *
     * @var string
     */
    protected $node = Nodes\NullNode::class;

    /**
     * Visit the node and update the query.
     *
     * @param mixed                              $query
     * @param \Railken\SQ\Contracts\NodeContract $node
     * @param string                             $context
     */
    public function visit($query, $node, string $context)
    {
        if ($node instanceof $this->node) {
            $bindings = [];
            $sql = [];

            $child0 = $node->getChildByIndex(0);

            if ($child0 instanceof Nodes\KeyNode) {
                $context === Nodes\OrNode::class && $query->orWhereNull($this->parseKey($child0->getValue()));
                $context === Nodes\AndNode::class && $query->whereNull($this->parseKey($child0->getValue()));
            }
        }
    }
}
