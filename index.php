<?php

define('ABS_PATH', __DIR__);

require_once('src/controllers/post.php');
require_once('src/controllers/comment.php');

use Application\Controllers\Post\PostController;
use Application\Controllers\Comment;
use Application\Controllers\Comment\CommentController;
use Application\Model\Comment\CommentRepository;

require_once 'twig.php';






//routeur
if (isset($_GET['action']) && $_GET['action'] !== '') {

    // if send contact at form homepage

    if($_GET['action'] === 'getPost'){

        $id = $_GET['id'];
        $post = (new PostController())->getPost($id);
        
        echo $twig->render('post.twig',array(
            'post'=>$post,
            // 'comments'=>getComments($id)
        ));

        die();
        
    }

	if ($_GET['action'] === 'confirmationForm') {

        if(confirmationForm()){
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $email = $_POST['email'];
            $message = $_POST['message'];
            sendMailContact($firstname,$lastname,$email,$message);
            echo $twig->render('elements/confirm.twig',array(
                'firstname'=>$firstname,
                'lastname'=>$lastname,
                'email'=>$email,
                'message'=>$message
            ));
        }
        
    }
    if ($_GET['action'] === 'addPost') {

        $title = $_POST['title'];
        $lead = $_POST['lead'];
        $content = $_POST['content'];
        $fk_user_id = $_POST['id_user'];

        (new PostController())->addPost($title,$lead,$content,$fk_user_id);
        

        
    }
    if ($_GET['action'] === 'modifyPost') {

                $title = $_POST['title'];
                $lead = $_POST['lead'];
                $content = $_POST['content'];
                $id_user = $_POST['id_user'];
                (new PostController())->modifyPost($_GET['id'],$title,$lead,$content,$id_user);
    }
    if ($_GET['action'] === 'createAccount') {
        if(confirmationCreateAccount() == 1){


        }
        else{
            echo $twig->render('register.twig',array(
                'errors'=>confirmationCreateAccount()
            ));
        }
    }
    if($_GET['action'] === 'addComment'){
        if(confirmationComment() == 1){
            addComment($_POST['comment']);
        }
        else{

        }
    }
    if($_GET['action'] === 'validComment'){
        if(isset($_GET['id']) && $_GET['id'] > 0){
            validComment($_GET['id']);
        }
    }

    if($_GET['action'] === 'deleteComment'){
        if(isset($_GET['id']) && $_GET['id'] > 0){
            deleteComment($_GET['id']);
        }
    }
}else{
    
    
    $host = $_SERVER['REQUEST_URI'];
    
    if($host === "/actualites/"){
        echo $twig->render('posts.twig',array(
            'posts'=> (new PostController())->getPosts(),
        ));
    }
    
    elseif($host === '/login'){
        echo $twig->render('login.twig');
    }
    elseif($host === '/register'){
        echo $twig->render('register.twig');
    }
    elseif($host === '/admin/dashboard'){
        echo $twig->render('admin/dashboard.twig');
    }
    elseif($host === '/admin/post/add'){
        echo $twig->render('admin/post/add.twig',array(
            'admins'=>(new PostController())->getAdmins(),
        ));
    }
    elseif($host === '/admin/posts'){
        echo $twig->render('admin/post/posts.twig',array(
            'posts'=>(new PostController())->getPosts(),
        ));
    }
    elseif(strpos($host, "admin/post/delete")){
        (new PostController())->deletePost($_GET['id']);
    }
    elseif(strpos($host, "admin/post/modify")){
        if(isset($_GET['id'])){
            echo $twig->render('admin/post/modify.twig',array(
                'post'=>(new PostController())->getPost($_GET['id']),
                'admins'=>(new PostController())->getAdmins()
            ));
        }
        
    }
    elseif(strpos($host, "admin/comments")){

         echo $twig->render('admin/comment/comments.twig',array(
            'comments'=>(new CommentController())->getAllComments()
         ));

    }
    else{
        echo $twig->render('index.twig');
    }

}

// echo $twig->render('index.twig');
