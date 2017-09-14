<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * house_id
 * name
 * price
 * decoration
 * storage 剩余房数
 *
 * Class NewHouse 新房
 * @package App
 */
class NewHouse extends Model
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
