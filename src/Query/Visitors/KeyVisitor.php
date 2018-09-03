<?php

namespace Railken\LaraEye\Query\Visitors;

use Railken\SQ\Contracts\NodeContract;
use Railken\SQ\Exceptions\QuerySyntaxException;
use Railken\SQ\Languages\BoomTree\Nodes as Nodes;

class KeyVisitor extends BaseVisitor
{
    /**
     * @var string
     */
    protected $base_table;

    /**
     * @var array
     */
    protected $keys;

    /**
     * @param string $base_table
     *
     * @return $this
     */
    public function setBaseTable($base_table)
    {
        $this->base_table = $base_table;

        return $this;
    }

    /**
     * @return string
     */
    public function getBaseTable()
    {
        return $this->base_table;
    }

    /**
     * @param mixed $keys
     *
     * @return $this
     */
    public function setKeys($keys)
    {
        $this->keys = $keys;

        return $this;
    }

    /**
     * @return array
     */
    public function getKeys()
    {
        return $this->keys;
    }

    /**
     * Visit the node and update the query.
     *
     * @param mixed                              $query
     * @param \Railken\SQ\Contracts\NodeContract $node
     * @param string                             $context
     */
    public function visit($query, NodeContract $node, string $context)
    {
        if ($node instanceof Nodes\KeyNode) {
            $key = $node->getValue();

            $keys = explode('.', $key);

            if (count($keys) === 1) {
                $keys = [$this->getBaseTable(), $keys[0]];
            }

            $key = implode('.', $keys);

            $node->setValue($key);

            if ($this->getKeys()[0] === '*') {
                return;
            }

            if (!in_array($key, $this->getKeys())) {
                throw new QuerySyntaxException();
            }
        }

        foreach ($node->getChilds() as $child) {
            $this->visit($query, $child, $context);
        }
    }
}
