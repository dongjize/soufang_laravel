<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * code 区域代号
 * name 区县名
 * city_id 所属城市
 *
 * Class District
 * @package App
 */
class District extends Model
{

    protected $hidden = ['created_at', 'updated_at'];

    public function city()
    {
        return $this->belongsTo('App\City', 'city_id', 'id');
    }


    public function estates()
    {
        return $this->hasMany('App\Estate', 'district_id', 'id');
    }

    public function newEstates()
    {
        return $this->estates()->where('is_new', '1');
    }

}
