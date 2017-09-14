<?php

namespace App\Http\Controllers\Api;

use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    use Helpers;

    protected $resultCode = 200;
    protected $message = '';

    public function returnList()
    {

    }

    /**
     * @return int
     */
    public function getResultCode()
    {
        return $this->resultCode;
    }

    /**
     * @param int $resultCode
     */
    public function setResultCode($resultCode)
    {
        $this->resultCode = $resultCode;
    }


    /**
     * 获取目标和用户的距离
     * @param $longitude
     * @param $latitude
     * @param $myLongitude
     * @param $myLatitude
     */
    public function getDistance($longitude, $latitude, $myLongitude, $myLatitude)
    {

    }



//    private function responseError($message) {
//        return $this->response([
//            ''
//        ]);
//    }

//    public function response($data) {
//        return Response::json($data);
//    }

}
