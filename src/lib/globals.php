<?php 

namespace Application\Lib\Globals;

class GlobalPost{


    public static function setPost($key, $value){
        $_POST[$key] = $value;
    }

    public static function getPost($key){
        return (isset($_POST[$key]) ? $_POST[$key] : null);
    }
}