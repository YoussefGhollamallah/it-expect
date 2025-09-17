<?php

namespace App\Controllers;

use App\Models\ModelUser;

class UserController
{
    private $ModelUser;

    public function __construct()
    {
        $this->ModelUser = new ModelUser();
    }

    public function register($firstame, $lastname, $email, $password)
    {
        $result = $this->ModelUser->register($firstame, $lastname, $email, $password);
        return $result;
    }

    public function login($email, $password)
    {
        $result = $this->ModelUser->login($email, $password);
        return $result;
    }
    
}