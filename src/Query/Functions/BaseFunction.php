<?php

namespace Railken\LaraEye\Query\Functions;

abstract class BaseFunction
{
    /**
     * The node that will trigger the visitor.
     *
     * @var string
     */
    protected $node;

    /**
     * The string function for the query.
     *
     * @var string
     */
    protected $name;

    /**
     * @return string
     */
    public function getNode()
    {
        return $this->node;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
