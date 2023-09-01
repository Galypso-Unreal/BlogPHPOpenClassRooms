<?php 
namespace Application\Lib\Globals;

class GlobalPost{

    public static function setPost($key, $value){
        $_POST[$key] = $value;
    }

    public static function getPost($key){
        return (isset($_POST[$key]) ? filter_input(INPUT_POST,$key) : null);
    }

    public static function forgetPost($key){
        unset($_POST[$key]);
    }

}