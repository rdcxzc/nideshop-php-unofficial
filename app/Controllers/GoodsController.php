<?php
namespace App\Controllers;


use App\Models\Brand;
use App\Models\Category;
use App\Models\Collect;
use App\Models\Comment;
use App\Models\Footprint;
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
        $uid = getUserId($decoded);

        $cid = $request->getParam('categoryId');
        if (!intval($cid)) {
            throw new \think\Exception('参数不正确');
        }
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
            if ($uid) {
                $searchHistoryModel->insert(
                    [
                        'keyword' => $keyword,
                        'user_id' => $uid,
                        'add_time' => time()
                    ]
                );
            }
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
        if (!empty($categoryIds)) {
            // 查找二级分类 parent_id
            $parentIds = $categoryModel->where('id', 'in', inToStr($categoryIds))->limit(10000)->column('parent_id');

            // 一级分类
            $parentCategory = $categoryModel->field('id,name')->order('sort_order asc')->where('id', 'in', inToStr($parentIds))->select()->toArray();

            if (!empty($parentCategory)) {
                $filterCategory = array_merge($filterCategory, $parentCategory);
            }
        }

        if (!$cid && intval($cid) > 0) {
            $categoryIds = $categoryModel->getCategoryWhereIn($cid);
            $map['category_id'] = ['in', $categoryIds];
        }
        $where = getWhereString($map);
        $goodsDataOrgin = $goodsModel->where($where)->field('id,name,list_pic_url,retail_price')->order($orderMap)->page($page, $size)->select()->toArray();
        $total = $goodsModel->where($where)->field('id,name,list_pic_url,retail_price')->count();
        $totalPages = ceil($total / $size);

        $goodsData = [
            'count' => $total,
            'totalPages' => $totalPages,
            'pagesize' => $size,
            'currentPage' => $page,
            'data' => $goodsDataOrgin
        ];

        $gData = [];
        foreach ($filterCategory as $k => $v) {
            $v['checked'] = (empty($cid) && $v['id'] === 0 || $v['id'] === intval($cid));
            $gData[] = $v;

        }
        $goodsData['filterCategory'] = $gData;
        $goodsData['goodsList'] = $goodsData['data'];

        return $this->api_r(0, '', 200, $goodsData, $response);
    }

    public function detail(Request $request, Response $response)
    {
        $decoded = $request->getHeader('x-nideshop-token');
        $uid = getUserId($decoded);
        $goodsId = $request->getParam('id');
        if (!intval($goodsId)) {
            throw new \think\Exception('参数不正确');
        }
        $goodsModel = new Goods();
        $goodsAttributeModel = new GoodsAttribute();
        $brandModel = new Brand();
        $commentModel = new Comment();
        $userModel = new User();
        $collectModel = new Collect();
        $footprintModel = new Footprint();
        if (empty($goodsId)) {
            throw new \think\Exception('参数不正确');
        }


        $info = $goodsModel->where(['id' => $goodsId])->find();
        $gallery = Db::name('goods_gallery')->where(['goods_id' => $goodsId])->limit(4)->select();
        $attribute = $goodsAttributeModel->getGoodsAttribute($goodsId);
        $issue = Db::name('goods_issue')->select();
        $brand = $brandModel->where(['id' => $info['brand_id']])->select()->toArray();
        $commentCount = $commentModel->where(['value_id' => $goodsId, 'type_id' => 0])->count('id');
        $hotComment = $commentModel->where(['value_id' => $goodsId, 'type_id' => 0])->find();
        if (!empty($hotComment)) {
            $hotComment = $hotComment->toArray();
        }

        $commentInfo = [];
        if (!empty($hotComment)) {
            $commentUser = $userModel->field('nickname,username,avatar')->where(['id' => $hotComment['user_id']])->find()->toArray();
            $picList = Db::name('comment_picture')->where(['comment_id' => $hotComment['id']])->select();
            $commentInfo = [
                'content' => base64_decode($hotComment['content']),
                'add_time' => time(),
                'nickname' => $commentUser['nickname'],
                'avatar' => $commentUser['avatar'],
                'pic_list' => $picList
            ];
        }

        $comment = [
            'count' => $commentCount,
            'data' => $commentInfo
        ];

        if ($uid) {
            // 当前用户是否收藏
            $userHasCollect = $collectModel->isUserHasCollect($uid, 0, $goodsId);
            // 记录用户的足迹 TODO
            $footprintModel->addFootprint($uid, $goodsId);
        }
        $specificaionList = $goodsModel->getSpecificationList($goodsId);
        $getProduct = $goodsModel->getProductList($goodsId);


        $response_data = [
            'info' => $info,
            'gallery' => $gallery,
            'attribute' => $attribute,
            'userHasCollect' => !empty($userHasCollect) ? $userHasCollect : '0',
            'issue' => $issue,
            'comment' => $comment,
            'brand' => $brand,
            'specificationList' => $specificaionList,
            'productList' => $getProduct
        ];
        return $this->api_r(0, '', 200, $response_data, $response);
    }
    public function newGoods(Request $request ,Response $response)
    {
        $res_data['bannerInfo'] = [
            'url' => '',
            'name' => '坚持初心，为你寻觅世间好物',
            'img_url' => 'http://yanxuan.nosdn.127.net/8976116db321744084774643a933c5ce.png'
        ];
        return $this->api_r(0,'',200,$res_data,$response);

    }

    public function hotGoods(Request $request ,Response $response)
    {
        $res_data['bannerInfo'] = [
            'url' => '',
            'name' => '大家都在买的严选好物',
            'img_url' => 'http://yanxuan.nosdn.127.net/8976116db321744084774643a933c5ce.png'
        ];
        return $this->api_r(0,'',200,$res_data,$response);
    }

    public function relatedGoods(Request $request,Response $response)
    {
        // 大家都在看商品,取出关联表的商品，如果没有则随机取同分类下的商品
        $goodsModel = new Goods();
        $goodsId = $request->getParam('id');
        if (!intval($goodsId)) {
            throw new \think\Exception('参数不正确');
        }
        $relatedGoodsIds = Db::name('related_goods')->where(['goods_id' => $goodsId])->column('related_goods_id');
        $relatedGoods = null;
        if (empty($relatedGoodsIds)) {
            // 查找同分类下的商品
            $goodsCategory = $goodsModel->where(['id'=> $goodsId])->find()->toArray();
          $relatedGoods = $goodsModel->where(['category_id'=>$goodsCategory['category_id']])->field('id,name, list_pic_url, retail_price')->limit(8)->select()->toArray();
        } else {
            $map['id'] = ['in',$relatedGoodsIds];
            $where = getWhereString($map);
            $relatedGoods = $goodsModel->where($where)->field('id, name, list_pic_url, retail_price')->select()->toArray();
        }
        $res_data = [
            'goodsList' => $relatedGoods
        ];

        return $this->api_r(0,'',200,$res_data,$response);

    }

}