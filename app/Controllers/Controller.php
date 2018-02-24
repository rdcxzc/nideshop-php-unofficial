<?php

/*
 * This file is part of PHP CS Fixer.
 * (c) kcloze <pei.greet@qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Controllers;

use Slim\Http\Response;

class Controller
{
    protected $container;
    private $response;
    protected $decoded;
    private $token;
    protected $userid;

    public function __construct($container)
    {
        $this->container = $container;
        $this->token = isset($_SERVER['HTTP_X_NIDESHOP_TOKEN']) ? $_SERVER['HTTP_X_NIDESHOP_TOKEN'] : FALSE;
        $this->userid = getUserId($this->token);
    }

    public function __get($property)
    {
        if ($this->container->{$property}) {
            return $this->container->{$property};
        }
    }

    protected function api($data = null,$response)
    {
        if (is_array($data)) {
            $data = json_encode($data);
        }
        return $response->withHeader('Content-Type','application/json')->write($data);
    }
    protected function api_r($code = '0',$msg = '',$status = '200',$data = NULL,$response)
    {
        $return_data = [
            'errno'  => $code,
            'errmsg' => $msg
        ];
        if(is_array($data)){
            $return_data['data']  = $data;
        }
        return $response
            ->withStatus($status)
            ->withHeader('Content-Type','application/json;charset=utf-8')
            ->withJson($return_data);
    }

//    protected function fail($msg = '')
//    {
//
//        return $this->response
//            ->withStatus($status)
//            ->withHeader('Content-Type','application/json;charset=utf-8')
//            ->withJson(['errno' => '',]);
//
//    }
}
