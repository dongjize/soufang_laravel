<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * title 标题
 * sub_title 副标题
 * author 作者
 * content 正文
 * pictures 图片
 *
 * Class Article 资讯
 * @package App
 */
class Article extends Model
{
//    public function author()
//    {
//        return $this->belongsTo('App\User', 'author', 'id');
//    }

    /**
     * 用","分割图片字符串
     * @return array
     */
//    public function getPicturesAttribute()
//    {
//        if ($this->pictures != null) {
//            return explode(',', $this->pictures);
//        } else {
//            return '';
//        }
//    }

}
