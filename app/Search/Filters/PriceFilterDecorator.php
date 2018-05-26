<?php

namespace App\Search\Filters;


class PriceFilterDecorator extends FilterDecorator {

    public function getFilteredIndices(){
        $dataIndices = $this->wrappedQuery->getFilteredIndices();
        $data = $this->getInitData();
        foreach ($dataIndices as $index) {
            $price = $data[$index]->{$this->queryKey};
            if (!$this->queryValue->includesPricePoint($price)){
                unset($dataIndices[$index]);
            }
        }
        return $dataIndices;
    }
}
