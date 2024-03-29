<?php
session_start();

require 'src/lib/globals.php';
require 'src/controllers/post.php';
require 'src/controllers/comment.php';
require 'src/controllers/user.php';
require 'src/controllers/form.php';
require 'twig.php';


use Application\Controllers\Post\PostController;
use Application\Controllers\Comment\CommentController;
use Application\Controllers\User\UserController;
use Application\Controllers\Form\FormController;
use Application\Lib\Globals\GlobalGet;
use Application\Lib\Globals\GlobalPost;
use Application\Lib\Globals\GlobalServer;
use Application\Lib\Globals\GlobalSession;


$post = new GlobalPost();
$session = new GlobalSession();
$server = new GlobalServer();
$get = new GlobalGet();

// Routeur.
if ($get->getKey('action') !== null && $get->getKey('action') !== '') {
    // USER.
    // Login.
    if ($get->getKey('action') === 'loginUser') {
        $user = (new UserController())->login();
        if ($user === 1) {
            header('Location: http://blog.local');
        } else if ($user === 2) {
            header('Location: http://blog.local/admin/posts');
        } else {
            if ($post->getPost('email') !== null) {
                echo $twig->render('login.twig', array('errors' => $user,'email' => htmlspecialchars($post->getPost('email'), ENT_NOQUOTES)));
            }
        }
    }

    // LOGOUT.
    if ($get->getKey('action') === 'logoutUser') {
        if ($session->getSession('LOGGED_USER') !== null) {
            $session->forgetSession('LOGGED_USER');
        }
        header('Location: http://blog.local/');
    }

    if ($get->getKey('action') === 'logoutAdmin') {
        if ($session->getSession('LOGGED_ADMIN') !== null) {
            $session->forgetSession('LOGGED_ADMIN');
        }
        header('Location: http://blog.local/');
    }
    // If send contact at form homepage.
    if ($get->getKey('action') === 'getPost') {
        $identifier = $get->getKey('id');
        $post = (new PostController())->getPost($identifier);
        $comments = (new CommentController())->getComments($identifier);
        echo $twig->render('post.twig', array('post' => $post,'comments' => $comments));
    }
    if ($get->getKey('action') === 'confirmationForm') {
        $confirm = (new FormController())->confirmationForm();
        if ($confirm === 1) {
            $informations = (new FormController())->sendMailContact();
            echo $twig->render('elements/confirm.twig', array('informations' => $informations));
        }
        else{
            echo $twig->render('index.twig', array('errors' => $confirm));
        }
    }
    if ($get->getKey('action') === 'addPost') {
        $title = $post->getPost('title');
        $lead_content = $post->getPost('lead_content');
        $content = $post->getPost('content');
        $fk_user_id = $post->getPost('id_user');

        (new PostController())->addPost($title, $lead_content, $content, $fk_user_id);
    }
    if ($get->getKey('action') === 'modifyPost') {
        $title = $post->getPost('title');
        $lead_content = $post->getPost('lead_content');
        $content = $post->getPost('content');
        $id_user = $post->getPost('id_user');
        (new PostController())->modifyPost($get->getKey('id'), $title, $lead_content, $content, $id_user);
    }
    if ($get->getKey('action') === 'createAccount') {
        $user = new UserController();
        $create = $user->createAccount();

        if (isset($create['errors']) === true) {
            echo $twig->render('register.twig', array('errors' => $create['errors'],'before' => $post->getAllPost()));
        } else {
            echo $twig->render('register-send.twig', array());
        }
    }
    if ($get->getKey('action') === 'addComment') {
        $comment = $post->getPost('comment');
        (new CommentController())->addComment($comment);
    }
    if ($get->getKey('action') === 'validComment') {
        (new CommentController())->validComment($get->getKey('id'));
    }
    if ($get->getKey('action') === 'deleteComment') {
        (new CommentController())->deleteComment($get->getKey('id'));
    }
    if ($get->getKey('action') === 'validateUser') {
        (new UserController())->validateUser((int)$get->getKey('id'));
        header("Location: /admin/users/");
    }
    if ($get->getKey('action') === 'deleteUser') {
        (new UserController())->deleteUser((int)$get->getKey('id'));
        header("Location: /admin/users/");
    }
} else {
    // FRONT OFFICE LINKS.
    $host = $server->getServer('REQUEST_URI');
    if ($host === "/") {
        echo $twig->render('index.twig');
    } elseif ($host === "/actualites/") {
        echo $twig->render('posts.twig', array('posts' => (new PostController())->getPosts()));
    } elseif ($host === '/login' && $session->getSession('LOGGED_USER') == true) {
        header('Location: http://blog.local');
    } elseif ($host === '/login') {
        echo $twig->render('login.twig');
    } elseif ($host === '/register' && $session->getSession('LOGGED_USER') == true) {
        header('Location: http://blog.local');
    } elseif ($host === '/register') {
        echo $twig->render('register.twig');
    } elseif ($host === '/admin/login') {
        if ($session->getSession('LOGGED_ADMIN') !== null) {
            header('Location: /admin/posts');
        } else {
            echo $twig->render('admin/login.twig');
        }
    }
    // BACK-OFFICE LINKS.
    if ($session->getSession('LOGGED_ADMIN') !== null) {
        if ($host === '/admin/post/add') {
            echo $twig->render('admin/post/add.twig', array('admins' => (new PostController())->getAdmins()));
        } elseif ($host === '/admin/posts') {
            echo $twig->render('admin/post/posts.twig', array('posts' => (new PostController())->getPosts()));
        } elseif (strpos($host, "admin/post/delete")) {
            (new PostController())->deletePost($get->getKey('id'));
        } elseif (strpos($host, "admin/post/modify")) {
            if ($get->getKey('id') !== null) {
                echo $twig->render('admin/post/modify.twig', array('post' => (new PostController())->getPost($get->getKey('id')),'admins' => (new PostController())->getAdmins()));
            }
        } elseif (strpos($host, "admin/comments")) {
            echo $twig->render('admin/comment/comments.twig', array('comments' => (new CommentController())->getAllComments()));
        } elseif (strpos($host, "admin/users")) {
            echo $twig->render('admin/user/users.twig', array('users' => (new UserController())->getAllUsers()));
        }
    }
}
