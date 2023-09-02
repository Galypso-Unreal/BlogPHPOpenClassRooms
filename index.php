<?php
session_start();

require 'src/controllers/post.php';
require 'src/controllers/comment.php';
require 'src/controllers/user.php';
require 'src/controllers/form.php';
require 'twig.php';
require 'src/lib/globals.php';

use Application\Controllers\Post\PostController;
use Application\Controllers\Comment\CommentController;
use Application\Controllers\User\UserController;
use Application\Controllers\Form\FormController;
use Application\Lib\Globals\GlobalPost;
use Application\Lib\Globals\GlobalServer;
use Application\Lib\Globals\GlobalSession;


$post = new GlobalPost();
$session = new GlobalSession();
$server = new GlobalServer();

//routeur
if (isset($_GET['action']) && $_GET['action'] !== '') {

    // USER

    //login

    if ($_GET['action'] === 'loginUser') {

        $user = (new UserController())->login();

        if ($user == 1) {
            header('Location: http://blog.local');
        } else if ($user == 2) {
            header('Location: http://blog.local/admin/posts');
        } else {
            if($post->getPost('email') == true){

                echo $twig->render('login.twig', array(
                    'errors' => $user,
                    'email' => htmlspecialchars($post->getPost('email'),ENT_NOQUOTES),
    
                ));
            }
            
        }
    }

    //LOGOUT

    if ($_GET['action'] === 'logoutUser') {

        if ($session->getSession('LOGGED_USER') == true) {
            $session->forgetSession('LOGGED_USER');
        }

        header('Location: http://blog.local/');
    }

    if($_GET['action'] === 'logoutAdmin'){

        if ($session->getSession('LOGGED_ADMIN') == true) {
            $session->forgetSession('LOGGED_ADMIN');
        }
        header('Location: http://blog.local/');
    }

    // if send contact at form homepage

    if ($_GET['action'] === 'getPost') {

        $id = $_GET['id'];
        $post = (new PostController())->getPost($id);
        $comments = (new CommentController())->getComments($id);
        echo $twig->render('post.twig', array(
            'post' => $post,
            'comments' => $comments
        ));
    }

    if ($_GET['action'] === 'confirmationForm') {

        if ((new FormController())->confirmationForm()) {
            $informations = (new FormController())->sendMailContact();
            echo $twig->render('elements/confirm.twig', array(
                'informations' => $informations
            ));
        }
    }
    if ($_GET['action'] === 'addPost') {

        $title = $post->getPost('title');
        $lead_content = $post->getPost('lead_content');
        $content = $post->getPost('content');
        $fk_user_id = $post->getPost('id_user');

        (new PostController())->addPost($title, $lead_content, $content, $fk_user_id);
    }
    if ($_GET['action'] === 'modifyPost') {

        $title = $post->getPost('title');
        $lead_content = $post->getPost('lead_content');
        $content = $post->getPost('content');
        $id_user = $post->getPost('id_user');
        (new PostController())->modifyPost($_GET['id'], $title, $lead_content, $content, $id_user);
    }
    if ($_GET['action'] === 'createAccount') {

        $user = new UserController();
        $create = $user->createAccount();

        if (is_array($create) == 1) {
            echo $twig->render('register.twig', array(
                'errors' => $create,
                'before' => $post->getAllPost()
            ));
        } else {
            echo $twig->render('register-send.twig', array());
        }
    }
    if ($_GET['action'] === 'addComment') {
        $comment = $post->getPost('comment');
        (new CommentController())->addComment($comment);
    }
    if ($_GET['action'] === 'validComment') {

        (new CommentController())->validComment($_GET['id']);
    }

    if ($_GET['action'] === 'deleteComment') {

        (new CommentController())->deleteComment($_GET['id']);
    }
    if ($_GET['action'] === 'validateUser') {

        (new UserController())->validateUser((int)$_GET['id']);
        header("Location: /admin/users/");
    }
    if ($_GET['action'] === 'deleteUser') {

        (new UserController())->deleteUser((int)$_GET['id']);
        header("Location: /admin/users/");
    }
} else {

    // FRONT OFFICE LINKS 

    $host = $server->getServer('REQUEST_URI');

    if ($host === "/") {
        echo $twig->render('index.twig');
    } elseif ($host === "/actualites/") {
        echo $twig->render('posts.twig', array(
            'posts' => (new PostController())->getPosts(),
        ));
    } elseif ($host === '/login' && $session->getSession('LOGGED_USER') == true) {
        header('Location: http://blog.local');
    } elseif ($host === '/login') {
        echo $twig->render('login.twig');
    } elseif ($host === '/register' && $session->getSession('LOGGED_USER') == true) {
        header('Location: http://blog.local');
    } elseif ($host === '/register') {
        echo $twig->render('register.twig');
    } elseif ($host === '/admin/login') {
        if ($session->getSession('LOGGED_ADMIN') == true) {
            header('Location: /admin/posts');
        } else {
            echo $twig->render('admin/login.twig');
        }
    }

    // BACK-OFFICE LINKS

    if ($session->getSession('LOGGED_ADMIN') == true) {

        if ($host === '/admin/post/add') {
            echo $twig->render('admin/post/add.twig', array(
                'admins' => (new PostController())->getAdmins(),
            ));
        } elseif ($host === '/admin/posts') {
            echo $twig->render('admin/post/posts.twig', array(
                'posts' => (new PostController())->getPosts(),
            ));
        } elseif (strpos($host, "admin/post/delete")) {
            (new PostController())->deletePost($_GET['id']);
        } elseif (strpos($host, "admin/post/modify")) {
            if (isset($_GET['id'])) {
                echo $twig->render('admin/post/modify.twig', array(
                    'post' => (new PostController())->getPost($_GET['id']),
                    'admins' => (new PostController())->getAdmins()
                ));
            }
        } elseif (strpos($host, "admin/comments")) {

            echo $twig->render('admin/comment/comments.twig', array(
                'comments' => (new CommentController())->getAllComments()
            ));
        } elseif (strpos($host, "admin/users")) {

            echo $twig->render('admin/user/users.twig', array(
                'users' => (new UserController())->getAllUsers()
            ));
        }
    }
}

// echo $twig->render('index.twig');
