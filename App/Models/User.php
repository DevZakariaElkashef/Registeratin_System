<?php

namespace App\Models;

use AllowDynamicProperties;
use Core\Model;
use Core\View;
use PDO;

#[AllowDynamicProperties]
class User extends Model
{
    public $errors = [];

    public function __construct($data = [])
    {
        foreach($data as $key => $prop){
            $this->$key = $prop;
        }
    }

    public function save()
    {
        $this->validate();

        if(empty($this->errors)){

            $password = password_hash($this->password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (name, email, password_hash) VALUES (:name, :email, :password_hash)";

            $db = static::getDB();

            $stmt = $db->prepare($sql);

            $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            $stmt->bindValue(':password_hash', $password, PDO::PARAM_STR);

            return $stmt->execute();
        }else{
            return false;
        }
    }

    public function validate()
    {
        # name
        if($this->name == ''){
            $this->errors[] = 'Name Is Required';
        }
        if(strlen($this->name) < 3){
            $this->errors[] = 'Name Must Be At Least 3 char';
        }

        # email
        if(filter_var($this->email, FILTER_VALIDATE_EMAIL) === false){
            $this->errors[] = 'Email Is Invalied';
        }
        if(static::emialExsists($this->email)){
            $this->errors[] = 'Email Is Already Taken';
        }

        # password
        if(strlen($this->password) < 3){
            $this->errors[] = 'Password Must Be At Least 3 char';
        }
    }

    public static function emialExsists($email)
    {
        return static::findByEmail($email) !== false;
    }

    public static function findByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = :email ";
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        return $stmt->fetch();
    }

    public static function authenticate($email, $password)
    {
        $user = static::findByEmail($email);

        if($user){
            if(password_verify($password, $user->password_hash)){
                return $user;
            }
        }
        return false;
        
    }

    public static function findById($id)
    {
        $sql = "SELECT * FROM users WHERE id = :id ";
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        return $stmt->fetch();
    }
}