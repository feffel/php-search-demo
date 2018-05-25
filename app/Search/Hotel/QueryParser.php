<?php

namespace App\Search\Hotel;

use App\Helpers;

class QueryParser {

    private $queryArr;
    private $parsedQuery;

    public function __construct(array $queryArr) {
        $this->queryArr = $queryArr;
    }

    public function isValid() {

        if (!isset($this->parsedQuery)){
            $valid = $this->parse();
            if ($valid !== true){
                return false;
            }
        }
        return true;
    }

    public function getParsedQuery() {

        if ($this->isValid() === false){
            return false;
        }
        return $this->parsedQuery;
    }

    protected function parse() {
        $parsedQuery = [
            'name'        => $this->parseName(),
            'destination' => $this->parseDestination(),
            'price'       => $this->parsePrice(),
            'date'        => $this->parseDate(),
        ];
        foreach ($parsedQuery as  $param) {
            if ($param === false){
                return false;
            }
        }
        $this->parsedQuery = $parsedQuery;
        return true;
    }

    private function parseName(){
        $name = null;
        if (isset($this->queryArr['name'])){
            $name = $this->queryArr['name'];
            if (!is_string($name)){
                return false;
            }
        }
        return $name;
    }

    private function parseDestination(){
        $destination = null;
        if (isset($this->queryArr['destination'])){
            $destination = $this->queryArr['destination'];
            if (!is_string($destination)){
                return false;
            }
        }
        return $destination;
    }

    private function parsePrice(){
        $priceRange = null;
        if (isset($this->queryArr['price'])){
            if (!is_string($this->queryArr['price'])){
                return false;
            }
            $priceStr = explode(':', $this->queryArr['price']);
            if (count($priceStr) != 2){
                return false;
            }
            if (!is_numeric($priceStr[0]) || !is_numeric($priceStr[1])){
                return false;
            }
            if ($priceStr[0] > $priceStr[1]){
                return false;
            }
            $priceRange = [];
            $priceRange['from'] = (float)$priceStr[0];
            $priceRange['to'] = (float)$priceStr[1];
        }
        return $priceRange;
    }

    private function parseDate(){
        $dateRange = null;
        if (isset($this->queryArr['date'])){
            if (!is_string($this->queryArr['date'])){
                return false;
            }
            $dateStr = explode(":", $this->queryArr['date']);
            if (count($dateStr) != 2){
                return false;
            }
            $dateRange = [
                'from' => Helpers::dateFromStr($dateStr[0]),
                'to' => Helpers::dateFromStr($dateStr[1]),
            ];
            if ($dateRange['from'] === false || $dateRange['to'] === false){
                return false;
            }
            if ($dateRange['from'] > $dateRange['to']){
                return false;
            }
        }
        return $dateRange;
    }
}