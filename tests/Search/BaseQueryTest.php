<?php


use App\Search\BaseQuery;


class BaseQueryTest extends TestCase
{

    public function jsonDataProvider()
    {
        return new JsonFileDataProviderIterator(__FILE__, '');
    }

    public function testNewBasicQuery()
    {   
        $query = new BaseQuery();
        $this->assertNotNull($query);
        return $query;
    }

    /**
     * @depends      testNewBasicQuery
     * @dataProvider jsonDataProvider
     */
    public function testSetInitialData(array $data, BaseQuery $query)
    {
        $query->setInitData($data);
        $this->assertNotNull($data);
        return $query;
    }

    /**
     * @depends      testNewBasicQuery
     * @dataProvider jsonDataProvider
     */
    public function testGetInitialData(array $data, BaseQuery $query)
    {
        $queryData = $query->getInitData();
        $this->assertEquals($data, $queryData);
        return $query;
    }

    /**
     * @depends      testNewBasicQuery
     * @dataProvider jsonDataProvider
     */
    public function testGetFilteredIndices(array $data, BaseQuery $query)
    {
        $queryDataIndices = $query->getFilteredIndices();
        $dataIndices = range(0, count($data) - 1);
        $this->assertEquals($dataIndices, $queryDataIndices);
        return $query;
    }

    /**
     * @depends      testNewBasicQuery
     * @dataProvider jsonDataProvider
     */

    public function testSearch(array $data, BaseQuery $query)
    {
        $results = $query->search();
        $this->assertEquals($results, $results);
    }


}
