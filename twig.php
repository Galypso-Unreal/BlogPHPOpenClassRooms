<?php 
require_once __DIR__.'/vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__.'/src/views');
$twig = new \Twig\Environment($loader, ['debug'=>true]);
$twig->addExtension(new \Twig\Extension\DebugExtension());
// define all globals variable usable in twig




$twig->addGlobal('img_assets', 'src/assets/img/');
$twig->addGlobal('controllers', 'src/controllers/');