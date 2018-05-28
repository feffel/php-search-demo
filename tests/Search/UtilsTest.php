<?php

use \App\HotelUtils;


class UtilsTest extends TestCase
{ 
    public function testValidFetchData()
    {   
        $hotels = '{"hotels":[{"name": "abc","price": 102.2,"city": "dubai","availability":[{"from":"10-10-2020","to":"15-10-2020"},{"from":"10-12-2020","to":"15-12-2020"}]}]}';
        HotelUtils::shouldReceive('file_get_contents')
                    ->with(11)
                    ->andReturn($hotels);
        HotelUtils::makePartial();
        $r = HotelUtils::fetchData(11);
        $this->assertEquals($r, json_decode($hotels)->hotels);
    }

    public function testInvalidFetchData()
    {   
        $hotels = '"hotels":[{"name": "abc","price": 102.2,"city": "dubai","availability":[{"from":"10-10-2020","to":"15-10-2020"},{"from":"10-12-2020","to":"15-12-2020"}]}]}';
        HotelUtils::shouldReceive('file_get_contents')
                    ->with(11)
                    ->andReturn($hotels);
        HotelUtils::makePartial();
        $r = HotelUtils::fetchData(11);
        $this->assertFalse($r);
    }

    public function testValidGetData()
    {   
        $hotels = '[{"name": "abc","price": 102.2,"city": "dubai","availability":[{"from":"10-10-2020","to":"15-10-2020"},{"from":"10-12-2020","to":"15-12-2020"}]}]';
        HotelUtils::shouldReceive('fetchData')
                    ->with("11")
                    ->andReturn(json_decode($hotels));
        HotelUtils::makePartial();
        $r = HotelUtils::getData("11");
        $this->assertInstanceOf(\App\Cache\Hotel\ItemChunks::class, $r);
    }

    public function testGetSearchResults()
    {   
        $hotels = json_decode('[{"name": "abc","price": 102.2,"city": "dubai","availability":[{"from":"10-10-2020","to":"15-10-2020"},{"from":"10-12-2020","to":"15-12-2020"}]}]');
        $stub = $this->createMock(\App\Cache\Hotel\ItemChunks::class);
        $stub->method('getChunksCount')
             ->willReturn(1);
        $stub->method('getChunk')
             ->willReturn(($hotels));
        HotelUtils::shouldReceive('getData')
                    ->with("11")
                    ->andReturn($stub);
        HotelUtils::makePartial();
        $r = HotelUtils::getSearchResults([], "11");
        $this->assertEquals($hotels, $r);
    }

}
