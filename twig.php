<?php

use Twig\TwigFunction;
use Twig\Extra\String\StringExtension;
use Application\Model\Post\PostRepository;
use Application\Lib\Database\DatabaseConnection;
use Symfony\Component\DependencyInjection\Dumper\Dumper;

require_once __DIR__ . '/vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/src/views');
$twig = new \Twig\Environment($loader, ['debug' => true]);
$twig->addExtension(new \Twig\Extension\DebugExtension());
$twig->addExtension(new StringExtension());
// define all globals variable usable in twig

$getID = new TwigFunction('get_ID', function () {

   $host = $_SERVER['REQUEST_URI'];
   $id = explode('id=', $host);
   $id = end($id);
   return $id;
});

$twig->addFunction($getID);


$displayAuthor = new TwigFunction('displayAuthor', function (int $id) {

   $connection = new DatabaseConnection();
   $postRepository = new PostRepository();
   $postRepository->connection = $connection;

   return $postRepository->getAuthor($id);
});

$twig->addFunction($displayAuthor);


$twig->addGlobal('img_assets', 'http://blog.local/src/assets/img/');
// $twig->addGlobal('js_folder', ABS_PATH.'/assets/js/');
if (isset($_COOKIE['LOGGED_ADMIN']) && !empty($_COOKIE['LOGGED_ADMIN'])) {
   $twig->addGlobal('admin_cookie', json_decode($_COOKIE['LOGGED_ADMIN']));
}

if (isset($_COOKIE['LOGGED_USER']) && !empty($_COOKIE['LOGGED_USER'])) {
   $twig->addGlobal('user_cookie', json_decode($_COOKIE['LOGGED_USER']));
}
