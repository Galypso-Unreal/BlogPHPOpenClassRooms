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



}



