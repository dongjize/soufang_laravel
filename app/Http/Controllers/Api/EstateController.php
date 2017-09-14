<?php

namespace App\Http\Controllers\Api;

use App\Article;
use App\City;
use App\District;
use App\Estate;
use App\Http\Requests;
use App\OldHouse;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Mockery\Exception;


class EstateController extends BaseController
{
    private $districtId, $type, $lowPrice, $highPrice;

    /**
     * EstateController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth', ['only' => 'addCollection']);
    }


    /**
     * 获取某个城市的楼盘列表
     * @param $cityCode
     * @return mixed
     */
    public function index($cityCode)
    {
        try {
            $list = [];
            $city = City::where('city_code', $cityCode)->first();
            $estates = $city->estates;
            foreach ($estates as $estate) {
                $district = District::where('id', $estate['district_id'])->first();

                $list[] = [
                    'id' => $estate['id'],
                    'name' => $estate['name'],
                    'type' => $estate['type'],
                    'district' => $district['name'],
                    'longitude' => $estate['longitude'],
                    'latitude' => $estate['latitude'],
                    'address' => $estate['address'],
                    'avg_price' => $estate['avg_price'],
                    'is_new' => $estate['is_new'],
                    'avatar' => $estate['pictures'][0],
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
     * 单个楼盘信息
     * @param $cityCode
     * @param $id
     * @return mixed
     */
    public function show($cityCode, $id)
    {
        try {
            $estate = Estate::find($id);
            $district = District::where('id', $estate['district_id'])->first();
            $houses = $estate->houses;
            $houseList = [];
            foreach ($houses as $house) {
                $newHouse = $house->newHouse;
                $houseList[] = [
                    'id' => $house['id'],
                    'name' => $house['name'],
                    'area' => $house['area'],
                    'house_type' => $house->houseType(),
                    'photos' => $house['photos'],
                    'price' => $newHouse['price'],
                    'storage' => $newHouse['storage']
                ];
            }
            $data = array(
                'id' => $estate['id'],
                'name' => $estate['name'],
                'type' => $estate['type'],
                'district' => $district['name'],
                'longitude' => $estate['longitude'],
                'latitude' => $estate['latitude'],
                'address' => $estate['address'],
                'avg_price' => $estate['avg_price'],
                'is_new' => (boolean)$estate['is_new'],
                'pictures' => $estate['pictures'],
                'developer' => $estate['developer'],
                'open_date' => $estate['open_date'],
                'houses' => $houseList
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


    /**
     * 新开楼盘列表
     * @param $cityCode 城市编码
     * @return mixed
     */
    public function newEstates($cityCode)
    {
        try {
            $city = City::where('city_code', $cityCode)->first();
            $districts = $city->districts;

            $districtIds = [];

            $list = [];
            foreach ($districts as $district) {
                $districtIds[] = $district['id'];
            }

            $newEstates = Estate::where('is_new', "1")->whereIn('district_id', $districtIds)->get();

            foreach ($newEstates as $newEstate) {
                $district = District::where('id', $newEstate['district_id'])->first();
                $list[] = [
                    'id' => $newEstate['id'],
                    'name' => $newEstate['name'],
                    'type' => $newEstate['type'],
                    'district' => $district['name'],
                    'longitude' => $newEstate['longitude'],
                    'latitude' => $newEstate['latitude'],
                    'address' => $newEstate['address'],
                    'avg_price' => $newEstate['avg_price'],
                    'avatar' => $newEstate['pictures'][0]
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


    public function newEstate($cityCode, $id)
    {
        try {
            $estate = Estate::find($id);
            $district = District::where('id', $estate['district_id'])->first();
            $houses = $estate->houses;
            $houseList = [];
            foreach ($houses as $house) {
                $newHouse = $house->newHouse;
                $houseList[] = [
                    'id' => $house['id'],
                    'name' => $house['name'],
                    'area' => $house['area'],
                    'house_type' => $house->houseType(),
                    'photos' => $house['photos'],
                    'price' => $newHouse['price'],
                    'storage' => $newHouse['storage']
                ];
            }
            $data = array(
                'id' => $estate['id'],
                'name' => $estate['name'],
                'type' => $estate['type'],
                'district' => $district['name'],
                'longitude' => $estate['longitude'],
                'latitude' => $estate['latitude'],
                'address' => $estate['address'],
                'avg_price' => $estate['avg_price'],
                'is_new' => (boolean)$estate['is_new'],
                'pictures' => $estate['pictures'],
                'developer' => $estate['developer'],
                'open_date' => $estate['open_date'],
                'houses' => $houseList
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

    /**
     * 房产的户型列表
     * @param $cityCode
     * @param $id
     * @return mixed
     */
    public function getHouses($cityCode, $id)
    {
        try {
            $estate = Estate::find($id);
            $newHouses = $estate->newHouses;
            $list = [];
            foreach ($newHouses as $newHouse) {
                $house = $newHouse->house;
                $list[] = [
                    'id' => $newHouse['id'],
                    'name' => $house['name'],
                    'area' => $house['area'],
                    'house_type' => $house->houseType(),
                    'avatar' => $house['photos'][0],
                    'price' => $newHouse['price'],
                    'storage' => $newHouse['storage']
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
     * 获取筛选字段列表
     * @param $cityCode
     * @return mixed
     */
    public function getFilter($cityCode)
    {
        $city = City::where('city_code', $cityCode)->first();
        $districts = $city->districts;
        $data = [
            'districts' => $districts,
            'types' => [
                '不限', '住宅', '别墅', '商铺'
            ],
            'prices' => [
                '不限', '0-4999元', '5000-9999元', '10000-19999元', '20000-29999元',
                '30000-39999元', '40000-59999元', '60000-99999元', '100000-999999元'
            ]
        ];
        return Response::json([
            'result_code' => $this->resultCode,
            'message' => $this->message,
            'data' => $data
        ]);
    }


    /**
     * 字段过滤查询
     * @param $cityCode 城市码
     * @param $params   url参数
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
        $query = DB::table('estates');

        //如果限制区县, 查找该区estates, 否则查找该市estates
        if ($this->districtId) {
            $query = $query->where('district_id', $this->districtId)
                ->where('is_new', '1');
        } else {
            $estates = $city->estates->where('is_new', '1');
            $estateIds = [];
            foreach ($estates as $estate) {
                $estateIds[] = $estate['id'];
            }
            $query = $query->whereIn('district_id', $estateIds)
                ->where('is_new', '1');
        }

        $list = [];
        try {
            if ($this->type) {
                $query = $query->where('type', $this->type);
            }
            if ($this->lowPrice && $this->highPrice) {
                $query = $query->whereBetween('avg_price', [$this->lowPrice, $this->highPrice]);
            }
            $newEstates = $query->get();

            foreach ($newEstates as $newEstate) {
                $district = District::where('id', $newEstate->district_id)->first();
                $list[] = [
                    'id' => $newEstate->id,
                    'name' => $newEstate->name,
                    'type' => $newEstate->type,
                    'district' => $district->name,
                    'longitude' => $newEstate->longitude,
                    'latitude' => $newEstate->latitude,
                    'address' => $newEstate->address,
                    'avg_price' => $newEstate->avg_price,
                    'avatar' => explode(',', $newEstate->pictures)[0]
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
     * 首页推荐列表
     * @param $cityCode
     * @return mixed
     */
    public function introduce($cityCode)
    {
        $city = City::where('city_code', $cityCode);
        try {
            $list = [];

            $articles = Article::paginate(5);
            if ($articles) {
                foreach ($articles as $article) {
                    $author = User::find($article['author'])->first();
                    $author = array(
                        'id' => $author['id'],
                        'name' => $author['name']
                    );
                    $list[] = [
                        'data_type' => 'article',
                        'id' => $article['id'],
                        'title' => $article['title'],
                        'sub_title' => $article['sub_title'],
                        'author' => $author,
                        'is_collected' => false
                    ];
                }
            }

            $newEstates = Estate::where('is_new', '1')->get();
            if ($newEstates) {
                foreach ($newEstates as $newEstate) {
                    $district = District::where('id', $newEstate['district_id'])->first();
                    $list[] = [
                        'data_type' => 'new_estate',
                        'id' => $newEstate['id'],
                        'name' => $newEstate['name'],
                        'type' => $newEstate['type'],
                        'district' => $district['name'],
                        'longitude' => $newEstate['longitude'],
                        'latitude' => $newEstate['latitude'],
                        'address' => $newEstate['address'],
                        'avg_price' => $newEstate['avg_price'],
                        'avatar' => $newEstate['pictures'][0]
                    ];
                }
            }

            $oldHouses = OldHouse::paginate(3);
            if ($oldHouses) {
                foreach ($oldHouses as $oldHouse) {
                    $house = $oldHouse->house;
                    $estate = $house->estate;
                    $list[] = [
                        'data_type' => 'old_house',
                        'id' => $oldHouse['oldhouse_id'],
                        'title' => $oldHouse['title'],
                        'sale_price' => $oldHouse['sale_price'],
                        'floor' => $oldHouse['floor'],
                        'decoration' => $oldHouse['decoration'],
                        'area' => $house['area'],
                        'house_type' => $house->houseType(),
                        'avatar' => $house['photos'][0],
                        'estate_name' => $estate['name']
                    ];
                }
            }
            shuffle($list);
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
     * 添加用户收藏
     * @param $id
     */
    public function addCollection($id)
    {
        $user = Auth::user();
        DB::table('user_collection_estate')->insert([
            'user_id' => $user['id'],
            'estate_id' => $id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        return $user;
    }


    /**
     * 获取用户收藏列表
     * @return mixed
     */
    public function getCollections()
    {
        $user = Auth::user();
        $data = DB::table('user_collection_estate')->select('user_id', $user['id'])->get();
        return Response::json([
            'result_code' => $this->resultCode,
            'message' => $this->message,
            'data' => $data
        ]);
    }


    /**
     * 对传入参数进行字符串操作,映射为数组
     * @param $param
     */
    private function mapping($param)
    {
        switch (substr($param, 0, 1)) {
            case 'a': //区域
                $this->districtId = substr($param, 1, strlen($param));
                break;
            case 'b': //类型
                $map = ['1' => '住宅', '2' => '别墅', '3' => '商铺'];
                $typeNumber = substr($param, 1, strlen($param));
                $this->type = $map[$typeNumber];
                break;
            case 'c': //价格
                $priceRange = explode('d', substr($param, 1, strlen($param)));
                $this->lowPrice = $priceRange[0];
                $this->highPrice = $priceRange[1];
                break;

        }
    }

}
