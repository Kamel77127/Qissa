<?php


namespace App\Core\Middlewares;

use App\Core\Application;
use App\Core\Middlewares\BaseMiddleware;
use App\Core\Exception\ForbiddenException;

class AdminMiddleware extends BaseMiddleware
{


    public array $actions = [];

    public function __construct(array $actions = [])
    {
        $this->actions = $actions;
    }

    public function execute()
    {
        if(Application::isGuest() || !Application::isAdmin())
        {
            if(in_array(Application::$app->controller->action , $this->actions))
            {
              throw new ForbiddenException();
            }
        }
    }







}