<?php

namespace App\Controllers;

use App\Models\User;
use Core\Controller;

class Account extends Controller
{
    public function validateEmailAction()
    {
        $is_valid = ! User::emialExsists($_GET['email']);

        header('Content-Type: application/json');
        echo json_encode($is_valid);
    }
}