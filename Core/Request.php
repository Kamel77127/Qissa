<?php

namespace App\Core;

use App\Core\Exception\FilesException;
use http\Message\Body;

class Request
{

    private array $routeParams = [];


public function setRouteParams($params)
{
    $this->routeParams = $params;
    return $this;
}


    public function getPath()
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');

        if($position === false)
        {
            return $path;
        }
        return substr($path, 0, $position);
    }


   public function getMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }



    public function isGet()
    {
        return $this->getMethod() === 'get';
    }

    
    public function isPost()
    {
        return $this->getMethod() === 'post';
    }






    public function getBody()
    {
        $body = [];
        if($this->getMethod() === 'get')
        {
            foreach($_GET as $key => $value)
            {
                if($this->filter_string($value))
                {
                    $body[$key] = $value;
                }
            }

        }

        if($this->getMethod() === 'post')
        {
            foreach($_POST as $key => $value)
            {
                if($this->filter_string($value))
                {
                    $body[$key] = $value;
                }
            }

        }

        return $body;
    }

public function getFiles()
{

    if(isset($_FILES) && !empty($_FILES))
    {
        foreach($_FILES as $key => $value)
        {
                if($value['name'] !== '' && $value['tmp_name'] !=='') {
                    $extension = Application::$app->files->getExtension($value['name']);
                    $body[$key] =
                        ['name' => uniqid($value['name']) . $extension,
                            'tmp_name' => $value['tmp_name']];
                }


        }
    }
    if(isset($body))
    {
        return $body;
    }else {
        return false;
    }
}

    public function filter_string(string $string): bool
    {
        $str = preg_replace('/\x00|<[^>]*>?/', '', $string);
        return str_replace(["'", '"'], ['&#39;', '&#34;'], $str);
    }

        public function filter_files(string $fileName): bool
        {
           $array =  explode('.' , $fileName);
           $name = array_shift($array);
           return (bool)preg_match("`^[-0-9A-Z_\.]+$`i", $name);
        }

    public function getRequestUri($uri)
    {
        $url = trim($uri , '/');
        $array = explode('/' , $url);
        array_shift($array);
        return $array ;
    }


}