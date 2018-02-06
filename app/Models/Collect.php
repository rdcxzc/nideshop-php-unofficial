<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/6
 * Time: 10:09
 */

namespace App\Models;


use think\Model;

class Collect extends Model
{
    /**
     * 判断用户是否收藏过该对象
     * @param $userId
     * @param $typeId
     * @param $valueId
     * @return mixed
     */
    public function isUserHasCollect($userId, $typeId, $valueId)
    {
        $hasCollect = $this->where(['type_id' => $typeId, 'value_id' => $valueId, 'user_id' => $userId])->limit(1)->count('id');
        return $hasCollect;
    }

}