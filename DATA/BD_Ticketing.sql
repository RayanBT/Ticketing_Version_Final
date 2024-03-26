-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : mar. 26 mars 2024 à 22:40
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `BD_Ticketing`
--

-- --------------------------------------------------------

--
-- Structure de la table `actions_tickets`
--

CREATE TABLE `actions_tickets` (
  `id_action` int(11) NOT NULL,
  `id_ticket` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `date_action` datetime NOT NULL,
  `action` varchar(50) NOT NULL,
  `justification` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `libelle_ticket`
--

CREATE TABLE `libelle_ticket` (
  `id_libelle` int(11) NOT NULL,
  `libelle` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `libelle_ticket`
--

INSERT INTO `libelle_ticket` (`id_libelle`, `libelle`) VALUES
(9, 'Demande d\'assistance pour la configuration'),
(7, 'Problème avec un périphérique externe'),
(3, 'Problème d\'accès à un logiciel'),
(4, 'Problème d\'impression'),
(1, 'Problème de connexion réseau'),
(6, 'Problème de matériel'),
(5, 'Problème de messagerie électronique'),
(8, 'Problème de stockage');

-- --------------------------------------------------------

--
-- Structure de la table `tickets`
--

CREATE TABLE `tickets` (
  `id_ticket` int(11) NOT NULL,
  `login` varchar(32) NOT NULL,
  `id_libelle` int(11) NOT NULL,
  `description` text NOT NULL,
  `salle` varchar(3) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `priorite` enum('Faible','Moyen','Important','Urgent') NOT NULL,
  `date_creation` date NOT NULL,
  `statut` enum('Ouvert','En cours','Fermé') NOT NULL DEFAULT 'Ouvert',
  `technicien` varchar(32) NOT NULL DEFAULT 'Non assigné'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `tickets`
--

INSERT INTO `tickets` (`id_ticket`, `login`, `id_libelle`, `description`, `salle`, `ip`, `priorite`, `date_creation`, `statut`, `technicien`) VALUES
(59, 'rayan', 9, 'IPIPIP', 'G22', '::1', 'Faible', '2024-03-26', 'Fermé', 'tech1'),
(60, 'rayan', 9, 'vlbqdfq', 'G22', '::1', 'Faible', '2024-03-26', 'Ouvert', 'Non assigné'),
(61, 'rayan', 9, 'lbqdvvc', 'G26', '::1', 'Faible', '2024-03-26', 'Ouvert', 'Non assigné');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `nom` varchar(32) NOT NULL,
  `login` varchar(32) NOT NULL,
  `email` varchar(50) NOT NULL,
  `mdp` varchar(32) NOT NULL,
  `user_role` enum('utilisateur','admin_systeme','admin_web','technicien') NOT NULL DEFAULT 'utilisateur'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id_user`, `nom`, `login`, `email`, `mdp`, `user_role`) VALUES
(11, 'Gestion', 'gestion', 'gestion@gestion.com', '3f1c4215f8cb', 'admin_web'),
(12, 'tec1', 'tec1', 'tec1@gmail.com', '3f1c4215f8cb', 'technicien'),
(14, 'tec2', 'tec2', 'tec2@gmail.com', '3f1c4215f8cb', 'technicien'),
(16, 'admin système', 'admin', 'admin_sys@gmail.com', '3f1c4215f8cb', 'admin_systeme'),
(17, 'tec3', 'tec3', 'tec3@gmail.com', '3f1c4215f8cb', 'technicien');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `actions_tickets`
--
ALTER TABLE `actions_tickets`
  ADD PRIMARY KEY (`id_action`);

--
-- Index pour la table `libelle_ticket`
--
ALTER TABLE `libelle_ticket`
  ADD PRIMARY KEY (`id_libelle`),
  ADD UNIQUE KEY `libelle` (`libelle`);

--
-- Index pour la table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id_ticket`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `login` (`login`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `actions_tickets`
--
ALTER TABLE `actions_tickets`
  MODIFY `id_action` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `libelle_ticket`
--
ALTER TABLE `libelle_ticket`
  MODIFY `id_libelle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id_ticket` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
