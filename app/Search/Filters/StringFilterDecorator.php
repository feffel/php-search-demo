<?php

namespace App\Search\Filters;

class StringFilterDecorator extends FilterDecorator
{
    /**
     * @return bool
     */
    private function searchString(string $str)
    {
        foreach ($this->queryValue as $queryWord) {
            if (stripos($str, $queryWord) !== false) {
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
            $str = $data[$index]->{$this->queryKey};
            if(!$this->searchString($str)) {
                unset($dataIndices[$index]);
            }
        }
        return $dataIndices;
    }
}
