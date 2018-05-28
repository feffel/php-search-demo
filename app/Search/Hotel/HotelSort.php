<?php

namespace App\Search\Hotel;

use App\Search\BaseSort;

class HotelSort extends BaseSort
{
    protected function descendSort(): callable
    {
        $key = $this->key;
        return function ($a, $b) use ($key) {
            return $a->$key < $b->$key? 1 : -1;
        };
    }

    protected function ascendSort(): callable
    {
        $key = $this->key;
        return function ($a, $b) use ($key) {
            return $a->$key > $b->$key? 1 : -1;
        };
    }
}
