<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Expr\AssignOp\Mod;

/**
 * title 卖房标题
 * sale_price 二手房总价
 * floor 楼层
 * decoration 装修状况
 *
 * Class OldHouse 二手房
 * @package App
 */
class OldHouse extends Model
{
    protected $hidden = ['created_at', 'updated_at'];

    public function house()
    {
        return $this->belongsTo('App\House', 'house_id', 'id');
    }

    public function estate()
    {
        $house = $this->house;
        return $house->belongsTo('App\Estate', 'estate_id', 'id');
    }

    public function district()
    {
        return $this->estate()->district;
    }

    public function city()
    {
        return $this->district()->city;
    }
}
