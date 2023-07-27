<?php
namespace Application\Model\User;

require_once('./src/lib/database.php');

use Application\Lib\Database\DatabaseConnection;
use PDO;
use DateTime;
use DateTimeZone;


class User{

    public int $id;
    public string $lastname;
    public string $firstname;
    public string $email;
    public string $password;
    public int $is_valid;
    public ?string $deleted_at;
    public int $fk_id_role;

}

class UserRepository{

    public DatabaseConnection $connection;

    function createAccount(){

        $db = $this->connection->getConnection();

        $sql = "INSERT INTO b_user (lastname,firstname,email,password,is_valid,fk_id_role) VALUES (:lastname,:firstname,:email,:password,:is_valid,:fk_id_role)";

        $insert = $db->prepare($sql);

        $lastname = $_POST['lastname'];
        $firstname = $_POST['firstname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $is_valid = 0;
        $fk_id_role = 2;


        $insert->bindParam(':lastname',$lastname);
        $insert->bindParam(':firstname',$firstname);
        $insert->bindParam(':email',$email);
        $insert->bindParam(':password',$password);
        $insert->bindParam(':is_valid',$is_valid);
        $insert->bindParam(':fk_id_role',$fk_id_role);

        $insert->execute();

    }

    function login(){

        $db = $this->connection->getConnection();

        $sql = "SELECT email,password FROM b_user WHERE email=:email AND password=:password";

        $data = $db->prepare($sql);

        $email = $_POST['mail'];
        $password = $_POST['password'];

        $data->bindParam(':email',$lastname);
        $data->bindParam(':password',$lastname);

        $data->execute();

    }

    function getAllUsers(){

        $db = $this->connection->getConnection();

        $sql = "SELECT * FROM b_user WHERE NOT fk_id_role = 1 AND deleted_at IS NULL";

        $data = $db->prepare($sql);

        $data->execute();

        $users = [];
    
        while(($row = $data->fetch(PDO::FETCH_ASSOC))){
            $user = new User();

            $user->id =     $row['id'];
            $user->lastname =     $row['lastname'];
            $user->firstname =     $row['firstname'];
            $user->email =    $row['email'];
            $user->is_valid =    $row['is_valid'];
            $user->fk_id_role =    $row['fk_id_role'];
            
            
            $users[] = $user;
        }
        return $users;

    }
    function validateUser($id){

        $db = $this->connection->getConnection();

        $sql = "UPDATE b_user SET is_valid = 1 WHERE id=:id";

        $data = $db->prepare($sql);

        $data->bindParam(':id',$id);

        $data->execute();

    }

    function deleteUser($id){

        $db = $this->connection->getConnection();

        $date = new DateTime('now', new DateTimeZone('Europe/Paris'));
        $datef = $date->format('Y-m-d H:i:s');

        $sql = "UPDATE b_user SET deleted_at=:deleted_at WHERE id=:id";

        $delete = $db->prepare($sql);

        $delete->bindParam('deleted_at',$datef);
        $delete->bindParam('id',$id);

        $delete->execute();
    
}

}