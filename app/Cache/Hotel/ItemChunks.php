<?php 

namespace App\Cache\Hotel;

use Illuminate\Support\Facades\Cache;

class ItemChunks {

    private $chunksKeys;
    private $storedAt;
    
    public function __construct(array $chunks){
        $this->chunksKeys = $chunks;
        $this->storedAt = time();
    }

    private static function genKey($offset, $length){
        return uniqid($offset.'-'.$length, true);
    }

    public static function fromData($data, $chunkSize){
        $dataSize = count($data);
        $chunksKeys = [];
        for ($i=0; $i < $dataSize ; $i+=$chunkSize) { 
            $chunk = array_slice($data, $i, $i+$chunkSize);
            $chunkKey = self::genKey($i, $i+$chunkSize);
            Cache::put($chunkKey, $chunk, 1);
            $chunksKeys[] = $chunkKey;
        }
        return new self($chunksKeys);
    }

    public function forget(){
        foreach ($this->chunksKeys as $chunkKey) {
            Cache::forget($chunkKey);
        }
    }

    public function age(): int{
        return time() - $this->storedAt;
    }

    public function getChunk($ind){
        return Cache::get($this->chunksKeys[$ind]);
    }

    public function getChunksCount(){
        return count($this->chunksKeys);
    }
}