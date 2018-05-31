<?php

namespace App\Search\Hotel;

use App\Search\BaseQuery;

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
        foreach ($parsedQuery as $query) {
            $searchQuery = new $query['filterClass'](
                $searchQuery,
                $query['queryKey'],
                $query['queryValue']
            );
        }
        return $searchQuery;
    }
}
