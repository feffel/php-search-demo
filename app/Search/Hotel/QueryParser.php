<?php

namespace App\Search\Hotel;

use App\Types\{DateRange, PriceRange};

class QueryParser
{
    /**
     * @var array
     */
    private $queryArr;

    /**
     * @var array
     */
    private $parsedQuery;

    public function __construct(array $queryArr)
    {
        $this->queryArr = $queryArr;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        if (!isset($this->parsedQuery)) {
            $valid = $this->parse();
            if ($valid !== true){
                return false;
            }
        }
        return true;
    }

    /**
     * @return array|false
     */
    public function getParsedQuery() 
    {

        if ($this->isValid() === false) {
            return false;
        }
        return $this->parsedQuery;
    }

    /**
     * @return bool
     */
    protected function parse() 
    {
        $parsedQuery = [
            'name'        => $this->parseName(),
            'destination' => $this->parseDestination(),
            'price'       => $this->parsePrice(),
            'date'        => $this->parseDate(),
        ];
        foreach ($parsedQuery as $param) {
            if ($param === false){
                return false;
            }
        }
        $this->parsedQuery = $parsedQuery;
        return true;
    }

    /**
     * Removes non-alphanumeric characters from a string and splits it by white spaces
     *
     * @return array
     */
    private function cleanStr(string $str): array
    {
        $str = preg_replace("/[^a-zA-Z0-9 ]+/", "", $str);
        return array_filter(explode(' ', $str));
    }

    /**
     * @return array
     */
    private function parseStr($key)
    {
        $str = null;
        if (isset($this->queryArr[$key])) {
            $str = $this->queryArr[$key];
            if (!is_string($str)) {
                return false;
            }
            $str = $this->cleanStr($str);
        }
        return $str;
    }

    /**
     * @return array|null|false
     */
    private function parseName()
    {
        return $this->parseStr('name');
    }

    /**
     * @return array|null|false
     */
    private function parseDestination()
    {
        return $this->parseStr('destination');
    }

    /**
     * @return PriceRange|null|false
     */
    private function parsePrice()
    {
        $priceRange = null;
        if (isset($this->queryArr['price'])) {
            if (!is_string($this->queryArr['price'])) {
                return false;
            }
            $priceRange = PriceRange::createFromStr($this->queryArr['price']);
        }
        return $priceRange;
    }

    /**
     * @return DateRange|null|false
     */
    private function parseDate()
    {
        $dateRange = null;
        if (isset($this->queryArr['date'])) {
            if (!is_string($this->queryArr['date'])) {
                return false;
            }
            $dateRange = DateRange::createFromStr($this->queryArr['date']);
        }
        return $dateRange;
    }
}
