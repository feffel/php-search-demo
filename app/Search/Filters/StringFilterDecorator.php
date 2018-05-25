<?php

namespace App\Search\Filters;


class StringFilterDecorator extends FilterDecorator {

    public function getFilteredIndices(){
        $dataIndices = $this->wrappedQuery->getFilteredIndices();
        $data = $this->getInitData();
        foreach ($dataIndices as $index) {
            $found = false;
            $dataKey = $this->queryKey;
            foreach ($this->queryValue as $queryWord) {
                if (stripos($data[$index]->$dataKey, $queryWord) !== false){
                    $found = true;
                    break;
                }
            }
            if (!$found){
                unset($dataIndices[$index]);
            }
        }
        return $dataIndices;
    }
}
