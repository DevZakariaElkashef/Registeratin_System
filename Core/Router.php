<?php

namespace Core;

class Router
{
    protected $routes = [];

    protected $params = [];

    public function add($route, $params = [])
    {
        $route = preg_replace('/\//', '\\/', $route);
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);
        $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);
        $route = '/^' . $route . '$/i';

        $this->routes[$route] = $params;
    }

    public function match($url)
    {
        foreach($this->routes as $route => $params){
            if(preg_match($route, $url, $matches)){
                foreach($matches as $key => $match){
                    if(is_string($key)){
                        $params[$key] = $match;
                    }
                }
                $this->params = $params;
                return true;
            }
        }
        return false;
    }


    public function disPatch($url)
    {
        $url = $this->removeQueryStingVariables($url);

        if($this->match($url)){
            $controller = $this->params['controller'];
            $controller = $this->convertToStudlyCaps($controller);
            $controller = $this->getNamespace() . $controller;

            if(class_exists($controller)){
                $controller_object = new $controller($this->params);

                $action = $this->params['action'];
                $action = $this->convertToCamelCase($action);

                if(is_callable([$controller_object, $action])){
                    $controller_object->$action();
                }else{
                    throw new \Exception("method ($action) not found");
                }
            }else{
                throw new \Exception("class ($controller) not found");
            }
        }else{  
            throw new \Exception("no route for this url ($url) ");
        }
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    public function getParams()
    {
        return $this->params;
    }

    protected function convertToStudlyCaps($string)
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

    protected function convertToCamelCase($string)
    {
        return ucfirst($this->convertToStudlyCaps($string));
    }

    protected function getNamespace()
    {
        $path = 'App\Controllers\\';

        if(array_key_exists('namespace', $this->params)){
            $path .= $this->params['namespace'] . '\\';
        }

        return $path;
    }

    protected function removeQueryStingVariables($url)
    {
        if ($url != '') {
            $parts = explode('&', $url, 2);

            if (strpos($parts[0], '=') === false) {
                $url = $parts[0];
            } else {
                $url = '';
            }
        }

        return $url;
    }
}