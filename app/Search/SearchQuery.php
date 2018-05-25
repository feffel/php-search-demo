<?php

namespace App\Search;

interface SearchQuery {
    public function search();
    public function getInitData();
    public function getFilteredIndices();
}
