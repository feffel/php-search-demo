<?php

namespace App\Search\Filters;


class PriceFilterDecorator extends FilterDecorator {

    public function getFilteredIndices(){
        $dataIndices = $this->wrappedQuery->getFilteredIndices();
        $data = $this->getInitData();
        foreach ($dataIndices as $index) {
            $dataKey = $this->queryKey;
            $dataVal = $data[$index]->$dataKey;
            if ($this->queryValue['from'] > $dataVal
                 || $this->queryValue['to'] < $dataVal){
                unset($dataIndices[$index]);
            }
        }
        return $dataIndices;
    }
}
