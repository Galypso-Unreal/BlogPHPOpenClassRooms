<?php

use Twig\TwigFunction;
use Application\Model\Post\PostRepository;
use Application\Lib\Database\DatabaseConnection;
use Application\Lib\Globals\GlobalServer;
use Application\Lib\Globals\GlobalSession;

require __DIR__ . '/vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/src/views');
$twig = new \Twig\Environment($loader, ['debug' => true]);
$twig->addExtension(new \Twig\Extension\DebugExtension());
// define all globals variable usable in twig




$getID = new TwigFunction('get_ID', function () {
    $server = new GlobalServer();
    if ($server->getServer('REQUEST_URI') == true) {
        $host = $server->getServer('REQUEST_URI');
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

$session = new GlobalSession();

$twig->addGlobal('img_assets', 'http://blog.local/src/assets/img/');

if ($session->getSession('LOGGED_ADMIN') == true && !empty($session->getSession('LOGGED_ADMIN'))) {
    $twig->addGlobal('admin_session', $session->getSession('LOGGED_ADMIN'));
}

if ($session->getSession('LOGGED_USER') == true && !empty($session->getSession('LOGGED_USER'))) {
    $twig->addGlobal('user_session', $session->getSession('LOGGED_USER'));
}
