<?php

use App\Search\Hotel\HotelSort;


class HotelSortTest extends TestCase
{
    public function jsonDataProvider()
    {
        return new JsonFileDataProviderIterator(__FILE__, '');
    }

    /**
     * @dataProvider jsonDataProvider
     */
    public function testNewHotelSort(stdClass $dataProvider)
    {   
        $data = $dataProvider->data;
        $hotelSort = new HotelSort($data, 'price', 'ascending');
        $this->assertNotNull($hotelSort);
        $hotelSort = new HotelSort($data, 'name', 'ascending');
        $this->assertNotNull($hotelSort);
        $hotelSort = new HotelSort($data, 'price', 'descending');
        $this->assertNotNull($hotelSort);
    }

    /**
     * @dataProvider jsonDataProvider
     */
    public function testSorting(stdClass $dataProvider)
    {   
        $params = $dataProvider->params;
        $data = $dataProvider->data;
        $expected = $dataProvider->expected;
        $hotelSort = new HotelSort($data, ...$params);
        $hotelSort->sort();
        $this->assertEquals($data, $expected);
    }
}
