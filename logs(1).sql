-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 18 juin 2024 à 16:35
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `bp`
--

-- --------------------------------------------------------

--
-- Structure de la table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` datetime NOT NULL,
  `user` varchar(255) NOT NULL,
  `profile` varchar(255) NOT NULL,
  `event_type` varchar(50) NOT NULL,
  `log_message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `logs`
--

INSERT INTO `logs` (`id`, `ip_address`, `timestamp`, `user`, `profile`, `event_type`, `log_message`) VALUES
(1, '::1', '2024-06-04 21:55:24', 'Unknown User', 'Unknown Profile', 'Information', 'Home page accessed.'),
(2, '::1', '2024-06-04 22:37:22', 'Unknown User', 'Unknown Profile', 'Information', 'Entrée de test dans le journal'),
(3, '::1', '2024-06-04 22:37:23', 'Unknown User', 'Unknown Profile', 'Information', 'Entrée de test dans le journal'),
(4, '::1', '2024-06-04 22:37:28', 'Unknown User', 'Unknown Profile', 'Information', 'Entrée de test dans le journal'),
(5, '::1', '2024-06-04 22:41:44', 'Unknown User', 'Unknown Profile', 'Information', 'Entrée de test dans le journal'),
(6, '::1', '2024-06-04 22:41:46', 'Unknown User', 'Unknown Profile', 'Information', 'Entrée de test dans le journal'),
(7, '::1', '2024-06-04 22:41:46', 'Unknown User', 'Unknown Profile', 'Information', 'Entrée de test dans le journal'),
(8, '::1', '2024-06-04 22:41:47', 'Unknown User', 'Unknown Profile', 'Information', 'Entrée de test dans le journal'),
(9, '::1', '2024-06-04 22:41:47', 'Unknown User', 'Unknown Profile', 'Information', 'Entrée de test dans le journal'),
(10, '::1', '2024-06-04 22:42:21', 'Unknown User', 'Unknown Profile', 'Information', 'Entrée de test dans le journal'),
(11, '::1', '2024-06-04 22:42:22', 'Unknown User', 'Unknown Profile', 'Information', 'Entrée de test dans le journal'),
(12, '::1', '2024-06-04 22:42:30', 'Unknown User', 'Unknown Profile', 'Information', 'Home page accessed.'),
(13, '::1', '2024-06-04 22:42:40', 'Unknown User', 'Unknown Profile', 'Information', 'Entrée de test dans le journal'),
(14, '::1', '2024-06-04 22:45:30', 'Unknown User', 'Unknown Profile', 'Information', 'Entrée de test dans le journal'),
(15, '::1', '2024-06-04 22:45:31', 'Unknown User', 'Unknown Profile', 'Information', 'Entrée de test dans le journal'),
(16, '::1', '2024-06-04 22:45:31', 'Unknown User', 'Unknown Profile', 'Information', 'Entrée de test dans le journal'),
(17, '::1', '2024-06-04 22:45:32', 'Unknown User', 'Unknown Profile', 'Information', 'Entrée de test dans le journal'),
(18, '::1', '2024-06-04 22:45:37', 'Unknown User', 'Unknown Profile', 'Information', 'Entrée de test dans le journal'),
(19, '::1', '2024-06-04 22:45:52', 'Unknown User', 'Unknown Profile', 'Information', 'Entrée de test dans le journal'),
(20, '::1', '2024-06-04 22:49:03', 'Unknown User', 'Unknown Profile', 'Information', 'Entrée de test dans le journal'),
(21, '::1', '2024-06-04 22:49:03', 'Unknown User', 'Unknown Profile', 'Information', 'Test de la fonction de journalisation.'),
(22, '::1', '2024-06-04 23:42:12', 'Unknown User', 'Unknown Profile', 'Information', 'Entrée de test dans le journal'),
(23, '::1', '2024-06-04 23:42:12', 'Unknown User', 'Unknown Profile', 'Information', 'Test de la fonction de journalisation.'),
(24, '::1', '2024-06-04 23:42:13', 'Unknown User', 'Unknown Profile', 'Information', 'Entrée de test dans le journal'),
(25, '::1', '2024-06-04 23:42:13', 'Unknown User', 'Unknown Profile', 'Information', 'Test de la fonction de journalisation.'),
(26, '::1', '2024-06-04 23:42:13', 'Unknown User', 'Unknown Profile', 'Information', 'Entrée de test dans le journal'),
(27, '::1', '2024-06-04 23:42:13', 'Unknown User', 'Unknown Profile', 'Information', 'Test de la fonction de journalisation.'),
(28, '::1', '2024-06-04 23:47:33', 'Unknown User', 'Unknown Profile', 'Information', 'Entrée de test dans le journal'),
(29, '::1', '2024-06-04 23:47:33', 'Unknown User', 'Unknown Profile', 'Information', 'Test de la fonction de journalisation.'),
(30, '::1', '2024-06-04 23:53:15', 'Unknown User', 'Unknown Profile', 'Information', 'Entrée de test dans le journal'),
(31, '::1', '2024-06-04 23:53:15', 'Unknown User', 'Unknown Profile', 'Information', 'Test de la fonction de journalisation.'),
(32, '::1', '2024-06-05 00:06:19', 'Unknown User', 'Unknown Profile', 'Information', 'Entrée de test dans le journal'),
(33, '::1', '2024-06-05 00:06:19', 'Unknown User', 'Unknown Profile', 'Information', 'Test de la fonction de journalisation.'),
(34, '::1', '2024-06-05 00:06:20', 'Unknown User', 'Unknown Profile', 'Information', 'Entrée de test dans le journal'),
(35, '::1', '2024-06-05 00:06:20', 'Unknown User', 'Unknown Profile', 'Information', 'Test de la fonction de journalisation.'),
(36, '::1', '2024-06-05 00:06:20', 'Unknown User', 'Unknown Profile', 'Information', 'Entrée de test dans le journal'),
(37, '::1', '2024-06-05 00:06:20', 'Unknown User', 'Unknown Profile', 'Information', 'Test de la fonction de journalisation.'),
(38, '::1', '2024-06-05 00:06:20', 'Unknown User', 'Unknown Profile', 'Information', 'Entrée de test dans le journal'),
(39, '::1', '2024-06-05 00:06:20', 'Unknown User', 'Unknown Profile', 'Information', 'Test de la fonction de journalisation.'),
(40, '::1', '2024-06-05 00:06:21', 'Unknown User', 'Unknown Profile', 'Information', 'Entrée de test dans le journal'),
(41, '::1', '2024-06-05 00:06:21', 'Unknown User', 'Unknown Profile', 'Information', 'Test de la fonction de journalisation.'),
(42, '::1', '2024-06-05 00:07:10', 'Unknown User', 'Unknown Profile', 'Information', 'Entrée de test dans le journal'),
(43, '::1', '2024-06-05 00:07:10', 'Unknown User', 'Unknown Profile', 'Information', 'Test de la fonction de journalisation.'),
(44, '::1', '2024-06-05 00:07:14', 'Unknown User', 'Unknown Profile', 'Information', 'Entrée de test dans le journal'),
(45, '::1', '2024-06-05 00:07:14', 'Unknown User', 'Unknown Profile', 'Information', 'Test de la fonction de journalisation.'),
(46, '::1', '2024-06-05 00:07:14', 'Unknown User', 'Unknown Profile', 'Information', 'Entrée de test dans le journal'),
(47, '::1', '2024-06-05 00:07:14', 'Unknown User', 'Unknown Profile', 'Information', 'Test de la fonction de journalisation.'),
(48, '::1', '2024-06-05 00:07:15', 'Unknown User', 'Unknown Profile', 'Information', 'Entrée de test dans le journal'),
(49, '::1', '2024-06-05 00:07:15', 'Unknown User', 'Unknown Profile', 'Information', 'Test de la fonction de journalisation.'),
(50, '::1', '2024-06-05 00:07:30', 'Unknown User', 'Unknown Profile', 'Information', 'Entrée de test dans le journal'),
(51, '::1', '2024-06-05 00:07:30', 'Unknown User', 'Unknown Profile', 'Information', 'Test de la fonction de journalisation.'),
(52, '::1', '2024-06-05 00:11:48', 'Unknown User', 'Unknown Profile', 'Information', 'Entrée de test dans le journal'),
(53, '::1', '2024-06-05 00:11:48', 'Unknown User', 'Unknown Profile', 'Information', 'Test de la fonction de journalisation.'),
(54, '::1', '2024-06-05 00:11:50', 'Unknown User', 'Unknown Profile', 'Information', 'Entrée de test dans le journal'),
(55, '::1', '2024-06-05 00:11:50', 'Unknown User', 'Unknown Profile', 'Information', 'Test de la fonction de journalisation.'),
(56, '::1', '2024-06-05 00:11:57', 'Unknown User', 'Unknown Profile', 'Information', 'Entrée de test dans le journal'),
(57, '::1', '2024-06-05 00:11:57', 'Unknown User', 'Unknown Profile', 'Information', 'Test de la fonction de journalisation.'),
(58, '::1', '2024-06-05 00:12:27', 'Unknown User', 'Unknown Profile', 'Information', 'Entrée de test dans le journal'),
(59, '::1', '2024-06-05 00:12:27', 'Unknown User', 'Unknown Profile', 'Information', 'Test de la fonction de journalisation.'),
(60, '::1', '2024-06-05 00:12:29', 'Unknown User', 'Unknown Profile', 'Information', 'Entrée de test dans le journal'),
(61, '::1', '2024-06-05 00:12:29', 'Unknown User', 'Unknown Profile', 'Information', 'Test de la fonction de journalisation.'),
(62, '::1', '2024-06-05 00:12:39', 'Unknown User', 'Unknown Profile', 'Information', 'Entrée de test dans le journal'),
(63, '::1', '2024-06-05 00:12:39', 'Unknown User', 'Unknown Profile', 'Information', 'Test de la fonction de journalisation.'),
(64, '::1', '2024-06-05 00:12:41', 'Unknown User', 'Unknown Profile', 'Information', 'Entrée de test dans le journal'),
(65, '::1', '2024-06-05 00:12:41', 'Unknown User', 'Unknown Profile', 'Information', 'Test de la fonction de journalisation.'),
(66, '::1', '2024-06-05 00:12:42', 'Unknown User', 'Unknown Profile', 'Information', 'Entrée de test dans le journal'),
(67, '::1', '2024-06-05 00:12:42', 'Unknown User', 'Unknown Profile', 'Information', 'Test de la fonction de journalisation.');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
