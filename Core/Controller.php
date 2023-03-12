<?php

namespace Core;

use App\Auth;

abstract class Controller
{
    public $route_params = [];

    public function __construct($route_params)
    {
        $this->route_params = $route_params;
    }


    public function __call($name, $args)
    {
        $method = $name . 'Action';
        if (method_exists($this, $method)) {
            if ($this->before() !== false) {
                call_user_func_array([$this, $method], $args);
                $this->after();
            }
        } else {
            throw new \Exception("Method $method not found in controller " . get_class($this));
            
        }
    }

    protected function before()
    {
        
    }

    protected function after()
    {
        
    }

    public function redirect($url, $code = 303)
    {
        header('Location: http://' . $_SERVER['HTTP_HOST'] . $url, true, $code);
        exit;
    }

    public function requireLogin()
    {
        if(! Auth::isLogedIn()){
             Auth::remmberRequestedPage();
            $this->redirect('/login');
        }
    }
}