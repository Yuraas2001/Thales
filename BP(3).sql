-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 17, 2024 at 10:41 AM
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
-- Database: `BP`
--

-- --------------------------------------------------------

--
-- Table structure for table `BonnesPratiques`
--

CREATE TABLE `BonnesPratiques` (
  `IDBonnePratique` int(11) NOT NULL,
  `Description` text NOT NULL,
  `Etat` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `BonnesPratiques`
--

INSERT INTO `BonnesPratiques` (`IDBonnePratique`, `Description`, `Etat`) VALUES
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
(42, 'test', 0),
(43, 'khas', 0);

-- --------------------------------------------------------

--
-- Table structure for table `MotsCles`
--

CREATE TABLE `MotsCles` (
  `IDMotsCles` int(11) NOT NULL,
  `NomMotsCles` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `MotsCles`
--

INSERT INTO `MotsCles` (`IDMotsCles`, `NomMotsCles`) VALUES
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
(54, 'khas');

-- --------------------------------------------------------

--
-- Table structure for table `PasswordRequirements`
--

CREATE TABLE `PasswordRequirements` (
  `id` int(6) UNSIGNED NOT NULL,
  `n` int(3) NOT NULL,
  `p` int(3) NOT NULL,
  `q` int(3) NOT NULL,
  `r` int(3) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `PasswordRequirements`
--

INSERT INTO `PasswordRequirements` (`id`, `n`, `p`, `q`, `r`, `reg_date`) VALUES
(3, 1, 1, 1, 1, '2024-06-12 17:23:11');

-- --------------------------------------------------------

--
-- Table structure for table `Phases`
--

CREATE TABLE `Phases` (
  `IDPhase` int(11) NOT NULL,
  `NomPhase` enum('Codage','Analyse','Execution','Preparation') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `Phases`
--

INSERT INTO `Phases` (`IDPhase`, `NomPhase`) VALUES
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
-- Table structure for table `PratiqueMotsCles`
--

CREATE TABLE `PratiqueMotsCles` (
  `IDBonnePratique` int(11) NOT NULL,
  `IDMotsCles` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `PratiqueMotsCles`
--

INSERT INTO `PratiqueMotsCles` (`IDBonnePratique`, `IDMotsCles`) VALUES
(23, 25),
(24, 26),
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
(41, 51),
(42, 52),
(42, 53),
(43, 54);

-- --------------------------------------------------------

--
-- Table structure for table `PratiquePhases`
--

CREATE TABLE `PratiquePhases` (
  `IDBonnePratique` int(11) NOT NULL,
  `IDPhase` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `PratiquePhases`
--

INSERT INTO `PratiquePhases` (`IDBonnePratique`, `IDPhase`) VALUES
(23, 20),
(24, 21),
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
(41, 38),
(42, 39),
(43, 40);

-- --------------------------------------------------------

--
-- Table structure for table `PratiqueProg`
--

CREATE TABLE `PratiqueProg` (
  `IDProgramme` int(11) NOT NULL,
  `IDBonnePratique` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `PratiqueProg`
--

INSERT INTO `PratiqueProg` (`IDProgramme`, `IDBonnePratique`) VALUES
(23, 23),
(24, 23),
(25, 24),
(26, 24),
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
(55, 41),
(56, 42),
(57, 42),
(58, 43);

-- --------------------------------------------------------

--
-- Table structure for table `Programmes`
--

CREATE TABLE `Programmes` (
  `IDProgramme` int(11) NOT NULL,
  `NomProgramme` enum('GENERIC','PROG_1','PROG_2','PROG_3') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `Programmes`
--

INSERT INTO `Programmes` (`IDProgramme`, `NomProgramme`) VALUES
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
-- Table structure for table `Utilisateurs`
--

CREATE TABLE `Utilisateurs` (
  `IDUtilisateur` int(11) NOT NULL,
  `NomUtilisateur` varchar(60) NOT NULL,
  `MotDePasse` varchar(255) NOT NULL,
  `TypeUtilisateur` tinyint(1) NOT NULL DEFAULT 0,
  `NBtentative` tinyint(3) NOT NULL DEFAULT 0,
  `Bloque` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `Utilisateurs`
--

INSERT INTO `Utilisateurs` (`IDUtilisateur`, `NomUtilisateur`, `MotDePasse`, `TypeUtilisateur`, `NBtentative`, `Bloque`) VALUES
(11, 'admin', '$2y$10$m8KAMbBNgX9TV4nzcrx.jO5MnjqahFxVApektFj0B2jfeQW/UQUS6', 1, 0, 0),
(12, 'khawla', '$2y$10$yqFqAn5V9rQfMtssbJY9rens3d4Reo5iQvK5uoSr4KagfBEn6O9ia', 0, 2, 0),
(22, 'faysa', '$2y$10$g0LbwDdsn18FZWwk9aIAY.T.MBtlZkX0.OjD2/M.CVNqUW/dRIDg.', 0, 0, 0),
(24, 'nasser', '$2y$10$7h3dYyr0d.zRPtOd4cgV2.b3T.M0LRt3M3pdSoH8cTIgI7H30G.4u', 1, 1, 0),
(25, 'cam', '$2y$10$U.TFsfVwI.F.6k1ft2/H9.A/8RzlZdB2ziUY5MqowsYaMM/aJd3SC', 1, 0, 0);

--
-- Indexes for dumped tables
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
-- Indexes for table `PasswordRequirements`
--
ALTER TABLE `PasswordRequirements`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `IDBonnePratique` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `MotsCles`
--
ALTER TABLE `MotsCles`
  MODIFY `IDMotsCles` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `PasswordRequirements`
--
ALTER TABLE `PasswordRequirements`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `Phases`
--
ALTER TABLE `Phases`
  MODIFY `IDPhase` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `Programmes`
--
ALTER TABLE `Programmes`
  MODIFY `IDProgramme` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `Utilisateurs`
--
ALTER TABLE `Utilisateurs`
  MODIFY `IDUtilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

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
