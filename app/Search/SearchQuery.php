<?php

namespace App\Search;

interface SearchQuery
{
    /**
     * Returns filtered data based on the search result of the query
     *
     * @return arary
     */
    public function search();

    /**
     * Returns the initial data used before applying search
     *
     * @return arary
     */
    public function getInitData();

    /**
     * @param arary $data
     */
    public function setInitData($data);

    /**
     * Returns filtered keys to the initial data array after applying search
     *
     * @return arary
     */
    public function getFilteredIndices();
}
