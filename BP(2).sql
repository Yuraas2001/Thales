-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 13 juin 2024 à 19:05
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
(49, 'test8', 0),
(50, 'PYTHON', 0),
(51, 'python2', 0),
(52, 'SIMO', 0),
(53, 'SIMO2023', 0),
(57, 'test10', 0),
(58, 'test20', 0),
(63, 'TEST400', 1),
(64, 'q<scsqc', 0);

-- --------------------------------------------------------

--
-- Structure de la table `motscles`
--

DROP TABLE IF EXISTS `motscles`;
CREATE TABLE IF NOT EXISTS `motscles` (
  `IDMotsCles` int NOT NULL AUTO_INCREMENT,
  `NomMotsCles` varchar(60) NOT NULL,
  PRIMARY KEY (`IDMotsCles`),
  UNIQUE KEY `unique_motscles` (`NomMotsCles`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `motscles`
--

INSERT INTO `motscles` (`IDMotsCles`, `NomMotsCles`) VALUES
(25, 'TOUS'),
(26, 'PL'),
(27, ''),
(28, 'POS3'),
(29, 'sauf deploiement'),
(30, 'MeO'),
(31, 'post RDP1'),
(32, 'annulé'),
(33, 'GPS'),
(34, 'warmstart'),
(35, 'BF GYRO'),
(36, 'calibration'),
(37, 'test'),
(38, 'test2'),
(39, 'khas');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `phases`
--

DROP TABLE IF EXISTS `phases`;
CREATE TABLE IF NOT EXISTS `phases` (
  `IDPhase` int NOT NULL AUTO_INCREMENT,
  `NomPhase` enum('Codage','Analyse','Execution','Preparation') NOT NULL,
  PRIMARY KEY (`IDPhase`),
  UNIQUE KEY `unique_phases` (`NomPhase`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `phases`
--

INSERT INTO `phases` (`IDPhase`, `NomPhase`) VALUES
(20, 'Codage'),
(29, 'Execution'),
(36, 'Analyse'),
(37, 'Preparation');

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
(28, 32),
(28, 33),
(29, 34),
(30, 35),
(31, 36),
(31, 37),
(31, 38),
(32, 39),
(33, 35), -- updated to valid ID
(34, 36), -- updated to valid ID
(35, 37), -- updated to valid ID
(36, 38), -- updated to valid ID
(37, 39), -- updated to valid ID
(38, 26), -- updated to valid ID
(39, 26), -- updated to valid ID
(40, 26), -- updated to valid ID
(41, 26), -- updated to valid ID
(49, 26), -- updated to valid ID
(50, 26), -- updated to valid ID
(51, 26), -- updated to valid ID
(52, 26), -- updated to valid ID
(57, 25),
(27, 25),
(27, 31),
(63, 28),
(64, 26); -- updated to valid ID

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
(25, 20), -- updated to valid ID
(26, 20), -- updated to valid ID
(28, 20), -- updated to valid ID
(29, 20), -- updated to valid ID
(30, 20), -- updated to valid ID
(31, 20), -- updated to valid ID
(32, 29),
(33, 29),
(34, 29),
(35, 29),
(36, 29),
(37, 29),
(38, 36),
(39, 36),
(40, 37),
(41, 37),
(57, 20), -- updated to valid ID
(58, 36),
(27, 20), -- updated to valid ID
(63, 29),
(64, 20); -- updated to valid ID

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
(23, 25), -- updated to valid ID
(24, 25), -- updated to valid ID
(23, 26), -- updated to valid ID
(24, 26), -- updated to valid ID
(23, 28), -- updated to valid ID
(24, 28), -- updated to valid ID
(23, 29), -- updated to valid ID
(24, 29), -- updated to valid ID
(23, 30), -- updated to valid ID
(24, 30), -- updated to valid ID
(23, 31), -- updated to valid ID
(24, 31), -- updated to valid ID
(23, 32), -- updated to valid ID
(24, 32), -- updated to valid ID
(23, 33), -- updated to valid ID
(24, 33), -- updated to valid ID
(23, 34), -- updated to valid ID
(24, 34), -- updated to valid ID
(23, 35), -- updated to valid ID
(24, 36), -- updated to valid ID
(23, 37), -- updated to valid ID
(24, 37), -- updated to valid ID
(23, 38), -- updated to valid ID
(24, 39), -- updated to valid ID
(23, 39), -- updated to valid ID
(24, 40), -- updated to valid ID
(23, 41), -- updated to valid ID
(24, 41), -- updated to valid ID
(25, 57), -- updated to valid ID
(23, 27), -- updated to valid ID
(24, 27), -- updated to valid ID
(23, 63), -- updated to valid ID
(24, 63), -- updated to valid ID
(25, 63); -- updated to valid ID

-- --------------------------------------------------------

--
-- Structure de la table `programmes`
--

DROP TABLE IF EXISTS `programmes`;
CREATE TABLE IF NOT EXISTS `programmes` (
  `IDProgramme` int NOT NULL AUTO_INCREMENT,
  `NomProgramme` varchar(255) NOT NULL,
  PRIMARY KEY (`IDProgramme`),
  UNIQUE KEY `unique_programmes` (`NomProgramme`)
) ENGINE=InnoDB AUTO_INCREMENT=125 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `programmes`
--

INSERT INTO `programmes` (`IDProgramme`, `NomProgramme`) VALUES
(23, 'PROG_1'),
(24, 'PROG_2'),
(25, 'GENERIC'),
(26, 'PROG_3');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `IDUtilisateur` int NOT NULL AUTO_INCREMENT,
  `NomUtilisateur` varchar(60) NOT NULL,
  `MotDePasse` varchar(255) NOT NULL,
  `TypeUtilisateur` enum('0','1','2') NOT NULL,
  `NBtentative` tinyint NOT NULL DEFAULT '0',
  `Bloque` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`IDUtilisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`IDUtilisateur`, `NomUtilisateur`, `MotDePasse`, `TypeUtilisateur`, `NBtentative`, `Bloque`) VALUES
(11, 'admin', '$2y$10$EPAyJStFI03IovEq8M.3FeUZY9QQI/8DqIrSsRiXFEptatWqfoA.e', '0', 0, 0),
(24, 'nasser', '$2y$10$VIEiNK4ngHOxAC0iPjMIY.Odk.5fimV7na/y/swhQQq0uqBzJnNVC', '1', 0, 0),
(44, 'qqsq', '$2y$10$5C9yqSstWJzmd3D2Jgoqi.4kmhM6/jG9EvHg5gW.JognFDAsYbMtm', '0', 0, 0),
(48, 'superadmin', '$2y$10$hMckynK.mFVdU6LhTJ0gfebPSBdvp7bZDN7Ek9f7sfZzf78ZDQHU.', '2', 4, 0),
(49, 'test', '$2y$10$Tt/1ImNtkonCtjtECWybFuV2dnJjjhjUjG1x37ytwsLo/eE73Cej2', '0', 0, 0),
(52, 'TEST', '$2y$10$JMB9.5kRTocgLqcLkujajehSugCJfSnougnFFmQqWthZDbNtlzISG', '', 0, 0),
(54, 'cQCQC', '$2y$10$2hLUQsEqiVWPn/TNt/GfFezuKBBLH4KKnt3rhNVb4i3eNBz4K54ee', '0', 0, 0),
(55, 'cdsc', '$2y$10$0aWoImr2EPjLykqORFdGNOQNE7nANp0cJT9tM1wN/mlPD7O50mVbq', '0', 0, 0),
(57, 'faysa', '$2y$10$bLhsWCzRauLIOHnfImlg7.7nxSi0i3tf.utYnh6FGPnpWSHGtlJLK', '', 0, 0);

--
-- Contraintes pour les tables déchargées
--

--
-- Indexes for table `BonnesPratiques`
--
ALTER TABLE `BonnesPratiques`
  ADD PRIMARY KEY (`IDBonnePratique`);

--
-- Indexes for table `MotsCles`
--
ALTER TABLE `MotsCles`
  ADD PRIMARY KEY (`IDMotsCles`);

--
-- Indexes for table `Phases`
--
ALTER TABLE `Phases`
  ADD PRIMARY KEY (`IDPhase`);

--
-- Indexes for table `PratiqueMotsCles`
--
ALTER TABLE `PratiqueMotsCles`
  ADD KEY `fk_motscles` (`IDMotsCles`),
  ADD KEY `fk_bp` (`IDBonnePratique`);

--
-- Indexes for table `PratiquePhases`
--
ALTER TABLE `PratiquePhases`
  ADD KEY `fk_bonnepratiques` (`IDBonnePratique`),
  ADD KEY `fk_phases` (`IDPhase`);

--
-- Indexes for table `PratiqueProg`
--
ALTER TABLE `PratiqueProg`
  ADD KEY `fk_bonneP` (`IDBonnePratique`),
  ADD KEY `fk_prog` (`IDProgramme`);

--
-- Indexes for table `Programmes`
--
ALTER TABLE `Programmes`
  ADD PRIMARY KEY (`IDProgramme`);

--
-- Indexes for table `Utilisateurs`
--
ALTER TABLE `Utilisateurs`
  ADD PRIMARY KEY (`IDUtilisateur`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `BonnesPratiques`
--
ALTER TABLE `BonnesPratiques`
  MODIFY `IDBonnePratique` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `MotsCles`
--
ALTER TABLE `MotsCles`
  MODIFY `IDMotsCles` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `Phases`
--
ALTER TABLE `Phases`
  MODIFY `IDPhase` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `Programmes`
--
ALTER TABLE `Programmes`
  MODIFY `IDProgramme` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `Utilisateurs`
--
ALTER TABLE `Utilisateurs`
  MODIFY `IDUtilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `PratiqueMotsCles`
--
ALTER TABLE `PratiqueMotsCles`
  ADD CONSTRAINT `fk_bp` FOREIGN KEY (`IDBonnePratique`) REFERENCES `BonnesPratiques` (`IDBonnePratique`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_motscles` FOREIGN KEY (`IDMotsCles`) REFERENCES `MotsCles` (`IDMotsCles`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `PratiquePhases`
--
ALTER TABLE `PratiquePhases`
  ADD CONSTRAINT `fk_bonnepratiques` FOREIGN KEY (`IDBonnePratique`) REFERENCES `BonnesPratiques` (`IDBonnePratique`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_phases` FOREIGN KEY (`IDPhase`) REFERENCES `Phases` (`IDPhase`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `PratiqueProg`
--
ALTER TABLE `PratiqueProg`
  ADD CONSTRAINT `fk_bonneP` FOREIGN KEY (`IDBonnePratique`) REFERENCES `BonnesPratiques` (`IDBonnePratique`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_prog` FOREIGN KEY (`IDProgramme`) REFERENCES `Programmes` (`IDProgramme`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
