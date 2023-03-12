<?php

namespace App\Controllers;

use App\Auth;
use Core\Controller;
use Core\View;

class Posts extends Authenticated
{
    public function indexAction()
    {
                
        View::templateRender('posts.html');
    }
}