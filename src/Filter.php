<?php

namespace Railken\LaraEye;

use Railken\LaraEye\Query\Functions as Functions;
use Railken\LaraEye\Query\Visitors as Visitors;
use Railken\SQ\Languages\BoomTree\Resolvers as Resolvers;
use Railken\SQ\QueryParser;

class Filter
{
    /**
     * @var string
     */
    protected $table;

    /**
     * @var array
     */
    protected $keys;

    /**
     * Construct.
     *
     * @param string $table
     * @param array  $keys
     */
    public function __construct(string $table, array $keys)
    {
        $this->table = $table;
        $this->keys = array_map(function ($key) use ($table) {
            if ($key === '*') {
                return $key;
            }

            $keys = explode('.', $key);

            if (count($keys) === 1) {
                return implode('.', [$table, $key]);
            }

            return $key;
        }, $keys);
    }

    /**
     * Filter query with where.
     *
     * @return Query\Builder
     */
    public function getBuilder()
    {
        $builder = new Query\Builder();
        $builder->setVisitors([
            (new Visitors\KeyVisitor($builder))->setKeys($this->keys)->setBaseTable($this->table),
            new Visitors\GroupVisitor($builder),
            new Visitors\EqVisitor($builder),
            new Visitors\NotEqVisitor($builder),
            new Visitors\GtVisitor($builder),
            new Visitors\GteVisitor($builder),
            new Visitors\LtVisitor($builder),
            new Visitors\LteVisitor($builder),
            new Visitors\CtVisitor($builder),
            new Visitors\SwVisitor($builder),
            new Visitors\EwVisitor($builder),
            new Visitors\AndVisitor($builder),
            new Visitors\OrVisitor($builder),
            new Visitors\NotInVisitor($builder),
            new Visitors\InVisitor($builder),
            new Visitors\NullVisitor($builder),
            new Visitors\NotNullVisitor($builder),
        ]);
        $builder->setFunctions([
            new Functions\ConcatFunction(),
        ]);

        return $builder;
    }

    /**
     * Convert the string query into an object (e.g.).
     *
     * @return QueryParser
     */
    public function getParser()
    {
        $parser = new QueryParser();
        $parser->addResolvers([
            new Resolvers\ValueResolver(),
            new Resolvers\KeyResolver(),
            new Resolvers\GroupingResolver(),
            new Resolvers\SumFunctionResolver(),
            new Resolvers\DateFormatFunctionResolver(),
            new Resolvers\ConcatFunctionResolver(),
            new Resolvers\NotEqResolver(),
            new Resolvers\EqResolver(),
            new Resolvers\LteResolver(),
            new Resolvers\LtResolver(),
            new Resolvers\GteResolver(),
            new Resolvers\GtResolver(),
            new Resolvers\CtResolver(),
            new Resolvers\SwResolver(),
            new Resolvers\EwResolver(),
            new Resolvers\NotInResolver(),
            new Resolvers\InResolver(),
            new Resolvers\NotNullResolver(),
            new Resolvers\NullResolver(),
            new Resolvers\AndResolver(),
            new Resolvers\OrResolver(),
        ]);

        return $parser;
    }

    /**
     * Filter query with where.
     *
     * @param mixed  $query
     * @param string $filter
     */
    public function build($query, $filter)
    {
        $parser = $this->getParser();
        $builder = $this->getBuilder();

        try {
            $builder->build($query, $parser->parse($filter));
        } catch (\Railken\SQ\Exceptions\QuerySyntaxException $e) {
            throw new \Railken\SQ\Exceptions\QuerySyntaxException($filter);
        }
    }
}
