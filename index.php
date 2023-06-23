<?php

define('ABS_PATH', __DIR__);
require_once 'src/lib/database.php';
/* models */

require_once('src/model/post.php');

/* controllers */
require_once 'src/controllers/back/form.php';
require_once 'src/controllers/front/post.php';

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
                'post'=>getPost($id)
            ));  
        
    }
    elseif($host === '/admin/dashboard'){
        echo $twig->render('admin/dashboard.twig');
    }
    else{
        echo $twig->render('index.twig');
    }

}

// echo $twig->render('index.twig');
