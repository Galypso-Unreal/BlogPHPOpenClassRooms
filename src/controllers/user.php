<?php 

function confirmationCreateAccount(){

    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmepassword = $_POST['confirmepassword'];

    if((isset($firstname) && $firstname != '') || (isset($lastname) && $lastname != '') || (isset($email) && $email != '') || (isset($password) && $password != '') || (isset($confirmepassword) && $confirmepassword != '')){
        return 1;
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

        return $errors;
    }
    
}
function createAccount(string $firstname, string $lastname, string $email, string $password){
    $database = new DatabaseConnection();
    $db = $database->getConnection();


    
}