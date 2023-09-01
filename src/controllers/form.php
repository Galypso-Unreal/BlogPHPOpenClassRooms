<?php
// checking all varaibles in form
namespace Application\Controllers\Form;

use Exception;
use Application\Lib\Globals\GlobalPost;

require './src/lib/globals.php';

/* The `class FormController` is a PHP class that contains two functions: `confirmationForm()` and
`sendMailContact()`. */

class FormController
{

    /**
     * The function checks if a form is valid by verifying if all required fields are set and if the
     * length of each field is within the specified limits.
     * 
     * @return int 0 or 1.
     */

    function confirmationForm()
    {
        $post = new GlobalPost();


        if ( $post->getPost('firstname') === null || $post->getPost('lastname') === null || $post->getPost('email') === null || $post->getPost('message') === null ) {
            echo "Le formulaire n'est pas valide il vous faut : un nom, un prénom, un email et un message";
            return 0;
        } else {
            
            if($post->getPost('firstname') !== null){
               $firstname = htmlspecialchars($post->getPost('firstname'),ENT_NOQUOTES);
            }

            if($post->getPost('lastname') !== null){
                $lastname = htmlspecialchars($post->getPost('lastname'),ENT_NOQUOTES);
            }

            if($post->getPost('email') !== null){
                $email = htmlspecialchars($post->getPost('email'),ENT_NOQUOTES);
            }

            if($post->getPost('message') !== null){
                $message = htmlspecialchars($post->getPost('message'),ENT_NOQUOTES); 
            }
            if (strlen((int) $firstname > 50)) {
                echo "Votre prénom est trop long";
                return 0;
            } elseif (strlen($lastname) > 50) {
                echo "Votre nom est trop long";
                return 0;
            } elseif (strlen($email) > 100) {
                echo "Votre email est trop long";
                return 0;
            } elseif (strlen($message) > 500) {
                echo "Votre message est trop long";
                return 0;
            }
            return 1;
        }
    }

    /**
     * The function `sendMailContact` sends an email with contact information and a message if all
     * required fields are filled out.
     * 
     * @return an array called , which contains the values of the variables firstname,
     * lastname, email, and message.
     */

    function sendMailContact()
    {
        $post = new GlobalPost();

        if($post->getPost('firstname') !== null){
            $firstname = htmlspecialchars($post->getPost('firstname'), ENT_NOQUOTES);
        }

        if($post->getPost('lastname') !== null){
            $lastname = htmlspecialchars($post->getPost('lastname'), ENT_NOQUOTES);
        }

        if($post->getPost('email') !== null){
            $email = htmlspecialchars($post->getPost('email'), ENT_NOQUOTES);
        }

        if($post->getPost('message') !== null){
            $message = htmlspecialchars($post->getPost('message'), ENT_NOQUOTES);
        }
        
        
        
        if (isset($firstname) && $firstname != '' && isset($lastname) && $lastname != '' && isset($email) && $email != '' && isset($message) && $message != '') {

        $informations = array(
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'message' => $message
        );

        $to = 'johann.moser0681@gmail.com';
        $subject = 'Demande de contact';


        $message = 'Prénom : ' . $firstname . PHP_EOL . 'Nom : ' . $lastname . PHP_EOL . 'Email : ' . $email . PHP_EOL . 'Message : ' . $message . PHP_EOL;
        mail($to, $subject, $message);
        return $informations;

        } else {
            throw new Exception("L'envoi du mail n'est pas valide. Un message, prénom, nom et une adresse email est obligatoire pour l'envoi du mail");
        }
    }
}
