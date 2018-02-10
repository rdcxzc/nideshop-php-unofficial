<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/31
 * Time: 17:33
 */

namespace App\Controllers;


use App\Models\Brand;
use Slim\Http\Request;
use Slim\Http\Response;

class BrandController extends Controller
{
    public function getList(Request $request , Response $response)
    {
        $page = $request->getParam('page');
        $size = $request->getParam('size');
        $page = $page ? $page : 1;
        $size = $size ? $size : 10;
        $brandModel = new Brand();
        $count = $brandModel->count();
        $data = $brandModel->field('id, name, floor_price, app_list_pic_url')->page($page,$size)->select()->toArray();
        $totalPages = ceil($count / $size);
        $response_data = [
            'count' => $count,
            'totalPages' => $totalPages,
            'pagesize' => $size,
            'currentPage' => 1,
            'data' => $data

        ];
        return $this->api_r(0,'',200,$response_data,$response);

    }

    public function detail(Request $request,Response $response)
    {
        $id = $request->getParam('id');
        $brandModel = new Brand();
        $data = $brandModel->where(['id' => $id])->find()->toArray();
        $response_data = [
            'brand' => $data
        ];

        return $this->api_r(0,'',200,$response_data,$response);
    }

}