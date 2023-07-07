<?php

use function PHPSTORM_META\elementType;

define('ABS_PATH', __DIR__);
require_once 'src/lib/database.php';
/* models */

require_once('src/model/post.php');

/* controllers */
require_once 'src/controllers/back/form.php';
require_once 'src/controllers/front/post.php';
require_once 'src/controllers/user.php';
require_once 'src/controllers/comment.php';

require_once 'twig.php';






//routeur
if (isset($_GET['action']) && $_GET['action'] !== '') {

    // if send contact at form homepage


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

        if(confirmationPostAdd()){
            $title = $_POST['title'];
            $chapo = $_POST['chapo'];
            $content = $_POST['content'];
            $id_user = $_POST['id_user'];
            addPost($title,$chapo,$content,$id_user);
        }
        else{
            echo "error";
        }

        
    }
    if ($_GET['action'] === 'modifyPost') {
        if(isset($_GET['id']) && $_GET['id']>0){
            if(confirmationPostAdd()){
                $title = $_POST['title'];
                $chapo = $_POST['chapo'];
                $content = $_POST['content'];
                $id_user = $_POST['id_user'];
                modifyPost($_GET['id'],$title,$chapo,$content,$id_user);
            }
            else{
                echo "error";
            }
        }
        else{
            echo "error2";
        }
        
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
}else{
    
    
    $host = $_SERVER['REQUEST_URI'];
    
    if($host === "/actualites/"){
        echo $twig->render('posts.twig',array(
            'posts'=> getPosts(),
        ));
    }
    elseif(strpos($host, "actualite/")){
        
        $id = explode('/',$host);
        $id = end($id);

            echo $twig->render('post.twig',array(
                'post'=>getPost($id),
                'comments'=>getComments($id)
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
            'admins'=>getAdmins(),
        ));
    }
    elseif($host === '/admin/posts'){
        echo $twig->render('admin/post/posts.twig',array(
            'posts'=>getPosts(),
        ));
    }
    elseif(strpos($host, "admin/post/delete")){
        if(isset($_GET['id'])){
            deletePost($_GET['id']);
        }
    }
    elseif(strpos($host, "admin/post/modify")){
        if(isset($_GET['id'])){
            echo $twig->render('admin/post/modify.twig',array(
                'post'=>getPost($_GET['id']),
                'admins'=>getAdmins()
            ));
        }
        
    }
    else{
        echo $twig->render('index.twig');
    }

}

// echo $twig->render('index.twig');
