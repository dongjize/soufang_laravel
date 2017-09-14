<?php

namespace App\Http\Controllers\Api;

use App\City;
use App\Http\Requests;
use Illuminate\Support\Facades\Response;
use Mockery\Exception;

class CityController extends BaseController
{
    public function index()
    {
        try {
            $cities = City::all();
            $list = [];
            foreach ($cities as $city) {
                $list[] = [
                    'id' => $city['id'],
                    'name' => $city['name'],
                    'city_code' => $city['city_code'],
                    'province' => $city['province'],
                    'is_open' => (boolean)$city['is_open']
                ];
            }
            $data = ['list' => $list];
        } catch (Exception $e) {
            $this->resultCode = 400;
            $this->message = 'error';
        } finally {
            return Response::json([
                'result_code' => $this->resultCode,
                'message' => $this->message,
                'data' => $data
            ]);
        }


//        return $this->collection($cities, new CityTransformer());
    }

    public function show($id)
    {
        $city = City::find($id);
        if ($city) {
            return Response::json([
                'result_code' => $this->resultCode,
                'message' => $this->message,
                'data' => $city
            ]);
        } else {
            return '';
        }
    }

}
