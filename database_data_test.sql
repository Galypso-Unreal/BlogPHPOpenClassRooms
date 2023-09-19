-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : mer. 06 sep. 2023 à 09:01
-- Version du serveur : 8.0.16
-- Version de PHP : 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `blog`
--

-- --------------------------------------------------------

--
-- Structure de la table `b_comment`
--

DROP TABLE IF EXISTS `b_comment`;
CREATE TABLE `b_comment` (
  `id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `is_valid` tinyint(4) NOT NULL DEFAULT '0',
  `deleted_at` datetime DEFAULT NULL,
  `fk_user_id` int(11) NOT NULL,
  `fk_post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `b_comment`
--

INSERT INTO `b_comment` (`id`, `comment`, `is_valid`, `deleted_at`, `fk_user_id`, `fk_post_id`) VALUES
(8, 'J\'ai regardé la version 8.2.10 de PHP, en vrai c\'est vraiment pas mal.', 1, NULL, 15, 9),
(9, 'Les changelog sont ici :) -https://www.php.net/ChangeLog-8.php#8.2.10', 1, NULL, 15, 9);

-- --------------------------------------------------------

--
-- Structure de la table `b_post`
--

DROP TABLE IF EXISTS `b_post`;
CREATE TABLE `b_post` (
  `id` int(11) NOT NULL,
  `title` varchar(80) NOT NULL,
  `lead_content` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  `fk_user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `b_post`
--

INSERT INTO `b_post` (`id`, `title`, `lead_content`, `content`, `created_at`, `modified_at`, `deleted_at`, `fk_user_id`) VALUES
(8, 'Pourquoi et comment faire sa veille informatique / technologique ?', 'Aujourd’hui on va parler de veille. Veille techno mais pas que !', 'C’est quoi la veille ?\r\nLa veille existe dans la plupart des métiers, cela va du garagiste qui va se tenir au courant des nouveaux problèmes, nouveaux modèles, nouvelles méthodes, à l’architecte qui va lui aussi se tenir au courant des nouveautés, que ce soit en matière de mode ou en matière de législation. Dans de nombreux métiers, cette veille est indispensable pour ne pas se retrouver rapidement larguée. Elle n’est donc pas une option dans notre métier.\r\n\r\nAujourd’hui, je vous propose de parler de veille, car selon moi dans notre métier d’informaticien, la veille est indispensable. Pour moi, elle fait partie intégrante de ce métier et vous devez lui trouver une place dans votre agenda. Ne serait-ce que 10min par jour. Vous verrez cela peut faire la différence. Si vous n’êtes pas informaticien, mais passionné, alors cette veille peut aussi vous être utile. Pour votre métier ou vos passions.\r\n\r\nDans l’informatique, on appelle cela, de la veille technologique, elle fait partie du travail de l’informaticien, que ce soit le technicien informatique, l’administrateur système, réseaux, développeur et bien d’autres postes encore.\r\n\r\nEn effet, l’informatique étant en constante évolution, la veille est une partie essentielle de notre travail et peut prendre beaucoup de temps. C’est pour cela qu’il faut savoir organiser sa veille technologie pour être toujours au top sans perdre trop de temps.\r\n\r\nQue permet la veille techno ?\r\nD’éviter de prendre des décisions sur des informations obsolètes\r\nD’éviter de réinventer ce qui existe déjà\r\nRester à jour et augmenter vos compétences\r\nOk, c’est bien beau tout ça, mais alors, comment fait-on une veille techno de qualité ?\r\n \r\nJe fais de la veille depuis quelques années, je ne sais pas quelle est la meilleure technique, mais je vais parler de mon expérience, après à vous de juger si c’est pertinent pour votre veille et si cela vaut le coup d’utiliser les mêmes techniques que moi. Vous pouvez également partager vos techniques perso en commentaire.\r\nQuels outils pour améliorer sa veille techno ?\r\nIl y a quelques années, lorsque j’ai commencé l’informatique il n’y avait pas beaucoup d’outils, c’était beaucoup de bibliothèque et magazine dans les kiosques, maintenant on à tellement d’outils qui permette d’organiser la veille que cela en devient presque difficile. Le risque est de rapidement se perdre dans le nombre d’informations.', '2023-09-01 12:48:00', '2023-09-01 12:48:00', NULL, 1),
(9, 'Qu\'est ce que PHP?', 'PHP (officiellement, ce sigle est un acronyme récursif pour PHP Hypertext Preprocessor) est un langage de scripts généraliste et Open Source, spécialement conçu pour le développement d\'applications web. Il peut être intégré facilement au HTML.', 'Au lieu d\'utiliser des tonnes de commandes afin d\'afficher du HTML (comme en C ou en Perl), les pages PHP contiennent des fragments HTML dont du code qui fait \"quelque chose\" (dans ce cas, il va afficher \"Bonjour, je suis un script PHP !\"). Le code PHP est inclus entre une balise de début &lt;?php et une balise de fin ?&gt; qui permettent au serveur web de passer en mode PHP.\r\n\r\nCe qui distingue PHP des langages de script comme le Javascript, est que le code est exécuté sur le serveur, générant ainsi le HTML, qui sera ensuite envoyé au client. Le client ne reçoit que le résultat du script, sans aucun moyen d\'avoir accès au code qui a produit ce résultat. Vous pouvez configurer votre serveur web afin qu\'il analyse tous vos fichiers HTML comme des fichiers PHP. Ainsi, il n\'y a aucun moyen de distinguer les pages qui sont produites dynamiquement des pages statiques.\r\n\r\nLe grand avantage de PHP est qu\'il est extrêmement simple pour les néophytes, mais offre des fonctionnalités avancées pour les experts. Ne craignez pas de lire la longue liste de fonctionnalités PHP. Vous pouvez vous plonger dans le code, et en quelques instants, écrire des scripts simples.\r\n\r\nBien que le développement de PHP soit orienté vers la programmation pour les sites web, vous pouvez en faire bien d\'autres usages. Lisez donc la section Que peut faire PHP ? ou bien le tutoriel d\'introduction si vous êtes uniquement intéressé dans la programmation web.', '2023-09-01 12:53:58', '2023-09-01 12:53:58', NULL, 1),
(10, 'Comment sécuriser un site Internet ?', 'Vous avez envie de savoir comment sécuriser un site Internet afin d’éviter toute déconvenue ? Pour vous aider dans cette mission qui s’annonce périlleuse, nous avons décidé de réaliser un guide comprenant plusieurs actions à réaliser dans le but d’améliorer la sécurité de votre site WordPress, Joomla, ou autre. En appliquant chacune des étapes listées, vous allez pouvoir aborder les choses on ne peut plus sereinement.', 'Pourquoi est-il important de sécuriser son site ?\r\n\r\nAvant de vous expliquer comment sécuriser au mieux un site web, nous avons jugé nécessaire de vous expliquer quelles peuvent être les conséquences si jamais votre site venait à être vulnérable et mal sécurisé.\r\n\r\nVous allez voir à la suite qu’il y a trois conséquences qu’il ne va surtout pas falloir négliger tant leur impact peut être grand. Il faut donc bien garder ces idées en tête quand vous créez un site web.\r\n\r\nCela affecte le référencement de votre site\r\nLes moteurs de recherche (et Google tout particulièrement) protègent leurs utilisateurs face aux sites malveillants. Si votre site n’est pas sécurisé par un certificat SSL et le protocole HTTPS, ou qu’il a été infecté par un malware, un message d’avertissement sera directement affiché sur l’écran des utilisateurs leur demandant s’ils sont certains de vouloir poursuivre et de courir un risque.', '2023-09-01 12:57:03', '2023-09-01 12:57:16', NULL, 14);

-- --------------------------------------------------------

--
-- Structure de la table `b_role`
--

DROP TABLE IF EXISTS `b_role`;
CREATE TABLE `b_role` (
  `id` int(11) NOT NULL,
  `label` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `b_role`
--

INSERT INTO `b_role` (`id`, `label`) VALUES
(1, 'Administrateur'),
(2, 'Utilisateur');

-- --------------------------------------------------------

--
-- Structure de la table `b_user`
--

DROP TABLE IF EXISTS `b_user`;
CREATE TABLE `b_user` (
  `id` int(11) NOT NULL,
  `lastname` varchar(60) NOT NULL,
  `firstname` varchar(60) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `is_valid` tinyint(4) NOT NULL DEFAULT '0',
  `deleted_at` datetime DEFAULT NULL,
  `fk_id_role` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `b_user`
--

INSERT INTO `b_user` (`id`, `lastname`, `firstname`, `email`, `password`, `is_valid`, `deleted_at`, `fk_id_role`) VALUES
(1, 'Moser', 'Johann', 'johann@gmail.com', 'b34797a18fe4237302726e50e1b86dcc838f12a5', 1, NULL, 1),
(14, 'Dayn', 'François', 'francois@gmail.com', '5187da0b934cc25eb2201a3ec9206c24b13cb23b', 1, NULL, 1),
(15, 'Moser', 'Johann', 'johann-utilisateur@gmail.com', 'b34797a18fe4237302726e50e1b86dcc838f12a5', 1, NULL, 2);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `b_comment`
--
ALTER TABLE `b_comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_b_commentaire_b_utilisateur1_idx` (`fk_user_id`),
  ADD KEY `fk_b_commentaire_b_post1_idx` (`fk_post_id`);

--
-- Index pour la table `b_post`
--
ALTER TABLE `b_post`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_b_post_b_utilisateur1_idx` (`fk_user_id`);

--
-- Index pour la table `b_role`
--
ALTER TABLE `b_role`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `b_user`
--
ALTER TABLE `b_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_b_utilisateur_b_role_idx` (`fk_id_role`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `b_comment`
--
ALTER TABLE `b_comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `b_post`
--
ALTER TABLE `b_post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `b_role`
--
ALTER TABLE `b_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `b_user`
--
ALTER TABLE `b_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `b_comment`
--
ALTER TABLE `b_comment`
  ADD CONSTRAINT `fk_b_commentaire_b_post1` FOREIGN KEY (`fk_post_id`) REFERENCES `b_post` (`id`),
  ADD CONSTRAINT `fk_b_commentaire_b_utilisateur1` FOREIGN KEY (`fk_user_id`) REFERENCES `b_user` (`id`);

--
-- Contraintes pour la table `b_post`
--
ALTER TABLE `b_post`
  ADD CONSTRAINT `fk_b_post_b_utilisateur1` FOREIGN KEY (`fk_user_id`) REFERENCES `b_user` (`id`);

--
-- Contraintes pour la table `b_user`
--
ALTER TABLE `b_user`
  ADD CONSTRAINT `fk_b_utilisateur_b_role` FOREIGN KEY (`fk_id_role`) REFERENCES `b_role` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
