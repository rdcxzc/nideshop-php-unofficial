<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/30
 * Time: 9:54
 */
namespace App\Models;

use think\Model;

class Cart extends Model
{
    protected $name = "cart";

    /**
     * 获取购物车商品
     * @param $condition
     * @param string $field
     * @return mixed
     */
    public function getGoodsList($condition,$field="*")
    {
        return $this->field($field)->where($condition)->select();
    }

    /**
     * 获取购物车选中的商品
     * @param $condition
     * @param string $field
     * @return mixed
     */
    public function getCheckedGoodsList($condition,$field="*"){
        return $this->select($field ,$condition);
    }

    /**
     * 清空已购买商品
     * @param null $condition
     * @return int
     */
    public function clearBuyGoods($condition = null)
    {
        return $this->delete($condition);
    }

}