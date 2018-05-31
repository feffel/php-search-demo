<?php

namespace App\Types;

/**
 * PriceRange Class
 *
 * Defines an inclusive price range.
 */
class PriceRange
{
    /**
     * @var float
     */
    private $from;

    /**
     * @var float
     */
    private $to;

    /**
     * PriceRange Constructor
     *
     * First price in range must not be greater than the last.
     *
     * @param float $from   first date in range
     * @param float $to     last date in range
     */
    public function __construct(float $from, float $to)
    {
        if ($from > $to) {
            throw new Exception("Invalid PriceRange, from price is greater than to price");
        }
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * Creates PriceRange from price range string
     * Both from and to must be represent valid numeric values
     *
     * @static
     * @param string $priceRangeStr     must be in the format "from:to"
     * @return PriceRange|false
     */
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

    /**
     * Checks if a price is included within it's range
     *
     * @param float $b
     * @return bool
     */
    public function includesPricePoint(float $b)
    {
        return ($this->from <= $b
                && $this->to >= $b);
    }

    /**
     * @return float
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @return float
     */
    public function getTo()
    {
        return $this->from;
    }
}
