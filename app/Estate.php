<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * name
 * type 住宅/别墅/商铺
 * latitude
 * longitude
 * district 区县
 * address 地址
 * avg_price 均价
 * is_new 是否新楼盘? 1/0
 * pictures 楼盘/小区相册
 * developer 开发商
 * open_date 建筑年代/交房日期
 *
 * Class Estate
 * @package App
 */
class Estate extends Model
{
    protected $hidden = ['created_at', 'updated_at'];

    public function district()
    {
        return $this->belongsTo('App\District', 'district_id', 'id');
    }

    public function houses()
    {
        return $this->hasMany('App\House', 'estate_id', 'id');
    }

    public function newHouses() {
        if($this->is_new == '1') {
            return $this->hasManyThrough('App\NewHouse', 'App\House', 'estate_id', 'house_id');
        }
    }

    public function oldHouses()
    {
        if($this->is_new == '0') {
            return $this->hasManyThrough('App\OldHouse', 'App\House', 'estate_id', 'house_id');
        }
    }

    public function rentHouses()
    {
        if($this->is_new == '0') {
            return $this->hasManyThrough('App\RentHouse', 'App\House', 'estate_id', 'house_id');
        }
    }

    public function city()
    {
        return $this->district()->city();
    }

    public function getPicturesAttribute($str)
    {
        return explode(',', $str);
    }

    public function getNewEstates()
    {
        $newEstates = Estate::where('is_new', 1)->get();
        return $newEstates;
    }

}
