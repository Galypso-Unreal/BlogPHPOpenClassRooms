<?php
namespace Application\Model\User;

require_once('./src/lib/database.php');

use Application\Lib\Database\DatabaseConnection;
use PDO;
use DateTime;
use DateTimeZone;
use Exception;

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

        $sql = "INSERT INTO b_user (lastname,firstname,email,password,fk_id_role) VALUES (:lastname,:firstname,:email,:password,:fk_id_role)";

        $insert = $db->prepare($sql);

        $lastname = htmlspecialchars($_POST['lastname'],ENT_NOQUOTES);
        $firstname = htmlspecialchars($_POST['firstname'],ENT_NOQUOTES);
        $email = htmlspecialchars($_POST['email'],ENT_NOQUOTES);
        $password = htmlspecialchars($_POST['password'],ENT_NOQUOTES);
        $fk_id_role = 2;

        $chiff = md5($password);

        $insert->bindParam(':lastname',$lastname);
        $insert->bindParam(':firstname',$firstname);
        $insert->bindParam(':email',$email);
        $insert->bindParam(':password',$chiff);
        $insert->bindParam(':fk_id_role',$fk_id_role);

        $insert->execute();

    }

    function checkUniqueEmail(){
        $db = $this->connection->getConnection();

        $sql = "SELECT * FROM b_user WHERE email = :email";

        $data = $db->prepare($sql);

        $email = htmlspecialchars($_POST['email'],ENT_NOQUOTES);

        $data->bindParam(':email',$email);

        $data->execute();

        $row = $data->fetch(PDO::FETCH_ASSOC);
    
        if($row >= 1){
            return 0;
        }
        else{
            return 1;
        }
    }

    function checkUserExist(){
        $db = $this->connection->getConnection();

        $sql = "SELECT email,password,is_valid FROM b_user WHERE email=:email AND password=:password";

        $data = $db->prepare($sql);

        if(isset($_POST['email']) && $_POST['password']){
            $email = htmlspecialchars($_POST['email'],ENT_NOQUOTES);
            $password = htmlspecialchars($_POST['password'],ENT_NOQUOTES);
            $chiff = md5($password);
        }
        
        
        

        $data->bindParam(':email',$email);
        $data->bindParam(':password',$chiff);

        $data->execute();

        $row = $data->fetch(PDO::FETCH_ASSOC);

        if($row){
            if($row['is_valid'] == 1){
                return 1;
            }
            else{
                return 2;
            }
            
        }
        else{
            return 0;
        }
    }

    function login(){
        
        $db = $this->connection->getConnection();

        $sql = "SELECT id,email,firstname,lastname,fk_id_role FROM b_user WHERE email=:email AND password=:password";

        $data = $db->prepare($sql);

        $email = htmlspecialchars($_POST['email'],ENT_NOQUOTES);
        $password = htmlspecialchars($_POST['password'],ENT_NOQUOTES);
        
        $chiff = md5($password);

        $data->bindParam(':email',$email);
        $data->bindParam(':password',$chiff);

        $data->execute();

        $row = $data->fetch(PDO::FETCH_ASSOC);

        if($row){
            
            if($row['fk_id_role'] == 1){

            
            setcookie(
                'LOGGED_ADMIN',
                json_encode(array(
                    "id"=>$row['id'],
                    "email"=>$row['email'],
                    "firstname"=>$row['firstname'],
                    "lastname"=>$row['lastname'] 
                )),
                time()+3600*2,'/'
            );
            
            return 2;
            
            }
            else{
                setcookie(
                    'LOGGED_USER',
                    json_encode(array(
                        "id"=>$row['id'],
                        "email"=>$row['email'],
                        "firstname"=>$row['firstname'],
                        "lastname"=>$row['lastname'] 
                    )),
                    time()+3600*2,'/'
                );
                return 1;
            }
            
            
        }
        else{
            throw new Exception('Une erreur est survenue lors de la récupération des données');
        }
        

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

    function getUser(){

        $db = $this->connection->getConnection();

        $sql = "SELECT * FROM b_user WHERE fk_id_role = 1 AND id = :id";

        $data = $db->prepare($sql);

        $data->execute();

        $row = $data->fetch(PDO::FETCH_ASSOC);

        if($row){
            $user = new User();

            $user->id = $row['id'];
            $user->firstname = $row['firstname'];
            $user->lastname = $row['lastname'];
            return $user;
        }
        return 0;
        
        

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