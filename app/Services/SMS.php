<?php namespace App\Services;

use App\SMSDaily;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

/**
 * Created by PhpStorm.
 * User: dong
 * Date: 16/3/27
 * Time: 23:22
 */
class SMS
{
    protected $smsDailyMax = 100;
    protected $timeout = 5; //minutes, cache expire
    protected $blackList = ['18818275366'];
    protected $debugMode = true;


    /**
     * @param $mobile
     * @return array
     */
    public static function sendLogin($mobile)
    {
        $will = SMS::canSend($mobile);
        if ($will['code'] != 0) {
            return $will;
        }

        //生成verifyCode & content
        $randCode = SMS::randomCode($mobile);
        $content = SMS::loginMessage($randCode);
        Cache::put(SMS::loginCacheKey($mobile), $randCode, Config::get('sms.timeout'));
        $sent = false;
        $result = array();
        // if($cached) {
        $sent = SMS::sendSMS($mobile, $content);
        // }

        $data = ['mobile' => $mobile, 'content' => $content, 'success' => $sent];
        $smsItem = SMSDaily::create($data);

        if ($sent) {
            $result['code'] = 0;
        } else {
            $result['code'] = 2;
            $result['msg'] = "发送失败，请稍后再试。";
        }

        return $result;
    }


    /**
     * 认证验证码
     * @param $mobile
     * @param $verify
     * @return array
     */
    public static function validate($mobile, $verify)
    {
        $cacheKey = SMS::loginCacheKey($mobile);
        $cache = Cache::get($cacheKey);
        $result = array();

        if (strlen($verify) > 0 && $cache == $verify) {
            $result['code'] = 0;
            Cache::forget($cacheKey);
        } else {
            $result['code'] = 1;
            $result['msg'] = "验证码错误";
        }
        return $result;
    }


    /**
     * 根据次数限制,决定是否可以发送验证码
     * @param $mobile
     * @return array
     */
    public function canSend($mobile)
    {
        $day = date('Y-m-d');
        $count = DB::table('sms_daily')
            ->whereRaw("mobile = '" . $mobile . "' and DATE(created_at) = '" . $day . "' and success = true")->count();
        $result = array('code' => 0);
        if ($count >= $this->smsDailyMax) {
            $result['code'] = 1;
            $result['msg'] = "今天发送的短信已超过限制，请明天再试。";
        }
        return $result;
    }


    /**
     * @param $mobile
     * @param $content
     * @return bool
     */
    static function sendSMS($mobile, $content)
    {
        if (!env('SMS_SEND')) {
            return true;
        }
        header('Content-Type: text/html; charset = UTF-8');
        $flag = 0;
        $params = ''; //要post的数据

        $argv = array(
            'userid' => '1111',
            'account' => 'jlcs11',
            'password' => 'jlcs11',
            'mobile' => $mobile,
            'content' => $content,
            'sendTime' => '',
            'action' => 'send',
            'extno' => ''
        );
        foreach ($argv as $key => $value) {
            if ($flag != 0) {
                $params .= "&";
                $flag = 1;
            }
            $params .= $key . "=";
            $params .= urlencode($value);// urlencode($value);
            $flag = 1;
        }
        $url = "http://sh2.ipyy.com/sms.aspx?" . $params; //提交的url地址
        $result = file_get_contents($url);
        // echo($result);

        $xml = simplexml_load_string($result);
        $con = $xml->returnstatus == "Success";
        if ($con) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * 生成随机6位验证码
     * @param null $mobile
     * @return string
     */
    static function randomCode($mobile = null)
    {
        if (!env('SMS_SEND')) {
            return '123456';
        }
        if ($mobile && in_array($mobile, Config::get('sms.fixed_list'))) {
            return '123456';
        }
        $charSet = '0123456789';
        $max = strlen($charSet);
        $str = '';
        for ($i = 0; $i < 6; $i++) {
            $str = $str . $charSet[rand(0, $max - 1)];
        }
        return $str;
    }

    static function loginMessage($code)
    {
        return "【ECNU】您的验证码是" . $code . ",为了您的账号安全,请勿泄露.";
    }

    static function loginCacheKey($mobile)
    {
        return 'sms_login_' . $mobile;
    }


}