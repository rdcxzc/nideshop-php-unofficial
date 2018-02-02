<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/2
 * Time: 9:23
 */

use App\Service\Token;

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

function getUserId()
{
    $base_token = Token::getUserId();
    return $base_token;
}