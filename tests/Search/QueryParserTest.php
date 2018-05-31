<?php


use App\Types\{DateRange, PriceRange};
use App\Search\Hotel\QueryParser;


class QueryParserTest extends TestCase
{

    public function validQueryProvider()
    {
        return [
            'no_search'         => ['params'=>[]],
            'name_search'       => ['params'=>['name'=>'media .!']],
            'destination_search'=> ['params'=>['destination'=>'lol !.media']],
            'date_search'       => ['params'=>['date'=>'1-1-2017:5-1-2017']],
            'price_search'      => ['params'=>['price'=>'15.7:20']]
        ];
    }

    public function invalidQueryProvider()
    {
        return [
          'name_search'         => ['params'=>['name'=>new stdClass()]],
          'destination_search'  => ['params'=>['destination'=>new stdClass()]],
          'date_search_1'       => ['params'=>['date'=>'1-1-2017:15-1-2017:15-1-2017']],
          'date_search_2'       => ['params'=>['date'=>'1-1:15-1-2017']],
          'date_search_3'       => ['params'=>['date'=>'20-1-2017:15-1-2017']],
          'date_search_4'       => ['params'=>['date'=>new stdClass()]],
          'price_search_1'      => ['params'=>['price'=>'15.7:20:30']],
          'price_search_2'      => ['params'=>['price'=>'what:20']],
          'price_search_3'      => ['params'=>['price'=>'15!20']],
          'price_search_4'      => ['params'=>['price'=>new stdClass()]]
        ];
    }

    public function createDateRange($a, $b)

    {
        return new DateRange(new DateTime($a),new DateTime($b));
    }

    public function createPriceRange($a, $b)
    {
        return new PriceRange((float) $a,(float) $b);
    }

    public function testNewQueryParser()
    {   
        $queryParser = new QueryParser([]);
        $this->assertNotNull($queryParser);
    }

    /**
     * @dataProvider validQueryProvider
     */
    public function testParseValidQueries(array $params)
    {
        $queryParser = new QueryParser($params);
        $this->assertNotNull($queryParser);
        $this->assertTrue($queryParser->isValid());
        return $queryParser;
    }

    /**
     * @dataProvider invalidQueryProvider
     */
    public function testParseInvalidQuery(array $params)
    {   
        $queryParser = new QueryParser($params);
        $this->assertFalse($queryParser->isValid());
        $this->assertFalse($queryParser->getParsedQuery());
    }
}
