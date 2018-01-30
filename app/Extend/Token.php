<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/30
 * Time: 9:27
 */
namespace App\Extend;

class Token
{
    public $decoded;

    public function populate($decoded)
    {
        $this->decoded = $decoded;
    }

    public function hasScope(array $scope)
    {
        return !!count(array_intersect($scope, $this->decoded->scope));
    }
}