<?php

namespace App\Core;

class CSRF 
{

public function createToken ()
{
       return $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

public function getToken() {
    return $_SESSION['csrf_token'];
}


public function checkToken($token)
{
   return $token === $_SESSION['csrf_token'];
   
}

public function  csrfInput()
{
    echo '<input type="hidden" name="csrf_token" id="csrf_token" value="'. $this->getToken() . '" >';
}


}

