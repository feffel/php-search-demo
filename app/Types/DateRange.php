<?php

namespace App\Types;

use \Datetime;
use Illuminate\Support\Facades\Config;

class DateRange {
    
    private $from;
    private $to;

    public function __construct(DateTime $from, DateTime $to){
        if ($from > $to){
            return false;
        }
        $this->from = $from;
        $this->to = $to;
    }

    private static function dateFromStr($dateStr){
        $dateFormat = Config::get('constants.options.date_format');
        $dateStr .= ' 00:00';
        return DateTime::createFromFormat($dateFormat, $dateStr);
    }

    public static function createFromStr($dateRangeStr){
        $dateArr = explode(":", $dateRangeStr);
        if (count($dateArr) != 2){
            return false;
        }
        $from = self::dateFromStr($dateArr[0]);
        $to = self::dateFromStr($dateArr[1]);
        if ($from === false || $to === false || $to < $from){
            return false;
        }
        return new self($from, $to);
    }

    public static function createFromStdClass($dateStd){
        $from = self::dateFromStr($dateStd->from);
        $to = self::dateFromStr($dateStd->to);
        if ($from === false || $to === false){
            return false;
        }
        return new self($from, $to);
    }

    public function includesRange(DateRange $b){
        return ($this->from <= $b->from 
                && $this->to >= $b->to);
    }

    public function getFrom(){
        return $this->from;
    }

    public function getTo(){
        return $this->from;
    }
}