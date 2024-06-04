-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 28, 2024 at 02:54 PM
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
(7, 'test', 0),
(8, 'pcamf', 0),
(9, 'qdqsd', 0),
(10, 'p', 0);

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
(5, 'test'),
(6, 'pcam'),
(7, 'qdqddq'),
(8, 'p');

-- --------------------------------------------------------

--
-- Table structure for table `Phases`
--

CREATE TABLE `Phases` (
  `IDPhase` int(11) NOT NULL,
  `NomPhase` enum('Codage','Analyse','Execution','Preparation') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
(7, 5),
(8, 6),
(9, 7),
(10, 8);

-- --------------------------------------------------------

--
-- Table structure for table `PratiquePhases`
--

CREATE TABLE `PratiquePhases` (
  `IDBonnePratique` int(11) NOT NULL,
  `IDPhase` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `PratiqueProg`
--

CREATE TABLE `PratiqueProg` (
  `IDProgramme` int(11) NOT NULL,
  `IDBonnePratique` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Programmes`
--

CREATE TABLE `Programmes` (
  `IDProgramme` int(11) NOT NULL,
  `NomProgramme` enum('GENERIC','PROG_1','PROG_2','PROG_3') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
(10, 'nasser', 'nasser', 1, 0, 0),
(11, 'admin', 'admin', 1, 0, 0),
(12, 'khawla', 'khawla', 0, 0, 0);

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
  MODIFY `IDBonnePratique` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `MotsCles`
--
ALTER TABLE `MotsCles`
  MODIFY `IDMotsCles` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `Phases`
--
ALTER TABLE `Phases`
  MODIFY `IDPhase` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `Programmes`
--
ALTER TABLE `Programmes`
  MODIFY `IDProgramme` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `Utilisateurs`
--
ALTER TABLE `Utilisateurs`
  MODIFY `IDUtilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

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
