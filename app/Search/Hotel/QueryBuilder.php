<?php

namespace App\Search\Hotel;

use App\Search\BaseQuery;
use App\Search\Filters\{DateFilterDecorator,
                        StringFilterDecorator,
                        PriceFilterDecorator};

class QueryBuilder
{
    /**
     * @var QueryParser
     */
    private $queryParser;

    public function __construct(QueryParser $queryParser)
    {
        $this->queryParser = $queryParser;
    }

    /**
     * Applies different query filters based on the query params extracted from $queryParser
     *
     * @return SearchQuery|false
     */
    public function build()
    {
        if ($this->queryParser->isValid() !== true) {
            return false;
        }
        $parsedQuery = $this->queryParser->getParsedQuery();
        $searchQuery = new BaseQuery();
        if (isset($parsedQuery['name'])) {
            $searchQuery = new StringFilterDecorator(
                $searchQuery,
                'name',
                $parsedQuery['name']
            );
        }
        if (isset($parsedQuery['destination'])) {
            $searchQuery = new StringFilterDecorator(
                $searchQuery,
                'city',
                $parsedQuery['destination']
            );
        }
        if (isset($parsedQuery['price'])) {
            $searchQuery = new PriceFilterDecorator(
                $searchQuery,
                'price',
                $parsedQuery['price']
            );
        }
        if (isset($parsedQuery['date'])) {
            $searchQuery = new DateFilterDecorator(
                $searchQuery,
                'availability',
                $parsedQuery['date']
            );
        }
        return $searchQuery;
    }
}
