<?php

namespace App\Search\Filters;

use App\Search\SearchQuery;

abstract class FilterDecorator implements SearchQuery
{
    /**
     * @var SearchQuery
     */
    protected $wrappedQuery;

    /**
     * @var string
     */
    protected $queryKey;

    /**
     * @var mixed
     */
    protected $queryValue;

    /**
     * FilterDecorator Constructor
     *
     * @param SearchQuery $wrappedQuery
     * @param string $queryKey
     * @param mixed $queryValue
     */
    public function __construct(SearchQuery $wrappedQuery, $queryKey, $queryValue)
    {
        $this->wrappedQuery = $wrappedQuery;
        $this->queryKey = $queryKey;
        $this->queryValue = $queryValue;
    }

    /**
     * @return array
     */
    public function search()
    {
        return array_values(
            array_intersect_key(
                $this->getInitData(),
                $this->getFilteredIndices()
            )
        );
    }

    /**
     * @return array
     */
    public function getInitData()
    {
        return $this->wrappedQuery->getInitData();
    }

    /**
     * @param array $data
     */
    public function setInitData($data)
    {
        return $this->wrappedQuery->setInitData($data);
    }
}
