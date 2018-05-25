<?php

namespace App;

use \Datetime;
use Illuminate\Support\Facades\Config;

class Helpers {

    public static function dateFromStr($dateStr){
        $dateFormat = Config::get('constants.options.date_format');
        $dateStr .= ' 00:00';
        return DateTime::createFromFormat($dateFormat, $dateStr);
    }

}