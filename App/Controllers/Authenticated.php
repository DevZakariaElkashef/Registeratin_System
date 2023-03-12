<?php

namespace App\Controllers;

use Core\Controller;

abstract class Authenticated extends Controller
{
    protected function before()
    {
        $this->requireLogin();
    }
}