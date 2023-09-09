<?php

namespace Application\Model\User;

require_once './src/lib/database.php';

use Application\Lib\Database\DatabaseConnection;
use Application\Lib\Globals\GlobalPost;
use Application\Lib\Globals\GlobalSession;
use PDO;
use DateTime;
use DateTimeZone;
use Exception;

/*
    The User class represents a user with various properties such as id, lastname, firstname, email,
    password, is_valid, deleted_at, and fk_id_role.
*/

class User
{

    /*
        The line `public int $identifier;` is declaring a public property `$identifier` of type `int` in
        the `User` class. This property represents the identifier or ID of a user. It can be used to
        store and access the ID of a user object.
    */
    public int $identifier;

    /*
        The line `public string $lastname;` is declaring a public property `$lastname` of type `string`
        in the `User` class. This property represents the lastname of a user and can be used to store
        and access the lastname of a user object.
    */
    public string $lastname;

    /*
        The line `public string $firstname;` is declaring a public property `$firstname` of type
        `string` in the `User` class. This property represents the firstname of a user and can be used
        to store and access the firstname of a user object.
    */
    public string $firstname;

    /*
        The line `public string $email;` is declaring a public property `$email` of type
        `string` in the `User` class. This property represents the email of a user and can be used
        to store and access the email of a user object.
    */
    public string $email;

    /*
        The above code is declaring a public string variable named "$passowrd" in PHP.
        This is the password of user coded in SHA1
    */
    public string $password;

    /*
        The line `public bool $is_valid;` is declaring a public property named `$is_valid` of type
        `bool` in the `Comment` class. This property represents the validity status of a comment. By
        declaring it as public, it can be accessed and modified from outside the class.
    */
    public int $is_valid;

    /*
        The above code is declaring a public property named "$deleted_at" in a PHP class. The property
        has a type hint of "?string", which means it can either be a string or null.
    */
    public ?string $deleted_at;

    /*
        $fk_id_role is the ID of the role of the user. For example ID:2 is a basic user.
    */
    public int $fk_id_role;
}
//End class

/*
    The `UserRepository` class is responsible for interacting with the database to perform various
    operations related to the `User` entity. It contains methods for creating a new user account,
    checking if an email is unique, checking if a user exists, logging in a user, retrieving all users,
    retrieving a specific user, validating a user, and deleting a user. These methods use the
    `DatabaseConnection` class to establish a connection with the database and execute SQL queries.
*/
class UserRepository
{

    
    /*
     * The above code is declaring a public property named `$connection` of type `DatabaseConnection`
     * in a PHP class.
     */
    public DatabaseConnection $connection;

    /**
     * The function creates a new user account by inserting the user's information into the database.
     */
    public function createAccount(): Void
    {
        $post = new GlobalPost();

        $database = $this->connection->getConnection();

        $sql = "INSERT INTO b_user (lastname,firstname,email,password,fk_id_role) VALUES (:lastname,:firstname,:email,:password,:fk_id_role)";

        $insert = $database->prepare($sql);

        if ($post->getPost('lastname') !== null) {
            $lastname = htmlspecialchars($post->getPost('lastname'), ENT_NOQUOTES);
        }

        if ($post->getPost('firstname') !== null) {
            $firstname = htmlspecialchars($post->getPost('firstname'), ENT_NOQUOTES);
        }

        if ($post->getPost('email') !== null) {
            $email = htmlspecialchars($post->getPost('email'), ENT_NOQUOTES);
        }

        if ($post->getPost('password') !== null) {
            $password = htmlspecialchars($post->getPost('password'), ENT_NOQUOTES);
        }

        if (isset($lastname) === true && isset($firstname) === true && isset($email) === true && isset($password) === true) {
            $fk_id_role = 2;

            $chiff = sha1($password);

            $insert->bindParam(':lastname', $lastname);
            $insert->bindParam(':firstname', $firstname);
            $insert->bindParam(':email', $email);
            $insert->bindParam(':password', $chiff);
            $insert->bindParam(':fk_id_role', $fk_id_role, PDO::PARAM_INT);

            $insert->execute();
        }
    }
    /**
     * The function checks if an email is unique by querying the database for any existing records with
     * the same email.
     * @return Int an integer value. If the query returns at least one row, it will return 0.
     * Otherwise, it will return 1.
     */
    public function checkUniqueEmail(): Int
    {
        $post = new GlobalPost();

        $database = $this->connection->getConnection();

        $sql = "SELECT * FROM b_user WHERE email = :email";

        $data = $database->prepare($sql);

        $email = htmlspecialchars($post->getPost('email'), ENT_NOQUOTES);

        $data->bindParam(':email', $email);

        $data->execute();

        $row = $data->fetch(PDO::FETCH_ASSOC);

        if ($row >= 1) {
            return 0;
        } else {
            return 1;
        }

    }


    /**
     * The function `checkUserExist` checks if a user exists in the database based on their email and
     * password, and returns a value indicating the user's validity status.
     * @return Int an integer value. If the user exists and is valid, it returns 1. If the user exists
     * but is not valid, it returns 2. If the user does not exist, it returns 0.
     */
    public function checkUserExist(): Int
    {
        $post = new GlobalPost();

        $database = $this->connection->getConnection();

        $sql = "SELECT email,password,is_valid FROM b_user WHERE email=:email AND password=:password";

        $data = $database->prepare($sql);

        if ($post->getPost('email') !== null && $post->getPost('password') !== null) {
            $email = htmlspecialchars($post->getPost('email'), ENT_NOQUOTES);
            $password = htmlspecialchars($post->getPost('password'), ENT_NOQUOTES);
            $chiff = sha1($password);
        }

        $data->bindParam(':email', $email);
        $data->bindParam(':password', $chiff);

        $data->execute();

        $row = $data->fetch(PDO::FETCH_ASSOC);

        if (empty($row) === false) {
            if ($row['is_valid'] === 1) {
                return 1;
            } else {
                return 2;
            }
        } else {
            return 0;
        }

    }


    /**
     * The login function in PHP checks if the provided email and password match a user in the
     * database, and if so, sets a cookie with the user's information and returns a value indicating
     * the user's role.
     * @return Int an integer value. If the login is successful and the user has the role of an admin,
     * it returns 2. If the login is successful and the user has a regular user role, it returns 1. If
     * there is an error during the login process, it throws an exception.
     */
    public function login(): Int
    {
        $post = new GlobalPost();
        $session = new GlobalSession();

        $database = $this->connection->getConnection();

        $sql = "SELECT id,email,firstname,lastname,fk_id_role FROM b_user WHERE email=:email AND password=:password";

        $data = $database->prepare($sql);

        $email = htmlspecialchars($post->getPost('email'), ENT_NOQUOTES);
        $password = htmlspecialchars($post->getPost('password'), ENT_NOQUOTES);


        $chiff = sha1($password);

        $data->bindParam(':email', $email);
        $data->bindParam(':password', $chiff);

        $data->execute();

        $row = $data->fetch(PDO::FETCH_ASSOC);

        if (empty($row) === false) {
            if ($row['fk_id_role'] === 1) {
                $session->setSession(
                    'LOGGED_ADMIN',
                    array("id" => htmlspecialchars($row['id'], ENT_NOQUOTES),"email" => htmlspecialchars($row['email'], ENT_NOQUOTES),"firstname" => htmlspecialchars($row['firstname'], ENT_NOQUOTES),"lastname" => htmlspecialchars($row['lastname'], ENT_NOQUOTES)));
                return 2;
            } else {
                $session->setSession('LOGGED_USER', array("id" => htmlspecialchars($row['id'], ENT_NOQUOTES),"email" => htmlspecialchars($row['email'], ENT_NOQUOTES),"firstname" => htmlspecialchars($row['firstname'], ENT_NOQUOTES),"lastname" => htmlspecialchars($row['lastname'], ENT_NOQUOTES)));
                return 1;
            }
        } else {
            throw new Exception('Une erreur est survenue lors de la récupération des données');
        }
    }


    /**
     * The function getAllUsers retrieves all users from the database who are not administrators and
     * have not been deleted.
     * @return array an array of User objects.
     */
    public function getAllUsers(): array
    {

        $database = $this->connection->getConnection();

        $sql = "SELECT * FROM b_user WHERE NOT fk_id_role = 1 AND deleted_at IS NULL";

        $data = $database->prepare($sql);

        $data->execute();

        $users = [];

        while (($row = $data->fetch(PDO::FETCH_ASSOC))) {
            $user = new User();
            $user->identifier = htmlspecialchars($row['id'], ENT_NOQUOTES);
            $user->lastname = htmlspecialchars($row['lastname'], ENT_NOQUOTES);
            $user->firstname = htmlspecialchars($row['firstname'], ENT_NOQUOTES);
            $user->email = htmlspecialchars($row['email'], ENT_NOQUOTES);
            $user->is_valid = htmlspecialchars($row['is_valid'], ENT_NOQUOTES);
            $user->fk_id_role = htmlspecialchars($row['fk_id_role'], ENT_NOQUOTES);


            $users[] = $user;
        }
        return $users;
    }


    /**
     * The getUser function retrieves a user from the database with a specific role and ID, and returns
     * a User object with the user's information.
     * @return User an instance of the User class if a row is found in the database with the specified
     * conditions. If no row is found, it returns 0.
     */
    public function getUser(): User
    {

        $database = $this->connection->getConnection();

        $sql = "SELECT * FROM b_user WHERE fk_id_role = 1 AND id = :id";

        $data = $database->prepare($sql);

        $data->execute();

        $row = $data->fetch(PDO::FETCH_ASSOC);

        if (empty($row) === false) {
            $user = new User();
            $user->identifier = htmlspecialchars($row['id'], ENT_NOQUOTES);
            $user->firstname = htmlspecialchars($row['firstname'], ENT_NOQUOTES);
            $user->lastname = htmlspecialchars($row['lastname'], ENT_NOQUOTES);

            return $user;
        }
        return 0;

    }


    /**
     * The function updates the "is_valid" field of a user in the "b_user" table to 1, indicating that
     * the user is valid.
     * @param int $identifier Identifier
     * The parameter "$identifier" is an integer representing the user's ID.
     */
    public function validateUser(int $identifier)
    {

        $database = $this->connection->getConnection();

        $sql = "UPDATE b_user SET is_valid = 1 WHERE id=:id";

        $data = $database->prepare($sql);

        $data->bindParam(':id', $identifier, PDO::PARAM_INT);

        $data->execute();

    }


    /**
     * The deleteUser function updates the deleted_at column of the b_user table with the current date
     * and time for a specific user ID.
     * @param int $identifier Identifier
     * The parameter "$identifier" is an integer that represents the ID of the user that needs to be deleted from the database.
     */
    public function deleteUser(int $identifier)
    {

        $database = $this->connection->getConnection();

        $date = new DateTime('now', new DateTimeZone('Europe/Paris'));
        $datef = htmlspecialchars($date->format('Y-m-d H:i:s'), ENT_NOQUOTES);

        $sql = "UPDATE b_user SET deleted_at=:deleted_at WHERE id=:id";

        $delete = $database->prepare($sql);

        $delete->bindParam('deleted_at', $datef);
        $delete->bindParam('id', $identifier, PDO::PARAM_INT);

        $delete->execute();

    }

    
}
