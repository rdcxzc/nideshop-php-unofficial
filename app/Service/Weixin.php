<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/1
 * Time: 12:35
 */

namespace App\Service;

use App\Extend\WXBizDataCrypt;

//use App\

class Weixin
{
    /**
     * 解析微信登录用户数据
     * @param sessionKey
     * @param encryptedData
     * @param iv
     */
    public static function decryptUserInfoData($sessionKey, $encryptedData, $iv)
    {
        $appid = getenv('APP_ID');
        $pc = new WXBizDataCrypt();
        $errCode = $pc->setParams($appid,$sessionKey)->decryptData($encryptedData, $iv, $data);
        if ($errCode == '0') {
            return $data;
        } else {
            return $errCode;
        }
    }


}