<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/2
 * Time: 9:23
 */

use App\Service\Token;
use Firebase\JWT\JWT;
/**
 * 数组转String
 * @param array $data
 * @return string
 */
function inToStr(array $data)
{
    $query = '';
    if (is_array($data)) {
        $query = implode(',', $data);
//        $query = mb_substr($query,0,strlen($query));
    }
    return $query;
}

function getUserId($jwt)
{
    if(!empty($jwt)) {
        $decoded = JWT::decode($jwt[0], getenv('JWT_SECRET'), array('HS256'));
        if (is_array($decoded)) {
            return $decoded['user_id'];
        } elseif (is_object($decoded)) {
            return $decoded->user_id;
        }
    }
    return false;
}

// model
function getWhereString($data)
{
    $qf = new \App\Third\QueryFormat();
    $res = $qf->parseWhere($data);
    return $res;

}