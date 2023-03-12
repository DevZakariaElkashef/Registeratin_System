<?php

namespace App\Controllers;

use App\Auth;
use Core\Controller;
use Core\View;

class Home extends Authenticated
{
    public function indexAction()
    {
        View::templateRender('index.html');
    }
}