<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use App\HotelUtils;
use Illuminate\Http\Request;
use Response;
use Validator;

class HotelController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Hotel Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'nullable|max:25',
            'destination' => 'nullable|max:25',
            'price' => 'nullable|priceRange',
            'date' => 'nullable|dateRange',
            'sort-by' => 'nullable|in:name,price',
            'sorting' => 'nullable|in:ascending,descending',
        ]);
    }

    public function response($data, $status){
        return Response::json($data, $status);
    }

    public function show(Request $request)
    {
        $validator = $this->validator($request->all());
        if($validator->fails())
        {
            return $this->response(["error"=>"invalid_format"], 400);
        }
        $results = HotelUtils::getSearchResults($request->all());
        if($results === false)
        {
            return $this->response(["error"=>"invalid_params"], 400);
        }
        return $this->response($results, 200);
    }
}
