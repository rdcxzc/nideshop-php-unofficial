<?php


namespace App\Controllers;

use Firebase\JWT\JWT;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \DateTime;

class AuthController extends Controller
{
    public function loginByWeixin(Request $request, Response $response)
    {

        $now = new DateTime();
        $future = new DateTime("now +2 hours");

        $payload = [
            "iat" => $now->getTimeStamp(),
            "exp" => $future->getTimeStamp()
        ];
        $secret = getenv("JWT_SECRET");
        $token = @JWT::encode($payload, $secret, "HS256");
        $data["token"] = $token;
        $data["expires"] = $future->getTimeStamp();

        return $this->api_r('0','',201,$data,$response);

//        return $response->withStatus(201)
//            ->withHeader("Content-Type", "application/json")
//            ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }

    public function getUserLogin()
    {
        print_r(getenv());

    }
}