<?php

namespace App\Search\Hotel;

use App\Search\BaseQuery;
use App\Search\Filters\{DateFilterDecorator,
                        StringFilterDecorator,
                        PriceFilterDecorator};

class QueryBuilder {

    private $data;
    private $queryParser;

    public function __construct(QueryParser $queryParser, array $data) {
        $this->queryParser = $queryParser;
        $this->data = $data;
    }

    public function build() {

        if ($this->queryParser->isValid() !== true){
            return false;
        }

        $parsedQuery = $this->queryParser->getParsedQuery();
        $searchQuery = new BaseQuery($this->data);
        if (isset($parsedQuery['name'])){
            $searchQuery = new StringFilterDecorator($searchQuery, 'name',
                                               $parsedQuery['name']);
        }
        if (isset($parsedQuery['destination'])){
            $searchQuery = new StringFilterDecorator($searchQuery, 'city',
                                               $parsedQuery['destination']);
        }
        if (isset($parsedQuery['price'])){
            $searchQuery = new PriceFilterDecorator($searchQuery, 'price',
                                              $parsedQuery['price']);
        }
        if (isset($parsedQuery['date'])){
            $searchQuery = new DateFilterDecorator($searchQuery, 'availability',
                                             $parsedQuery['date']);
        }
        return $searchQuery;
    }
}
