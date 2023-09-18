<?php
// Checking all inputs in form.
namespace Application\Controllers\Form;

use Exception;
use Application\Lib\Globals\GlobalPost;



/*
    The `class FormController` is a PHP class that contains two functions: `confirmationForm()` and
    `sendMailContact()`.
*/

class FormController
{

    /**
     * The function checks if a form is valid by verifying if all required fields are set and if the
     * length of each field is within the specified limits.
     * @return int 0 or 1.
     */
    
    public function confirmationForm()
    {
        $post = new GlobalPost();

        if ($post->getPost('firstname') !== null) {
            $firstname = htmlspecialchars($post->getPost('firstname'), ENT_NOQUOTES);
        }

        if ($post->getPost('lastname') !== null) {
            $lastname = htmlspecialchars($post->getPost('lastname'), ENT_NOQUOTES);
        }

        if ($post->getPost('email') !== null) {
            $email = htmlspecialchars($post->getPost('email'), ENT_NOQUOTES);
        }

        if ($post->getPost('message') !== null) {
            $message = htmlspecialchars($post->getPost('message'), ENT_NOQUOTES);
        }
        
        if ((isset($firstname) === true && strlen($firstname) <= 50 === true && strlen($firstname) > 0 === true) && (isset($lastname) === true && strlen($lastname) <= 50 === true && strlen($lastname) > 0 === true) && (isset($email) === true && strlen($email) <= 100 === true && strlen($email) > 0 === true) && (isset($message) === true && strlen($message) <= 500 === true && strlen($message) > 0 === true)) {
            return 1;

        } else {
            $errors = [];

            // Check existing values

            if(isset($firstname)){
                $errors['old_firstname'] = $firstname;
            }
            if(isset($lastname)){
                $errors['old_lastname'] = $lastname;
            }
            if(isset($email)){
                $errors['old_email'] = $email;
            }
            if(isset($message)){
                $errors['old_message'] = $message;
            }
            
            // Check form strlen


            if (strlen($firstname) > 50 === true) {
                $errors['firstname'] = "Votre prénom est trop long";
            }
            
            if (strlen($lastname) > 50 === true) {
                $errors['lastname'] = "Votre nom est trop long";
            }
            
            if (strlen($email) > 100 === true) {
                $errors['email'] = "Votre email est trop long";
            }
            
            if (strlen($message) > 500 === true) {
                $errors['message'] = "Votre message est trop long";
            }
            
            // Check form not empty

            if (strlen($firstname) === 0) {
                $errors['firstname'] = "Votre prénom est vide";
            }
            
            if (strlen($lastname) === 0) {
                $errors['lastname'] = "Votre nom est vide";
            }
            
            if (strlen($email) === 0) {
                $errors['email'] = "Votre email est vide";
            }
            
            if (strlen($message) === 0) {
                $errors['message'] = "Votre message est vide";
            }
            return $errors;

        }
        // End if
    }
    //End confirmationForm()

    /**
     * The function `sendMailContact` sends an email with contact information and a message if all
     * required fields are filled out.
     * @return an array called , which contains the values of the variables firstname,
     * lastname, email, and message.
     */
    public function sendMailContact()
    {
        $post = new GlobalPost();

        if ($post->getPost('firstname') !== null) {
            $firstname = htmlspecialchars($post->getPost('firstname'), ENT_NOQUOTES);
        }

        if ($post->getPost('lastname') !== null) {
            $lastname = htmlspecialchars($post->getPost('lastname'), ENT_NOQUOTES);
        }

        if ($post->getPost('email') !== null) {
            $email = htmlspecialchars($post->getPost('email'), ENT_NOQUOTES);
        }

        if ($post->getPost('message') !== null) {
            $message = htmlspecialchars($post->getPost('message'), ENT_NOQUOTES);
        }

        if (isset($firstname) === true && $firstname !== '' && isset($lastname) === true && $lastname !== '' && isset($email) === true && $email !== '' && isset($message) === true && $message !== '') {
            $informations = array('firstname' => $firstname,'lastname' => $lastname,'email' => $email,'message' => $message);

            $toMail = 'johann.moser0681@gmail.com';
            $subject = 'Demande de contact';


            $message = 'Prénom : ' . $firstname . PHP_EOL . 'Nom : ' . $lastname . PHP_EOL . 'Email : ' . $email . PHP_EOL . 'Message : ' . $message . PHP_EOL;
            mail($toMail, $subject, $message);
            return $informations;
        } else {
            throw new Exception("L'envoi du mail n'est pas valide. Un message, prénom, nom et une adresse email est obligatoire pour l'envoi du mail");
        }
        
    }


}
