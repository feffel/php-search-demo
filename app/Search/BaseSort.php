<?php

namespace App\Search;

abstract class BaseSort {
    
    protected $data;
    protected $key;
    protected $descending = false;

    public function __construct(&$data, $key, bool $descending){
        $this->data = &$data;
        $this->key = $key;
        $this->descending = $descending;
    }

    public function sort(){
        if ($this->descending){
            $cmprFunc = $this->descendSort();
        } else {
            $cmprFunc = $this->ascendSort();
        }
        usort($this->data, $cmprFunc);
        return;
    }
}