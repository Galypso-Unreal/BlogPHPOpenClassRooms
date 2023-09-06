<?php

namespace Application\Model\Comment;

require_once './src/lib/database.php';

use Application\Lib\Database\DatabaseConnection;
use Application\Lib\Globals\GlobalGet;
use Application\Lib\Globals\GlobalSession;
use PDO;
use DateTime;
use DateTimeZone;

/* 
The Comment class represents a comment with various properties such as id, comment content, validity
status, deletion timestamp, and foreign keys for user and post.*/

class Comment
{
    public int $identifier;
    public string $comment;
    public bool $is_valid;
    public ?string $deleted_at;
    public int $fk_user_id;
    public int $fk_post_id;
}

/*
The `CommentRepository` class is responsible for interacting with the database to perform CRUD
(Create, Read, Update, Delete) operations on the `Comment` objects. It contains methods for
retrieving all comments, adding a new comment, deleting a comment, and validating a comment. These
methods use the `DatabaseConnection` class to establish a connection with the database and execute
SQL queries.*/

class CommentRepository
{

    /*
    `public DatabaseConnection ;` is declaring a public property named `` of
    type `DatabaseConnection`. This property is used to store an instance of the
    `DatabaseConnection` class, which is responsible for establishing a connection with the
    database. By declaring this property as public, it can be accessed and used by other methods
    within the `CommentRepository` class.*/

    public DatabaseConnection $connection;

    /**
     * The function getAllComments retrieves all comments from the database and returns them as an
     * array of Comment objects.
     * @return array an array of Comment objects.*/


    public function getAllComments(): array
    {

        $db = $this->connection->getConnection();

        $sql = "SELECT * FROM b_comment WHERE deleted_at IS NULL ORDER BY is_valid";


        $data = $db->prepare($sql);

        $data->execute();

        $comments = [];

        while (($row = $data->fetch(PDO::FETCH_ASSOC))) {
            $comment = new Comment();

            $comment->identifier = htmlspecialchars($row['id'], ENT_NOQUOTES);
            $comment->comment = htmlspecialchars($row['comment'], ENT_NOQUOTES);
            $comment->is_valid = htmlspecialchars($row['is_valid'], ENT_NOQUOTES);
            $comment->deleted_at = htmlspecialchars($row['deleted_at'], ENT_NOQUOTES);
            $comment->fk_user_id = htmlspecialchars($row['fk_user_id'], ENT_NOQUOTES);
            $comment->fk_post_id = htmlspecialchars($row['fk_post_id'], ENT_NOQUOTES);


            $comments[] = $comment;
        }
        return $comments;
    }


    /**
     * The function `addComment` inserts a comment into the database with the specified comment, user
     * ID, and post ID.
     * @param string comment The parameter "$comment" is a string that represents the comment that will
     * be added to the database.*/


    public function addComment(string $comment): Void
    {

        $db = $this->connection->getConnection();
        $session = new GlobalSession();
        $get = new GlobalGet();

        if ($session->getSession('LOGGED_USER')['id'] == true) {
            $user =   htmlspecialchars($session->getSession('LOGGED_USER')['id'], ENT_NOQUOTES);
        }

        if (isset($comment) === true) {
            $comment_sec = htmlspecialchars($comment, ENT_NOQUOTES);
        }

        if ($get->getKey('id')) {
            $post = htmlspecialchars($get->getKey('id'), ENT_NOQUOTES);
        }


        $sql = "INSERT INTO b_comment (comment,fk_user_id,fk_post_id) VALUES (:comment,:fk_user_id,:fk_post_id)";
        $insert = $db->prepare($sql);

        $insert->bindParam(':comment', $comment_sec);
        $insert->bindParam(':fk_user_id', $user, PDO::PARAM_INT);
        $insert->bindParam(':fk_post_id', $post, PDO::PARAM_INT);

        $insert->execute();
    }


    /**
     * The function `deleteComment` updates the `deleted_at` column of a comment in the database with
     * the current date and time.
     * @param int identifier The parameter "$identifier" is an integer that represents the unique identifier of the
     * comment that needs to be deleted.*/


    public function deleteComment(int $identifier): Void
    {

        $db = $this->connection->getConnection();

        $date = new DateTime('now', new DateTimeZone('Europe/Paris'),);
        $datef = htmlspecialchars($date->format('Y-m-d H:i:s'), ENT_NOQUOTES);

        $sql = "UPDATE b_comment SET deleted_at=:deleted_at WHERE id=:id";


        $delete = $db->prepare($sql);

        $delete->bindParam('deleted_at', $datef);
        $delete->bindParam('id', $identifier, PDO::PARAM_INT);


        $delete->execute();
    }


    /**
     * The validComment function updates the is_valid field of a comment in the b_comment table to 1,
     * indicating that the comment is valid.
     * @param int identifier The parameter "$identifier" is an integer that represents the ID of the comment that needs
     * to be validated.*/


    public function validComment(int $identifier): Void
    {

        $db = $this->connection->getConnection();

        $sql = "UPDATE b_comment SET is_valid='1' WHERE id=:id";

        $data = $db->prepare($sql);

        $data->bindParam(':id', $identifier, PDO::PARAM_INT);

        $data->execute();
    }


    /**
     * The function `getComments` retrieves comments from the database based on the provided post ID.
     * @param int identifier The parameter "$identifier" is an integer that represents the ID of a post. This function
     * retrieves all the comments associated with that post from the database.
     * @return array an array of Comment objects.*/

     
    public function getComments(int $identifier): array
    {

        $db = $this->connection->getConnection();

        $sql = "SELECT id,comment,fk_user_id FROM b_comment WHERE fk_post_id = :id AND is_valid = '1' AND deleted_at IS NULL";


        $data = $db->prepare($sql);

        $data->bindParam(':id', $identifier, PDO::PARAM_INT);

        $data->execute();

        $comments = [];

        while (($row = $data->fetch(PDO::FETCH_ASSOC))) {

            $comment = new Comment();

            $comment->identifier = htmlspecialchars($row['id'], ENT_NOQUOTES);
            $comment->comment = htmlspecialchars($row['comment'], ENT_NOQUOTES);
            $comment->fk_user_id = htmlspecialchars($row['fk_user_id'], ENT_NOQUOTES);

            $comments[] = $comment;
        }
        return $comments;
    }

    
}
