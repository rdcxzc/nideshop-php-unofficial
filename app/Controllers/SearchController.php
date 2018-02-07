<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/31
 * Time: 17:31
 */

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use think\Db;

class SearchController extends Controller
{
    public function index(Request $request, Response $response)
    {
        // 取出输入框默认的关键词
        $defaultKeyword = Db::name('keywords')->where(['is_default' => 1])->limit(1)->find();
        // 取出热闹关键词
        $decoded = $response->getHeader('x-nideshop-token');
        $uid = getUserId($decoded);

        $hotKeywordList = Db::name('keywords')->distinct('keyword')->field('keyword, is_hot')->limit(10)->select();
        if ($uid) {
            $historyKeywordList = Db::name('search_history')->distinct('keyword')->where(['user_id' => $uid])->limit(10)->column('keyword');
        } else {
            $historyKeywordList = [];
        }

        $response_data = [
            'defaultKeyword' => $defaultKeyword,
            'historyKeywordList' => $historyKeywordList,
            'hotKeywordList' => $hotKeywordList
        ];
        return $this->api_r(0, '', 200, $response_data, $response);
    }

    public function helper(Request $request, Response $response)
    {
        $keyword = $request->getParam('keyword');
        $map['keyword'] = ['like', $keyword . "%"];
        $where = getWhereString($map);
        $keywords = Db::name('keywords')->distinct('keyword')->where($where)->limit(10)->column('keyword');
        return $this->api_r(0, '', 200, $keywords, $response);

        //return this.success(keywords);
    }

    public function clearhistory(Request $request, Response $response)
    {
        $decoded = $request->getHeader('x-nideshop-token');
        $uid = getUserId($decoded);
        Db::name('search_history')->where(['user_id' => $uid])->delete();
        return $this->api_r(0, '', 200, [], $response);
    }

}