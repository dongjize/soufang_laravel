<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * title 租房标题
 * rent_price 租价
 * floor 楼层
 * rent_type 整租/合租
 *
 * Class RentHouse 租房
 * @package App
 */
class RentHouse extends Model
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
