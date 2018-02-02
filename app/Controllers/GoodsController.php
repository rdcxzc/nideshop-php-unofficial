<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/30
 * Time: 12:36
 */

namespace App\Controllers;


use App\Models\Category;
use App\Models\Goods;
use App\Models\SearchHistory;
use Slim\Http\Request;
use Slim\Http\Response;

class GoodsController extends Controller
{
    public function category(Request $request, Response $response)
    {
        $id = $request->getParam('id');
        $categoryModel = new Category();

        $currentCategory = $categoryModel->where(['id' => $id])->find()->toArray();
        $parentCategory = $categoryModel->where(['id' => $currentCategory['parent_id']])->find()->toArray();
        $brotherCategory = $categoryModel->where(['parent_id' => $currentCategory['parent_id']])->select()->toArray();

        $response_data = [
            'currentCategory' => $currentCategory,
            'parentCategory' => $parentCategory,
            'brotherCategory' => $brotherCategory
        ];


        return $this->api_r(0, '', 200, $response_data, $response);
    }

    public function count(Request $request, Response $response)
    {
        $goodsModel = new Goods();
        $goodsCount = $goodsModel->where(['is_delete' => 0, 'is_on_sale' => 1])->count('id');
        $response_data = [
            'goodsCount' => $goodsCount
        ];
        return $this->api_r(0, '', 200, $response_data, $response);


    }

    public function getList(Request $request, Response $response)
    {
        $cid = $request->getParam('categoryId');
        $brandId = $request->getParam('brandId');
        $keyword = $request->getParam('keyword');
        $isNew = $request->getParam('isNew');
        $isHot = $request->getParam('isHot');
        $page = $request->getParam('page');
        $size = $request->getParam('size');
        $sort = $request->getParam('sort');
        $order = $request->getParam('order');

        $goodsModel = new Goods();
        $searchHistoryModel = new SearchHistory();

        $map = [];
        if(!empty($isNew)){
            $map['is_new'] = $isNew;
        }

        if(!empty($isHot)){
            $map['is_hot'] = $isHot;
        }
        if(!empty($keyword)){
            $map['name'] = ['like',"%".$keyword."%"];
            $searchHistoryModel->insert(
                [
                    'keyword' => $keyword,
                    'user_id' => getUserId(),
                    'add_time'=> time()
                ]
            );
        }

        if(!empty($brandId)){
            $map['brand_id'] = $brandId;
        }

        $orderMap = '';
        if($sort === 'price'){
            $orderMap = "retail_price {$order}";
        }else{
            $orderMap = "id desc";
        }


//        $filterCategory =

        //return $this->api($str, $response);

    }

    public function detail(Request $request, Response $response)
    {
        $id = $request->getParam('id');
        $str = '{"errno":0,"errmsg":"","data":{"info":{"id":1166008,"category_id":1005007,"goods_sn":"1166008","name":"Carat钻石 不粘厨具组合","brand_id":0,"goods_number":100,"keywords":"","goods_brief":"钻石涂层，不粘锅锅具组","goods_desc":"","is_on_sale":1,"add_time":0,"sort_order":5,"is_delete":0,"attribute_category":0,"counter_price":0,"extra_price":0,"is_new":1,"goods_unit":"只","primary_pic_url":"http://yanxuan.nosdn.127.net/056baf67bb8cc9a4f2544ac5954ab67c.jpg","list_pic_url":"http://yanxuan.nosdn.127.net/615a16e899e01efb780c488df4233f48.png","retail_price":459,"sell_volume":889,"primary_product_id":1178050,"unit_price":0,"promotion_desc":"限时购","promotion_tag":"","app_exclusive_price":0,"is_app_exclusive":0,"is_limited":0,"is_hot":0},"gallery":[],"attribute":[],"userHasCollect":0,"issue":[{"id":1,"goods_id":"1127052","question":"购买运费如何收取？","answer":"单笔订单金额（不含运费）满88元免邮费；不满88元，每单收取10元运费。\n(港澳台地区需满"},{"id":2,"goods_id":"1127052","question":"使用什么快递发货？","answer":"严选默认使用顺丰快递发货（个别商品使用其他快递），配送范围覆盖全国大部分地区（港澳台地区除"},{"id":3,"goods_id":"1127052","question":"如何申请退货？","answer":"1.自收到商品之日起30日内，顾客可申请无忧退货，退款将原路返还，不同的银行处理时间不同，"},{"id":4,"goods_id":"1127052","question":"如何开具发票？","answer":"1.如需开具普通发票，请在下单时选择“我要开发票”并填写相关信息（APP仅限2.4.0及以"}],"comment":{"count":0,"data":{}},"brand":{},"specificationList":[],"productList":[{"id":244,"goods_id":1166008,"goods_specification_ids":"","goods_sn":"1166008","goods_number":100,"retail_price":459}]}}';
        return $this->api($str, $response);
    }

}