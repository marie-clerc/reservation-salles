-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 21, 2021 at 01:14 PM
-- Server version: 5.7.31
-- PHP Version: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `reservationsalles`
--

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

DROP TABLE IF EXISTS `reservations`;
CREATE TABLE IF NOT EXISTS `reservations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `debut` datetime NOT NULL,
  `fin` datetime NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `titre`, `description`, `debut`, `fin`, `id_utilisateur`) VALUES
(25, 'fabio', 'fabio', '2021-01-22 09:00:00', '2021-01-22 10:00:00', 10),
(20, 'RESA LE LUNDI', 'A 8H', '2021-01-18 08:00:00', '2021-01-18 09:00:00', 8),
(21, 'RESA LE VENDREDI', 'A 8H', '2021-01-22 08:00:00', '2021-01-22 09:00:00', 8),
(22, 'RESA', 'RESA', '2021-01-19 15:00:00', '2021-01-19 16:00:00', 6),
(23, 'resa', 'resa', '2021-01-20 09:00:00', '2021-01-20 10:00:00', 9),
(24, 'mamma', 'mama', '2021-01-25 11:00:00', '2021-01-25 12:00:00', 10);

-- --------------------------------------------------------

--
-- Table structure for table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `login`, `password`) VALUES
(1, 'marie', '$2y$10$WcQhttXbG9m0nb4H6p7v/uXHgrabj/pV5j5UWD699mLuTXtjQlhNS'),
(2, 'sud', '$2y$10$n/DYbJfv82LnlZ3sqGETYOFsZ.QEnaGADakaRMu02NYHubETerm/i'),
(3, 'est', '$2y$10$wqaOncIzq.JoUA5Xu.QxxOe7zx6zrY2Y9oyb5rwLX6ooxepRAXGcq'),
(4, 'ouest', '$2y$10$FUYrmNjwRTMuAH7sqkEpDe7Mzd52GE/O7ABysNt1sWj0HgarQsjWe'),
(5, 'lala', '$2y$10$8Gq4hmYIHQEJCn7E6IJNluNyg7ezhcn35dPXakTlPBL1mDCx7JQ2e'),
(6, 'lili', '$2y$10$RafiJ/7BS.jJWlOPPngvoeFB.mpxlm5MqvefGGUu4NyNHZ8KzesrO'),
(7, 'mama2', '$2y$10$152I2Mw5vjUU8OWVPHB3QeUWpDRa.XCzPfOmlixbokFAUQPL/o1wu'),
(8, 'lulu', '$2y$10$vPHdhJzJ3yE16TdJzuuCeuyZA40Hxgifj5dRQPOyPGXLeegTNfGfi'),
(9, 'Joris2', '$2y$10$M3qnyg54fdi.z1zXO8kMZ.9JGahI4JkBUEVF.EIIr7w3FWp.CDMfi'),
(10, 'maria3', '$2y$10$Y8a4bQHb6KM2rAuaK9iije5BpBtGbM1FTJGKeX0lOoP6HmDkYFjj6');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
