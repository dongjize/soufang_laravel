<?php

namespace App\Http\Controllers\Api;

use App\City;
use App\District;
use App\House;
use App\OldHouse;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Mockery\Exception;

class OldHouseController extends BaseController
{
    private $districtId, $roomCount, $lowPrice, $highPrice, $smallArea, $bigArea;

    /**
     * 二手房列表
     * @param $cityCode
     * @return string
     */
    public function index($cityCode)
    {
        try {
            $city = City::where('city_code', $cityCode)->first();
            $estates = $city->estates->where('is_new', '0');
            $list = [];
            foreach ($estates as $estate) {
                $oldHouses = $estate->oldHouses;
                foreach ($oldHouses as $oldHouse) {
                    $house = $oldHouse->house;
                    $list[] = [
                        'id' => $oldHouse['oldhouse_id'],
                        'title' => $oldHouse['title'],
                        'area' => $house['area'],
                        'house_type' => $house->houseType(),
                        'sale_price' => $oldHouse['sale_price'],
                        'estate_name' => $estate['name'],
                        'avatar' => $house['photos'][0]
                    ];
                }
            }
            $data = ['list' => $list];
        } catch (Exception $e) {
            $this->resultCode = 404;
            $this->message = 'error';
        } finally {
            return Response::json([
                'result_code' => $this->resultCode,
                'message' => $this->message,
                'data' => $data
            ]);
        }
    }


    /**
     * 二手房详情页
     * @param $cityCode
     * @param $oldhouse_id
     * @return string
     */
    public function show($cityCode, $oldhouse_id)
    {
        try {
            $oldHouse = OldHouse::where('oldhouse_id', $oldhouse_id)->first();
            $house = $oldHouse->house;
            $estate = $house->estate;
            $data = array(
                'id' => $oldHouse['oldhouse_id'],
                'title' => $oldHouse['title'],
                'longitude' => $estate['longitude'],
                'latitude' => $estate['latitude'],
                'address' => $estate['address'],
                'area' => $house['area'],
                'sale_price' => $oldHouse['sale_price'],
                'house_type' => $house->houseType(),
                'floor' => $oldHouse['floor'],
                'decoration' => $oldHouse['decoration'],
                'year' => substr($estate['open_date'], 0, 4),
                'estate_name' => $estate['name'],
                'photos' => $house['photos']
            );
        } catch (Exception $e) {
            $this->resultCode = 404;
            $this->message = 'error';
        } finally {
            return Response::json([
                'result_code' => $this->resultCode,
                'message' => $this->message,
                'data' => $data
            ]);
        }

    }


    public function getFilter($cityCode)
    {
        $city = City::where('city_code', $cityCode)->first();
        $districts = $city->districts;
        $data = [
            'districts' => $districts,
            'areas' => [
                '0-29', '30-49', '50-69', '70-99', '100-129', '130-149', '150-199', '200-1000'
            ],
            'house_types' => [
                '一居', '二居', '三居', '四居', '五居以上'
            ],
            'sale_prices' => [
                '0-49万', '50-99万', '100-199万', '200-299万', '300-399万', '400-599万',
                '600-999万', '1000-1999万', '2000-2999万', '3000-99999万'
            ]
        ];
        return Response::json([
            'result_code' => $this->resultCode,
            'message' => $this->message,
            'data' => $data
        ]);
    }

    /**
     * 根据条件筛选二手房列表
     * @param $cityCode
     * @param $params 区县 面积 户型 价格
     * @return mixed
     */
    public function filter($cityCode, $params)
    {
        $city = City::where('city_code', $cityCode);

        //处理传进来的参数
        $paramsArr = explode('__', $params);
        foreach ($paramsArr as $param) {
            $this->mapping($param);
        }

        //如果限制区县, 查找该区estates, 否则查找该市estates

        if ($this->districtId != null) {
            $district = District::find($this->districtId);
            $estates = $district->estates->where('is_new', '0');
        } else {
            $estates = $city->estates->where('is_new', '0');
        }

        $oldHouses = [];
        for($i = 0; $i < count($estates); $i++) {
            $aaaa = $estates[$i]->oldHouses;
            for($j = 0; $j < count($aaaa); $j++) {
                array_push($oldHouses, $aaaa[$j]);
            }
        }

        $houseIds = [];
        foreach ($oldHouses as $oldHouse) {
            $houseIds[] = $oldHouse['house_id'];
        }

        $query = DB::table('houses')->leftJoin('old_houses', 'houses.id','=','old_houses.house_id');

        $query = DB::table('old_houses')
            ->leftJoin('houses', 'old_houses.house_id', '=', 'houses.id')
            ->whereIn('house_id', $houseIds);

        try {
            if ($this->lowPrice && $this->highPrice) {
                $query->whereIn('sale_price', [$this->lowPrice, $this->highPrice]);
            } elseif ($this->smallArea && $this->bigArea) {
                $query->whereIn('area', [$this->smallArea, $this->bigArea]);
            } elseif ($this->roomCount) {
                $query->where('room_count', $this->roomCount);
            }
            $oldHouses = $query->get();

            foreach ($oldHouses as $oldHouse) {
                $list[] = [
                    'id' => $oldHouse->oldhouse_id,
                    'title' => $oldHouse->title,
                    'sale_price' => $oldHouse->sale_price,
                    'area' => $oldHouse->area,
                    'room_count' => $oldHouse->room_count,
                    'parlour_count' => $oldHouse->parlour_count,
                    'avatar' => explode(',', $oldHouse->photos)[0]
                ];
            }
            $data = ['list' => $list];
        } catch (Exception $e) {
            $this->resultCode = 404;
            $this->message = 'error';
        } finally {
            return Response::json([
                'result_code' => $this->resultCode,
                'message' => $this->message,
                'data' => $data
            ]);
        }


    }


    /**
     * 对传入参数进行字符串操作,映射为数组
     * @param $param
     */
    private function mapping($param)
    {
        switch (substr($param, 0, 1)) {
            case 'a': //districtId
                $this->districtId = substr($param, 1, strlen($param));
                break;
            case 'b': //sale_price
                $priceRange = explode('c', substr($param, 1, strlen($param)));
                $this->lowPrice = $priceRange[0];
                $this->highPrice = $priceRange[1];
                break;
            case 'd': //area
                $areaRange = explode('e', substr($param, 1, strlen($param)));
                $this->smallArea = $areaRange[0];
                $this->bigArea = $areaRange[1];
                break;
            case 'f': //room_count
                $this->roomCount = substr($param, 1, strlen($param));
                break;
        }
    }

}
