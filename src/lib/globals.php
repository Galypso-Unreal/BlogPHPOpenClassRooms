<?php 
namespace Application\Lib\Globals;

class GlobalGet{

    public static function getKey($key){
        return (isset($_GET[$key]) ? filter_input(INPUT_GET,$key) : null);
    }

}

class GlobalPost{

    public static function setPost($key, $value){
        $_POST[$key] = $value;
    }

    public static function getPost($key){
        return (isset($_POST[$key]) ? filter_input(INPUT_POST,$key) : null);
    }

    public static function getAllPost(){
        return (isset($_POST) ? filter_input_array(INPUT_POST) : null);
    }

    public static function forgetPost($key){
        unset($_POST[$key]);
    }

}

class GlobalSession{

    public static function setSession($key, $value){
        $_SESSION[$key] = $value;
    }

    public static function getSession($key){
        return (isset($_SESSION[$key]) ? filter_var_array($_SESSION[$key]) : null);
    }

    public static function getAllSession(){
        return (isset($_SESSION) ? filter_var_array($_SESSION) : null);
    }

    public static function forgetSession($key){
        unset($_SESSION[$key]);
    }

}

class GlobalServer{

    public static function setServer($key, $value){
        $_SERVER[$key] = $value;
    }

    public static function getServer($key){
        return (isset($_SERVER[$key]) ? filter_var($_SERVER[$key]) : null);
    }

    public static function getAllServer(){
        return (isset($_SERVER) ? filter_var_array($_SERVER) : null);
    }

    public static function forgetServer($key){
        unset($_SERVER[$key]);
    }

}