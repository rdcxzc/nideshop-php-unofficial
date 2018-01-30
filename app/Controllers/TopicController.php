<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/30
 * Time: 11:13
 */

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;

class TopicController extends Controller
{
    public function getList(Request $request, Response $response)
    {
        //echo $_GET['page'];
        //print_r($request->getParams());
        $param = $request->getParam('page');
        $size = $request->getParam('size');
        $str = '{"errno":0,"errmsg":"","data":{"count":20,"totalPages":2,"pagesize":10,"currentPage":1,"data":[{"id":314,"title":"关爱他成长的每一个足迹","price_info":0,"scene_pic_url":"https://yanxuan.nosdn.127.net/14943267735961674.jpg","subtitle":"专业运动品牌同厂，毛毛虫鞋买二送一"},{"id":313,"title":"一次解决5个节日送礼难题","price_info":59.9,"scene_pic_url":"https://yanxuan.nosdn.127.net/14942996754171334.jpg","subtitle":"这些就是他们想要的礼物清单"},{"id":300,"title":"秒杀化学洗涤剂的纯天然皂","price_info":0,"scene_pic_url":"https://yanxuan.nosdn.127.net/14939843143621089.jpg","subtitle":"前段时间有朋友跟我抱怨，和婆婆住到一起才发现生活理念有太多不和。别的不提，光是洗..."},{"id":299,"title":"买过的人都说它是差旅神器","price_info":0,"scene_pic_url":"https://yanxuan.nosdn.127.net/14938873919030679.jpg","subtitle":"许多人经历过旅途中内裤洗晾不便的烦恼，尤其与旅伴同居一室时，晾在卫生间里的内裤更..."},{"id":295,"title":"他们在严选遇见的新生活","price_info":35.8,"scene_pic_url":"https://yanxuan.nosdn.127.net/14938092956370380.jpg","subtitle":"多款商品直减中，最高直减400元"},{"id":294,"title":"这只锅，可以从祖母用到孙辈","price_info":149,"scene_pic_url":"https://yanxuan.nosdn.127.net/14937214454750141.jpg","subtitle":"买100年传世珐琅锅送迷你马卡龙色小锅"},{"id":291,"title":"舒适新主张","price_info":29,"scene_pic_url":"https://yanxuan.nosdn.127.net/14939496197300723.jpg","subtitle":"如何挑选适合自己的好物？"},{"id":289,"title":"专业运动袜也可以高性价比","price_info":0,"scene_pic_url":"https://yanxuan.nosdn.127.net/14932840600970609.jpg","subtitle":"越来越多运动人士意识到，运动鞋要购置好的，鞋里的运动袜也不可忽视。专业运动袜帮助..."},{"id":287,"title":"严选新式样板间","price_info":29.9,"scene_pic_url":"https://yanxuan.nosdn.127.net/14931970965550315.jpg","subtitle":"一种软装一个家"},{"id":286,"title":"无“油”无虑的甜蜜酥脆","price_info":0,"scene_pic_url":"https://yanxuan.nosdn.127.net/14931121822100127.jpg","subtitle":"大家都知道，饮食组是严选体重最重的一组，基本上每个新人都能在一个月之内迅速长胖。..."}]}}';
        return $this->api($str,$response);
    }

    public function detail(Request $request,Response $response)
    {
        $id = $request->getParam('id\d');
        $str = '{"errno":0,"errmsg":"","data":{"id":314,"title":"关爱他成长的每一个足迹","content":"<img src=\"//yanxuan.nosdn.127.net/75c55a13fde5eb2bc2dd6813b4c565cc.jpg\">\n    <img src=\"//yanxuan.nosdn.127.net/e27e1de2b271a28a21c10213b9df7e95.jpg\">\n    <img src=\"//yanxuan.nosdn.127.net/9d413d1d28f753cb19096b533d53418d.jpg\">\n    <img src=\"//yanxuan.nosdn.127.net/64b0f2f350969e9818a3b6c43c217325.jpg\">\n    <img src=\"//yanxuan.nosdn.127.net/a668e6ae7f1fa45565c1eac221787570.jpg\">\n    <img src=\"//yanxuan.nosdn.127.net/0d4004e19728f2707f08f4be79bbc774.jpg\">\n    <img src=\"//yanxuan.nosdn.127.net/79ee021bbe97de7ecda691de6787241f.jpg\">","avatar":"https://yanxuan.nosdn.127.net/14943186689221563.png","item_pic_url":"https://yanxuan.nosdn.127.net/14943267735961674.jpg","subtitle":"专业运动品牌同厂，毛毛虫鞋买二送一","topic_category_id":2,"price_info":0,"read_count":"6.4k","scene_pic_url":"https://yanxuan.nosdn.127.net/14943267735961674.jpg","topic_template_id":0,"topic_tag_id":0,"sort_order":1,"is_show":1}}';
        return $this->api($str);

    }

    public function related(Request $request ,Response $response)
    {
        $id = $request->getParam('id\d');
        $str = '';
        return $this->api($str,$response);

    }
}