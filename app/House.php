<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * name 户型名
 * estate_id 所属楼盘/小区
 * area 面积
 * room_count x室
 * parlour_count x厅
 * toilet_count x卫
 * kitchen_count x厨
 * photos 图片列表
 *
 * Class House 户型
 * @package App
 */
class House extends Model
{

    protected $hidden = ['created_at', 'updated_at'];

    public function estate()
    {
        return $this->belongsTo('App\Estate', 'estate_id', 'id');
    }

    public function rentHouse()
    {
        return $this->hasOne('App\RentHouse', 'house_id');
    }

    public function oldHouse()
    {
        return $this->hasOne('App\OldHouse', 'house_id');
    }

    public function newHouse()
    {
        return $this->hasOne('App\NewHouse', 'house_id');
    }

    public function getPhotosAttribute($str)
    {
        return explode(',', $str);
    }

    public function houseType()
    {
        $room = $this->room_count;
        $parlour = $this->parlour_count;
        $toilet = $this->toilet_count;
        $kitchen = $this->kitchen_count;
        $houseType = ($room ? $room : 0) . '室' . ($parlour ? $parlour : 0) . '厅' .
            ($toilet ? $toilet . '卫' : null) . ($kitchen ? $kitchen . '厨' : null);
        return $houseType;
    }

}
