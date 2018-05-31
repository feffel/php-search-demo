<?php

namespace App\Types;

use \Datetime;
use Illuminate\Support\Facades\Config;

/**
 * DateRange Class
 *
 * Defines an  inclusive date range.
 */
class DateRange
{
    /**
     * @var DateTime
     */
    private $from;

    /**
     * @var DateTime
     */
    private $to;

    /**
     * DateRange Constructor
     *
     * First date in range must not be greater than the last.
     *
     * @param DateTime $from    first date in range
     * @param DateTime $to      last date in range
     */
    public function __construct(DateTime $from, DateTime $to)
    {
        if ($from > $to) {
            throw new Exception("Invalid DateRange, from date is greater than to date");
        }
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * Creates date from date string
     *
     * @static
     * @param string $dateStr   must be in the default date format defined in /config
     * @return DateTime
     */
    private static function dateFromStr($dateStr)
    {
        $dateFormat = Config::get('constants.options.date_format');
        $dateStr .= ' 00:00';
        return DateTime::createFromFormat($dateFormat, $dateStr);
    }

    /**
     * Creates DateRange from date range string
     * Both from and to must be in the default date format defined in /config
     *
     * @static
     * @param string $dateRangeStr  must be in the format "from:to"
     * @return DateRange|false
     */
    public static function createFromStr($dateRangeStr)
    {
        $dateArr = explode(":", $dateRangeStr);
        if (count($dateArr) != 2) {
            return false;
        }
        $from = self::dateFromStr($dateArr[0]);
        $to = self::dateFromStr($dateArr[1]);
        if ($from === false || $to === false || $to < $from){
            return false;
        }
        return new self($from, $to);
    }

    /**
     * Creates DateRange from stdClass with the defined propreties (from, to)
     * Both from and to must be in the default date format defined in /config
     *
     * @static
     * @param stdClass $dateStd
     * @return DateRange|false
     */
    public static function createFromStdClass($dateStd)
    {
        $from = self::dateFromStr($dateStd->from);
        $to = self::dateFromStr($dateStd->to);
        if ($from === false || $to === false || $to < $from){
            return false;
        }
        return new self($from, $to);
    }

    /**
     * Checks if a DateRange is included within it's range
     *
     * @param DateRange $b
     * @return bool
     */
    public function includesRange(DateRange $b)
    {
        return ($this->from <= $b->from
                && $this->to >= $b->to);
    }


    /**
     * @return DateTime
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @return DateTime
     */
    public function getTo()
    {
        return $this->from;
    }
}
