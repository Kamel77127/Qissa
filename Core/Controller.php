<?php

namespace App\Core;

use App\Core\Middlewares\BaseMiddleware;

class Controller
{

    public string $layout = 'baseTemplate';

    protected array $middleWares = [];
    public string $action = '';

    public function render($view , $params = [])
    {
        return Application::$app->view->renderViews($view, $params);
    }

    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    public function registerMiddleware(BaseMiddleware $middleware)
    {
        $this->middleWares[] = $middleware;
    }


    public function adminMiddleware(BaseMiddleware $middleware)
    {
        $this->middleWares[] = $middleware;
    }

    public function getMiddleWares(): array
    {
        return $this->middleWares;
    }




}