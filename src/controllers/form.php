<?php
// checking all varaibles in form
namespace Application\Controllers\Form;

use Exception;

class FormController
{

    function confirmationForm()
    {
        //if 
        if (!isset($_POST['firstname']) || !isset($_POST['lastname']) || !isset($_POST['email']) || !isset($_POST['message']) || $_POST['message'] == '') {
            echo "Le formulaire n'est pas valide il vous faut : un nom, un prénom, un email et un message";
            return 0;
        } else {
            if (strlen($_POST['firstname']) > 50) {
                echo "Votre prénom est trop long";
                return 0;
            } elseif (strlen($_POST['lastname']) > 50) {
                echo "Votre nom est trop long";
                return 0;
            } elseif (strlen($_POST['email']) > 100) {
                echo "Votre email est trop long";
                return 0;
            } elseif (strlen($_POST['message']) > 500) {
                echo "Votre message est trop long";
                return 0;
            }
            return 1;
        }
    }

    function sendMailContact()
    {

        $firstname = htmlspecialchars($_POST['firstname'], ENT_NOQUOTES);
        $lastname = htmlspecialchars($_POST['lastname'], ENT_NOQUOTES);
        $email = htmlspecialchars($_POST['email'], ENT_NOQUOTES);
        $message = htmlspecialchars($_POST['message'], ENT_NOQUOTES);

        $informations = array(
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'message' => $message
        );

        $to = 'johann.moser0681@gmail.com';
        $subject = 'Demande de contact';

        if (isset($firstname) && $firstname != '' && isset($lastname) && $lastname != '' && isset($email) && $email != '' && isset($message) && $message != '') {
            $message = 'Prénom : ' . $firstname . PHP_EOL . 'Nom : ' . $lastname . PHP_EOL . 'Email : ' . $email . PHP_EOL . 'Message : ' . $message . PHP_EOL;
            mail($to, $subject, $message);
            return $informations;
        } else {
            throw new Exception("L'envoi du mail n'est pas valide. Un message, prénom, nom et une adresse email est obligatoire pour l'envoi du mail");
        }
    }
}
