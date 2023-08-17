<?php 

namespace Application\Model\Comment;

require_once('./src/lib/database.php');

use Application\Lib\Database\DatabaseConnection;
use PDO;
use DateTime;
use DateTimeZone;
use Exception;

class Comment{
    public int $id;
    public string $comment;
    public bool $is_valid;
    public string $deleted_at;
    public int $fk_user_id;
    public int $fk_post_id;
}

class CommentRepository{

    public DatabaseConnection $connection;

    function getAllComments(){

        $db = $this->connection->getConnection();
    
        $sql = "SELECT * FROM b_comment WHERE deleted_at IS NULL ORDER BY is_valid";
        
    
        $data = $db->prepare($sql);
    
        $data->execute();
    
        $comments = [];
    
        while(($row = $data->fetch(PDO::FETCH_ASSOC))){
            $comment = [
                'id'=>$row['id'],
                'comment' => $row['comment'],
                'is_valid'=> $row['is_valid'],
                'user' => $row['fk_user_id'],
            ];
            $comments[] = $comment;
        }
        return $comments;
    
    }

    function addComment(string $comment){

        $db = $this->connection->getConnection();
        
        $user =   json_decode($_COOKIE['LOGGED_USER']);

        $post = $_GET['id'];
    
    
            $sql = "INSERT INTO b_comment (comment,fk_user_id,fk_post_id) VALUES (:comment,:fk_user_id,:fk_post_id)";
            $insert = $db->prepare($sql);
        
            $insert->bindParam(':comment', $comment);
            $insert->bindParam(':fk_user_id', $user->id);
            $insert->bindParam(':fk_post_id', $post);
        
            $insert->execute();
        
        
        
    }

    function deleteComment(int $id){

            $db = $this->connection->getConnection();
    
            $date = new DateTime('now', new DateTimeZone('Europe/Paris'));
            $datef = $date->format('Y-m-d H:i:s');
    
            $sql = "UPDATE b_comment SET deleted_at=:deleted_at WHERE id=:id";
    
            
            $delete = $db->prepare($sql);
    
            $delete->bindParam('deleted_at',$datef);
            $delete->bindParam('id',$id);
            
    
            $delete->execute();
    
    }
    function validComment(int $id){
    
            $db = $this->connection->getConnection();
        
            $sql = "UPDATE b_comment SET is_valid='1' WHERE id=:id";
        
            $data = $db->prepare($sql);
        
            $data->bindParam(':id',$id);
        
            $data->execute();
            
        }

    function getComments(int $id){

        $db = $this->connection->getConnection();
    
        $sql = "SELECT id,comment,fk_user_id FROM b_comment WHERE fk_post_id = :id AND is_valid = '1' AND deleted_at IS NULL";
        
    
        $data = $db->prepare($sql);
    
        $data->bindParam(':id',$id);
    
        $data->execute();
    
        $comments = [];
    
        while(($row = $data->fetch(PDO::FETCH_ASSOC))){

            $comment = new Comment();
            $comment->id = $row['id'];
            $comment->comment = $row['comment'];
            $comment->fk_user_id = $row['fk_user_id'];

            $comments[] = $comment;
        }
        return $comments;
    
    }

}

