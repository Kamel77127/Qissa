<?php

namespace App\Core\Exception;

class NotFoundException extends \Exception
{
protected $code = 404;
protected $message = "page non trouvé";

}