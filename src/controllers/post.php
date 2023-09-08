<?php

namespace Application\Controllers\Post;



require_once './src/lib/database.php';
require_once './src/model/post.php';

use Application\Lib\Database\DatabaseConnection;
use Application\Lib\Globals\GlobalGet;
use Application\Model\Post\PostRepository;


use Exception;

/*
    The `class PostController` is a PHP class that contains various functions related to retrieving,
    adding, modifying, and deleting posts and authors in a blog system. It uses a `PostRepository`
    object to interact with the database and perform these operations. The functions in the
    `PostController` class handle tasks such as retrieving all posts, retrieving a specific post by its
    ID, adding a new post, modifying an existing post, deleting a post, retrieving all admins, and
    retrieving an author by their ID. These functions make use of a `DatabaseConnection` object to
    establish a connection to the database.
*/

class PostController
{

    /**
     * The code contains various functions related to retrieving, adding, modifying, and deleting posts
     * and authors in a blog system.
     * @return The functions are returning different values depending on the logic and conditions
     * within each function. Some functions return a result from the database, some return a header
     * redirect, and some throw exceptions.
     */

    
    /**
     * The function retrieves posts from a database using a connection and repository object.
     * @return mixed result of the `getPosts()` method from the `PostRepository` class.
     */


    public function getPosts()
    {

        $connection = new DatabaseConnection();
        $postRepository = new PostRepository();
        $postRepository->connection = $connection;

        return $postRepository->getPosts();
        
    }


    /**
     * This PHP function retrieves a post from a database based on an identifier, but throws an
     * exception if the identifier is not provided or is invalid.
     * @param int $identifier Identifier
     * The `$identifier` parameter is an integer that represents the unique identifier of a post. It is used to retrieve a specific post from the database.
     * 
     * @return mixed code is returning the result of the `getPost()` method from the `$postRepository`
     * object.
     */


    public function getPost(int $identifier)
    {

        $connection = new DatabaseConnection();
        $postRepository = new PostRepository();
        $postRepository->connection = $connection;
        $get = new GlobalGet();

        /*
            The code is checking if the `$identifier` variable is set and is greater than 0. If
            all these conditions are true, it means that the post can be get and displayed to the user.
        */

        if ($get->getKey('id') !== null && $get->getKey('id') > 0) {
            return $postRepository->getPost($identifier);
        } else {
            throw new Exception('Aucun identifiant de billet envoyé');
        }

    }


    /**
     * The function `addPost` checks if the required variables are defined and not empty, and if so,
     * adds a post to the database and redirects the user to the list of all posts, otherwise throws an
     * exception.
     * @param string '$title' Title
     * The title of the post.
     * 
     * @param string '$lead_content' Lead_content
     * The parameter "$lead_content" is a string that represents the introductory content or summary of the post. It provides a brief overview or teaser of the main content of the post.
     * 
     * @param string '$content' Content
     * The "$content" parameter is a string that represents the main content of the post. It typically contains the detailed information or body of the post.
     * 
     * @param int '$fk_user_id' Fk_user_id
     * The parameter "$fk_user_id" is the foreign key that represents the user who is adding the post. It is an integer value that corresponds to the ID of the user in the database.
     * 
     * @return mixed header redirect to the URL "http://blog.local/admin/posts" if all the conditions for
     * adding a post are met.
     */


    public function addPost(string $title, string $lead_content, string $content, int $fk_user_id)
    {

        $connection = new DatabaseConnection();
        $postRepository = new PostRepository();
        $postRepository->connection = $connection;

        /*
            The code checks whether the following variables are defined: title, lead_contnet, content and fk_user_id. If all these conditions are true, the user can add a post.
            After adding the post to the database, the user is redirected to the list of all posts available on the website. If it's not possible to add any post, the user get an Exception
        */

        if (isset($title) === true && $title !== '' && isset($lead_content) === true && $lead_content !== '' && isset($content) === true && $content !== '' && isset($fk_user_id) === true && $fk_user_id > 0) {

            $postRepository->addPost($title, $lead_content, $content, $fk_user_id);
            return header('Location: http://blog.local/admin/posts');
        } else {

            throw new Exception('Le formulaire est incomplet');
        }

    }


    /**
     * The function `modifyPost` checks if the necessary variables are set and valid, and if so,
     * modifies a post and redirects the user to the post listing page, otherwise throws an exception.
     * @param int '$identifier' Identifier
     * The identifier is an integer that represents the unique identifier of the post that needs to be modified. It is used to identify the specific post in the database.
     * 
     * @param string '$title' Title
     * The title of the post that you want to modify. It should be a string.
     * 
     * @param string '$lead_content' Lead_content
     * The `lead_content` parameter is a string that represents the introductory or summary content of a blog post. It is typically a shorter version of the main content and is often used to provide a preview or teaser of the post.
     * 
     * @param string '$content' Content
     * The `content` parameter is a string that represents the main content of the post. It typically contains the body of the post, including text, images, and any other media that the post may include.
     * 
     * @param int '$id_user' Id_user
     * The `id_user` parameter is an integer that represents the identifier of the user who is modifying the post. It is used to ensure that the user has the necessary permissions to modify the post.
     * 
     * @return mixed header redirect to the URL "http://blog.local/admin/posts" if all the conditions are
     * met and the post is successfully modified. If any of the conditions are not met, an Exception
     * with the message "La modification ne peut pas être effectuée" is thrown.
     */


    public function modifyPost(int $identifier, string $title, string $lead_content, string $content, int $id_user)
    {

        $connection = new DatabaseConnection();
        $postRepository = new PostRepository();
        $postRepository->connection = $connection;

        /*
            The code is checking if the `$identifier` variable is set, and is greater than 0, if the $title is set, if the lead_content is set, if the id_user is set. If
            all these conditions are true, it means that the post can be modified and the user is redirected to the listing of posts in the back office. If ones of these conditions is not good, the user get an Exception
        */

        if (isset($identifier) === true && $identifier > 0 && isset($title) === true && $title !== '' && isset($lead_content) === true && $lead_content !== '' && isset($content) === true && $content !== '' && isset($id_user) === true && $id_user > 0) {
            $postRepository->modifyPost($identifier, $title, $lead_content, $content, $id_user);
            return header('Location: http://blog.local/admin/posts');
        } else {
            throw new Exception('La modification ne peut pas être effectuée');
        }

    }


    /**
     * This PHP function deletes a post from the database based on the given identifier and redirects
     * the user to the admin posts page.
     * @param int '$identifier' Identifier
     * The identifier parameter is an integer that represents the unique identifier of the post that needs to be deleted.
     * 
     * @return mixed header redirect to the URL "http://blog.local/admin/posts" if the condition is met and
     * the post is successfully deleted.
     */


    public function deletePost(int $identifier)
    {
        $connection = new DatabaseConnection();
        $postRepository = new PostRepository();
        $postRepository->connection = $connection;

        /*
            check if the variable $identifier is set and is greater than 0
        */

        if (isset($identifier) === true && $identifier > 0) {
            $postRepository->deletePost($identifier);
            return header('Location: http://blog.local/admin/posts');
        } else {
            throw new Exception("L'id n'est pas correcte");
        }

    }


    /**
     * The function "getAdmins" retrieves all admins from the database using a post repository and
     * returns them in an array, or throws an exception if there is an error with the database.
     * @return mixed method is returning all admins in an array of data.
     */


    public function getAdmins()
    {

        $connection = new DatabaseConnection();
        $postRepository = new PostRepository();
        $postRepository->connection = $connection;

        /*
            if the following methods can be done : getAdmins(), the method returns all admins in an array of data. Otherwise, an Exception is called
        */
        try {
            return $postRepository->getAdmins();
        } catch (\Throwable $th) {
            throw new Exception('Une erreur est survenue avec la base de données');
        }

    }

    
    /**
     * This PHP function retrieves the author of a post based on the given identifier.
     * @param int $identifier Identifier
     * The 'identifier'parameter is an integer that represents the ID of a post.
     * 
     * @return mixed author of the post is being returned.
     */


    public function getAuthor(int $identifier)
    {

        $connection = new DatabaseConnection();
        $postRepository = new PostRepository();
        $postRepository->connection = $connection;

        /*
            check if $identifier is valid, is an int and is more than 0.
            Return the author of the post
        */

        if (isset($identifier) === true && $identifier > 0 && is_int($identifier) === true) {
            return $postRepository->getAuthor($identifier);
        }

    }

    
}
