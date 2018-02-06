<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/1
 * Time: 17:33
 */

namespace App\Models;


use think\Model;

class Goods extends Model
{

    /**
     * 获取商品的product
     * @param $goodsId
     * @returns
     */
    public function getProductList($goodsId)
    {
        $goods = $this->table('__PRODUCT__')->where(['goods_id' => $goodsId])->select();
        return $goods;
    }

    /**
     * 获取商品的规格信息
     * @param goodsId
     * @returns {Promise.<Array>}
     */
    public function getSpecificationList($goodsId)
    {
        // 根据sku商品信息，查找规格值列表

        $specificationRes = $this
            ->table('__GOODS_SPECIFICATION__')
            ->alias('gs')
            ->field(['gs.*', 's.name'])
            ->join('__SPECIFICATION__ s', 'gs.specification_id=s.id')
            ->where(['gs.goods_id' => $goodsId])
            ->select();

        $specificationList = [];
        $hasSpecificationList = [];
        // 按规格名称分组
        for ($i = 0; $i < count($specificationRes); $i++) {
            $specItem = $specificationRes[$i];
            if (!$hasSpecificationList[$specItem['specification_id']]) {
                $specificationList[] = [
                    'specification_id' => $specItem['specification_id'],
                    'name' => $specItem['name'],
                    'valueList' => $specItem
                ];
                $hasSpecificationList[$specItem['specification_id']] = $specItem;
            } else {
                for ($j = 0; $j < count($specificationList);$j++) {
                    if ($specificationList[$j]['specification_id'] === $specItem['specification_id']) {
                        $specificationList[$j]['valueList'][] = $specItem;
                        break;
                    }
                }
      }
        }

        return $specificationList;
    }

}