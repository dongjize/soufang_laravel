<?php

namespace App\Http\Controllers\Api;

use App\City;
use App\District;
use App\RentHouse;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class RentHouseController extends BaseController
{
    private $districtId, $roomCount, $lowPrice, $highPrice;

    public function index($cityCode)
    {
        $city = City::where('city_code', $cityCode)->first();
        $estates = $city->estates;
        $houses = array();
        foreach ($estates as $estate) {
            array_push($houses, $estate->houses);
        }

        $rentHouses = array();

        if (count($houses) > 0) {
            foreach ($houses as $houseSubArr) {
                foreach ($houseSubArr as $house) {
                    array_push($rentHouses, $house->rentHouse);
                }
            }
        }
        if (count($rentHouses) > 0) {
            return Response::json([
                'result_code' => $this->resultCode,
                'message' => $this->message,
                'data' => $rentHouses
            ]);
        } else {
            return '';
        }
    }

    /**
     * 租房详情页
     * @param $cityCode
     * @param $id
     * @return string
     */
    public function show($cityCode, $id)
    {
        $rentHouse = RentHouse::find($id);
        $house = $rentHouse->house;
        $data = array([
            'title' => $rentHouse['title'],
            'rent_price' => $rentHouse['rent_price'],
            'area' => $house['area'],
            'floor' => $rentHouse['floor'],
            'condition' => $house->houseType(),
            'photos' => $house['photos']
        ]);
        if ($data) {
            return Response::json([
                'result_code' => $this->resultCode,
                'message' => $this->message,
                'data' => $data
            ]);
        } else {
            return '';
        }
    }

    /**
     * 根据条件筛选租房列表
     * @param $cityCode
     * @param $params
     * @return mixed
     */
    public function filter($cityCode, $params)
    {
        $city = City::where('city_code', $cityCode)->first();

        //处理传进来的参数
        $arr = explode('__', $params);
        foreach ($arr as $param) {
            $this->mapping($param);
        }

        //如果限制区县, 查找该区estates, 否则查找该市estates
        if ($this->districtId != null) {
            $district = District::find($this->districtId);
            $estates = $district->estates;
        } else {
            $estates = $city->estates;
        }

        $estateIds = array();
        foreach ($estates as $estate) {
            array_push($estateIds, $estate['id']);
        }
        $query = DB::table('rent_houses')
            ->leftJoin('houses', 'rent_houses.house_id', '=', 'houses.id')
            ->whereIn('estate_id', $estateIds);
        if ($this->roomCount != null) {
            $query->where('room_count', $this->roomCount);
        } elseif ($this->lowPrice != null && $this->highPrice) {
            $query->whereBetween('rent_price', [$this->lowPrice, $this->highPrice]);
        }

        $rentHouses = $query->get();

//        $data = array();
//        foreach($rentHouses as $rentHouse) {
//            array_push($data, array(
//                'title' => $rentHouse['title'],
//                'rent_price' => $rentHouse['rent_price'],
//                'condition' => $rentHouse['room_count'],
//                'photos' => $rentHouse['photos'],
//                'address' => $rentHouse->house->estate['address']
//            ));
//        }

        return Response::json([
            'result_code' => $this->resultCode,
            'message' => $this->message,
            'data' => $rentHouses
        ]);
    }


    /**
     * 获取筛选列表
     * @param $cityCode
     * @return mixed
     */
    public function getFilterVariables($cityCode)
    {
        $city = City::where('city_code', $cityCode)->first();
        $districts = District::where('city_id', $city['id'])->get();
        $rentPrices = array('rent_prices' => ['<1000', '1000-1999', '2000-2999', '3000-3999', '4000-5999', '6000-9999', '>=10000']);
        $conditions = array('conditions' => ['一居', '二居', '三居']);
        $rentTypes = array('rent_types' => ['整租', '合租']);

        $variables = array();
        array_push($variables, $districts);
        array_push($variables, $rentPrices);
        array_push($variables, $conditions);
        array_push($variables, $rentTypes);

        return Response::json([
            'result_code' => $this->resultCode,
            'message' => $this->message,
            'data' => $variables
        ]);
    }


    /**
     * 匹配字符串
     * @param $param
     */
    private function mapping($param)
    {
        switch (substr($param, 0, 1)) {
            case 'a': //room_count
                $this->roomCount = substr($param, 1, strlen($param));
                break;
            case 'b': //rent_price
                $priceRange = explode('c', substr($param, 1, strlen($param)));
                $this->lowPrice = $priceRange[0];
                $this->highPrice = $priceRange[1];
                break;
            default:
                $this->districtId = $param;
                break;
        }
    }


}
