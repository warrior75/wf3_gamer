-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Lun 11 Janvier 2016 à 14:53
-- Version du serveur :  10.1.9-MariaDB
-- Version de PHP :  5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `wf3_gamer`
--

-- --------------------------------------------------------

--
-- Structure de la table `games`
--

CREATE TABLE `games` (
  `id` int(11) NOT NULL,
  `img` varchar(255) NOT NULL,
  `title` varchar(55) NOT NULL,
  `description` varchar(255) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `plateform_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `games`
--

INSERT INTO `games` (`id`, `img`, `title`, `description`, `user_id`, `plateform_id`) VALUES
(1, 'http://image.jeuxvideo.com/medias-sm/142054/1420536609-7283-jaquette-avant.jpg', 'Uncharted 4 : A Thief''s End', ' Quatrième opus de la série de jeu d''action/aventure à succès de Naughty Dog, Uncharted 4 A Thief''s End vous permettra d''incarner Nathan Drake pour la première fois sur PS4.', 0, 0),
(2, 'http://image.jeuxvideo.com/images-sm/pc/t/w/twitpc0f.jpg', 'The Witcher', 'The Witcher est un jeu de rôle sur PC, permettant de parcourir l''univers fantastique et féerique à travers le royaume de Temeria.', 0, 0),
(3, 'http://image.jeuxvideo.com/images-sm/jaquettes/00049847/jaquette-the-king-of-fighters-xiii-pc-cover-avant-g-1381247001.jpg', 'The King of Fighters', 'The King of Fighters XIII est un jeu de combat en 2D sur Xbox 360. Le titre propose des affrontements qui voient s''opposer des équipes de 3 combattants', 0, 0),
(4, 'http://image.jeuxvideo.com/medias-sm/143533/1435331352-4036-jaquette-avant.jpg', ' Fallout 4', 'Fallout 4 est un RPG à la première personne se déroulant dans un univers post-apocalyptique. Dans un monde dévasté par les bombes', 0, 0),
(5, 'http://image.jeuxvideo.com/images-sm/jaquettes/00034710/jaquette-grand-theft-auto-v-pc-cover-avant-g-1415122060.jpg', 'Grand Theft Auto V', 'Jeu d''action-aventure en monde ouvert, Grand Theft Auto (GTA) V vous place dans la peau de trois personnages inédits', 0, 0),
(6, 'http://image.jeuxvideo.com/images-sm/jaquettes/00048287/jaquette-assassin-s-creed-unity-pc-cover-avant-g-1407851395.jpg', 'Assassin''s Creed Unity', 'Jeu d''action-aventure en monde ouvert, Assassin''s Creed Unity vous place dans la peau d''Arno Victor Dorian à l''époque de la Révolution française', 0, 1),
(7, 'http://image.jeuxvideo.com/images-sm/jaquettes/00053818/jaquette-football-manager-2015-pc-cover-avant-g-1407433694.jpg', 'Football Manager 2015', 'Jeu de gestion de football, Football Manager 2015 est dans la droite lignée de ses prédécesseurs mais apporte son lot de nouveautés', 0, 1),
(8, 'http://image.jeuxvideo.com/images-sm/jaquettes/00047879/jaquette-destiny-playstation-4-ps4-cover-avant-g-1410169099.jpg', ' Destiny ', ' Réalisé par les créateurs de la série Halo, Destiny est un FPS dans lequel le joueur se bat dans un contexte futuriste', 0, 3),
(9, 'http://image.jeuxvideo.com/medias-sm/142348/1423477230-4706-jaquette-avant.jpg', 'Xenoblade Chronicles X', 'Xenoblade Chronicles X est un jeu de rôle sur Wii U. Dans ce gigantesque monde ouvert et fantastique, les joueurs peuvent se déplacer et se battre à bord de robots volants baptisés Skells.', 0, 4);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `games`
--
ALTER TABLE `games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
