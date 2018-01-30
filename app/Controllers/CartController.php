<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/30
 * Time: 12:54
 */

namespace App\Controllers;


use Slim\Http\Request;
use Slim\Http\Response;

class CartController extends Controller
{
    public function goodscount(Request $request,Response $response)
    {
        $str = '{"errno":0,"errmsg":"","data":{"cartTotal":{"goodsCount":2}}}';
        return $this->api($str,$response);
    }

}