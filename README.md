# BlogPHPOpenClassRooms

Comment installer le projet
Prérecommendation : 

Version de PHP : 8.1.9
Version de MySQL : 8.0.16
Web serveur : Nginx ou Apache
phpMyAdmin 5.2.1
Local by flywheels : 6.7.2 ou plus


Etape 1


Utiliser un serveur local pour le projet. Un serveur local va permettre de coder sur une instance isolée afin de pouvoir faire tous les tests nécessaires avant une mise en production du site.

Voici les principaux logiciels permettant de mettre en place un serveur local : 

Local by flywheel (celui que j’utilise pour ce projet) : https://localwp.com/

WAMP : https://www.wampserver.com/

MAMP : https://www.mamp.info/en/mamp/windows/

ATTENTION : Je vous conseille tous de même d’utiliser le même logiciel que celui utilisé pour ce projet (Local by Flywheel) pour éviter d’éventuelles erreurs. Mais, cela n’est pas obligatoire.


Etape 2


Créer le serveur local.
Ici, je vais seulement proposer la procédure pour le logiciel : Local. Si vous ne savez pas comment créer un serveur avec WAMP ou MAMP, voici leur tutoriel respectif : 

https://youtu.be/GMjf8Hocf2U

https://youtu.be/L-lgLQBIjTg
Pour la procédure Local :

Premièrement, créer un site sur local avec le bouton + en bas à gauche du logiciel.

Ensuite, cliquer sur : Create a new site

Un nom à votre projet vous sera demandé (je vous conseille de mettre le même que celui du projet)

Vous pouvez configurer votre nom de domaine et le dossier du site en cliquant sur le bouton : Advanced options

Pour l’environnement, il faut cliquer sur Custom et mettre les valeurs suivantes :

PHP version : 8.1.9

Webserver : nginx 1.16.0

Database : MySQL 8.0.16

Ensuite, pour le username et le password vous pouvez mettre ce que vous voulez, mais garder bien en tête que votre username et password sera utilise pour la connexion à la base de données.

Pour continuer, cliquer sur Add site. Une demande administrateur vous sera demandée, cliquez sur OUI

Maintenant que tout est en place, aller dans le fichier du site. Vous pouvez directement y accéder en cliquant sur «Go to site folder» sur la page d’administration de votre site sur le panneau  « Local sites » de Local

Pour finir, aller dans app > public, supprimer tous les fichiers car ils ne seront pas utiles.


Etape 3


Cloner le projet du git.

Pour cloner le projet, utiliser la méthode HTTPS

Https : https://github.com/Galypso-Unreal/BlogPHPOpenClassRooms.git

Ensuite, dans un répertoire créer au préalable sur votre ordinateur grâce a Local by Flywheels ou un autre logiciel de création de site en local, ouvré votre console bash git et marqué cette ligne de commande : clone https://github.com/Galypso-Unreal/BlogPHPOpenClassRooms.git

Le projet sera ensuite cloné dans votre dossier. Si le projet est présent dans un autre dossier,

Exemple : monDossier > BlogPHPOpenClassRooms

Vous pouvez sans aucun problème déplacer TOUS les fichiers dans le dossier de niveau 1 (monDossier). Cependant, ATTENTION à bien voir les dossiers et fichiers cacher de votre ordinateur, car le fichier .git ne sera pas visible sans cette option.

Tutoriel pour voir les dossiers et fichiers cacher : 

Windows : https://support.microsoft.com/fr-fr/windows/voir-les-fichiers-et-les-dossiers-cach%C3%A9s-dans-windows-97fbc472-c603-9d90-91d0-1166d1d9f4b5

Mac :  https://www.ionos.fr/digitalguide/serveur/configuration/mac-afficher-les-fichiers-et-dossiers-caches/ 

Linux :  https://www.it-connect.fr/linux-comment-afficher-les-fichiers-et-dossiers-caches/ 

Pour ceux qui sont avec le logiciel Local by Flywheels, il faut bien mettre tous les dossiers le répertoire : public


Etape 4


Ce site comporte une base de données. Cela est nécessaire pour avoir : les utilisateurs, les posts ... 

Pour ce faire, un logiciel permettant de mettre en place une base de données sera nécessaire. 
Pour ce projet PHPMyAdmin est celui utilisé :

https://www.phpmyadmin.net/ (la version 5.2.1 est conseillée)

Vous pouvez récupérer un jeu de données à la racine du projet nommé :  database_data_test.sql


Etape 5


Configurer le branchement de la base de données :

Aller dans le dossier du projet : src > lib (si le dossier n’est pas créé, créez-en un)

Modifier ou créer le fichier : database.php

Ensuite, ajouter ce code s’il n’est pas encore présent :

<?php

namespace Application\Lib\Database;

use PDO;

/* The `class DatabaseConnection` is a PHP class that represents a database connection. It has a
property `` which is an instance of the `PDO` class, and a method `getConnection()` which
returns the database connection. */

class DatabaseConnection
{

  public ?PDO $database = null;
  
  /**
   * The function returns a PDO connection to a MySQL database.
   * 
   * @return PDO a PDO object, which represents a connection to a database.
   */

  public function getConnection(): PDO

  {
    if ($this->database === null) {
      $this->database = new PDO('mysql:host=YOUR_HOST:YOUR_PORT;dbname=YOUR_DATABASENAME;charset=utf8', 'YOUR_USER', 'YOUR_PASSWORD_USER');
    }

    return $this->database;
  }
}

Ensuite, modifier les différentes variables en majuscule : YOUR_HOST, YOUR_PORT, YOUR_DATABASENAME, ‘YOUR_USER’, ‘YOUR_PASSWORD_USER’ avec vos informations.


Informations importantes


- Ne jamais coder sur la main
- Envoyer une pull request pour merger votre branche vers la main ( Le merge devra est revu par un autre développeur avec d’être accepté)
- Ne laisser pas vos informations personnelles sur le base de données
- Penser à supprimer vos branche quand la fonctionnalité est complètement fini et vérifié
