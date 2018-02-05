<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/1
 * Time: 17:35
 */

namespace App\Models;


use think\Model;

class Category extends Model
{
    public function getChildCategoryId($parentId)
    {
        $childIds = $this->where(['parent_id' => $parentId])->limit(10000)->column('id');
        return $childIds;
    }

    public function getCategoryWhereIn($categoryId)
    {
        $childIds = $this->getChildCategoryId($categoryId);
        $childIds[] = $categoryId;
        return $childIds;
    }

}