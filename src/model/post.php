<?php
namespace Application\Model\Post;

require_once('./src/lib/database.php');

use Application\Lib\Database\DatabaseConnection;
use PDO;
use DateTime;
use DateTimeZone;

class Post{

    public int $id;
    public string $title;
    public string $lead_content;
    public string $content;
    public string $created_at;
    public string $modified_at;
    public ?string $deleted_at;
    public int $fk_user_id;

}

class PostRepository{

    public DatabaseConnection $connection;

    function getPosts(): array{

        $db = $this->connection->getConnection();
    
        $sql = "SELECT * FROM b_post WHERE deleted_at is NULL";
    
        $data = $db->prepare($sql);
        $data->execute();
    
        $posts = [];
    
        while(($row = $data->fetch(PDO::FETCH_ASSOC))){
            $post = new Post();

            $post->id =     $row['id'];
            $post->title =     $row['title'];
            $post->lead_content =     $row['lead_content'];
            $post->content =    $row['content'];
            $post->created_at =    $row['created_at'];
            $post->modified_at =     $row['modified_at'];
            $post->deleted_at =   $row['deleted_at'];
            $post->fk_user_id =   $row['fk_user_id'];
            
            
            $posts[] = $post;
        }
        return $posts;
    }

    function getPost(int $id): Post{
    
        $db = $this->connection->getConnection();
    
        $sql = "SELECT * FROM b_post WHERE id=:id";
    
        $data = $db->prepare($sql);

        $data->bindParam('id',$id);
        $data->execute();
    
        while(($row = $data->fetch(PDO::FETCH_ASSOC))){
            $post = new Post();

            $post->id = $row['id'];
            $post->title = $row['title'];
            $post->lead_content = $row['lead_content'];
            $post->content = $row['content'];
            $post->fk_user_id = $row['fk_user_id'];
            $post->modified_at = $row['modified_at'];
            
        }
        
        return $post;
    }
    
    
    function getAuthor(int $id): Array{

        $db = $this->connection->getConnection();
    
        $sql = "SELECT id,firstname,lastname,email FROM b_user WHERE id=:id";
    
        $data = $db->prepare($sql);
    
        $data->execute(array(':id'=>$id));
        while(($row = $data->fetch(PDO::FETCH_ASSOC))){
            $author = [
                'id'=>$row['id'],
                'firstname'=>$row['firstname'],
                'lastname'=>$row['lastname'],
                'email'=>$row['email']
            ];
        }
    
        return $author;
    }
    
    
    
    function addPost($title,$lead_content,$content, int $fk_user_id){

        $db = $this->connection->getConnection();
        
        $sql = "INSERT INTO b_post (title,lead_content,content,fk_user_id) VALUES (:title,:lead_content,:content,:fk_user_id)";

        $insert = $db->prepare($sql);
        $lead_content_sec = htmlspecialchars($lead_content,ENT_NOQUOTES);
        $title_sec =htmlspecialchars($title,ENT_NOQUOTES);
        $content_sec =htmlspecialchars($content,ENT_NOQUOTES);
        $fk_user_id = '1';
        $insert->bindParam(':title', $title_sec);
        $insert->bindParam(':lead_content', $lead_content_sec);
        $insert->bindParam(':content', $content_sec);
        $insert->bindParam(':fk_user_id', $fk_user_id,PDO::PARAM_INT);

        $insert->execute();

        header('Location: http://blog.local/admin/posts');

    
    }
    
    function modifyPost($id,$title,$lead_content,$content,$id_user){

        $db = $this->connection->getConnection();
    
    
        $sql = "UPDATE b_post SET title=:title,lead_content=:lead_content,content=:content,fk_user_id=:fk_user_id,modified_at=:modified_at WHERE id=:id";
        $insert = $db->prepare($sql);
        $date = new DateTime('now', new DateTimeZone('Europe/Paris'));
        $datef = htmlspecialchars($date->format('Y-m-d H:i:s'),ENT_NOQUOTES);

            $title_sec = htmlspecialchars($title,ENT_NOQUOTES);
            $lead_content_sec = htmlspecialchars($lead_content,ENT_NOQUOTES);
            $content_sec = htmlspecialchars($content,ENT_NOQUOTES);
            $id_sec = htmlspecialchars($id,ENT_NOQUOTES);
            $id_user_sec = htmlspecialchars($id_user,ENT_NOQUOTES);

    
        $insert->bindParam(':title', $title_sec);
        $insert->bindParam(':lead_content', $lead_content_sec);
        $insert->bindParam(':content', $content_sec);
        $insert->bindParam(':fk_user_id', $id_user_sec);
        $insert->bindParam(':modified_at', $datef);
        $insert->bindParam(':id', $id_sec);
    
        $insert->execute();

        header('Location: http://blog.local/admin/posts');
    
    }
    
    function deletePost($id){

            $db = $this->connection->getConnection();
    
            

            $date = new DateTime('now', new DateTimeZone('Europe/Paris'));
            $datef = $date->format('Y-m-d H:i:s');

            $sql = "UPDATE b_post SET deleted_at=:deleted_at WHERE id=:id";
    
            $delete = $db->prepare($sql);

            $delete->bindParam('deleted_at',$datef);
            $delete->bindParam('id',$id);

            $delete->execute();
        
            header('Location: http://blog.local/admin/posts');
        
    }
    
    function getAdmins(){
    
        $database = new DatabaseConnection();
        $db = $database->getConnection();
        
        $sql = "SELECT u.id,u.lastname,u.firstname,u.email FROM b_user u JOIN b_role r ON r.id = u.fk_id_role WHERE r.label = 'Administrateur'";
    
        $data = $db->prepare($sql);
    
        $data->execute();
    
        $admins = [];
    
        while(($row = $data->fetch(PDO::FETCH_ASSOC))){
            $admin = [
                'id'=>$row['id'],
                'lastname' => $row['lastname'],
                'firstname' => $row['firstname'],
                'email' => $row['email'],
            ];
            $admins[] = $admin;
        }
        return $admins;
    
    }

}



