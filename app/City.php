<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * name
 * city_code
 * province
 *
 * Class City 城市
 * @package App
 */
class City extends Model
{
    protected $hidden = ['created_at', 'updated_at'];

    public function districts()
    {
        return $this->hasMany('App\District');
    }

    public function estates()
    {
        return $this->hasManyThrough('App\Estate', 'App\District', 'city_id', 'district_id');
    }

    public function houses()
    {
        $estates = $this->estates;
        return $this->estates->hasManyThrough('App\House', 'App\Estate', 'district_id', 'estate_id');
    }

//    public function houses()
//    {
//
//        return $this->hasManyThrough('App\House', 'App\District', 'App\Estate', 'city_id', 'district_id', 'estate_id');
//    }

//    public function rentHouses()
//    {
//        return $this->hasManyThrough()
//    }

//    public function oldHouses()
//    {
//        return $this->houses()->oldHouses();
//    }
//
//    public function newHouses()
//    {
//        return $this->houses()->newHouses();
//    }

}
