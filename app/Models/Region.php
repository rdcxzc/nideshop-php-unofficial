<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/7
 * Time: 16:02
 */

namespace App\Models;


use think\Model;

class Region extends Model
{


    public function getRegionList($parentId)
    {
        return $this->where(['parent_id' => $parentId])->select();
    }


    public function getRegionInfo($regionId)
    {
        return $this->where(['id' => $regionId])->find();
    }


    public function getRegionName($regionId)
    {
        return $this->where(['id' => $regionId])->limit(1)->column('name');
    }

}