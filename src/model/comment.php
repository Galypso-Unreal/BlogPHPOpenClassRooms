<?php

namespace Application\Model\Comment;

require_once('./src/lib/database.php');

use Application\Lib\Database\DatabaseConnection;
use PDO;
use DateTime;
use DateTimeZone;

class Comment
{
    public int $id;
    public string $comment;
    public bool $is_valid;
    public ?string $deleted_at;
    public int $fk_user_id;
    public int $fk_post_id;
}

class CommentRepository
{

    public DatabaseConnection $connection;

    function getAllComments(): array
    {

        $db = $this->connection->getConnection();

        $sql = "SELECT * FROM b_comment WHERE deleted_at IS NULL ORDER BY is_valid";


        $data = $db->prepare($sql);

        $data->execute();

        $comments = [];

        while (($row = $data->fetch(PDO::FETCH_ASSOC))) {
            $comment = new Comment();

            $comment->id = htmlspecialchars($row['id'], ENT_NOQUOTES);
            $comment->comment = htmlspecialchars($row['comment'], ENT_NOQUOTES);
            $comment->is_valid = htmlspecialchars($row['is_valid'], ENT_NOQUOTES);
            $comment->deleted_at = htmlspecialchars($row['deleted_at'], ENT_NOQUOTES);
            $comment->fk_user_id = htmlspecialchars($row['fk_user_id'], ENT_NOQUOTES);
            $comment->fk_post_id = htmlspecialchars($row['fk_post_id'], ENT_NOQUOTES);


            $comments[] = $comment;
        }
        return $comments;
    }

    function addComment(string $comment): Void
    {

        $db = $this->connection->getConnection();

        $user =   json_decode(htmlspecialchars($_COOKIE['LOGGED_USER'], ENT_NOQUOTES));
        $comment_sec = htmlspecialchars($comment, ENT_NOQUOTES);
        $post = htmlspecialchars($_GET['id'], ENT_NOQUOTES);


        $sql = "INSERT INTO b_comment (comment,fk_user_id,fk_post_id) VALUES (:comment,:fk_user_id,:fk_post_id)";
        $insert = $db->prepare($sql);

        $insert->bindParam(':comment', $comment_sec);
        $insert->bindParam(':fk_user_id', $user->id, PDO::PARAM_INT);
        $insert->bindParam(':fk_post_id', $post, PDO::PARAM_INT);

        $insert->execute();
    }

    function deleteComment(int $id): Void
    {

        $db = $this->connection->getConnection();

        $date = new DateTime('now', new DateTimeZone('Europe/Paris'),);
        $datef = htmlspecialchars($date->format('Y-m-d H:i:s'), ENT_NOQUOTES);

        $sql = "UPDATE b_comment SET deleted_at=:deleted_at WHERE id=:id";


        $delete = $db->prepare($sql);

        $delete->bindParam('deleted_at', $datef);
        $delete->bindParam('id', $id, PDO::PARAM_INT);


        $delete->execute();
    }

    function validComment(int $id): Void
    {

        $db = $this->connection->getConnection();

        $sql = "UPDATE b_comment SET is_valid='1' WHERE id=:id";

        $data = $db->prepare($sql);

        $data->bindParam(':id', $id, PDO::PARAM_INT);

        $data->execute();
    }

    function getComments(int $id): array
    {

        $db = $this->connection->getConnection();

        $sql = "SELECT id,comment,fk_user_id FROM b_comment WHERE fk_post_id = :id AND is_valid = '1' AND deleted_at IS NULL";


        $data = $db->prepare($sql);

        $data->bindParam(':id', $id, PDO::PARAM_INT);

        $data->execute();

        $comments = [];

        while (($row = $data->fetch(PDO::FETCH_ASSOC))) {

            $comment = new Comment();

            $comment->id = htmlspecialchars($row['id'], ENT_NOQUOTES);
            $comment->comment = htmlspecialchars($row['comment'], ENT_NOQUOTES);
            $comment->fk_user_id = htmlspecialchars($row['fk_user_id'], ENT_NOQUOTES);

            $comments[] = $comment;
        }
        return $comments;
    }
}
