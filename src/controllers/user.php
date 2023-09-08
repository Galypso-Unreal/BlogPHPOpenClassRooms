<?php

namespace Application\Controllers\User;



require_once './src/lib/database.php';
require_once './src/model/user.php';

use Application\Lib\Database\DatabaseConnection;
use Application\Lib\Globals\GlobalPost;
use Application\Model\User\UserRepository;
use Exception;

/*
    The `class UserController` is a PHP class that contains functions for handling user-related
    operations such as creating an account, checking unique email, checking if a user exists, logging
    in, getting all users, validating a user, and deleting a user. It uses a `DatabaseConnection` class
    and a `UserRepository` class to interact with the database and perform the necessary operations. The
    `createAccount()` function checks the input data and creates a new user account if all the
    conditions are met, otherwise it returns an array of errors. The other functions perform similar
    operations for different user-related tasks.
*/

class UserController
{

    /**
     * The code contains PHP functions for creating an account, checking unique email, checking if a
     * user exists, logging in, getting all users, validating a user, and deleting a user.
     * @return The function `createAccount()` returns either the result of the `createAccount()` method
     * from the `$userRepository` object if all the conditions are met, or an array of errors if any of
     * the conditions fail.
     */


    /**
     * The function `createAccount()` is responsible for creating a new user account by validating the
     * input data and checking for any errors.
     * @return The function `createAccount()` returns either the result of the `createAccount()` method
     * from the `$userRepository` object if all the input data is valid and the email is unique, or an
     * array of errors if any of the input data is invalid or the email is not unique.
     */

     
    public function createAccount()
    {

        $connection = new DatabaseConnection();
        $userRepository = new UserRepository();
        $userRepository->connection = $connection;
        $post = new GlobalPost();

        /*
            The code is assigning the values of the `$post` superglobal variables to the corresponding
            variables. These variables are used to store the user input data for creating a new user
            account. The `$post` superglobal is an associative array that contains data submitted via
            an HTTP POST request. In this case, the code is retrieving the values of the `firstname`,
            `lastname`, `email`, `password`, and `confirmepassword` fields from the form submission and
            assigning them to the respective variables.
        */

        if ($post->getPost('firstname') !== null) {
            $firstname = $post->getPost('firstname');
        }

        if ($post->getPost('lastname') !== null) {
            $lastname = $post->getPost('lastname');
        }

        if ($post->getPost('email') !== null) {
            $email = $post->getPost('email');
        }

        if ($post->getPost('password') !== null) {
            $password = $post->getPost('password');
        }

        if ($post->getPost('confirmepassword') !== null) {
            $confirmepassword = $post->getPost('confirmepassword');
        }

        /*
            This code block is checking the validity of the input data for creating a new user account.
            It checks if all the required fields (`firstname`, `lastname`, `email`, `password`,
            `confirmepassword`) are set and not empty. It also checks the length of the `$firstname`
            (up to 60 characters), `$lastname` (up to 60 characters), and `$email` (up to 100
            characters) to ensure they are within the allowed limits.
        */

        if ((isset($firstname) === true &&  strlen($firstname) > 0 === true && strlen($firstname) <= 60 === true) && (isset($lastname) === true && strlen($lastname) > 0 === true && strlen($lastname) <= 60 === true) && (isset($email) === true && strlen($email) > 0 === true && strlen($email) <= 100 === true) && (isset($password) === true && strlen($password) > 0 === true) && (isset($confirmepassword) === true && strlen($confirmepassword) > 0 === true) && $password === $confirmepassword && $this->checkUniqueEmail() === 1) {

            return $userRepository->createAccount();
        } else {

            $errors = [];

            if (isset($firstname) === false || $firstname === '') {
                $errors['firstname'] = 'Veuillez enregister votre prénom';
            }

            if (isset($lastname) === false || $lastname === '') {
                $errors['lastname'] = 'Veuillez enregister votre nom';
            }

            if (isset($email) === false || $email === '') {
                $errors['email'] = 'Veuillez enregister votre email';
            }

            if (isset($password) === false || $password === '') {
                $errors['password'] = 'Veuillez enregister votre mot de passe';
            }

            if (isset($confirmepassword) === false || $confirmepassword === '') {
                $errors['confirmepassword'] = 'Veuillez confirmer votre mot de passe';
            }
            if ($confirmepassword !== $password) {
                $errors['confirmepassword'] = "Le mot de passe de confirmation n'est pas correcte";
            }

            if ($this->checkUniqueEmail() === 0) {
                $errors['email'] = 'Cet email est déjà existant';
            }

            return $errors;
        }

    }


    /**
     * The function checks if an email is unique by using a database connection and a user repository.
     * 
     * @return the result of the checkUniqueEmail() method from the UserRepository class.
     */


    public function checkUniqueEmail()
    {
        $connection = new DatabaseConnection();
        $userRepository = new UserRepository();
        $userRepository->connection = $connection;

        return $userRepository->checkUniqueEmail();

    }


    /**
     * The function checks if a user exists in the database.
     * 
     * @return the result of the checkUserExist() method from the UserRepository class.
     */


    public function checkUserExist()
    {

        $connection = new DatabaseConnection();
        $userRepository = new UserRepository();
        $userRepository->connection = $connection;

        return $userRepository->checkUserExist();

    }


    /**
     * The `login()` function handles the login functionality by checking if the email and password
     * fields are set, validating the user's credentials, and returning any errors encountered during
     * the process.
     * @return either the result of the login attempt (if successful) or an array of errors (if there
     * are any validation issues or the user does not exist).
     */


    public function login()
    {

        $connection = new DatabaseConnection();
        $userRepository = new UserRepository();
        $userRepository->connection = $connection;
        $post = new GlobalPost();

        /*
            This code block is handling the login functionality. It checks if the `email` and `password`
            fields are set in the `$_POST` superglobal array, which contains data submitted via an HTTP
            POST request. If both fields are set, the code assigns their values to the `$email` and
            `$password` variables.
        */

        if ($post->getPost('email') !== null && $post->getPost('password') !== null) {
            $email = $post->getPost('email');
            $password = $post->getPost('password');
        }

        $check = $this->checkUserExist();
        $errors = [];

        if ((isset($email) === true && $email !== '') && (isset($password) === true && strlen($password) > 0 === true &&  $check === 1)) {
            return $userRepository->login();
        } else {

            if (isset($email) === false || $email === '') {
                $errors['email'] = 'Veuillez enregister votre email';
            }
            if (isset($password) === false || $password === '') {
                $errors['password'] = 'Veuillez enregister votre mot de passe';
            }
            if ($check === 0 && isset($password) === true && $password !== '' && isset($email) === true && $email !== '') {
                $errors['check'] = "L'adresse email ou le mot de passe est incorrecte";
            }
            if ($check === 2 && isset($password) === true && $password !== '' && isset($email) === true && $email !== '') {
                $errors['check'] = "Votre compte n'est pas encore validé";
            }

            return $errors;
        }

    }


    /**
     * The function retrieves all users from the database using a UserRepository object and a
     * DatabaseConnection object.
     * @return The getAllUsers() function is returning all the users from the UserRepository.
     */


    public function getAllUsers()
    {

        $connection = new DatabaseConnection();
        $userRepository = new UserRepository();
        $userRepository->connection = $connection;

        return $userRepository->getAllUsers();

    }


    /**
     * The `validateUser()` function checks if the given identifier is valid and calls the
     * corresponding method in the UserRepository to validate the user in the database.
     * @param int The `$identifier` parameter is used to identify a user in the database. It is expected to be an integer value greater than 0.
     * 
     * @return The code is returning the result of the `validateUser()` method of the `$userRepository`
     * object.
     */


    public function validateUser($identifier)
    {

        $connection = new DatabaseConnection();
        $userRepository = new UserRepository();
        $userRepository->connection = $connection;

        /*
            The code block is checking if the `$identifier` variable is set, greater than 0, and of type
            integer. If all these conditions are true, it calls the `validateUser()` method of the
            `$userRepository` object and passes the `$identifier` as an argument. This method is responsible for
            validating a user in the database.
        */

        if (isset($identifier) === true && $identifier > 0 === true && is_int($identifier) === true) {
            return $userRepository->validateUser($identifier);
        } else {
            throw new Exception("L'ID n'est pas correcte");
        }

    }


    /**
     * The `deleteUser()` function checks if the provided identifier is valid and calls the
     * corresponding method to delete the user from the database.
     * @param int The `$identifier` parameter is used to identify the user that needs to be deleted from the database. It should be an integer value representing the unique identifier of the user.
     * 
     * @return The code is returning the result of the `deleteUser()` method of the `$userRepository`
     * object.
     */


    public function deleteUser($identifier)
    {

        $connection = new DatabaseConnection();
        $userRepository = new UserRepository();
        $userRepository->connection = $connection;

        /*
            The code block is checking if the `$identifier` variable is set, greater than 0, and of type
            integer. If all these conditions are true, it calls the `deleteUser()` method of the
            `$userRepository` object and passes the `$identifier` as an argument. This method is responsible for
            deleting a user from the database.
        */

        if (isset($identifier) === true && $identifier > 0 === true && is_int($identifier) === true) {
            return $userRepository->deleteUser($identifier);
        } else {
            throw new Exception("L'ID n'est pas correcte");
        }
        
    }

    
}
