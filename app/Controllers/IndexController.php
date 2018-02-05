<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/30
 * Time: 11:08
 */

namespace App\Controllers;

use App\Models\Ad;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Channel;
use App\Models\Goods;
use App\Models\Topic;
use Slim\Http\Request;
use Slim\Http\Response;


class IndexController extends Controller
{

    public function __construct($container)
    {
        parent::__construct($container);

    }

    public function index(Request $request, Response $response)
    {
        $categoryIds = [];
        $newCategoryList = [];
        $bannerModel = new Ad();
        $channelModel = new Channel();
        $goodsModel = new Goods();
        $brandModel = new Brand();
        $topicModel = new Topic();
        $categoryModel = new Category();

        $banner = $bannerModel->where(['ad_position_id' => '1'])->select()->toArray();
        $channel = $channelModel->order('sort_order asc')->select()->toArray();
        $newGoods = $goodsModel->where(['is_new' => 1])->field('id,name,list_pic_url,retail_price')->limit(4)->select()->toArray();
        $hotGoods = $goodsModel->where(['is_hot' => 1])->field('id,name,list_pic_url,retail_price,goods_brief')->limit(3)->select()->toArray();
        $brandList = $brandModel->where(['is_new' => 1])->order('new_sort_order asc')->limit(4)->select()->toArray();
        $topicList = $topicModel->limit(3)->select()->toArray();
        $categoryList = $categoryModel->where('parent_id = 0 and `name` <> "推荐"')->select()->toArray();

        //获取 child-CategoryIds  ---- 避免在 循环中使用数据库操作
        foreach ($categoryList as $key => $value) {
            // 主分类 ID
            $categoryIds[] = $value['id'];
        }
        //$categoryIds = inToStr($categoryIds);
        $map['parent_id'] = ['in',$categoryIds];
        $where = getWhereString($map);

        $childCategory = $categoryModel->where($where)->field('parent_id,id')->order('id asc')->select()->toArray();

        foreach ($categoryList as $ckey => $cvalue) {
            foreach ($childCategory as $key => $value) {
                $childCategoryIds[] = $value['id'];
                if ($value['parent_id'] == $cvalue['id']) {
                    $categoryList[$ckey]['child'][] = $value['id'];
                }
            }
        }

        $categoryGoods = $goodsModel->where('category_id', 'in', inToStr($childCategoryIds))->field('id,name,category_id,list_pic_url,retail_price')->select()->toArray();

        $goodsList = [];
        foreach ($categoryList as $key => $value) {
            foreach ($categoryGoods as $gkey => $gvalue) {
                foreach ($value['child'] as $ckey => $cvalue) {
                    if ($cvalue == $gvalue['category_id']) {
                        $goodsList[] = $gvalue;
                    }
                }
            }
            array_multisort(array_column($goodsList,'id'),SORT_ASC,$goodsList);

            $newCategoryList[] = [
                'id' => $value['id'],
                'name' => $value['name'],
                'goodsList' => $goodsList
            ];
            unset($goodsList);
        }

        $response_data = [
            'banner' => $banner,
            'channel' => $channel,
            'newGoodsList' => $newGoods,
            'hotGoodsList' => $hotGoods,
            'brandList' => $brandList,
            'topicList' => $topicList,
            'categoryList'=>$newCategoryList
        ];

        return $this->api_r(0, '', 200, $response_data, $response);

    }
}