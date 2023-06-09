<?php
define('ABS_PATH', __DIR__);

/* controllers */
require_once 'src/controllers/back/form.php';

require_once 'twig.php';


//routeur
if (isset($_GET['action']) && $_GET['action'] !== '') {

    // if send contact at form

	if ($_GET['action'] === 'confirmationForm') {
        if(confirmationForm()){
            echo $twig->render('elements/confirm.twig',array(
                'firstname'=>$_POST['firstname'],
                'lastname'=>$_POST['lastname'],
                'email'=>$_POST['email'],
                'message'=>$_POST['message']
            ));
        }
        
    }
}else{
    echo $twig->render('index.twig');
}

// echo $twig->render('index.twig');
