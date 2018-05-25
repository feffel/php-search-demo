<?php

namespace App\Search\Filters;

use App\Helpers;

class DateFilterDecorator extends FilterDecorator {

    public function getFilteredIndices(){
        $dataIndices = $this->wrappedQuery->getFilteredIndices();
        $data = $this->getInitData();

        foreach ($dataIndices as $index) {
            $dataKey = $this->queryKey;
            $dateArr = $data[$index]->$dataKey;
            $found = false;
            foreach ($dateArr as $date) {
                $hotelAvlbl = [
                    'from' => Helpers::dateFromStr($date->from),
                    'to' => Helpers::dateFromStr($date->to),
                ];
                if ($this->queryValue['from'] >= $hotelAvlbl['from'] 
                     && $this->queryValue['to'] <= $hotelAvlbl['to']){
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                unset($dataIndices[$index]);
            }
        }
        return $dataIndices;
    }
}

