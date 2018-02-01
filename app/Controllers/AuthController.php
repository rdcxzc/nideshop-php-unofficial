<?php


namespace App\Controllers;

use App\Extend\HttpCurl;
use App\Extend\UUID;
use App\Models\User;
use App\Service\Token;
use Firebase\JWT\JWT;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \DateTime;
use App\Service\Weixin;


class AuthController extends Controller
{
    public function loginByWeixin(Request $request, Response $response)
    {
        $loginParam = file_get_contents("php://input");
        $params = json_decode($loginParam,true);

        $code = $params['code'];
        $fullUserInfo = $params['userInfo'];
        $userInfo = $fullUserInfo['userInfo'];
        $clientIp = '';

        $wxResult = $this->getUserinfo($code);
        if(!isset($wxResult['openid'])){
            return $this->api_r('10000','登录失败',200,'',$response);
        }

        // 验证数据有效性
        $signature = sha1( $fullUserInfo['rawData'] . $wxResult['session_key'] );
        if($signature != $fullUserInfo['signature']){
            return $this->api_r('10000','登录失败',200,'',$response);
        }

        // 解密数据
        $wxUSerInfo = Weixin::decryptUserInfoData($wxResult['session_key'],$fullUserInfo['encryptedData'],$fullUserInfo['iv']);
        if(empty($wxUSerInfo)){
            return $this->api_r('10000','登录失败',200,'',$response);
        }

        // 检查用户是否已经注册
        $userModel = new User();
        $condition['weixin_openid'] = $wxResult['openid'];
        $useridInfo = $userModel->getUserInfo($condition);
        $newUserId = $useridInfo['id'];

        if(empty($useridInfo)) {
                $insertUser = [
                    'username' => '微信用户'.UUID::v4(),
                    'password' => md5(md5($wxResult['openid'])),
                    'register_time' => time(),
                    'register_ip' => $clientIp,
                    'last_login_time' => time(),
                    'last_login_ip'=> $clientIp,
                    'mobile' => '',
                    'weixin_openid'=> $wxResult['openid'],
                    'avatar' =>  (!empty($userInfo['avatarUrl'])) ? $userInfo['avatarUrl'] : '',
                    'gender' =>  (!empty($userInfo['gender'])) ? $userInfo['gender'] : 0, // 性别 0：未知、1：男、2：女
                    'nickname'=> $userInfo['nickName']
                ];
                $userModel->addUser($insertUser);
                $newUserId = $userModel->getLastInsID();

        }
        $wxResult['user_id'] = $newUserId;

        // 查询用户信息
        $newUserInfo = $userModel->getUserInfo(['id' => $newUserId],'id, username, nickname, gender, avatar, birthday');

        // 更新登录信息
        unset($condition);
        $condition['id'] = $newUserId;
        $updateData = [
            'last_login_time' => time(),
            'last_login_ip'   => $clientIp
        ];
        $userModel->updateUser($condition,$updateData);

        // 创建 token
        unset($wxResult['session_key']);
        $returnToken = Token::create($wxResult);
        $returnToken['userInfo'] = $newUserInfo;


        return $this->api_r(0,'',200,$returnToken,$response);
    }

    private function getUserinfo($jscode)
    {
        $res = (new HttpCurl())->setParams(['appid' => getenv('APP_ID') ,'secret' => getenv('APP_SECRET') , 'js_code' => $jscode,'grant_type' => 'authorization_code'])->get('https://api.weixin.qq.com/sns/jscode2session');
        return json_decode($res ,true);
    }
}