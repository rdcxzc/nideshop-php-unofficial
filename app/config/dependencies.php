<?php

use Slim\Middleware\JwtAuthentication;
use App\Extend\UnauthorizedResponse;
use App\Extend\Token;


$container = $app->getContainer();

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};
// JWT 认证中间件
$container["JwtAuthentication"] = function ($container) {
    return new JwtAuthentication([
        "path" => ["/api/order", "/api/auth", "/api/collect", "/api/cart", "/api/pay", "/api/comment/post", "/api/address"],
        "header" => "X-Nideshop-Token",
        "attribute" => "jwt",
        "regexp" => "/(.*)/",
        "passthrough" => ["/api/auth/loginByWeixin"],
        "secret" => getenv("JWT_SECRET"),
        "logger" => $container["logger"],
        "error" => function ($request, $response, $arguments) {
            return new UnauthorizedResponse($arguments["message"], 401);
        },
        "callback" => function ($request, $response, $arguments) use ($container) {
            $container["jwt"]->populate($arguments["decoded"]);
            $token = isset($_SERVER['HTTP_X_NIDESHOP_TOKEN']) ? $_SERVER['HTTP_X_NIDESHOP_TOKEN'] : '';
            $container["jwt"]->putToken($token);
        }
    ]);
};

$container["jwt"] = function ($c) {
    return new Token;
};

// 定义 notFound 异常处理
$container['notFoundHandler'] = function ($container) {
    return function ($request, $response) use ($container) {
        return $container['response']
            ->withStatus(404)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode([
                    'errno' => 404,
                    'errmsg' => 'Resource not valid'])
            );
    };
};

// 定义 NotAllowed 异常处理
$container['notAllowedHandler'] = function ($container) {
    return function ($request, $response) use ($container) {
        return $container['response']
            ->withStatus(401)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode([
                    'errno' => 401,
                    'errmsg' => 'Method not allowed'])
            );
    };
};

// 定义 error 异常处理
$container['errorHandler'] = function ($container) {
    return function ($request, $response, $exception = null) use ($container) {
        $code = 500;
        $message = 'There was an error';

        if ($exception !== null) {
            $code = $exception->getCode();
            $message = $exception->getMessage();
        }
//        echo $code;
        if (in_array($code, ['0', '2002', '10501'])) {
            $o_code = $code;
            $code = 500;
        }
        return $container['response']
            ->withStatus($code)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode([
                    'errno' => $o_code,
                    'errmsg' => $message,
                    'success' => false])
            );
    };
};

