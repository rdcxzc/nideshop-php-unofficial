<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/1
 * Time: 10:06
 */

namespace App\Service;

use App\Models\User;
use DateTime;
use Firebase\JWT\JWT;

class Token
{
    // 获取中间件 解密得到的数据
    static public function getUserId()
    {
        $token = (new \App\Extend\Token()) ->decode();
        if(!$token['user_id']){
            return 0;
        }
        return $token['user_id'];
    }

    static public function getUserInfo()
    {
        $userId = self::getUserId();
        if ($userId <= 0) {
            return null;
        }
        $userModel = new User();
        $userInfo = $userModel->getUserInfo(['id' => $userId], 'id, username, nickname, gender, avatar, birthday');

        return (empty($userInfo) ? null : $userInfo);

    }

    static public function create($userData)
    {
        $future = new DateTime("now +2 hours");
        $payload = $userData;
        $secret = getenv("JWT_SECRET");
        $token = JWT::encode($payload, $secret, "HS256");
        $data["token"] = $token;
        return $data;
    }



}