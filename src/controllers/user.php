<?php

namespace Application\Controllers\User;



require_once('./src/lib/database.php');
require_once('./src/model/user.php');

use Application\Lib\Database\DatabaseConnection;
use Application\Model\User\UserRepository;
use Exception;

/* The `class UserController` is a PHP class that contains functions for handling user-related
operations such as creating an account, checking unique email, checking if a user exists, logging
in, getting all users, validating a user, and deleting a user. It uses a `DatabaseConnection` class
and a `UserRepository` class to interact with the database and perform the necessary operations. The
`createAccount()` function checks the input data and creates a new user account if all the
conditions are met, otherwise it returns an array of errors. The other functions perform similar
operations for different user-related tasks. */

class UserController
{

    /**
     * The code contains PHP functions for creating an account, checking unique email, checking if a
     * user exists, logging in, getting all users, validating a user, and deleting a user.
     * 
     * @return The function `createAccount()` returns either the result of the `createAccount()` method
     * from the `` object if all the conditions are met, or an array of errors if any of
     * the conditions fail.
     */


    function createAccount()
    {

        $connection = new DatabaseConnection();
        $userRepository = new UserRepository();
        $userRepository->connection = $connection;

        /* The code is assigning the values of the `` superglobal variables to the corresponding
        variables. These variables are used to store the user input data for creating a new user
        account. The `` superglobal is an associative array that contains data submitted via
        an HTTP POST request. In this case, the code is retrieving the values of the `firstname`,
        `lastname`, `email`, `password`, and `confirmepassword` fields from the form submission and
        assigning them to the respective variables. */

        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmepassword = $_POST['confirmepassword'];

        /* This code block is checking the validity of the input data for creating a new user account.
        It checks if all the required fields (`firstname`, `lastname`, `email`, `password`,
        `confirmepassword`) are set and not empty. It also checks the length of the ``
        (up to 60 characters), `` (up to 60 characters), and `` (up to 100
        characters) to ensure they are within the allowed limits. */

        if ((isset($firstname) &&  strlen($firstname) > 0 && strlen($firstname) <= 60) && (isset($lastname) && strlen($lastname) > 0 && strlen($lastname) <= 60) && (isset($email) && strlen($email) > 0 && strlen($email) <= 100) && (isset($password) && strlen($password > 0)) && (isset($confirmepassword) && strlen($confirmepassword) > 0) && $password === $confirmepassword && $this->checkUniqueEmail() == 1) {

            return $userRepository->createAccount();
            
        } else {

            $errors = [];

            if (!isset($firstname) || $firstname == '') {
                $errors['firstname'] = 'Veuillez enregister votre prénom';
            }

            if (!isset($lastname) || $lastname == '') {
                $errors['lastname'] = 'Veuillez enregister votre nom';
            }

            if (!isset($email) || $email == '') {
                $errors['email'] = 'Veuillez enregister votre email';
            }

            if (!isset($password) || $password == '') {
                $errors['password'] = 'Veuillez enregister votre mot de passe';
            }

            if (!isset($confirmepassword) || $confirmepassword == '') {
                $errors['confirmepassword'] = 'Veuillez confirmer votre mot de passe';
            }
            if ($confirmepassword != $password) {
                $errors['confirmepassword'] = "Le mot de passe de confirmation n'est pas correcte";
            }

            if ($this->checkUniqueEmail() == 0) {
                $errors['email'] = 'Cet email est déjà existant';
            }

            return $errors;
        }
    }

    function checkUniqueEmail()
    {
        $connection = new DatabaseConnection();
        $userRepository = new UserRepository();
        $userRepository->connection = $connection;

        return $userRepository->checkUniqueEmail();
    }

    function checkUserExist()
    {

        $connection = new DatabaseConnection();
        $userRepository = new UserRepository();
        $userRepository->connection = $connection;

        return $userRepository->checkUserExist();
    }

    function login()
    {

        $connection = new DatabaseConnection();
        $userRepository = new UserRepository();
        $userRepository->connection = $connection;

        /* This code block is handling the login functionality. It checks if the `email` and `password`
        fields are set in the `$_POST` superglobal array, which contains data submitted via an HTTP
        POST request. If both fields are set, the code assigns their values to the `$email` and
        `$password` variables. */

        if (isset($_POST['email']) && isset($_POST['password'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];
        }

        $check = $this->checkUserExist();
        $errors = [];

        if ((isset($email) && $email != '') && (isset($password) && strlen($password) > 0 &&  $check == 1)) {
            return $userRepository->login();
        } else {

            if (!isset($email) || $email == '') {
                $errors['email'] = 'Veuillez enregister votre email';
            }
            if (!isset($password) || $password == '') {
                $errors['password'] = 'Veuillez enregister votre mot de passe';
            }
            if ($check == 0 && isset($password) && $password !== '' && isset($email) && $email !== '') {
                $errors['check'] = "L'adresse email ou le mot de passe est incorrecte";
            }
            if ($check == 2 && isset($password) && $password !== '' && isset($email) && $email !== '') {
                $errors['check'] = "Votre compte n'est pas encore validé";
            }

            return $errors;
        }
    }

    function getAllUsers()
    {

        $connection = new DatabaseConnection();
        $userRepository = new UserRepository();
        $userRepository->connection = $connection;

        return $userRepository->getAllUsers();
    }

    function validateUser($id)
    {

        $connection = new DatabaseConnection();
        $userRepository = new UserRepository();
        $userRepository->connection = $connection;

        /* The code block is checking if the `$id` variable is set, greater than 0, and of type
        integer. If all these conditions are true, it calls the `validateUser()` method of the
        `$userRepository` object and passes the `$id` as an argument. This method is responsible for
        validating a user in the database. */

        if (isset($id) && $id > 0 && is_int($id)) {
            return $userRepository->validateUser($id);
        } else {
            throw new Exception("L'ID n'est pas correcte");
        }
    }

    function deleteUser($id)
    {

        $connection = new DatabaseConnection();
        $userRepository = new UserRepository();
        $userRepository->connection = $connection;

        /* The code block is checking if the `$id` variable is set, greater than 0, and of type
        integer. If all these conditions are true, it calls the `deleteUser()` method of the
        `$userRepository` object and passes the `$id` as an argument. This method is responsible for
        deleting a user from the database. */

        if (isset($id) && $id > 0 && is_int($id)) {
            return $userRepository->deleteUser($id);
        } else {
            throw new Exception("L'ID n'est pas correcte");
        }
    }
}
