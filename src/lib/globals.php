<?php

namespace Application\Lib\Globals;

class GlobalGet
{

    /**
     * The function `getKey` retrieves a value from the `$_GET` superglobal array using the provided
     * key, and returns it after filtering it.
     * @param string $key Key
     * is the name of the key that you want to retrieve from the superglobal array.
     *
     * @return mixed value of the specified key from the  array if it is set, otherwise it returns
     * null.
     */
    public static function getKey($key)
    {
        return (isset($_GET[$key]) === true ? filter_input(INPUT_GET, $key) : null);

    }

}

class GlobalPost
{

    /**
     * The function sets a value in the  array using a specified key.
     * @param string $key Key
     * parameter is a string that represents the name of the key in the  array where the value will be stored.
     *
     * @param mixed $value Value
     * parameter is the value that you want to set for the given key in the
     *  array.
     */
    public static function setPost($key, $value)
    {
        $_POST[$key] = $value;

    }


    /**
     * The function retrieves a value from the  array using the given key, and filters it for
     * security purposes.
     * @param string $key Key
     * parameter is the name of the input field in the HTML form that you want to retrieve the value for.
     *
     * @return mixed value of the specified key from the  array if it is set, otherwise it returns
     * null.
     */
    public static function getPost($key)
    {
        return (isset($_POST[$key]) === true ? filter_input(INPUT_POST, $key) : null);

    }


    /**
     * The function returns all the POST data, if it exists, after filtering it.
     * @return array filtered input array of the  superglobal variable if it is set, otherwise it
     * returns null.
     */
    public static function getAllPost()
    {
        return (isset($_POST) === true ? filter_input_array(INPUT_POST) : null);

    }


    /**
     * The function "forgetPost" in PHP is used to remove a specific key-value pair from the
     * superglobal array.
     * @param string $key Key
     * parameter is the name of the post variable that you want to remove from the array.
     */
    public static function forgetPost($key)
    {
        unset($_POST[$key]);

    }


}

class GlobalSession
{


    /**
     * The function sets a value in the PHP session using a given key.
     * @param string $key Key
     * is a string that represents the name of the session variable you want to set. It is used to identify the session variable and retrieve its value later.
     *
     * @param mixed $value Value
     * parameter is the value that you want to store in the session variable.
     */
    public static function setSession($key, $value)
    {
        $_SESSION[$key] = $value;

    }


    /**
     * The function sets a value in the PHP session using a given key.
     * @param string $key Key
     * is a string that represents the name of the session variable you want to set. It is used to identify the session variable and retrieve its value later.
     */
    public static function getSession($key)
    {
        return (isset($_SESSION[$key]) === true ? filter_var_array($_SESSION[$key]) : null);

    }


    /**
     * The function returns all session variables if they exist, otherwise it returns null.
     * @return array value of  after filtering it using filter_var_array. If  is set,
     * it will return the filtered array. If  is not set, it will return null.
     */
    public static function getAllSession()
    {
        return (isset($_SESSION) === true ? filter_var_array($_SESSION) : null);

    }


    /**
     * The forgetSession function in PHP is used to remove a specific key from the session array.
     * @param string $key Key
     * parameter is the name of the session variable that you want to remove from the  array.
     */
    public static function forgetSession($key)
    {
        unset($_SESSION[$key]);

    }


}

class GlobalServer
{

    /**
     * The function sets a value in the  superglobal array using the provided key.
     * @param string $key Key
     * parameter is a string that represents the name of the server variable that you want to set.
     *
     * @param mixed $value Value
     * parameter is the value that you want to set for the specified key in the
     *  array.
     */
    public static function setServer($key, $value)
    {
        $_SERVER[$key] = $value;

    }


    /**
     * The function "getServer" retrieves a value from the  superglobal array based on the
     * provided key, and returns it after applying the filter_var function.
     * @param string $key Key
     * parameter is a string that represents the name of the server variable that you want to retrieve.
     *
     * @return mixed value of the [$key] if it is set, otherwise it is returning null.
     */
    public static function getServer($key)
    {
        return (isset($_SERVER[$key]) === true ? filter_var($_SERVER[$key]) : null);

    }


    /**
     * The function returns all server variables if they exist, otherwise it returns null.
     * @return array  array after filtering its values using the filter_var_array() function.
     */
    public static function getAllServer()
    {
        return (isset($_SERVER) === true ? filter_var_array($_SERVER) : null);

    }


    /**
     * The function "forgetServer" in PHP is used to remove a specific key from the 
     * superglobal array.
     * @param string $key Key
     * parameter is the name of the server variable that you want to remove from the array.
     */
    public static function forgetServer($key)
    {
        unset($_SERVER[$key]);
        
    }

    
}
