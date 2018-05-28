<?php

use App\Types\PriceRange;
use App\Search\BaseQuery;
use App\Search\Filters\PriceFilterDecorator;


class PriceFilterDecoratorTest extends TestCase
{

    public function jsonQueryProvider()
    {
        return new JsonFileDataProviderIterator(__FILE__, 'QuerySearch');
    }

    public function newBaseQuery(): BaseQuery{
        return new BaseQuery();
    }

    public function createRange($a, $b): PriceRange{
        return new PriceRange((float) $a,(float) $b);
    }

    /**
     * @dataProvider jsonQueryProvider
     */
    public function testQuerySearch(stdClass $dataProvider)
    {   
        $baseQuery = $this->newBaseQuery();
        $query = $this->createRange(...($dataProvider->query));
        $priceFilter = new PriceFilterDecorator($baseQuery,
                                                $dataProvider->queryKey,
                                                $query);
        $priceFilter->setInitData($dataProvider->data);
        $result = $priceFilter->search();
        $this->assertEquals($result, $dataProvider->expected);
    }
}

