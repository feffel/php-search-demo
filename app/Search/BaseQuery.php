<?php

namespace App\Search;

class BaseQuery implements SearchQuery {
    
    private $data;

    public function __construct($data){
        $this->data = $data;
    }

    public function search(){
        return $this->getInitData();
    }

    public function getInitData(){
        return $this->data;
    }

    public function getFilteredIndices(){
        return range(0, count($this->data)-1);
    }
}