<?php

namespace Application\Controllers\Comment;



require_once './src/lib/database.php';
require_once './src/model/comment.php';

use Application\Lib\Database\DatabaseConnection;
use Application\Lib\Globals\GlobalGet;
use Application\Lib\Globals\GlobalSession;
use Application\Model\Comment\CommentRepository;
use Exception;


/* 
The `class CommentController` is a PHP class that serves as a controller for handling comments in an
application. It contains several methods for managing comments, such as retrieving all comments,
adding a new comment, deleting a comment, validating a comment, and retrieving comments for a
specific post. The class uses a `DatabaseConnection` object and a `CommentRepository` object to
interact with the database and perform the necessary operations on comments.*/

class CommentController
{

    /**
     * The code contains several functions related to managing comments in a blog, including retrieving
     * all comments, adding a comment, deleting a comment, validating a comment, and retrieving
     * comments for a specific post.
     * @return The functions `getAllComments()`, `addComment()`, `deleteComment()`, `validComment()`,
     * and `getComments()` are all returning different values.*/


    public function getAllComments()
    {

        /* 
        The code is creating a new instance of the `DatabaseConnection` class and a new instance of
        the `CommentRepository` class. It then assigns the `DatabaseConnection` object to the
        `connection` property of the `CommentRepository` object. This allows the `CommentRepository`
        object to have access to the database connection and perform operations on the database.*/

        $connection = new DatabaseConnection();
        $commentRepository = new commentRepository();
        $commentRepository->connection = $connection;

        return $commentRepository->getAllComments();
    }


    /**
     * The function `addComment` checks if the comment and user information are valid, and if so, adds
     * the comment to the database and redirects the user to the current post.
     * @param string comment The `$comment` parameter is a string that represents the comment that
     * needs to be added.
     * @return mixed code is returning a header redirect to the specified URL if all the conditions in
     * the if statement are true.*/


    public function addComment(string $comment)
    {

        $connection = new DatabaseConnection();
        $commentRepository = new commentRepository();
        $commentRepository->connection = $connection;
        $get = new GlobalGet();
        $session = new GlobalSession();

        $user = $session->getSession('LOGGED_USER');

        if ($get->getKey('id') == true) {
            $identifier = $get->getKey('id');
        }




        /* 
        The code is checking if the `$comment` variable is set and not empty, if the `$identifier` variable
        is set and greater than 0, if the `$user` variable is set and if the `$user->identifier` property is
        set. If all these conditions are true, it means that the comment and user information are
        valid, and the code proceeds to add the comment using the `addComment()` method of the
        `CommentRepository` object. After adding the comment, it redirects the user to the
        specified current post using the `header()` function.*/

        if (isset($comment) === true && $comment !== '' && isset($identifier) === true && $identifier > 0 && empty($user) === false && isset($user['id']) === true) {
            $commentRepository->addComment($comment);
            return header('Location: http://blog.local/actualites/index;php?action=getPost&id=' . $identifier);
        } else {
            throw new Exception('Vous ne pouvez pas ajouter de commentaire');
        }
    }


    /**
     * This PHP function deletes a comment from a database if the identifier is set, is an integer, and
     * is greater than 0, otherwise it throws an exception.
     * @param int identifier The `identifier` parameter is an integer that represents the unique
     * identifier of the comment that needs to be deleted.
     * @return mixed code is returning a header redirect to the URL "http://blog.local/admin/comments"
     * after deleting the comment.*/


    public function deleteComment(int $identifier)
    {
        /* 
        The code is checking if the `$identifier` variable is set, is an integer, and is greater than 0. If
        all these conditions are true, it means that the comment can be deleted.*/

        if (isset($identifier) === true && is_int($identifier) === true && $identifier > 0) {

            $connection = new DatabaseConnection();
            $commentRepository = new commentRepository();
            $commentRepository->connection = $connection;

            $commentRepository->deleteComment($identifier);

            return header('Location: http://blog.local/admin/comments');
        } else {
            throw new Exception("L'id n'est pas correct");
        }
    }


    /**
     * The function checks if a comment identifier is valid and redirects to the admin comments page if
     * it is, otherwise it throws an exception.
     * @param int identifier The `identifier` parameter is an integer that represents the ID of a
     * comment.
     * @return a header redirect to the URL "http://blog.local/admin/comments" if the comment
     * identifier is valid.*/


    public function validComment(int $identifier)
    {
        $connection = new DatabaseConnection();
        $commentRepository = new commentRepository();
        $commentRepository->connection = $connection;

        /* 
        The code is checking if the `$identifier` variable is set, is an integer, and is greater than 0. If
        all these conditions are true, it means that the comment can be considered valid.*/

        if (isset($identifier) === true && is_int($identifier) === true && $identifier > 0) {
            $commentRepository->validComment($identifier);
            return header('Location: http://blog.local/admin/comments');
        } else {
            throw new Exception("L'id du commentaire n'est pas bon");
        }
    }
    

    /**
     * This PHP function retrieves comments based on a given identifier if it meets certain conditions,
     * otherwise it throws an exception.
     * @param int identifier The `identifier` parameter is an integer that represents the unique
     * identifier of the comment that needs to be retrieved.
     * @return mixed the conditions in the if statement are true, the function will return the result of
     * calling the `getComments()` method on the `$commentRepository` object.*/


    public function getComments(int $identifier)
    {
        $connection = new DatabaseConnection();
        $commentRepository = new commentRepository();
        $commentRepository->connection = $connection;

        /*
        The code is checking if the `$identifier` variable is set, is an integer, and is greater than 0. If
        all these conditions are true, it means that the comment can be retrieved.*/

        if (isset($identifier) === true && is_int($identifier) === true && $identifier > 0) {
            return $commentRepository->getComments($identifier);
        } else {
            throw new Exception("Les commentaires ne peuvent pas être récupérés");
        }
    }


}
