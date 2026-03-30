-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Apr 23, 2024 alle 14:46
-- Versione del server: 10.4.28-MariaDB
-- Versione PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `swbd`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `allenamenti`
--

CREATE TABLE `allenamenti` (
  `id` int(11) NOT NULL,
  `gruppo` varchar(20) DEFAULT 'altro',
  `nome` varchar(20) NOT NULL,
  `username` varchar(15) NOT NULL,
  `giorno` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `peso`
--

CREATE TABLE `peso` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `peso` decimal(4,1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `peso`
--

INSERT INTO `peso` (`id`, `username`, `data`, `peso`) VALUES
(1, 'elio1234', '2023-04-24', 95.0),
(4, 'elio1234', '2023-05-24', 93.0),
(5, 'elio1234', '2023-06-24', 92.5),
(6, 'elio1234', '2023-07-24', 91.0),
(7, 'elio1234', '2023-08-24', 90.0),
(8, 'elio1234', '2023-09-24', 89.0),
(9, 'elio1234', '2023-10-24', 88.0),
(11, 'elio1234', '2023-11-07', 86.0);

-- --------------------------------------------------------

--
-- Struttura della tabella `users`
--

CREATE TABLE `users` (
  `nome` varchar(12) NOT NULL,
  `cognome` varchar(12) NOT NULL,
  `username` varchar(15) NOT NULL,
  `altezza` int(3) NOT NULL,
  `pesoAttuale` float NOT NULL,
  `pesoDesiderato` float NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `users`
--

INSERT INTO `users` (`nome`, `cognome`, `username`, `altezza`, `pesoAttuale`, `pesoDesiderato`, `password`) VALUES
('Elio', 'Fava', 'elio1234', 177, 86, 85, '$2y$10$7tBiKvQDumVVVAKS0KKAdu7gJpMpAK0EgCUPz4nLHx8AW1T7Busbu');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `allenamenti`
--
ALTER TABLE `allenamenti`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`);

--
-- Indici per le tabelle `peso`
--
ALTER TABLE `peso`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`);

--
-- Indici per le tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`username`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `allenamenti`
--
ALTER TABLE `allenamenti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `peso`
--
ALTER TABLE `peso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `allenamenti`
--
ALTER TABLE `allenamenti`
  ADD CONSTRAINT `allenamenti_ibfk_1` FOREIGN KEY (`username`) REFERENCES `users` (`username`);

--
-- Limiti per la tabella `peso`
--
ALTER TABLE `peso`
  ADD CONSTRAINT `peso_ibfk_1` FOREIGN KEY (`username`) REFERENCES `users` (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
