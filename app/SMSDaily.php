<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SMSDaily extends Model
{
    protected $fillable = array('mobile', 'content', 'success');
    protected $table = 'sms_daily';
}
