<?php

namespace Railken\LaraEye\Query\Visitors;

use Railken\LaraEye\Query\Builder;

class BaseVisitor
{
    /**
     * @var \Railken\LaraEye\Query\Builder
     */
    protected $builder;

    /**
     * Construct.
     *
     * @param \Railken\LaraEye\Query\Builder $builder
     */
    public function __construct(Builder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * Get builder.
     *
     * @return \Railken\LaraEye\Query\Builder
     */
    public function getBuilder()
    {
        return $this->builder;
    }
}
