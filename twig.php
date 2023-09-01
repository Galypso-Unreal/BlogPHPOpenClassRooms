<?php

use Twig\TwigFunction;
use Application\Model\Post\PostRepository;
use Application\Lib\Database\DatabaseConnection;


require __DIR__ . '/vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/src/views');
$twig = new \Twig\Environment($loader, ['debug' => true]);
$twig->addExtension(new \Twig\Extension\DebugExtension());
// define all globals variable usable in twig

$getID = new TwigFunction('get_ID', function () {
    if(isset($_SERVER['REQUEST_URI'])){
        $host = $_SERVER['REQUEST_URI'];
        $id = explode('id=', $host);
        $id = end($id);
        return $id;
    }

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
if (isset($_SESSION['LOGGED_ADMIN']) && !empty($_SESSION['LOGGED_ADMIN'])) {
   $twig->addGlobal('admin_session', $_SESSION['LOGGED_ADMIN']);
}

if (isset($_SESSION['LOGGED_USER']) && !empty($_SESSION['LOGGED_USER'])) {
   $twig->addGlobal('user_session', $_SESSION['LOGGED_USER']);
}
