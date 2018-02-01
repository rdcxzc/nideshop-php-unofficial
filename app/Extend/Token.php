<?php
namespace App\Extend;

class Token
{

    public $decoded;
    public $token;

    public function populate($decoded)
    {
        $this->decoded = $decoded;
    }
    public function putToken($token)
    {
        $this->token = $token;
    }
}