<?php

namespace App\Types;

class PriceRange
{
    
    private $from;
    private $to;

    public function __construct(float $from, float $to)
    {
        if ($from > $to) {
            return false;
        }
        $this->from = $from;
        $this->to = $to;
    }

    public static function createFromStr($priceRangeStr)
    {
        $dateArr = explode(":", $priceRangeStr);
        if (count($dateArr) != 2 || !is_numeric($dateArr[0]) || !is_numeric($dateArr[1])) {
            return false;
        }
        $from = (float)$dateArr[0];
        $to = (float)($dateArr[1]);
        return new self($from, $to);
    }

    public function includesPricePoint(float $b)
    {
        return ($this->from <= $b
                && $this->to >= $b);
    }

    public function getFrom()
    {
        return $this->from;
    }

    public function getTo()
    {
        return $this->from;
    }
}
