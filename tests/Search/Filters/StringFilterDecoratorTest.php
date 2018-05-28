<?php


use App\Search\BaseQuery;
use App\Search\Filters\StringFilterDecorator;


class StringFilterDecoratorTest extends TestCase
{

    public function jsonQueryProvider()
    {
        return new JsonFileDataProviderIterator(__FILE__, 'QuerySearch');
    }

    public function jsonStringProvider()
    {
        return new JsonFileDataProviderIterator(__FILE__, 'StringSearch');
    }

    public function newBaseQuery(): BaseQuery{
        return new BaseQuery();
    }

    /**
     * @dataProvider jsonStringProvider
     */
    public function testStringSearch(stdClass $dataProvider)
    {   
        $searchMethod = self::getMethod('searchString',
                                        'App\Search\Filters\StringFilterDecorator');
        $stringFilter = new StringFilterDecorator($this->newBaseQuery(),
                                                  '',
                                                  $dataProvider->query);
        $result = $searchMethod->invokeArgs($stringFilter, [$dataProvider->str]);
        $this->assertEquals($result, $dataProvider->expected);
    }

    /**
     * @dataProvider jsonQueryProvider
     */
    public function testQuerySearch(stdClass $dataProvider)
    {      
        $baseQuery = $this->newBaseQuery();
        $stringFilter = new StringFilterDecorator($baseQuery,
                                                  $dataProvider->queryKey,
                                                  $dataProvider->query);
        $stringFilter->setInitData($dataProvider->data);
        $result = $stringFilter->search();
        $this->assertEquals($result, $dataProvider->expected);
    }
}
