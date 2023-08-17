<?php
// define('ABS_PATH', $_SERVER['DOCUMENT_ROOT']);
require_once('src/controllers/post.php');
require_once('src/controllers/comment.php');
require_once('src/controllers/user.php');
require_once('src/controllers/form.php');

use Application\Controllers\Post\PostController;
use Application\Controllers\Comment;
use Application\Controllers\Comment\CommentController;
use Application\Controllers\User\UserController;
use Application\Model\Comment\CommentRepository;
use Application\Controllers\Form;
use Application\Controllers\Form\FormController;

require_once 'twig.php';






//routeur
if (isset($_GET['action']) && $_GET['action'] !== '') {

    // USER

    //login

    if($_GET['action'] === 'loginUser'){

        $user = (new UserController())->login();

        if($user == 1){
            header('Location: http://blog.local');
        }
        else if($user == 2){
            header('Location: http://blog.local/admin/posts');
        }
        else{
            echo $twig->render('login.twig',array(
                'errors'=>$user,
                'email'=>$_POST['email'],
                
            ));
        }
    }

    //LOGOUT

    if($_GET['action'] === 'logoutUser'){

        if(isset($_COOKIE['LOGGED_ADMIN'])){
            unset($_COOKIE['LOGGED_ADMIN']); 
            setcookie('LOGGED_ADMIN', null, -1,'/');
            var_dump($_COOKIE['LOGGED_ADMIN']);
        }

        if(isset($_COOKIE['LOGGED_USER'])){
            setcookie('LOGGED_USER', null, -1,'/');
            var_dump($_COOKIE['LOGGED_USER']);
        }
        
        header('Location: http://blog.local/');
    }

    // if send contact at form homepage

    if($_GET['action'] === 'getPost'){

        $id = $_GET['id'];
        $post = (new PostController())->getPost($id);
        $comments = (new CommentController())->getComments($id);
        echo $twig->render('post.twig',array(
            'post'=>$post,
            'comments'=>$comments
        ));
        
    }

	if ($_GET['action'] === 'confirmationForm') {

        if((new FormController())->confirmationForm()){
            $informations = (new FormController())->sendMailContact();
            echo $twig->render('elements/confirm.twig',array(
                'informations' => $informations
            ));
        }
        
    }
    if ($_GET['action'] === 'addPost') {

        $title = $_POST['title'];
        $lead_content = $_POST['lead_content'];
        $content = $_POST['content'];
        $fk_user_id = $_POST['id_user'];

        (new PostController())->addPost($title,$lead_content,$content,$fk_user_id);

        
    }
    if ($_GET['action'] === 'modifyPost') {

                $title = $_POST['title'];
                $lead_content = $_POST['lead_content'];
                $content = $_POST['content'];
                $id_user = $_POST['id_user'];
                (new PostController())->modifyPost($_GET['id'],$title,$lead_content,$content,$id_user);
    }
    if ($_GET['action'] === 'createAccount') {

        $user = new UserController();
        $create = $user->createAccount();

        if(is_array($create) == 1){
            echo $twig->render('register.twig',array(
                'errors'=>$create,
                'before'=>$_POST
            )); 
        }
        else{
            echo $twig->render('register-send.twig',array(
                   
            ));
        } 
        
    }
    if($_GET['action'] === 'addComment'){
        $comment = $_POST['comment'];
        (new CommentController())->addComment($comment);

    }
    if($_GET['action'] === 'validComment'){
        
        (new CommentController())->validComment($_GET['id']);
        
    }

    if($_GET['action'] === 'deleteComment'){

        (new CommentController())->deleteComment($_GET['id']);
        
    }
    if($_GET['action'] === 'validateUser'){

        (new UserController())->validateUser((int)$_GET['id']);
        header("Location: /admin/users/");
        
    }
    if($_GET['action'] === 'deleteUser'){

        (new UserController())->deleteUser((int)$_GET['id']);
        header("Location: /admin/users/");
        
    }
}else{
    
    // FRONT OFFICE LINKS 

    $host = $_SERVER['REQUEST_URI'];
    if($host === "/"){
        echo $twig->render('index.twig');
    }
    elseif($host === "/actualites/"){
        echo $twig->render('posts.twig',array(
            'posts'=> (new PostController())->getPosts(),
        ));
    }
    elseif($host === '/login' && isset($_COOKIE['LOGGED_USER'])){
        header('Location: http://blog.local');
    }
    elseif($host === '/login'){
        echo $twig->render('login.twig');
    }
    elseif($host === '/register' && isset($_COOKIE['LOGGED_USER'])){
        header('Location: http://blog.local');
    }
    elseif($host === '/register'){
        echo $twig->render('register.twig');
    }
    elseif($host === '/admin/login'){
        if(isset($_COOKIE['LOGGED_ADMIN'])){
            header('Location: /admin/posts');
        }
        else{
            echo $twig->render('admin/login.twig');
        }
        
    }

    // BACK-OFFICE LINKS
    
    if(isset($_COOKIE['LOGGED_ADMIN'])){


        if($host === '/admin/post/add'){
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
        elseif(strpos($host, "admin/users")){

            echo $twig->render('admin/user/users.twig',array(
            'users'=>(new UserController())->getAllUsers()
            ));
        }

    }
    
    

}

// echo $twig->render('index.twig');
