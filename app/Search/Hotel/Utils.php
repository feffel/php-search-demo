<?php 

namespace App\Search\Hotel;

use Illuminate\Support\Facades\Config;
use App\Cache\Hotel\{CacheManager,ItemChunks};

define('SORT_KEYS', array(
    'name' => 'name',
    'price' => 'price',
));

class Utils{

    private static function fetchData($url){
        $raw_json = file_get_contents($url);
        $json_obj = json_decode($raw_json);
        if(!isset($json_obj)){
            return false;
        }
        return $json_obj->hotels;
    }

    private static function getData($url){
        CacheManager::acquireLock();
        $recent = CacheManager::getRecent($url);
        if (!isset($recent)){
            $data = self::fetchData($url);
            $recent = ItemChunks::fromData($data, 250);
            CacheManager::put($recent, $url);
        }
        CacheManager::releaseLock();
        return $recent;
    }

    public static function getSearchResults($params, $sourceUrl = null) {
        if (!isset($sourceUrl)){
            $sourceUrl = Config::get('constants.options.hotels_url');
        }
        $parser = new QueryParser($params);
        if ($parser->isValid() !== true){
            return false;
        }
        $query = (new QueryBuilder($parser))->build();
        $chunkedItem = self::getData($sourceUrl);
        if ($chunkedItem === false){
            return false;
        }
        $results = [];
        for ($i=0; $i<$chunkedItem->getChunksCount(); $i++){
            $chunk = $chunkedItem->getChunk($i);
            $query->setInitData($chunk);
            $results = array_merge($results, $query->search());
        }
        unset($chunk);
        if(isset($params['sort-by'])){
            $key = SORT_KEYS[$params['sort-by']];
            $sorting = $params['sorting']?? null;
            (new HotelSort($results, $key, $sorting))->sort();
        }
        return $results;
    }
}