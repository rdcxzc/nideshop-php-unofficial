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

    /**
     * 获取下级的地区列表
     * @param $parentId
     * @returns array
     */
    public function getRegionList($parentId)
    {
        return $this->where(['parent_id' => $parentId])->select();
    }

    /**
     * 获取区域的信息
     * @param $regionId
     * @returns {Promise.<*>}
     */
    public function getRegionInfo($regionId)
    {
        return $this->where(['id' => $regionId])->find();
    }

    /**
     * 获取区域的名称
     * @param regionId
     * @returns {Promise.<*>}
     */
    public function getRegionName($regionId)
    {
        return $this->where(['id' => $regionId])->limit(1)->column('name');
    }

}