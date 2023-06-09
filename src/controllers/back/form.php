<?php
// checking all varaibles in form

function confirmationForm(){
    //if 
    if(!isset($_POST['firstname']) || !isset($_POST['lastname']) || !isset($_POST['email']) || !isset($_POST['message']) || $_POST['message'] == ''){
        echo "Le formulaire n'est pas valide il vous faut : un nom, un prénom, un email et un message";
        return 0;
    }
    else{
        if(strlen($_POST['firstname']) > 50){
            echo "Votre prénom est trop long";
            return 0;
        }
        
        elseif(strlen($_POST['lastname'] ) > 50){
            echo "Votre nom est trop long";
            return 0;
        }

        elseif(strlen($_POST['email'] ) > 100){
            echo "Votre email est trop long";
            return 0;
        }

        elseif(strlen($_POST['message'] ) > 500){
            echo "Votre message est trop long";
            return 0;
        }
        return 1;
    }
}
