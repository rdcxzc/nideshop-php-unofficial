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

class CommentController extends Controller
{
    public function getList(Request $request,Response $response)
    {
        if($request->isPost()){

            $params = $request->getParams();
            $typeId = $params['typeId'];
            $valueId = $params['valueId'];
            $content = $params['content'];
            $insertData = [
                'type_id'  => $typeId,
                'value_id' => $valueId,
                'content'  => base64_encode($content),

            ];
            //$insertId =

        }

    }

}