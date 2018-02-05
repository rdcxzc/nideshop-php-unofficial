<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/30
 * Time: 12:36
 */

namespace App\Controllers;


use App\Models\Brand;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Goods;
use App\Models\GoodsAttribute;
use App\Models\SearchHistory;
use App\Models\User;
use Slim\Exception\Exception;
use Slim\Http\Request;
use Slim\Http\Response;
use think\Db;

class GoodsController extends Controller
{
    public function category(Request $request, Response $response)
    {
        $id = $request->getParam('id');
        if (!intval($id)) {
            throw new \think\Exception('参数不正确');
        }
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

        $decoded = $request->getHeader('x-nideshop-token');

        $cid = $request->getParam('categoryId');
        $brandId = $request->getParam('brandId');
        $keyword = $request->getParam('keyword');
        $isNew = $request->getParam('isNew');
        $isHot = $request->getParam('isHot');
        $page = $request->getParam('page') ? $request->getParam('page') : 1;
        $size = $request->getParam('size') ? $request->getParam('size') : 10;
        $sort = $request->getParam('sort');
        $order = $request->getParam('order');

        $goodsModel = new Goods();
        $searchHistoryModel = new SearchHistory();
        $categoryModel = new Category();

        $map = [];
        if (!empty($isNew)) {
            $map['is_new'] = $isNew;
        }

        if (!empty($isHot)) {
            $map['is_hot'] = $isHot;
        }
        if (!empty($keyword)) {
            $map['name'] = ['like', "%" . $keyword . "%"];
            $searchHistoryModel->insert(
                [
                    'keyword' => $keyword,
                    'user_id' => getUserId($decoded),
                    'add_time' => time()
                ]
            );
        }

        if (!empty($brandId)) {
            $map['brand_id'] = $brandId;
        }

        $orderMap = '';
        if ($sort === 'price') {
            $orderMap = "retail_price {$order}";
        } else {
            $orderMap = "id desc";
        }

        $filterCategory[] = ['id' => 0, 'name' => '全部', 'checked' => false];

        $categoryIds = $goodsModel->where($map)->limit(10000)->column('category_id');
        if(!empty($categoryIds)){
            // 查找二级分类 parent_id
            $parentIds = $categoryModel->where('id','in',inToStr($categoryIds))->limit(10000)->column('parent_id');

            // 一级分类
            $parentCategory = $categoryModel->field('id,name')->order('sort_order asc')->where('id','in',inToStr($parentIds))->select()->toArray();

            if(!empty($parentCategory)){
                $filterCategory = array_merge($filterCategory,$parentCategory);
            }
        }

        if(!$cid && intval($cid) > 0){
            $categoryIds = $categoryModel->getCategoryWhereIn($cid);
            $map['category_id'] = ['in',$categoryIds];
        }
        $where = getWhereString($map);
        $goodsDataOrgin = $goodsModel->where($where)->field('id,name,list_pic_url,retail_price')->order($orderMap)->page($page,$size)->select()->toArray();
        $total = $goodsModel->where($where)->field('id,name,list_pic_url,retail_price')->count();
        $totalPages = ceil( $total / $size);

        $goodsData = [
            'count' => $total,
            'totalPages' => $totalPages,
            'pagesize' => $size,
            'currentPage' => $page,
            'data'   => $goodsDataOrgin
        ];

        $gData = [];
        foreach($filterCategory as $k => $v){
            $v['checked'] = (empty($cid) && $v['id'] === 0 || $v['id'] === intval($cid));
            $gData[] = $v;

        }
        $goodsData['filterCategory'] = $gData;
        $goodsData['goodsList'] = $goodsData['data'];

        return $this->api_r(0,'',200,$goodsData,$response);
    }

    public function detail(Request $request, Response $response)
    {
        $goodsId = $request->getParam('id');
        $goodsModel = new Goods();
        $goodsAttributeModel = new GoodsAttribute();
        $brandModel = new Brand();
        $commentModel = new Comment();
        $userModel = new User();
        if(empty($goodsId)){
            throw new \think\Exception('参数不正确');
        }


        $info = $goodsModel->where(['id' => $goodsId])->find();
        $gallery = Db::name('goods_gallery')->where(['goods_id' => $goodsId])->limit(4)->select();
        $attribute = $goodsAttributeModel->getGoodsAttribute($goodsId);
        $issue = Db::name('goods_issue')->select();
        $brand = $brandModel->where(['id' => $info['brand_id']])->select()->toArray();
        $commentCount = $commentModel->where(['value_id' => $goodsId ,'type_id' => 0])->count('id');
        $hotComment = $commentModel->where(['value_id' => $goodsId ,'type_id' => 0])->find()->toArray();

        $commentInfo = [];
        if(!empty($hotComment)){
            $commentUser = $userModel->field('nickname,username,avatar')->where(['id' => $hotComment['user_id']])->find()->toArray();
            $picList = Db::name('comment_picture')->where(['comment_id' => $hotComment['id']])->select();
            $commentInfo = [
                'content'  => base64_decode($hotComment['content']),
                'add_time' => time(),
                'nickname' => $commentUser['nickname'],
                'avatar'   => $commentUser['avatar'],
                'pic_list' => $picList
            ];
        }
        /**
         *
        const issue = await this.model('goods_issue').select();
        const brand = await this.model('brand').where({id: info.brand_id}).find();
        const commentCount = await this.model('comment').where({value_id: goodsId, type_id: 0}).count();
        const hotComment = await this.model('comment').where({value_id: goodsId, type_id: 0}).find();
        let commentInfo = {};
        if (!think.isEmpty(hotComment)) {
        const commentUser = await this.model('user').field(['nickname', 'username', 'avatar']).where({id: hotComment.user_id}).find();
        commentInfo = {
        content: new Buffer(hotComment.content, 'base64').toString(),
        add_time: think.datetime(new Date(hotComment.add_time * 1000)),
        nickname: commentUser.nickname,
        avatar: commentUser.avatar,
        pic_list: await this.model('comment_picture').where({comment_id: hotComment.id}).select()
        };
        }

        const comment = {
        count: commentCount,
        data: commentInfo
        };

        // 当前用户是否收藏
        const userHasCollect = await this.model('collect').isUserHasCollect(think.userId, 0, goodsId);

        // 记录用户的足迹 TODO
        await await this.model('footprint').addFootprint(think.userId, goodsId);

        // return this.json(jsonData);
        return this.success({
        info: info,
        gallery: gallery,
        attribute: attribute,
        userHasCollect: userHasCollect,
        issue: issue,
        comment: comment,
        brand: brand,
        specificationList: await model.getSpecificationList(goodsId),
        productList: await model.getProductList(goodsId)
        });
         *
         */
//        return $this->api($str, $response);
    }

    public function index(Request $request, Response $response)
    {

        $data = [];
        return $this->api_r(0, '', 200, $data, $response);
    }

}