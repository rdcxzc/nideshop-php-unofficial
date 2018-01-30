<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/30
 * Time: 13:07
 */

namespace App\Controllers;


use Slim\Http\Request;

class CollectController extends Controller
{
    public function addordelete(Request $request,Response $response)
    {
        $str = '{"errno":0,"errmsg":"","data":{"type":"add"}}';
        return $this->api($str,$response);
    }

}