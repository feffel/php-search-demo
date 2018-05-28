<?php

use App\Search\BaseQuery;
use App\Search\Filters\{StringFilterDecorator, DateFilterDecorator, PriceFilterDecorator};
use App\Search\Hotel\{QueryParser, QueryBuilder};


class QueryBuilderTest extends TestCase
{ 
    public function validQueryProvider()
    {
        return [
            'no_search'         => ['params'=>  [],
                                    'expect'=>  BaseQuery::class
                                   ],
            'name_search'       => [
                                    'params'=> ['name'=>'media .!'],
                                    'expect'=> StringFilterDecorator::class
                                   ],
            'destination_search'=> [
                                    'params'=>['destination'=>'lol !.media'],
                                    'expect'=> StringFilterDecorator::class
                                   ],
            'date_search'       => [
                                    'params'=>['date'=>'1-1-2017:5-1-2017'],
                                    'expect'=> DateFilterDecorator::class
                                   ],
            'price_search'      => [
                                    'params'=>['price'=>'15.7:20'],
                                    'expect'=> PriceFilterDecorator::class
                                   ]
        ];
    }
    public function testNewQueryBuilder()
    {   
        $queryBuilder = new QueryBuilder(new QueryParser([]));
        $this->assertNotNull($queryBuilder);
    }

    /**
     * @dataProvider validQueryProvider
     */
    public function testBuildValidQueries(array $params, string $expect)
    {
        $queryParser = new QueryParser($params);
        $queryBuilder = new QueryBuilder($queryParser);
        $this->assertNotNull($queryBuilder);
        $query = $queryBuilder->build();
        $this->assertNotNull($query);
        $this->assertInstanceOf($expect, $query);

    }

    public function testBuildInvalidQueries()
    {
        $queryParser = new QueryParser(['name'=>new stdClass()]);
        $queryBuilder = new QueryBuilder($queryParser);
        $query = $queryBuilder->build();
        $this->assertFalse($query);
    }
}
