<?php

namespace App\Controllers;

use App\Auth;
use App\Models\User;
use Core\Controller;
use Core\View;

class Login extends Controller
{
    public function indexAction()
    {
        if(Auth::isLogedIn()){
            $this->redirect('/');
        }else{
            View::templateRender('auth_login_boxed.html');
        }
    }

    public function signInAction()
    {
        $user = User::authenticate($_POST['email'], $_POST['password']);

        if($user){
            Auth::login($user);
            $this->redirect(Auth::getReturnPage());
        }else{
            View::templateRender('auth_login_boxed.html', [
                'email' => $_POST['email']
            ]);
        }
    }

    public function distroyAction()
    {
        Auth::logout();

        $this->redirect('/', 303);
    }
}