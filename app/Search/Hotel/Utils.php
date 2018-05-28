<?php 

namespace App\Search\Hotel;

use Illuminate\Support\Facades\Config;
use App\Cache\Hotel\{CacheManager,ItemChunks};


class Utils{

    const SORT_KEYS = [
        'name' => 'name',
        'price' => 'price'
    ];

    public function file_get_contents($url){
        return file_get_contents($url);
    }

    public function fetchData($url){
        $raw_json = $this->file_get_contents($url);
        $json_obj = json_decode($raw_json);
        if(!isset($json_obj)){
            return false;
        }
        return $json_obj->hotels;
    }

    public function getData($url){
        CacheManager::acquireLock();
        $recent = CacheManager::getRecent($url);
        if (!isset($recent)){
            $data = $this->fetchData($url);
            $recent = ItemChunks::fromData($data, 250);
            CacheManager::put($recent, $url);
        }
        CacheManager::releaseLock();
        return $recent;
    }

    public function getSearchResults($params, $sourceUrl = null) {
        if (!isset($sourceUrl)){
            $sourceUrl = Config::get('constants.options.hotels_url');
        }
        $parser = new QueryParser($params);
        if ($parser->isValid() !== true){
            return false;
        }
        $query = (new QueryBuilder($parser))->build();
        $chunkedItem = $this->getData($sourceUrl);
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
            $key = self::SORT_KEYS[$params['sort-by']];
            $sorting = $params['sorting']?? null;
            (new HotelSort($results, $key, $sorting))->sort();
        }
        return $results;
    }
}