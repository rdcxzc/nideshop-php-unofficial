<?php

use Slim\Middleware\JwtAuthentication;
use App\Extend\UnauthorizedResponse;
use App\Extend\Token;
//use Tuupola\Middleware\JwtAuthentication;

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];

    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger   = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));

    return $logger;
};




$container["JwtAuthentication"] = function ($container) {
    return new JwtAuthentication([
        "path" => ["/api/order","/api/auth","/api/collect","/api/cart","/api/pay","/api/comment/post","/api/address"],
        "header" => "X-Nideshop-Token",
        "regexp" => "/(.*)/",
        "passthrough"  => ["/api/index", "/info","/api/auth/loginByWeixin"],
        "secret" => getenv("JWT_SECRET"),
        "logger" => $container["logger"],
        "error" => function ($request, $response, $arguments) {
            return new UnauthorizedResponse($arguments["message"], 401);
        },
        "callback" => function ($request, $response, $arguments) use ($container) {
            file_put_contents('aaaa.json',json_encode($arguments));
            $container["jwt"] = $arguments["decoded"];
        }
    ]);
};

$container["token"] = function ($c) {
    return new Token;
};

