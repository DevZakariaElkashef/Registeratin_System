<?php

namespace App\Controllers;

use App\Auth;
use App\Models\User;
use Core\Controller;
use Core\View;

class Register extends Controller
{
    public function index()
    {
        if(Auth::isLogedIn()){
            $this->redirect('/', 303);
        }else{
            View::templateRender('auth_register_boxed.html');
        }
    }


    public function Create()
    {
        $user = new User($_POST);

        if($user->save()){
            $this->redirect('/', 303);
        }else{
            var_dump($user->errors);
        }
    }
}