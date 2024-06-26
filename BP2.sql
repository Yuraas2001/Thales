-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 18 juin 2024 à 10:22
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.18

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
-- Structure de la table `bonnespratiques`
--

DROP TABLE IF EXISTS `bonnespratiques`;
CREATE TABLE IF NOT EXISTS `bonnespratiques` (
  `IDBonnePratique` int NOT NULL AUTO_INCREMENT,
  `Description` text NOT NULL,
  `Etat` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`IDBonnePratique`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `bonnespratiques`
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
(41, 'expédition équipement: liste des documents à fournir', 0),
(65, 'k', 0);

-- --------------------------------------------------------

--
-- Structure de la table `motscles`
--

DROP TABLE IF EXISTS `motscles`;
CREATE TABLE IF NOT EXISTS `motscles` (
  `IDMotsCles` int NOT NULL AUTO_INCREMENT,
  `NomMotsCles` varchar(60) NOT NULL,
  PRIMARY KEY (`IDMotsCles`)
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `motscles`
--

INSERT INTO `motscles` (`IDMotsCles`, `NomMotsCles`) VALUES
(25, 'TOUS'),
(26, 'TOUS'),
(27, ''),
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
(51, 'TOUS'),
(52, 'test'),
(53, 'test2'),
(54, 'khas'),
(55, 'Blue'),
(56, 'batata'),
(57, 'gggg'),
(58, 'n'),
(59, 'all'),
(60, 'hello'),
(61, 'jijiji'),
(62, 'oooooo');

-- --------------------------------------------------------

--
-- Structure de la table `passwordrequirements`
--

DROP TABLE IF EXISTS `passwordrequirements`;
CREATE TABLE IF NOT EXISTS `passwordrequirements` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `n` int NOT NULL,
  `p` int NOT NULL,
  `q` int NOT NULL,
  `r` int NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `passwordrequirements`
--

INSERT INTO `passwordrequirements` (`id`, `n`, `p`, `q`, `r`, `reg_date`) VALUES
(3, 1, 1, 1, 1, '2024-06-17 12:02:54');

-- --------------------------------------------------------

--
-- Structure de la table `phases`
--

DROP TABLE IF EXISTS `phases`;
CREATE TABLE IF NOT EXISTS `phases` (
  `IDPhase` int NOT NULL AUTO_INCREMENT,
  `NomPhase` enum('Codage','Analyse','Execution','Preparation') NOT NULL,
  PRIMARY KEY (`IDPhase`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `phases`
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
-- Structure de la table `pratiquemotscles`
--

DROP TABLE IF EXISTS `pratiquemotscles`;
CREATE TABLE IF NOT EXISTS `pratiquemotscles` (
  `IDBonnePratique` int NOT NULL,
  `IDMotsCles` int NOT NULL,
  KEY `fk_motscles` (`IDMotsCles`),
  KEY `fk_bp` (`IDBonnePratique`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `pratiquemotscles`
--

INSERT INTO `pratiquemotscles` (`IDBonnePratique`, `IDMotsCles`) VALUES
(25, 27),
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
(41, 25),
(65, 25);

-- --------------------------------------------------------

--
-- Structure de la table `pratiquephases`
--

DROP TABLE IF EXISTS `pratiquephases`;
CREATE TABLE IF NOT EXISTS `pratiquephases` (
  `IDBonnePratique` int NOT NULL,
  `IDPhase` int NOT NULL,
  KEY `fk_bonnepratiques` (`IDBonnePratique`),
  KEY `fk_phases` (`IDPhase`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `pratiquephases`
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
(41, 37),
(65, 20);

-- --------------------------------------------------------

--
-- Structure de la table `pratiqueprog`
--

DROP TABLE IF EXISTS `pratiqueprog`;
CREATE TABLE IF NOT EXISTS `pratiqueprog` (
  `IDProgramme` int NOT NULL,
  `IDBonnePratique` int NOT NULL,
  KEY `fk_bonneP` (`IDBonnePratique`),
  KEY `fk_prog` (`IDProgramme`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `pratiqueprog`
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
(23, 23),
(24, 23),
(23, 24),
(24, 24),
(54, 41),
(54, 65),
(58, 65);

-- --------------------------------------------------------

--
-- Structure de la table `programmes`
--

DROP TABLE IF EXISTS `programmes`;
CREATE TABLE IF NOT EXISTS `programmes` (
  `IDProgramme` int NOT NULL AUTO_INCREMENT,
  `NomProgramme` varchar(255) NOT NULL,
  PRIMARY KEY (`IDProgramme`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `programmes`
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
(58, 'PROG_3'),
(59, ''),
(60, ''),
(61, ''),
(62, ''),
(63, 'test'),
(67, 'scwcs'),
(68, 'PROG_800'),
(69, 'prog_300'),
(70, 'sxs');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `IDUtilisateur` int NOT NULL AUTO_INCREMENT,
  `NomUtilisateur` varchar(60) NOT NULL,
  `MotDePasse` varchar(255) NOT NULL,
  `TypeUtilisateur` tinyint(1) NOT NULL DEFAULT '0',
  `NBtentative` tinyint NOT NULL DEFAULT '0',
  `Bloque` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`IDUtilisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`IDUtilisateur`, `NomUtilisateur`, `MotDePasse`, `TypeUtilisateur`, `NBtentative`, `Bloque`) VALUES
(44, 'faysa', '$2y$10$PgVl7gVKHsa8EBC5bqGwWOj.fewpV804ixeZqm87MozO/KrQ.SLau', 0, 0, 0),
(45, 'nasser', '$2y$10$Ea12/Wa4P.QP1McAPENM0u.hfafILiZ1M17vLprOsvNc0MhhwVRcK', 1, 1, 0),
(48, 'cam', '$2y$10$dBvRQiAmYNh6/e8mUXIBYu5I7J1eWM/697p2XfT.rzeZZnRuPulLC', 2, 4, 0),
(49, 'test', '$2y$10$4j/cGyKgvrHpaiOJa2WR7.fc6QHCZDS0IdmE8tpj4V6sXARbLnxq.', 1, 0, 0),
(51, 'admin', '$2y$10$..o2h/dWsB1k/KupI2VnauGSWPysvW6NQnqCS4Od5XRN5IEaWIKmy', 1, 0, 0);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `pratiquemotscles`
--
ALTER TABLE `pratiquemotscles`
  ADD CONSTRAINT `fk_bp` FOREIGN KEY (`IDBonnePratique`) REFERENCES `bonnespratiques` (`IDBonnePratique`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_motscles` FOREIGN KEY (`IDMotsCles`) REFERENCES `motscles` (`IDMotsCles`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `pratiquephases`
--
ALTER TABLE `pratiquephases`
  ADD CONSTRAINT `fk_bonnepratiques` FOREIGN KEY (`IDBonnePratique`) REFERENCES `bonnespratiques` (`IDBonnePratique`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_phases` FOREIGN KEY (`IDPhase`) REFERENCES `phases` (`IDPhase`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `pratiqueprog`
--
ALTER TABLE `pratiqueprog`
  ADD CONSTRAINT `fk_bonneP` FOREIGN KEY (`IDBonnePratique`) REFERENCES `bonnespratiques` (`IDBonnePratique`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_prog` FOREIGN KEY (`IDProgramme`) REFERENCES `programmes` (`IDProgramme`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
