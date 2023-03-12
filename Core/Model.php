<?php

namespace Core;

use App\Config;
use PDO;
use PDOException;

abstract class Model
{


    public static function getDB()
    {
        $db_host = Config::DB_HOST;
        $db_user = Config::DB_USER;
        $db_pass = Config::DB_PASS;
        $db_name = Config::DB_NAME;

        $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
        if($conn){
            return $conn;
        }else{
            throw new \Exception("Error connection");
        }
    }
}