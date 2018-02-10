<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/31
 * Time: 17:33
 */

namespace App\Controllers;


use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\Region;

class RegionController extends Controller
{
    public function getList(Request $request,Response $response)
    {
        $parentId = $request->getParam('parentId');
        $regionModel = new Region();
        $regionList = $regionModel->getRegionList($parentId);
        return $this->api_r(0,'',200,$regionList,$response);
    }

}