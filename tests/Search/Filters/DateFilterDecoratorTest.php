<?php


use App\Types\DateRange;
use App\Search\BaseQuery;
use App\Search\Filters\DateFilterDecorator;

class DateFilterDecoratorTest extends TestCase
{

    public function jsonQueryProvider()
    {
        return new JsonFileDataProviderIterator(__FILE__, 'QuerySearch');
    }

    public function jsonDateProvider()
    {
        return new JsonFileDataProviderIterator(__FILE__, 'DateSearch');
    }

    public function newBaseQuery(): BaseQuery{
        return new BaseQuery();
    }

    public function createRange($a, $b): DateRange{
        return new DateRange(new DateTime($a),new DateTime($b));
    }

    /**
     * @dataProvider jsonDateProvider
     */
    public function testDateSearch(stdClass $dataProvider)
    {   
        $searchMethod = self::getMethod('dateSearch',
                                        'App\Search\Filters\DateFilterDecorator');
        $query = $this->createRange(...($dataProvider->query));
        $dateFilter = new DateFilterDecorator($this->newBaseQuery(),
                                              '',
                                              $query);
        $result = $searchMethod->invokeArgs($dateFilter, [$dataProvider->dates]);
        $this->assertEquals($result, $dataProvider->expected);
    }

    /**
     * @dataProvider jsonQueryProvider
     */
    public function testQuerySearch(stdClass $dataProvider)
    {      
        $baseQuery = $this->newBaseQuery();
        $query = $this->createRange(...($dataProvider->query));
        $dateFilter = new DateFilterDecorator($baseQuery,
                                              $dataProvider->queryKey,
                                              $query);
        $dateFilter->setInitData($dataProvider->data);
        $result = $dateFilter->search();
        $this->assertEquals($result, $dataProvider->expected);
    }
}
