<?php
/**
 * Created by PhpStorm.
 * User: dong
 * Date: 16/3/28
 * Time: 22:23
 */

namespace App\Facades;


use Illuminate\Support\Facades\Facade;

class SMSFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return new \App\Services\SMS;
    }
}