<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix'=> 'admin', 'namespace'=>'Admin'], function() {
    Route::resource('articles', 'ArticleController');
});

$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function ($api) {
    $api->group(['namespace' => 'App\Http\Controllers\Api'], function ($api) {

        $api->group(['prefix' => '{city_code}'], function ($api) {
            /**
             * ==== 区县 ====
             */
            $api->resource('districts', 'DistrictController');
            /**
             * ==== 房产 ====
             */
            $api->resource('estates', 'EstateController');

            /**
             * ==== 租房 ====
             */
            $api->get('rent_houses', 'RentHouseController@index');
            $api->get('rent_houses/rent/{id}', 'RentHouseController@show');
            $api->get('rent_houses/{params}', 'RentHouseController@filter');
            $api->get('rent_houses/params/list', 'RentHouseController@getFilterVariables');
            /**
             * ==== 二手房 ====
             */
            $api->get('old_houses', 'OldHouseController@index');
            $api->get('old_houses/sale/{id}', 'OldHouseController@show');
            $api->get('old_houses/{params}', 'OldHouseController@filter');

            /**
             * ==== 新房 ====
             */
            //新房列表
            $api->get('new_estates', 'EstateController@newEstates');
            //新房详情
            $api->get('new_estates/{id}', 'EstateController@newEstate');

            $api->get('new_estates/{id}/houses', 'EstateController@getHouses');
            //筛选查询
            $api->get('new_estates/sale/{params}', 'EstateController@filter');
            //
            $api->group(['prefix' => 'filter'], function ($api) {
                $api->get('new_estates', 'EstateController@getFilter');
                $api->get('old_houses', 'OldHouseController@getFilter');
            });

            $api->get('introduce', 'EstateController@introduce');


        });

        /**
         * ==== 城市 ====
         */
        $api->resource('cities', 'CityController');

        /**
         * ==== 文章 ====
         */
        $api->resource('articles', 'ArticleController');
        $api->group(['prefix' => 'articles'], function ($api) {
            $api->get('collections', 'ArticleController@getCollections');
            $api->get('collect', 'ArticleController@addCollection');
        });

        /**
         * ==== 收藏房产 ====
         */
        $api->group(['prefix' => 'estates'], function ($api) {
            $api->get('collections', 'EstateController@getCollections');
            $api->get('collect/{id}', 'EstateController@addCollection');
        });

//        $api->resource('user', 'UserController');
//        $api->group(['prefix' => 'user'], function ($api) {
//            $api->post('login', 'AuthController');
//
//        });
    });
});


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //
});

Route::group(['middleware' => 'web'], function () {
    Route::auth();

    Route::get('/home', 'HomeController@index');
});
