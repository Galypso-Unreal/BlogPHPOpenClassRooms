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
// Define all globals variable usable in twig




$getID = new TwigFunction('get_ID', function () {
    $server = new GlobalServer();
    if ($server->getServer('REQUEST_URI') !== null) {
        $host = $server->getServer('REQUEST_URI');
        $identifier = explode('id=', $host);
        $identifier = end($identifier);
        return $identifier;
    }
});

$twig->addFunction($getID);


$displayAuthor = new TwigFunction('displayAuthor', function (int $identifier) {

    $connection = new DatabaseConnection();
    $postRepository = new PostRepository();
    $postRepository->connection = $connection;

    return $postRepository->getAuthor($identifier);
    
});

$twig->addFunction($displayAuthor);

$session = new GlobalSession();

$twig->addGlobal('img_assets', 'http://blog.local/src/assets/img/');

if ($session->getSession('LOGGED_ADMIN') !== null && empty($session->getSession('LOGGED_ADMIN')) === false) {
    $twig->addGlobal('admin_session', $session->getSession('LOGGED_ADMIN'));
}

if ($session->getSession('LOGGED_USER') !== null && empty($session->getSession('LOGGED_USER')) === false) {
    $twig->addGlobal('user_session', $session->getSession('LOGGED_USER'));
}
