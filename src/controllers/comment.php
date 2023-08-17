<?php

namespace Application\Controllers\Comment;



require_once('./src/lib/database.php');
require_once('./src/model/comment.php');

use Application\Lib\Database\DatabaseConnection;
use Application\Model\Comment\CommentRepository;
use Exception;

class CommentController{
    function getAllComments(){

        $connection = new DatabaseConnection();
        $commentRepository = new commentRepository();
        $commentRepository->connection = $connection;
    
        return $commentRepository->getAllComments();
    
    }

    function addComment($comment){

        $connection = new DatabaseConnection();
        $commentRepository = new commentRepository();
        $commentRepository->connection = $connection;

        $user = json_decode($_COOKIE['LOGGED_USER']);
        $id = $_GET['id'];

        if(isset($comment) && $comment != '' && isset($id) && $id > 0 && $user && isset($user->id)){
          $commentRepository->addComment($comment);
          return header('Location: http://blog.local/actualites/index;php?action=getPost&id='.$id);
        }
        
        else{
            throw new Exception('Vous ne pouvez pas ajouter de commentaire');
        }
    
    }

    function deleteComment(int $id){
        if(isset($id) && is_int($id) && $id > 0){

            $connection = new DatabaseConnection();
            $commentRepository = new commentRepository();
            $commentRepository->connection = $connection;

            $commentRepository->deleteComment($id);

            return header('Location: http://blog.local/admin/comments');
        }
        else{
            throw new Exception("L'id n'est pas correct");
        }
    }

    function validComment(int $id){
        $connection = new DatabaseConnection();
            $commentRepository = new commentRepository();
            $commentRepository->connection = $connection;

        if(isset($id) && is_int($id) && $id > 0){
            $commentRepository->validComment($id);
            return header('Location: http://blog.local/admin/comments');
        }
        else
        {
            throw new Exception("L'id du commentaire n'est pas bon");
        }
        
    }

    function getComments(int $id){
        $connection = new DatabaseConnection();
            $commentRepository = new commentRepository();
            $commentRepository->connection = $connection;

        if(isset($id) && is_int($id) && $id > 0){
            return $commentRepository->getComments($id);
        }
        else{
            throw new Exception("Les commentaires ne peuvent pas être récupérés");
        }
    }

}




