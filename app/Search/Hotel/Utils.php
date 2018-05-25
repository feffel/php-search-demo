<?php 

namespace App\Search\Hotel;

use Illuminate\Support\Facades\Config;

define('SORT_KEYS', array(
    'name' => 'name',
    'price' => 'price',
));

class Utils{

    public static function fetchData($url){
        $raw_json = file_get_contents($url);
        $json_obj = json_decode($raw_json);
        if(!isset($json_obj)){
            return false;
        }
        return $json_obj->hotels;
    }

    public static function getSearchResults($params, $sourceUrl = null) {
        if (!isset($sourceUrl)){
            $sourceUrl = Config::get('constants.options.hotels_url');
        }
        $parser = new QueryParser($params);
        if ($parser->isValid() !== true){
            return false;
        }
        $hotelData = self::fetchData($sourceUrl);
        if ($hotelData === false){
            return false;
        }
        $query = (new QueryBuilder($parser, $hotelData))->build();
        $results = $query->search();
        if(isset($params['sort-by'])){
            $key = SORT_KEYS[$params['sort-by']];
            $descending = (bool) (isset($params['sorting'])
                                  && $params['sorting'] == 'descending');
            (new HotelSort($results, $key, $descending))->sort();
        }
        return $results;
    }
}