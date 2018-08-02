<?php

namespace Railken\LaraEye\Query;

use Illuminate\Support\Collection;
use Railken\SQ\Contracts\NodeContract;
use Railken\SQ\Languages\BoomTree\Nodes as Nodes;

class Builder
{
    /**
     * @var string
     */
    protected $context;

    /**
     * @var array
     */
    protected $visitors;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected $functions;

    /**
     * Construct.
     *
     * @var array
     */
    public function __construct()
    {
    }

    /**
     * Set visitors.
     *
     * @param array $visitors
     *
     * @return $this
     */
    public function setVisitors($visitors)
    {
        $this->visitors = $visitors;

        return $this;
    }

    /**
     * Set functions.
     *
     * @param array $functions
     *
     * @return $this
     */
    public function setFunctions($functions)
    {
        $this->functions = new Collection($functions);

        return $this;
    }

    /**
     * @return Collection
     */
    public function getFunctions()
    {
        return $this->functions;
    }

    /**
     * Build the query.
     *
     * @param mixed                              $query
     * @param \Railken\SQ\Contracts\NodeContract $node
     * @param string                             $context
     */
    public function build($query, NodeContract $node, $context = Nodes\AndNode::class)
    {
        foreach ($this->visitors as $visitor) {
            $visitor->visit($query, $node, $context);
        }
    }
}
