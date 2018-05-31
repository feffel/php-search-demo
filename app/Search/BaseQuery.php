<?php

namespace App\Search;

class BaseQuery implements SearchQuery
{
    
    /**
     * @var arary
     */
    private $data;

    public function __construct()
    {
    }

    /**
     * Returns all data, since BaseQuery does not implement any filtration logic
     *
     * @return arary
     */
    public function search()
    {
        return $this->getInitData();
    }

    /**
     * @return arary
     */
    public function getInitData()
    {
        return $this->data;
    }

    /**
     * @return arary
     */
    public function setInitData($data)
    {
        $this->data = $data;
    }

    /**
     * @return arary    consists of $data keys
     */
    public function getFilteredIndices()
    {
        return array_keys($this->data);
    }
}
