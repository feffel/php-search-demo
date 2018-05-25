<?php

namespace App\Search\Filters;

use App\Search\SearchQuery;

abstract class FilterDecorator implements SearchQuery {

    public $wrappedQuery;
    public $queryKey;
    public $queryValue;

    public function __construct(SearchQuery $wrappedQuery, $queryKey, $queryValue){
        $this->wrappedQuery = $wrappedQuery;
        $this->queryKey = $queryKey;
        $this->queryValue = $queryValue;
    }

    public function search(){
        return array_values(array_intersect_key($this->getInitData(),
                                                $this->getFilteredIndices()));
    }

    public function getInitData(){
        return $this->wrappedQuery->getInitData();
    }
}
