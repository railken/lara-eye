<?php

namespace Railken\LaraEye\Query\Visitors;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Railken\SQ\Languages\BoomTree\Nodes as Nodes;

abstract class BaseOperatorVisitor extends BaseVisitor
{
    /**
     * The node that will trigger the visitor.
     *
     * @var string
     */
    protected $node = Nodes\Node::class;

    /**
     * The string operator for the query.
     *
     * @var string
     */
    protected $operator = '';

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
            $child0 = $node->getChildByIndex(0);
            $child1 = $node->getChildByIndex(1);

            if ($context === Nodes\OrNode::class) {
                $query->orWhere($this->parseNode($child0), $this->operator, $this->parseNode($child1));
            }

            if ($context === Nodes\AndNode::class) {
                $query->where($this->parseNode($child0), $this->operator, $this->parseNode($child1));
            }
        }
    }

    /**
     * Parse the node.
     *
     * @param \Railken\SQ\Contracts\NodeContract $node
     *
     * @return mixed
     */
    public function parseNode($node)
    {
        if ($node instanceof Nodes\KeyNode) {
            return $this->parseKey($node->getValue());
        }

        if ($node instanceof Nodes\ValueNode) {
            return $this->parseValue($node->getValue());
        }

        if ($node instanceof Nodes\FunctionNode) {
            // .. ?

            $f = $this->getBuilder()->getFunctions()->first(function ($item, $key) use ($node) {
                $class = $item->getNode();

                return $node instanceof $class;
            });

            if (!$f) {
                throw new \Railken\SQ\Exceptions\QuerySyntaxException();
            }

            $childs = new Collection();

            foreach ($node->getChilds() as $child) {
                $childs[] = $this->parseNode($child);
            }

            $childs = $childs->map(function ($v) {
                if ($v instanceof \Illuminate\Database\Query\Expression) {
                    return $v->getValue();
                }

                return $v;
            });

            return DB::raw($f->getName().'('.$childs->implode(',').')');
        }
    }

    /**
     * Parse key.
     *
     * @param string $key
     *
     * @return string
     */
    public function parseKey($key)
    {
        $key = (new Collection(explode('.', $key)))->map(function ($part) {
            return '`'.$part.'`';
        })->implode('.');

        return DB::raw($key);
    }

    /**
     * Parse value.
     *
     * @param string $value
     *
     * @return string
     */
    public function parseValue($value)
    {
        return $value;
    }
}
