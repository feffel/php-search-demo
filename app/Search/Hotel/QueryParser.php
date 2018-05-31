<?php

namespace App\Search\Hotel;

use App\Types\{DateRange, PriceRange};
use App\Search\Filters\{DateFilterDecorator,
                        StringFilterDecorator,
                        PriceFilterDecorator};

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
            if ($valid !== true) {
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
            'name'        => [
                'queryValue'    => $this->parseStr('name'),
                'queryKey'      => 'name',
                'filterClass'   => StringFilterDecorator::class,
            ],
            'destination' => [
                'queryValue'    => $this->parseStr('destination'),
                'queryKey'      => 'city',
                'filterClass'   => StringFilterDecorator::class,
            ],
            'price'       => [
                'queryValue'    => $this->parsePrice('price'),
                'queryKey'      => 'price',
                'filterClass'   => PriceFilterDecorator::class,
            ],
            'date'        => [
                'queryValue'    => $this->parseDate('date'),
                'queryKey'      => 'availability',
                'filterClass'   => DateFilterDecorator::class,
            ]
        ];
        foreach ($parsedQuery as $key => $param) {
            if ($param['queryValue'] === false) {
                return false;
            }
            if ($param['queryValue'] === null) {
                unset($parsedQuery[$key]);
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
     * @param string key
     * @return array|null|false
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
     * @param string key
     * @return PriceRange|null|false
     */
    private function parsePrice($key)
    {
        $priceRange = null;
        if (isset($this->queryArr[$key])) {
            if (!is_string($this->queryArr[$key])) {
                return false;
            }
            $priceRange = PriceRange::createFromStr($this->queryArr[$key]);
        }
        return $priceRange;
    }

    /**
     * @param string key
     * @return DateRange|null|false
     */
    private function parseDate($key)
    {
        $dateRange = null;
        if (isset($this->queryArr[$key])) {
            if (!is_string($this->queryArr[$key])) {
                return false;
            }
            $dateRange = DateRange::createFromStr($this->queryArr[$key]);
        }
        return $dateRange;
    }
}
