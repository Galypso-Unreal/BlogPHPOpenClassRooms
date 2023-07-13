<?php

namespace Application\Controllers\Post;



require_once('./src/lib/database.php');
require_once('./src/model/post.php');

use Application\Lib\Database\DatabaseConnection;
use Application\Model\Post\PostRepository;
use Exception;

class PostController{

    function getPosts(){

        $connection = new DatabaseConnection();
        $postRepository = new PostRepository();
        $postRepository->connection = $connection;

        return $postRepository->getPosts();
    }

    function getPost($id){

        $connection = new DatabaseConnection();
        $postRepository = new PostRepository();
        $postRepository->connection = $connection;

        if(isset($_GET['id']) && $_GET['id'] > 0){
            return $postRepository->getPost($id);
        }
        else{
            throw new Exception('Aucun identifiant de billet envoyé');
        }
        
    }

    function addPost($title, $lead_content, $content, int $fk_user_id){

        $connection = new DatabaseConnection();
        $postRepository = new PostRepository();
        $postRepository->connection = $connection;

        if($title != '' && $lead_content != '' && $content != '' && isset($fk_user_id)){
            
            return $postRepository->addPost($title,$lead_content,$content,$fk_user_id);
            
        }
        else{
            throw new Exception('Le formulaire est incomplet');
        }
    }

    function modifyPost($id,$title,$lead_content,$content,$id_user){

        $connection = new DatabaseConnection();
        $postRepository = new PostRepository();
        $postRepository->connection = $connection;

        if(isset($id) && $title != '' && $lead_content != '' && $content != '' && isset($id_user)){
            return $postRepository->modifyPost($id,$title,$lead_content,$content,$id_user);
        }
        else{
            throw new Exception('La modification ne peut pas être effectuée');
        }
    }

    function deletePost($id){
        $connection = new DatabaseConnection();
        $postRepository = new PostRepository();
        $postRepository->connection = $connection;

        if(isset($id) && $id > 0){
            return $postRepository->deletePost($id);
        }
        else{
            throw new Exception("L'id n'est pas correcte");
        }
    }

    function getAdmins(){

        $connection = new DatabaseConnection();
        $postRepository = new PostRepository();
        $postRepository->connection = $connection;

        try {
            return $postRepository->getAdmins();
        } catch (\Throwable $th) {
            throw new Exception('Une erreur est survenue avec la base de données');  
        }
        
    }

    function getAuthor(int $id){

        $connection = new DatabaseConnection();
        $postRepository = new PostRepository();
        $postRepository->connection = $connection;

        if(isset($id) && $id > 0 && is_int($id)){
            return $postRepository->getAuthor($id);
        }

    }



}



