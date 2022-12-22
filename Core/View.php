<?php

namespace App\Core;

class View
{

    public string $title = '';
    public string $css = '';
    public string $javascript = '';
    public string $meta = '';

    public function renderViews($view, $params = [])
    {
        $viewContent = $this->renderOnlyView($view, $params);
        $baseContent = $this->baseContent();
        return str_replace('{{content}}', $viewContent,$baseContent);

    }

    protected function baseContent()
    {
        $layout = Application::$app->layout;

        if(Application::$app->controller)
        {
            $layout = Application::$app->controller->layout;
        }

        ob_start();
        include_once Application::$ROOT_DIR . "/views/layout/$layout.php";
        return ob_get_clean();
    }

    protected function renderOnlyView($view ,$params)
    {
        foreach($params as $key => $value)
        {
            $$key = $value;
        }
        ob_start();
        include_once Application::$ROOT_DIR . "/views/$view.php";
        return ob_get_clean();
    }
}