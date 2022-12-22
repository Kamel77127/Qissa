<?php

namespace App\Core\Exception;

class ForbiddenException extends \Exception
{
protected $message = 'tu n\'as pas la permission d\'accèder à la page.';
    protected $code = 403;
}