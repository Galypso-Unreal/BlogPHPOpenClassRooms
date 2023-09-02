<?php

namespace Application\Controllers\Comment;



require_once('./src/lib/database.php');
require_once('./src/model/comment.php');

use Application\Lib\Database\DatabaseConnection;
use Application\Lib\Globals\GlobalGet;
use Application\Lib\Globals\GlobalSession;
use Application\Model\Comment\CommentRepository;
use Exception;


/* The `class CommentController` is a PHP class that serves as a controller for handling comments in an
application. It contains several methods for managing comments, such as retrieving all comments,
adding a new comment, deleting a comment, validating a comment, and retrieving comments for a
specific post. The class uses a `DatabaseConnection` object and a `CommentRepository` object to
interact with the database and perform the necessary operations on comments. */

class CommentController
{

    /**
     * The code contains several functions related to managing comments in a blog, including retrieving
     * all comments, adding a comment, deleting a comment, validating a comment, and retrieving
     * comments for a specific post.
     * 
     * @return The functions `getAllComments()`, `addComment()`, `deleteComment()`, `validComment()`,
     * and `getComments()` are all returning different values.
     */

    function getAllComments()
    {

        /* The code is creating a new instance of the `DatabaseConnection` class and a new instance of
        the `CommentRepository` class. It then assigns the `DatabaseConnection` object to the
        `connection` property of the `CommentRepository` object. This allows the `CommentRepository`
        object to have access to the database connection and perform operations on the database. */

        $connection = new DatabaseConnection();
        $commentRepository = new commentRepository();
        $commentRepository->connection = $connection;

        return $commentRepository->getAllComments();
    }

    function addComment(string $comment)
    {

        $connection = new DatabaseConnection();
        $commentRepository = new commentRepository();
        $commentRepository->connection = $connection;
        $get = new GlobalGet();
        $session = new GlobalSession();

        $user = $session->getSession('LOGGED_USER');       

        if($get->getKey('id') == true){
            $id = $get->getKey('id');
        }
        

        
        
        /* The code is checking if the `$comment` variable is set and not empty, if the `$id` variable
        is set and greater than 0, if the `$user` variable is set and if the `$user->id` property is
        set. If all these conditions are true, it means that the comment and user information are
        valid, and the code proceeds to add the comment using the `addComment()` method of the
        `CommentRepository` object. After adding the comment, it redirects the user to the
        specified current post using the `header()` function. */

        if (isset($comment) && $comment != '' && isset($id) && $id > 0 && $user && isset($user['id'])) {
            $commentRepository->addComment($comment);
            return header('Location: http://blog.local/actualites/index;php?action=getPost&id=' . $id);
        } else {
            throw new Exception('Vous ne pouvez pas ajouter de commentaire');
        }
    }

    function deleteComment(int $id)
    {
        /* The code is checking if the `$id` variable is set, is an integer, and is greater than 0. If
        all these conditions are true, it means that the comment can be deleted. */

        if (isset($id) && is_int($id) && $id > 0) {

            $connection = new DatabaseConnection();
            $commentRepository = new commentRepository();
            $commentRepository->connection = $connection;

            $commentRepository->deleteComment($id);

            return header('Location: http://blog.local/admin/comments');
        } else {
            throw new Exception("L'id n'est pas correct");
        }
    }

    function validComment(int $id)
    {
        $connection = new DatabaseConnection();
        $commentRepository = new commentRepository();
        $commentRepository->connection = $connection;

        /* The code is checking if the `$id` variable is set, is an integer, and is greater than 0. If
        all these conditions are true, it means that the comment can be considered valid. */

        if (isset($id) && is_int($id) && $id > 0) {
            $commentRepository->validComment($id);
            return header('Location: http://blog.local/admin/comments');
        } else {
            throw new Exception("L'id du commentaire n'est pas bon");
        }
    }

    function getComments(int $id)
    {
        $connection = new DatabaseConnection();
        $commentRepository = new commentRepository();
        $commentRepository->connection = $connection;

        /* The code is checking if the `$id` variable is set, is an integer, and is greater than 0. If
        all these conditions are true, it means that the comment can be retrieved. */

        if (isset($id) && is_int($id) && $id > 0) {
            return $commentRepository->getComments($id);
        } else {
            throw new Exception("Les commentaires ne peuvent pas être récupérés");
        }
    }
}
