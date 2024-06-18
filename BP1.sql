-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 18, 2024 at 07:30 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bp`
--

-- --------------------------------------------------------

--
-- Table structure for table `bonnespratiques`
--

CREATE TABLE `bonnespratiques` (
  `IDBonnePratique` int(11) NOT NULL,
  `Description` text NOT NULL,
  `Etat` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `bonnespratiques`
--

INSERT INTO `bonnespratiques` (`IDBonnePratique`, `Description`, `Etat`) VALUES
(23, 'creertest <test>avec option c ou p selon besoin', 0),
(24, 'modini <test>', 0),
(25, 'Vérifier que les dates dans le .tp correspond au fichier définition dans REF_CONTEXT/<contexte>/definition', 0),
(26, 'Mettre une instruction set_1553_Error pour chaque instrument PL utilisé : Set_1553_Error (\"1\", \"TIMEOUT\"); /* POSEIDON 1 = 1, POSEIDON 2 = 4 */ Set_1553_Error (\"7\", \"TIMEOUT\"); /* DORIS 1 = 7, DORIS 2 = 10 */', 0),
(27, 'Autoriser l\'utilisation de la FDTM durant le test', 0),
(28, 'Modifier la configuration du RM en cas de reconfiguration inattendue durant le test', 0),
(29, 'Envoyer les TC spécifiques de reprise de contexte des SADM spécifiques avant l\'envoi des TC de contexte', 0),
(30, 'Modifier la FDIR pour le health status GYRO pendant le mode TEST après l\'envoi des TC de contexte', 0),
(31, 'La procedure de WarmStart à utiliser se trouve dans la librairie cc_proc_warmstart. Elle porte le nom WS_miss5_<ctx_reprise>_PM<A ou B>(<tGPSWarmStart>).', 0),
(32, 'Vérifier que outputs et outputs_ctx sont accessibles en écriture', 0),
(33, 'Vérifier la date de la dernière calibration. Elle doit être < 15 jours sinon, il faut calibrer les gyros', 0),
(34, 'Mettre à jour le fichier gyrostd.cal dans $SGSE_HOME/CURRENT_CONF/CHR et $SGSE_HOME/CONF/CHR en fonction des gyros utilisés', 0),
(35, 'Modifier le fichier plbs.chr pour configurer le paquet DIODE sur le RT, la sous adresse, la fréquence souhaitée (si paquet diode nécessaire)', 0),
(36, 'Connecter l\'EBB POS 3 en fonction du PM utilisé (si besoin)', 0),
(37, 'Vérifier que l\'alimentation du DHU est ON', 0),
(38, 'Mettre ON les alimentations de l\'EBB POS 3 quand nécessaire', 0),
(39, 'ana_fonc <test> c v', 0),
(40, 'réception équipement: liste des documents à demander', 0),
(41, 'expédition équipement: liste des documents à fournir', 0);

-- --------------------------------------------------------

--
-- Table structure for table `logs`
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
-- Dumping data for table `logs`
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

-- --------------------------------------------------------

--
-- Table structure for table `motscles`
--

CREATE TABLE `motscles` (
  `IDMotsCles` int(11) NOT NULL,
  `NomMotsCles` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `motscles`
--

INSERT INTO `motscles` (`IDMotsCles`, `NomMotsCles`) VALUES
(25, 'TOUS'),
(26, 'TOUS'),
(28, 'PL'),
(29, 'POS3'),
(30, 'TOUS'),
(31, 'sauf deploiement'),
(32, 'TOUS'),
(33, 'MeO'),
(34, 'post RDP1'),
(35, 'annulé'),
(36, 'MeO'),
(37, 'GPS'),
(38, 'warmstart'),
(39, 'TOUS'),
(40, 'BF GYRO'),
(41, 'calibration'),
(42, 'BF GYRO'),
(43, 'PL'),
(44, 'PL'),
(45, 'POS3'),
(46, 'TOUS'),
(47, 'PL'),
(48, 'POS3'),
(49, 'TOUS'),
(50, 'TOUS'),
(51, 'TOUS');

-- --------------------------------------------------------

--
-- Table structure for table `passwordrequirements`
--

CREATE TABLE `passwordrequirements` (
  `id` int(10) UNSIGNED NOT NULL,
  `n` int(11) NOT NULL,
  `p` int(11) NOT NULL,
  `q` int(11) NOT NULL,
  `r` int(11) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `passwordrequirements`
--

INSERT INTO `passwordrequirements` (`id`, `n`, `p`, `q`, `r`, `reg_date`) VALUES
(3, 1, 1, 1, 1, '2024-06-17 12:02:54');

-- --------------------------------------------------------

--
-- Table structure for table `phases`
--

CREATE TABLE `phases` (
  `IDPhase` int(11) NOT NULL,
  `NomPhase` enum('Codage','Analyse','Execution','Preparation') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `phases`
--

INSERT INTO `phases` (`IDPhase`, `NomPhase`) VALUES
(20, 'Codage'),
(21, 'Codage'),
(22, 'Codage'),
(23, 'Codage'),
(24, 'Codage'),
(25, 'Codage'),
(26, 'Codage'),
(27, 'Codage'),
(28, 'Codage'),
(29, 'Execution'),
(30, 'Execution'),
(31, 'Execution'),
(32, 'Execution'),
(33, 'Execution'),
(34, 'Execution'),
(35, 'Execution'),
(36, 'Analyse'),
(37, 'Preparation'),
(38, 'Preparation'),
(39, 'Analyse'),
(40, 'Preparation');

-- --------------------------------------------------------

--
-- Table structure for table `pratiquemotscles`
--

CREATE TABLE `pratiquemotscles` (
  `IDBonnePratique` int(11) NOT NULL,
  `IDMotsCles` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `pratiquemotscles`
--

INSERT INTO `pratiquemotscles` (`IDBonnePratique`, `IDMotsCles`) VALUES
(26, 28),
(26, 29),
(27, 30),
(27, 31),
(28, 32),
(28, 33),
(29, 34),
(30, 35),
(31, 36),
(31, 37),
(31, 38),
(32, 39),
(33, 40),
(33, 41),
(34, 42),
(35, 43),
(36, 44),
(36, 45),
(37, 46),
(38, 47),
(38, 48),
(39, 49),
(40, 50),
(23, 25),
(24, 25),
(41, 25);

-- --------------------------------------------------------

--
-- Table structure for table `pratiquephases`
--

CREATE TABLE `pratiquephases` (
  `IDBonnePratique` int(11) NOT NULL,
  `IDPhase` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `pratiquephases`
--

INSERT INTO `pratiquephases` (`IDBonnePratique`, `IDPhase`) VALUES
(25, 22),
(26, 23),
(27, 24),
(28, 25),
(29, 26),
(30, 27),
(31, 28),
(32, 29),
(33, 30),
(34, 31),
(35, 32),
(36, 33),
(37, 34),
(38, 35),
(39, 36),
(40, 37),
(23, 20),
(24, 36),
(41, 37);

-- --------------------------------------------------------

--
-- Table structure for table `pratiqueprog`
--

CREATE TABLE `pratiqueprog` (
  `IDProgramme` int(11) NOT NULL,
  `IDBonnePratique` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `pratiqueprog`
--

INSERT INTO `pratiqueprog` (`IDProgramme`, `IDBonnePratique`) VALUES
(27, 25),
(28, 25),
(29, 26),
(30, 26),
(31, 27),
(32, 27),
(33, 28),
(34, 28),
(35, 29),
(36, 29),
(37, 30),
(38, 30),
(39, 31),
(40, 31),
(41, 32),
(42, 32),
(43, 33),
(44, 33),
(45, 34),
(46, 34),
(47, 35),
(48, 36),
(49, 37),
(50, 37),
(51, 38),
(52, 39),
(53, 39),
(54, 40),
(23, 24),
(24, 24),
(24, 41),
(54, 41),
(23, 23),
(24, 23);

-- --------------------------------------------------------

--
-- Table structure for table `programmes`
--

CREATE TABLE `programmes` (
  `IDProgramme` int(11) NOT NULL,
  `NomProgramme` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `programmes`
--

INSERT INTO `programmes` (`IDProgramme`, `NomProgramme`) VALUES
(23, 'PROG_1'),
(24, 'PROG_2'),
(25, 'PROG_1'),
(26, 'PROG_2'),
(27, 'PROG_1'),
(28, 'PROG_2'),
(29, 'PROG_1'),
(30, 'PROG_2'),
(31, 'PROG_1'),
(32, 'PROG_2'),
(33, 'PROG_1'),
(34, 'PROG_2'),
(35, 'PROG_1'),
(36, 'PROG_2'),
(37, 'PROG_1'),
(38, 'PROG_2'),
(39, 'PROG_1'),
(40, 'PROG_2'),
(41, 'PROG_1'),
(42, 'PROG_2'),
(43, 'PROG_1'),
(44, 'PROG_2'),
(45, 'PROG_1'),
(46, 'PROG_2'),
(47, 'PROG_2'),
(48, 'PROG_2'),
(49, 'PROG_1'),
(50, 'PROG_2'),
(51, 'PROG_2'),
(52, 'PROG_1'),
(53, 'PROG_2'),
(54, 'GENERIC'),
(55, 'GENERIC'),
(56, 'GENERIC'),
(57, 'PROG_1'),
(58, 'PROG_3');

-- --------------------------------------------------------

--
-- Table structure for table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `IDUtilisateur` int(11) NOT NULL,
  `NomUtilisateur` varchar(60) NOT NULL,
  `MotDePasse` varchar(255) NOT NULL,
  `TypeUtilisateur` tinyint(1) NOT NULL DEFAULT 0,
  `NBtentative` tinyint(4) NOT NULL DEFAULT 0,
  `Bloque` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `utilisateurs`
--

INSERT INTO `utilisateurs` (`IDUtilisateur`, `NomUtilisateur`, `MotDePasse`, `TypeUtilisateur`, `NBtentative`, `Bloque`) VALUES
(59, 'user', '$2y$10$6DogKJ8tYa5EqDMgjvtVx.A3CsoC2UokAiQgeN6bZGoGdjxY2Pgjm', 0, 2, 0),
(60, 'super', '$2y$10$1oNTMgB2oC.OlCR.BSgsS.XPaR6OuTAsfOoFUfgEwSa57RO8TfEH2', 2, 2, 0),
(61, 'admin', '$2y$10$FbX05rpNHXzTATNV1wmPkOFmjBcxXGwkUAHWx1e8GJzeV1EliGVde', 1, 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bonnespratiques`
--
ALTER TABLE `bonnespratiques`
  ADD PRIMARY KEY (`IDBonnePratique`);

--
-- Indexes for table `motscles`
--
ALTER TABLE `motscles`
  ADD PRIMARY KEY (`IDMotsCles`);

--
-- Indexes for table `passwordrequirements`
--
ALTER TABLE `passwordrequirements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `phases`
--
ALTER TABLE `phases`
  ADD PRIMARY KEY (`IDPhase`);

--
-- Indexes for table `pratiquemotscles`
--
ALTER TABLE `pratiquemotscles`
  ADD KEY `fk_motscles` (`IDMotsCles`),
  ADD KEY `fk_bp` (`IDBonnePratique`);

--
-- Indexes for table `pratiquephases`
--
ALTER TABLE `pratiquephases`
  ADD KEY `fk_bonnepratiques` (`IDBonnePratique`),
  ADD KEY `fk_phases` (`IDPhase`);

--
-- Indexes for table `pratiqueprog`
--
ALTER TABLE `pratiqueprog`
  ADD KEY `fk_bonneP` (`IDBonnePratique`),
  ADD KEY `fk_prog` (`IDProgramme`);

--
-- Indexes for table `programmes`
--
ALTER TABLE `programmes`
  ADD PRIMARY KEY (`IDProgramme`);

--
-- Indexes for table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`IDUtilisateur`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bonnespratiques`
--
ALTER TABLE `bonnespratiques`
  MODIFY `IDBonnePratique` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `motscles`
--
ALTER TABLE `motscles`
  MODIFY `IDMotsCles` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `passwordrequirements`
--
ALTER TABLE `passwordrequirements`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `phases`
--
ALTER TABLE `phases`
  MODIFY `IDPhase` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `programmes`
--
ALTER TABLE `programmes`
  MODIFY `IDProgramme` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `IDUtilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pratiquemotscles`
--
ALTER TABLE `pratiquemotscles`
  ADD CONSTRAINT `fk_bp` FOREIGN KEY (`IDBonnePratique`) REFERENCES `bonnespratiques` (`IDBonnePratique`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_motscles` FOREIGN KEY (`IDMotsCles`) REFERENCES `motscles` (`IDMotsCles`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pratiquephases`
--
ALTER TABLE `pratiquephases`
  ADD CONSTRAINT `fk_bonnepratiques` FOREIGN KEY (`IDBonnePratique`) REFERENCES `bonnespratiques` (`IDBonnePratique`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_phases` FOREIGN KEY (`IDPhase`) REFERENCES `phases` (`IDPhase`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pratiqueprog`
--
ALTER TABLE `pratiqueprog`
  ADD CONSTRAINT `fk_bonneP` FOREIGN KEY (`IDBonnePratique`) REFERENCES `bonnespratiques` (`IDBonnePratique`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_prog` FOREIGN KEY (`IDProgramme`) REFERENCES `programmes` (`IDProgramme`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
