<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/5
 * Time: 16:39
 */

namespace App\Models;


use think\Model;

class GoodsAttribute extends Model
{
    public function getGoodsAttribute($goodsId)
    {
        //return $this->hasOne('attribute','id','id')->field('name,value')->select();
        return $this
            ->alias('a')
            ->field('b.name,a.value')
            ->join('__ATTRIBUTE__ b','a.attribute_id=b.id')
            ->order('a.id asc')
            ->where('a.goods_id = '.$goodsId)
            ->select()->toArray();
    }

}