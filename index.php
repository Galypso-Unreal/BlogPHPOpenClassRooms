<?php

require_once './vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('./src/views');
$twig = new \Twig\Environment($loader, []);

// define all globals variable usable in twig

$twig->addGlobal('img_assets', 'src/assets/img/');


echo $twig->render('index.twig');
