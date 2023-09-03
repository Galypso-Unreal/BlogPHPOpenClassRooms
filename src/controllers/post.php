<?php

namespace Application\Controllers\Post;



require_once './src/lib/database.php';
require_once './src/model/post.php';

use Application\Lib\Database\DatabaseConnection;
use Application\Lib\Globals\GlobalGet;
use Application\Model\Post\PostRepository;


use Exception;

/* The `class PostController` is a PHP class that contains various functions related to retrieving,
adding, modifying, and deleting posts and authors in a blog system. It uses a `PostRepository`
object to interact with the database and perform these operations. The functions in the
`PostController` class handle tasks such as retrieving all posts, retrieving a specific post by its
ID, adding a new post, modifying an existing post, deleting a post, retrieving all admins, and
retrieving an author by their ID. These functions make use of a `DatabaseConnection` object to
establish a connection to the database. */

class PostController
{

    /**
     * The code contains various functions related to retrieving, adding, modifying, and deleting posts
     * and authors in a blog system.
     * 
     * @return The functions are returning different values depending on the logic and conditions
     * within each function. Some functions return a result from the database, some return a header
     * redirect, and some throw exceptions.*/

    public function getPosts()
    {

        $connection = new DatabaseConnection();
        $postRepository = new PostRepository();
        $postRepository->connection = $connection;

        return $postRepository->getPosts();
    }

    public function getPost(int $id)
    {

        $connection = new DatabaseConnection();
        $postRepository = new PostRepository();
        $postRepository->connection = $connection;
        $get = new GlobalGet();

        /* The code is checking if the `$id` variable is set and is greater than 0. If
        all these conditions are true, it means that the post can be get and displayed to the user. */

        if ($get->getKey('id') && $get->getKey('id') > 0) {
            return $postRepository->getPost($id);
        } else {
            throw new Exception('Aucun identifiant de billet envoyé');
        }
    }

    public function addPost(string $title, string $lead_content, string $content, int $fk_user_id)
    {

        $connection = new DatabaseConnection();
        $postRepository = new PostRepository();
        $postRepository->connection = $connection;

        /* The code checks whether the following variables are defined: title, lead_contnet, content and fk_user_id. If all these conditions are true, the user can add a post.
        After adding the post to the database, the user is redirected to the list of all posts available on the website. If it's not possible to add any post, the user get an Exception*/

        if (isset($title) && $title != '' && isset($lead_content) && $lead_content != '' && isset($content) && $content != '' && isset($fk_user_id) && $fk_user_id > 0) {

            $postRepository->addPost($title, $lead_content, $content, $fk_user_id);
            return header('Location: http://blog.local/admin/posts');
        } else {

            throw new Exception('Le formulaire est incomplet');
        }
    }

    public function modifyPost(int $id, string $title, string $lead_content, string $content, int $id_user)
    {

        $connection = new DatabaseConnection();
        $postRepository = new PostRepository();
        $postRepository->connection = $connection;

        /* The code is checking if the `$id` variable is set, and is greater than 0, if the $title is set, if the lead_content is set, if the id_user is set. If
        all these conditions are true, it means that the post can be modified and the user is redirected to the listing of posts in the back office. If ones of these conditions is not good, the user get an Exception */

        if (isset($id) && $id > 0 && isset($title) && $title != '' && isset($lead_content) && $lead_content != '' && isset($content) && $content != '' && isset($id_user) && $id_user > 0) {
            $postRepository->modifyPost($id, $title, $lead_content, $content, $id_user);
            return header('Location: http://blog.local/admin/posts');
        } else {
            throw new Exception('La modification ne peut pas être effectuée');
        }
    }

    public function deletePost(int $id)
    {
        $connection = new DatabaseConnection();
        $postRepository = new PostRepository();
        $postRepository->connection = $connection;

        /* check if the variable $id is set and is greater than 0 */

        if (isset($id) && $id > 0) {
            $postRepository->deletePost($id);
            return header('Location: http://blog.local/admin/posts');
        } else {
            throw new Exception("L'id n'est pas correcte");
        }
    }

    public function getAdmins()
    {

        $connection = new DatabaseConnection();
        $postRepository = new PostRepository();
        $postRepository->connection = $connection;

        /* if the following methods can be done : getAdmins(), the method returns all admins in an array of data. Otherwise, an Exception is called*/
        try {
            return $postRepository->getAdmins();
        } catch (\Throwable $th) {
            throw new Exception('Une erreur est survenue avec la base de données');
        }
    }

    public function getAuthor(int $id)
    {

        $connection = new DatabaseConnection();
        $postRepository = new PostRepository();
        $postRepository->connection = $connection;

        /* check if $id is valid, is an int and is more than 0.
        Return the author of the post */

        if (isset($id) && $id > 0 && is_int($id)) {
            return $postRepository->getAuthor($id);
        }
    }
}
