<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\User;
use App\Services\SMS;
use Dingo\Api\Http\Middleware\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class UserController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function update()
    {
        $user = Auth::user();
    }

    public function getProfile()
    {
        $user = Auth::user();
        return Response::json([
            'result_code' => $this->resultCode,
            'message' => $this->message,
            'data' => $user
        ]);
    }


    public function postSend()
    {
        $input = Input::all();
        $mobile = $input['mobile'];
        $event = $input['event'];
        $result = SMS::sendLogin($mobile, $event);
        return Response::json([
            'result_code' => $this->resultCode,
            'message' => $this->message,
            'data' => $result
        ]);
    }


    public function postLogin()
    {
        $input = Input::all();
        $mobile = $input['mobile'];
        $verify = $input['verify_code'];

        $result = SMS::validate($mobile, $verify);

        if ($result['code']) {
            return Response::json([

            ]);
        } else {

        }

    }

    public function postRegister()
    {
        $input = Input::all();
        $mobile = $input['mobile'];

    }

    public function checkUser($mobile)
    {
        $user = User::where('mobile', $mobile)->first();
        if (!$user) {

        } else {

        }
        Auth::login($user, true);
        return $user;

    }

}
