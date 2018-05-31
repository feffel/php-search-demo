<?php

namespace App\Search\Filters;

use App\Types\DateRange;

class DateFilterDecorator extends FilterDecorator
{

    /**
     * @return bool
     */
    private function dateSearch(array $avlblArr)
    {
        $queryDate = $this->queryValue;
        foreach ($avlblArr as $date) {
            $hotelAvlbl = DateRange::createFromStdClass($date);
            if ($hotelAvlbl->includesRange($queryDate)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return array
     */
    public function getFilteredIndices()
    {
        $dataIndices = $this->wrappedQuery->getFilteredIndices();
        $data = $this->getInitData();
        foreach ($dataIndices as $index) {
            $dateArr = $data[$index]->{$this->queryKey};
            if (!$this->dateSearch($dateArr)) {
                unset($dataIndices[$index]);
            }
        }
        return $dataIndices;
    }
}
