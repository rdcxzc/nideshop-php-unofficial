<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/6
 * Time: 10:32
 */

namespace App\Models;


use think\Model;

class Footprint extends Model
{
    public function addFootprint($userId, $goodsId)
    {
        // 用户已经登录才可以添加到足迹
        if ($userId > 0 && $goodsId > 0) {
            $this->insert([
                'goods_id' => $goodsId,
                'user_id' => $userId,
                'add_time' => time()
            ]);
        }
    }

}