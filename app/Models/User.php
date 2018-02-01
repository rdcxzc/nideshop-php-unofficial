<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/1
 * Time: 12:04
 */

namespace App\Models;
use think\Model;

class User extends Model
{
    public function getUserInfo($condition,$field='*')
    {
        return $this->field($field)->where($condition)->find()->getData();
    }
    public function addUser($data)
    {
        return $this->insert($data);
    }
    public function updateUser($condition,$data)
    {
        return $this->where($condition)->update($data);
    }

}