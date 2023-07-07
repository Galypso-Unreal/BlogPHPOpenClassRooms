<?php
namespace Application\Model\Post;

require_once('./src/lib/database.php');

use Application\Lib\Database\DatabaseConnection;
use PDO;

class Post{

    public int $id;
    public string $title;
    public string $lead;
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
    
        $sql = "SELECT * FROM b_post";
    
        $data = $db->prepare($sql);
        $data->execute();
    
        $posts = [];
    
        while(($row = $data->fetch(PDO::FETCH_ASSOC))){
            $post = new Post();

            $post->id =     $row['id'];
            $post->title =     $row['title'];
            $post->lead =     $row['lead'];
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
            $post->lead = $row['lead'];
            $post->content = $row['content'];
            $post->fk_user_id = $row['fk_user_id'];
            $post->modified_at = $row['modified_at'];
            
        }
        
        return $post;
    }
    
    function getIdPost(){
        $host = $_SERVER['REQUEST_URI'];
        $id = explode('/',$host);
        $id = end($id);
    
        return $id;
    }
    
    function getAuthor(int $id): Array{

        $db = $this->connection->getConnection();
    
        $sql = "SELECT id,prenom,nom,email FROM b_utilisateur WHERE id=:id";
    
        $data = $db->prepare($sql);
    
        $data->execute(array(':id'=>$id));
        while(($row = $data->fetch(PDO::FETCH_ASSOC))){
            $author = [
                'id'=>$row['id'],
                'firstname'=>$row['prenom'],
                'lastname'=>$row['nom'],
                'email'=>$row['email']
            ];
        }
    
        return $author;
    }
    
    
    
    function addPost($title,$chapo,$content,$id_user){

        $db = $this->connection->getConnection();
    
    
        $sql = "INSERT INTO b_post (titre,chapo,contenu,fk_utilisateur_id) VALUES (:titre,:chapo,:contenu,:fk_utilisateur_id)";
        $insert = $db->prepare($sql);
        $insert->execute(array(
            ":titre"=>$title,
            ":chapo"=>$chapo,
            ":contenu"=>$content,
            ":fk_utilisateur_id"=>$id_user,
        ));
    
    }
    
    function modifyPost($id,$title,$chapo,$content,$id_user){

        $db = $this->connection->getConnection();
    
    
        $sql = "UPDATE b_post SET titre=:titre,chapo=:chapo,contenu=:contenu,fk_utilisateur_id=:fk_utilisateur_id,modified_at=:modified_at WHERE id=:id";
        $insert = $db->prepare($sql);
        $date = new DateTime('now', new DateTimeZone('Europe/Paris'));
        $datef = $date->format('Y-m-d H:i:s');
    
        $insert->bindParam(':titre', $title);
        $insert->bindParam(':chapo', $chapo);
        $insert->bindParam(':contenu', $content);
        $insert->bindParam(':fk_utilisateur_id', $id_user);
        $insert->bindParam(':modified_at', $datef);
        $insert->bindParam(':id', $id);
    
        $insert->execute();
    
    }
    
    function deletePost($id){

            $db = $this->connection->getConnection();
    
            $sql = "DELETE FROM b_post WHERE id=:id";
    
            $delete = $db->prepare($sql);
            $delete->execute(array(':id'=>$id));
        
    }
    
    function getAdmins(){
    
        $database = new DatabaseConnection();
        $db = $database->getConnection();
        
        $sql = "SELECT u.id,u.nom,u.prenom,u.email FROM b_utilisateur u JOIN b_role r ON r.id = u.fk_role_id WHERE r.label = 'Administrateur'";
    
        $data = $db->prepare($sql);
    
        $data->execute();
    
        $admins = [];
    
        while(($row = $data->fetch(PDO::FETCH_ASSOC))){
            $admin = [
                'id'=>$row['id'],
                'lastname' => $row['nom'],
                'firstname' => $row['prenom'],
                'email' => $row['email'],
            ];
            $admins[] = $admin;
        }
        return $admins;
    
    }

}



