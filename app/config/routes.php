<?php

/**
 *
 * IndexUrl: NewApiRootUrl + 'index/index', //首页数据接口  ----
 * CatalogList: NewApiRootUrl + 'catalog/index',  //分类目录全部分类数据接口  ----
 * CatalogCurrent: NewApiRootUrl + 'catalog/current',  //分类目录当前分类数据接口 ----
 *
 * AuthLoginByWeixin: NewApiRootUrl + 'auth/loginByWeixin', //微信登录
 *
 * GoodsCount: NewApiRootUrl + 'goods/count',  //统计商品总数 ----
 * GoodsList: NewApiRootUrl + 'goods/list',  //获得商品列表 ----
 * GoodsCategory: NewApiRootUrl + 'goods/category',  //获得分类数据 ----
 * GoodsDetail: NewApiRootUrl + 'goods/detail',  //获得商品的详情----
 * GoodsNew: NewApiRootUrl + 'goods/new',  //新品----
 * GoodsHot: NewApiRootUrl + 'goods/hot',  //热门----
 * GoodsRelated: NewApiRootUrl + 'goods/related',  //商品详情页的关联商品（大家都在看）----
 *
 * BrandList: NewApiRootUrl + 'brand/list',  //品牌列表 ----
 * BrandDetail: NewApiRootUrl + 'brand/detail',  //品牌详情 ----
 *
 * CartList: NewApiRootUrl + 'cart/index', //获取购物车的数据
 * CartAdd: NewApiRootUrl + 'cart/add', // 添加商品到购物车
 * CartUpdate: NewApiRootUrl + 'cart/update', // 更新购物车的商品
 * CartDelete: NewApiRootUrl + 'cart/delete', // 删除购物车的商品
 * CartChecked: NewApiRootUrl + 'cart/checked', // 选择或取消选择商品
 * CartGoodsCount: NewApiRootUrl + 'cart/goodscount', // 获取购物车商品件数
 * CartCheckout: NewApiRootUrl + 'cart/checkout', // 下单前信息确认
 *
 * OrderSubmit: NewApiRootUrl + 'order/submit', // 提交订单
 * PayPrepayId: NewApiRootUrl + 'pay/prepay', //获取微信统一下单prepay_id
 *
 * CollectList: NewApiRootUrl + 'collect/list',  //收藏列表
 * CollectAddOrDelete: NewApiRootUrl + 'collect/addordelete',  //添加或取消收藏
 *
 * CommentList: NewApiRootUrl + 'comment/list',  //评论列表
 * CommentCount: NewApiRootUrl + 'comment/count',  //评论总数
 * CommentPost: NewApiRootUrl + 'comment/post',   //发表评论
 *
 * TopicList: NewApiRootUrl + 'topic/list',  //专题列表
 * TopicDetail: NewApiRootUrl + 'topic/detail',  //专题详情
 * TopicRelated: NewApiRootUrl + 'topic/related',  //相关专题
 *
 * SearchIndex: NewApiRootUrl + 'search/index',  //搜索页面数据
 * SearchResult: NewApiRootUrl + 'search/result',  //搜索数据
 * SearchHelper: NewApiRootUrl + 'search/helper',  //搜索帮助
 * SearchClearHistory: NewApiRootUrl + 'search/clearhistory',  //搜索帮助
 *
 * AddressList: NewApiRootUrl + 'address/list',  //收货地址列表
 * AddressDetail: NewApiRootUrl + 'address/detail',  //收货地址详情
 * AddressSave: NewApiRootUrl + 'address/save',  //保存收货地址
 * AddressDelete: NewApiRootUrl + 'address/delete',  //保存收货地址
 *
 * RegionList: NewApiRootUrl + 'region/list',  //获取区域列表
 *
 * OrderList: NewApiRootUrl + 'order/list',  //订单列表
 * OrderDetail: NewApiRootUrl + 'order/detail',  //订单详情
 * OrderCancel: NewApiRootUrl + 'order/cancel',  //取消订单
 *
 * FootprintList: NewApiRootUrl + 'footprint/list',  //足迹列表
 * FootprintDelete: NewApiRootUrl + 'footprint/delete',  //删除足迹
 * };
 *
 */

$app->group('/api', function () {
    // 首页
    $this->get('/index/index', '\App\Controllers\IndexController:index');
    $this->get('/catalog/index', '\App\Controllers\CategoryController:index');
    $this->get('/catalog/current', '\App\Controllers\CategoryController:current');
    // 微信登录授权
    $this->post('/auth/loginByWeixin', '\App\Controllers\AuthController:loginByWeixin');
    // 商品
    $this->get('/goods/count', '\App\Controllers\GoodsController:count');  //统计商品总数
    $this->get('/goods/list', '\App\Controllers\GoodsController:getList');  //获得商品列表
    $this->get('/goods/category', '\App\Controllers\GoodsController:category');  //获得分类数据
    $this->get('/goods/detail', '\App\Controllers\GoodsController:detail');  //获得商品的详情
    $this->get('/goods/new', '\App\Controllers\GoodsController:new');  //新品
    $this->get('/goods/hot', '\App\Controllers\GoodsController:hot');  //热门
    $this->get('/goods/related', '\App\Controllers\GoodsController:related');  //商品详情页的关联商品（大家都在看）
    // 品牌
    $this->get('/brand/list', '\App\Controllers\BrandController:getList');  //品牌列表
    $this->get('/brand/detail', '\App\Controllers\BrandController:detail'); //品牌详情
    // 购物车
    $this->get('/cart/index', '\App\Controllers\CartController:index'); //获取购物车的数据
    $this->get('/cart/add', '\App\Controllers\CartController:add'); // 添加商品到购物车
    $this->get('/cart/update', '\App\Controllers\CartController:update');// 更新购物车的商品
    $this->get('/cart/delete', '\App\Controllers\CartController:delete'); // 删除购物车的商品
    $this->get('/cart/checked', '\App\Controllers\CartController:checked'); // 选择或取消选择商品
    $this->get('/cart/goodscount', '\App\Controllers\CartController:goodscount'); // 获取购物车商品件数
    $this->get('/cart/checkout', '\App\Controllers\CartController:checked'); // 下单前信息确认
    // 订单/支付
    $this->get('/order/submit', '\App\Controllers\OrderController:submit'); // 提交订单
    $this->get('/pay/prepay', '\App\Controllers\PayController:prepay'); //获取微信统一下单prepay_id
    // 收藏
    $this->get('/collect/list', '\App\Controllers\CollectController:getList');  //收藏列表
    $this->get('/collect/addordelete', '\App\Controllers\CollectController:addordelete');  //添加或取消收藏
    // 评论
    $this->get('/comment/list', '\App\Controllers\CommentController:getList');  //评论列表
    $this->get('/comment/count', '\App\Controllers\CommentController:count');  //评论总数
    $this->get('/comment/post', '\App\Controllers\CommentController:post');  //发表评论
    // 专题
    $this->get('/topic/list', '\App\Controllers\TopicController:getList'); //专题列表
    $this->get('/topic/detail', '\App\Controllers\TopicController:detail'); //专题详情
    $this->get('/topic/related', '\App\Controllers\TopicController:related'); //相关专题
    // 搜索
    $this->get('/search/index', '\App\Controllers\SearchController:index');  //搜索页面数据
    $this->get('/search/result', '\App\Controllers\SearchController:result'); //搜索数据
    $this->get('/search/helper', '\App\Controllers\SearchController:helper');  //搜索帮助
    $this->get('/search/clearhistory', '\App\Controllers\SearchController:clearhistory');  //搜索帮助
    // 地址
    $this->get('/address/list', '\App\Controllers\AddressController:getList');  //收货地址列表
    $this->get('/address/detail', '\App\Controllers\AddressController:detail');  //收货地址详情
    $this->get('/address/save', '\App\Controllers\AddressController:save');  //保存收货地址
    $this->get('/address/delete', '\App\Controllers\AddressController:delete');  //保存收货地址
    // 区域
    $this->get('/region/list', '\App\Controllers\RegionController:getList');  //获取区域列表
    // 订单
    $this->get('/order/list', '\App\Controllers\OrderController:getList');  //订单列表
    $this->get('/order/detail', '\App\Controllers\OrderController:detail');  //订单详情
    $this->get('/order/cancel', '\App\Controllers\OrderController:cancel');  //取消订单
    // 足记
    $this->get('/footprint/list', '\App\Controllers\FootprintController:getList');  //足迹列表
    $this->get('/footprint/delete', '\App\Controllers\FootprintController:delete'); //删除足迹
});