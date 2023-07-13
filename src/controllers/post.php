<?php

namespace Application\Controllers\Post;



require_once('./src/lib/database.php');
require_once('./src/model/post.php');

use Application\Lib\Database\DatabaseConnection;
use Application\Model\Post\PostRepository;
use Exception;

class PostController{
    
    function confirmationPostAdd(){
        if(isset($_POST['title']) && isset($_POST['chapo']) && isset($_POST['content']) && isset($_POST['id_user'])){
            if(strlen($_POST['title']) > 80){
                return 0;
            }
            return 1;
        }
        return 0;
    }

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

    function addPost($title, $lead, $content, int $fk_user_id){

        $connection = new DatabaseConnection();
        $postRepository = new PostRepository();
        $postRepository->connection = $connection;

        if($title != '' && $lead != '' && $content != '' && isset($fk_user_id)){
            
            return $postRepository->addPost($title,$lead,$content,$fk_user_id);
        }
        else{
            throw new Exception('Le formulaire est incomplet');
        }
    }

    function modifyPost($id,$title,$lead,$content,$id_user){

        $connection = new DatabaseConnection();
        $postRepository = new PostRepository();
        $postRepository->connection = $connection;

        if(isset($id) && $title != '' && $lead != '' && $content != '' && isset($id_user)){
            return $postRepository->modifyPost($id,$title,$lead,$content,$id_user);
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



}



