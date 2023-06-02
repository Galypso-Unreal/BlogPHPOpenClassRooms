<?php

require_once './vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('./src/views');
$twig = new \Twig\Environment($loader, []);

echo $twig->render('index.twig');
