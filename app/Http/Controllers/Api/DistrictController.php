<?php

namespace App\Http\Controllers\Api;

use App\City;
use App\District;
use App\Http\Requests;
use Illuminate\Support\Facades\Response;
use Mockery\Exception;

class DistrictController extends BaseController
{
    public function index($cityCode)
    {
        try {
            $city = City::where('city_code', $cityCode)->first();
            $districts = District::where('city_id', $city['id'])->get();
            $list = [];
            foreach ($districts as $district) {
                $list[] = $district;
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

    }

}
