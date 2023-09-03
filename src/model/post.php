<?php

namespace Application\Model\Post;

require_once './src/lib/database.php';

use Application\Lib\Database\DatabaseConnection;
use PDO;
use DateTime;
use DateTimeZone;

/* The class "Post" represents a post with various properties such as id, title, content, created_at,
modified_at, deleted_at, and fk_user_id. */

class Post
{

    public int $id;
    public string $title;
    public string $lead_content;
    public string $content;
    public string $created_at;
    public string $modified_at;
    public ?string $deleted_at;
    public int $fk_user_id;
}

/* The `PostRepository` class is responsible for interacting with the database to perform CRUD
operations on the `Post` objects. It contains methods for retrieving posts, retrieving a single post
by its ID, retrieving the author of a post, adding a new post, modifying an existing post, deleting
a post, and retrieving a list of administrators. */

class PostRepository
{

    /* `public DatabaseConnection ;` is declaring a public property `` of type
    `DatabaseConnection` in the `PostRepository` class. This property is used to store an instance
    of the `DatabaseConnection` class, which is responsible for establishing a connection to the
    database. */

    public DatabaseConnection $connection;

    /**
     * The function `getPosts` retrieves all posts from the database and returns them as an array of
     * Post objects.
     * 
     * @return array an array of Post objects.
     */

    public function getPosts(): array
    {

        $db = $this->connection->getConnection();

        $sql = "SELECT * FROM b_post WHERE deleted_at is NULL";

        $data = $db->prepare($sql);
        $data->execute();

        $posts = [];

        while (($row = $data->fetch(PDO::FETCH_ASSOC))) {

            $post = new Post();

            $post->id =     htmlspecialchars($row['id'], ENT_NOQUOTES);
            $post->title =     htmlspecialchars($row['title'], ENT_NOQUOTES);
            $post->lead_content =     htmlspecialchars($row['lead_content'], ENT_NOQUOTES);
            $post->content =    htmlspecialchars($row['content'], ENT_NOQUOTES);
            $post->created_at =    htmlspecialchars($row['created_at'], ENT_NOQUOTES);
            $post->modified_at =     htmlspecialchars($row['modified_at'], ENT_NOQUOTES);
            $post->deleted_at =   htmlspecialchars($row['deleted_at'], ENT_NOQUOTES);
            $post->fk_user_id =   htmlspecialchars($row['fk_user_id'], ENT_NOQUOTES);


            $posts[] = $post;
        }
        return $posts;
    }

    /**
     * The function `getPost` retrieves a post from the database based on the given ID and returns it
     * as a `Post` object.
     * 
     * @param int id The parameter "id" is an integer that represents the ID of the post that we want
     * to retrieve from the database.
     * 
     * @return Post an instance of the Post class.
     */

    public function getPost(int $id): Post
    {

        $db = $this->connection->getConnection();

        $sql = "SELECT * FROM b_post WHERE id=:id";

        $data = $db->prepare($sql);

        $data->bindParam('id', $id, PDO::PARAM_INT);
        $data->execute();

        while (($row = $data->fetch(PDO::FETCH_ASSOC))) {

            $post = new Post();

            $post->id = htmlspecialchars($row['id'], ENT_NOQUOTES);
            $post->title = htmlspecialchars($row['title'], ENT_NOQUOTES);
            $post->lead_content = htmlspecialchars($row['lead_content'], ENT_NOQUOTES);
            $post->content = htmlspecialchars($row['content'], ENT_NOQUOTES);
            $post->fk_user_id = htmlspecialchars($row['fk_user_id'], ENT_NOQUOTES);
            $post->modified_at = htmlspecialchars($row['modified_at'], ENT_NOQUOTES);
        }

        return $post;
    }


    /**
     * The function `getAuthor` retrieves author information from a database based on the provided ID.
     * 
     * @param int id The parameter "id" is an integer that represents the ID of the author you want to
     * retrieve from the database.
     * 
     * @return array containing the details of the author with the specified ID. The array includes
     * the author's ID, firstname, lastname, and email.
     */

    public function getAuthor(int $id)
    {

        $db = $this->connection->getConnection();

        $sql = "SELECT id,firstname,lastname,email FROM b_user WHERE id = :id";

        $data = $db->prepare($sql);

        $data->bindParam(':id', $id, PDO::PARAM_INT);

        $data->execute();

        while (($row = $data->fetch(PDO::FETCH_ASSOC))) {
            $author = [
                'id' => htmlspecialchars($row['id'], ENT_NOQUOTES),
                'firstname' => htmlspecialchars($row['firstname'], ENT_NOQUOTES),
                'lastname' => htmlspecialchars($row['lastname'], ENT_NOQUOTES),
                'email' => htmlspecialchars($row['email'], ENT_NOQUOTES)
            ];
        }

        return $author;
    }



    /**
     * The function `addPost` inserts a new post into the database with the provided title, lead
     * content, content, and user ID.
     * 
     * @param string title The title of the post.
     * @param string lead_content The lead_content parameter is a string that represents the
     * introductory or summary content of the post.
     * @param string content The "content" parameter is a string that represents the main content of
     * the post. It can contain any text or HTML content that you want to display in the post.
     * @param int fk_user_id The parameter "fk_user_id" is the foreign key that represents the user ID
     * of the user who is creating the post. It is used to associate the post with the user in the
     * database.
     */

    public function addPost(string $title, string $lead_content, string $content, int $fk_user_id): Void
    {

        $db = $this->connection->getConnection();

        $sql = "INSERT INTO b_post (title,lead_content,content,fk_user_id) VALUES (:title,:lead_content,:content,:fk_user_id)";

        $insert = $db->prepare($sql);
        $lead_content_sec = htmlspecialchars($lead_content, ENT_NOQUOTES);
        $title_sec = htmlspecialchars($title, ENT_NOQUOTES);
        $content_sec = htmlspecialchars($content, ENT_NOQUOTES);
        $fk_user_id = htmlspecialchars($fk_user_id, ENT_NOQUOTES);

        $insert->bindParam(':title', $title_sec);
        $insert->bindParam(':lead_content', $lead_content_sec);
        $insert->bindParam(':content', $content_sec);
        $insert->bindParam(':fk_user_id', $fk_user_id, PDO::PARAM_INT);

        $insert->execute();
    }

    /**
     * The function `modifyPost` updates a post in the database with the provided title, lead content,
     * content, user ID, and modified date.
     * 
     * @param int id The ID of the post that needs to be modified.
     * @param string title The title of the post that you want to modify.
     * @param string lead_content The parameter "lead_content" is a string that represents the
     * introductory content of a post. It is typically a shorter version of the main content and is
     * used to provide a summary or preview of the post.
     * @param string content The "content" parameter is a string that represents the updated content of
     * a post.
     * @param int id_user The id of the user who is modifying the post.
     */

    public function modifyPost(int $id, string $title, string $lead_content, string $content, int $id_user): Void
    {

        $db = $this->connection->getConnection();


        $sql = "UPDATE b_post SET title=:title,lead_content=:lead_content,content=:content,fk_user_id=:fk_user_id,modified_at=:modified_at WHERE id=:id";
        $insert = $db->prepare($sql);

        $date = new DateTime('now', new DateTimeZone('Europe/Paris'));
        $datef = htmlspecialchars($date->format('Y-m-d H:i:s'), ENT_NOQUOTES);

        $title_sec = htmlspecialchars($title, ENT_NOQUOTES);
        $lead_content_sec = htmlspecialchars($lead_content, ENT_NOQUOTES);
        $content_sec = htmlspecialchars($content, ENT_NOQUOTES);
        $id_sec = htmlspecialchars($id, ENT_NOQUOTES);
        $id_user_sec = htmlspecialchars($id_user, ENT_NOQUOTES);


        $insert->bindParam(':title', $title_sec);
        $insert->bindParam(':lead_content', $lead_content_sec);
        $insert->bindParam(':content', $content_sec);
        $insert->bindParam(':fk_user_id', $id_user_sec, PDO::PARAM_INT);
        $insert->bindParam(':modified_at', $datef);
        $insert->bindParam(':id', $id_sec, PDO::PARAM_INT);

        $insert->execute();
    }

    /**
     * The function `deletePost` updates the `deleted_at` column of a post in the `b_post` table with
     * the current date and time.
     * 
     * @param int id The parameter "id" is an integer that represents the ID of the post that needs to
     * be deleted.
     */

    public function deletePost(int $id)
    {

        $db = $this->connection->getConnection();



        $date = new DateTime('now', new DateTimeZone('Europe/Paris'));
        $datef = htmlspecialchars($date->format('Y-m-d H:i:s'), ENT_NOQUOTES);

        $sql = "UPDATE b_post SET deleted_at=:deleted_at WHERE id=:id";

        $delete = $db->prepare($sql);

        $delete->bindParam('deleted_at', $datef);
        $delete->bindParam('id', $id, PDO::PARAM_INT);

        $delete->execute();
    }

    /**
     * The function `getAdmins()` retrieves a list of administrators from a database table.
     * 
     * @return array an array of admins. Each admin is represented by an associative array with keys
     * 'id', 'lastname', 'firstname', and 'email'.
     */

    public function getAdmins(): array
    {

        $database = new DatabaseConnection();
        $db = $database->getConnection();

        $sql = "SELECT u.id,u.lastname,u.firstname,u.email FROM b_user u JOIN b_role r ON r.id = u.fk_id_role WHERE r.label = 'Administrateur'";

        $data = $db->prepare($sql);

        $data->execute();

        $admins = [];

        while (($row = $data->fetch(PDO::FETCH_ASSOC))) {
            $admin = [
                'id' => $row['id'],
                'lastname' => $row['lastname'],
                'firstname' => $row['firstname'],
                'email' => $row['email'],
            ];
            $admins[] = $admin;
        }
        return $admins;
    }
}
