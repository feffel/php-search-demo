<?php 

namespace App\Cache\Hotel;

use Illuminate\Support\Facades\Cache;

class CacheManager {

    const KEYS_LIMIT = 3;
    const MAX_USAGE = 10;
    const MAX_AGE = 22;
    const semKey = 522721;
    private static $semLock;
    

    public static function acquireLock(){
        if (!isset(self::$semLock)){
            self::$semLock = sem_get(self::semKey);
        }
        sem_acquire(self::$semLock);
    }

    public static function releaseLock(){
        sem_release(self::$semLock);
    }

    private static function clean($ref){
        $keys = self::getAllKeys($ref);
        foreach ($keys as $i) {
            if (Cache::has($i)){
                $item = Cache::get($i);
                if ($item->age() > self::MAX_AGE){
                    $item->forget();
                    Cache::forget($i);
                }
            }
        }
    }

    private static function generateKey($ref, $postFix){
        return $ref . '-' . $postFix;
    }

    public static function inCache($ref){
        $all = [];
        $keys = self::getAllKeys($ref);
        foreach ($keys as $i) {
            if (Cache::has($i)){
                $all[] = Cache::get($i);
            }
        }
        return $all;
    }

    private static function getAllKeys($ref){
        $keys = [];
        for ($i=0; $i < self::KEYS_LIMIT ; $i++) { 
            $keys[] = self::generateKey($ref, $i);
        }
        return $keys;
    }

    private static function getFreeKey($ref){
        $keys = self::getAllKeys($ref);
        foreach ($keys as $k) {
            if (!Cache::has($k)){
                return $k;
            }
        }
        return null;
    }

    private static function minAge(Array $arr){
        $ageFunc = function($a){
            return $a->age();
        };
        $mapped = array_map($ageFunc, $arr);
        return $arr[array_search(min($mapped), $mapped)];
    }

    public static function getRecent($ref){
        self::clean($ref);
        $inCache = self::inCache($ref);
        if(count($inCache) == 0){
            return null;
        }
        $mostRecent = self::minAge($inCache);
        if ($mostRecent->age() < self::MAX_USAGE){
            return $mostRecent;
        }
        return null;
    }

    public static function put($item, $ref){
        self::clean($ref);
        $key = self::getFreeKey($ref);
        Cache::put($key, $item, 1);
        return;
    }
}