<?php

namespace App\Core;



use App\Core\Exception\NotFoundException;

class Router
{

    protected array $routes = [];
    public Response $response;
    public Request $request;

    public function __construct()
    {
        $this->response = new Response();
        $this->request = new Request();
    }

public function get($path , $callback)
{

    $this->routes['get'][$path] = $callback;
    
}

public function post($path , $callback)
{

    $this->routes['post'][$path] = $callback;

}

    public function getRouteMap($method): array
    {
        return $this->routes[$method] ?? [];
    }


public function getCallback()
{
    $method = $this->request->getMethod();
    $url = $this->request->getPath();

    $url = trim($url, '/');


    $routes = $this->getRouteMap($method);

    $routeParams = false;


    foreach ($routes as $route => $callback) {

        $route = trim($route, '/');
        $routeNames = [];

        if (!$route) {
            continue;
        }


        if (preg_match_all('/\{(\w+)(:[^}]+)?}/', $route, $matches)) {
            $routeNames = $matches[1];
        }
      
        

        // Convert route name into regex pattern
        
        $routeRegex = "@^" . preg_replace_callback('/\{\w+(:([^}]+))?}/', fn($m) => isset($m[2]) ? "({$m[2]})" : '(\w+)', $route) . "$@";
   
        
        if (preg_match_all($routeRegex, $url, $valueMatches)) {

          
            $values = [];
            $vLenght = count($valueMatches);
            for ($i = 1; $i < $vLenght; $i++) {
            
                $values[] = $valueMatches[$i][0];
            }
            $routeParams = array_combine($routeNames, $values);

            $this->request->setRouteParams($routeParams);
           
            return $callback;
        }
    }

    return false;
}
public function resolve()
{
    $path = $this->request->getPath();
    $method = $this->request->getMethod();
 
 
    // je regarde dans l'array routes dans la méthod get y a t-il une path qui correspond à mon url
    // print_r($this->routes);
    $callback = $this->routes[$method][$path] ?? false;

    if($callback === false)
    {
        $callback = $this->getCallback();

        if($callback === false)
        {
       throw new NotFoundException();}
    
    }
   
 
    if(is_string($callback))
    {
        return Application::$app->view->renderViews($callback);
    }

    if (is_array($callback)) {


        $controller = new $callback[0]();
        Application::$app->controller = $controller;

        $controller->action = $callback[1];
        $callback[0] = $controller;
        foreach ($controller->getMiddleWares() as $middleWare)
        {
            $middleWare->execute();
        }
    }

    return call_user_func($callback, $this->request , $this->response);
}

}