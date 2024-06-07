-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 07, 2024 at 11:55 AM
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
(42, 'test', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `BonnesPratiques`
--
ALTER TABLE `BonnesPratiques`
  ADD PRIMARY KEY (`IDBonnePratique`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `BonnesPratiques`
--
ALTER TABLE `BonnesPratiques`
  MODIFY `IDBonnePratique` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
