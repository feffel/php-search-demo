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


    public static function has($key){
        if (Cache::has($key)){
            $item = Cache::get($key);
            if ($item->age() > self::MAX_AGE){
                self::forget($item, $key);
            } else {
                return true;
            }
        }
        return false;
    }

    public static function get($key){
        if (self::has($key)){
            return Cache::get($key);
        }
        return null;
    }

    public static function forget($item, $key){
        $item->forget();
        Cache::forget($key);
    }


    private static function generateKey($ref, $postFix){
        return $ref . '-' . $postFix;
    }

    public static function inCache($ref){
        $all = [];
        $keys = self::getAllKeys($ref);
        foreach ($keys as $i) {
            if (self::has($i)){
                $all[] = self::get($i);
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
            if (!self::has($k)){
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
        $key = self::getFreeKey($ref);
        Cache::put($key, $item, 1);
        return;
    }
}