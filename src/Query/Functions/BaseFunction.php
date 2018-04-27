<?php

namespace Railken\LaraEye\Query\Functions;

use Railken\SQ\Languages\BoomTree\Nodes as Nodes;

class BaseFunction
{
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
