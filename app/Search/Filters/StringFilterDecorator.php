<?php

namespace App\Search\Filters;


class StringFilterDecorator extends FilterDecorator {

    public function getFilteredIndices(){
        $data_indices = $this->wrappedQuery->getFilteredIndices();
        $data = $this->getInitData();
        foreach ($data_indices as $index) {
            $dataKey = $this->queryKey;
            if (stripos($data[$index]->$dataKey, $this->queryValue) === false){
                unset($data_indices[$index]);
            }
        }
        return $data_indices;
    }
}
