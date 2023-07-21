<?php 

namespace Application\Controllers\User;



require_once('./src/lib/database.php');
require_once('./src/model/user.php');

use Application\Lib\Database\DatabaseConnection;
use Application\Model\User\UserRepository;
use Exception;

class UserController{

    function createAccount(){

        $connection = new DatabaseConnection();
        $userRepository = new UserRepository();
        $userRepository->connection = $connection;

        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmepassword = $_POST['confirmepassword'];
    
        if((isset($firstname) &&  strlen($firstname) > 0 && strlen($firstname) <=60) && (isset($lastname) && strlen($lastname) > 0 && strlen($lastname) <= 60) && (isset($email) && strlen($email) > 0 && strlen($email) <= 100) && (isset($password) && strlen($password > 0)) && (isset($confirmepassword) && strlen($confirmepassword) > 0) && $password === $confirmepassword){

            return $userRepository->createAccount();
            
        }
        else{
    
            $errors = [];
    
            if(!isset($firstname) || $firstname == ''){
                $errors['firstname'] = 'Veuillez enregister votre prénom';
            }
    
            if(!isset($lastname) || $lastname == ''){
                $errors['lastname'] = 'Veuillez enregister votre nom';
            }
    
            if(!isset($email) || $email == ''){
                $errors['email'] = 'Veuillez enregister votre email';
            }
    
            if(!isset($password) || $password == ''){
                $errors['password'] = 'Veuillez enregister votre mot de passe';
            }
    
            if(!isset($confirmepassword) || $confirmepassword == ''){
                $errors['confirmepassword'] = 'Veuillez confirmer votre mot de passe';
            }
            if($confirmepassword != $password){
                $errors['confirmepassword'] = "Le mot de passe de confirmation n'est pas correcte";
            }
    
            return $errors;
        }
        
    }

    function login(){

        $connection = new DatabaseConnection();
        $userRepository = new UserRepository();
        $userRepository->connection = $connection;

        $email = $_POST['email'];
        $password = $_POST['password'];

    
        if((isset($email) && $email != '') || (isset($password) && $password != '')){
            return $userRepository->createAccount();
        }
        else{
    
            $errors = [];
    
            if(!isset($email) || $email == ''){
                $errors['email'] = 'Veuillez enregister votre email';
            }
    
            if(!isset($password) || $password == ''){
                $errors['password'] = 'Veuillez enregister votre mot de passe';
            }
    
            return $errors;
        }
    }

    function getAllUsers(){

        $connection = new DatabaseConnection();
        $userRepository = new UserRepository();
        $userRepository->connection = $connection;

        return $userRepository->getAllUsers();
    }

    function validateUser($id){

        $connection = new DatabaseConnection();
        $userRepository = new UserRepository();
        $userRepository->connection = $connection;

        if(isset($id) && $id > 0 && is_int($id)){
           return $userRepository->validateUser($id); 
        }
        else{
            throw new Exception("L'ID n'est pas correcte");
        }
        
    }

    function deleteUser($id){

        $connection = new DatabaseConnection();
        $userRepository = new UserRepository();
        $userRepository->connection = $connection;

        if(isset($id) && $id > 0 && is_int($id)){
           return $userRepository->deleteUser($id); 
        }
        else{
            throw new Exception("L'ID n'est pas correcte");
        }
        
    }

}

