-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Ago 06, 2024 alle 18:32
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `edoc`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `admin`
--

CREATE TABLE `admin` (
  `aemail` varchar(255) NOT NULL,
  `apassword` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dump dei dati per la tabella `admin`
--

INSERT INTO `admin` (`aemail`, `apassword`) VALUES
('admin@edoc.com', '123');

-- --------------------------------------------------------

--
-- Struttura della tabella `appointment`
--

CREATE TABLE `appointment` (
  `appoid` int(11) NOT NULL,
  `scheduleid` int(11) NOT NULL,
  `docid` int(10) NOT NULL,
  `pemail` varchar(50) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `ora_inizio` time NOT NULL,
  `ora_fine` time NOT NULL,
  `docname` varchar(100) NOT NULL,
  `specialization` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dump dei dati per la tabella `appointment`
--

INSERT INTO `appointment` (`appoid`, `scheduleid`, `docid`, `pemail`, `data`, `ora_inizio`, `ora_fine`, `docname`, `specialization`) VALUES
(1, 0, 1, '1', '2022-06-03', '00:00:00', '00:00:00', '', ''),
(4, 10, 0, 'patient@edoc.com', '2024-08-16', '17:00:00', '18:00:00', '', ''),
(22, 16, 0, 'patient@edoc.com', '2024-08-21', '10:11:00', '11:11:00', '', ''),
(7, 10, 0, 'patient@edoc.com', '2024-08-16', '17:00:00', '18:00:00', '', ''),
(8, 10, 0, 'patient@edoc.com', '2024-08-16', '17:00:00', '18:00:00', '', ''),
(65, 20, 0, 'patient2@gmail.com', '2024-08-04', '11:11:00', '12:12:00', 'House', ''),
(10, 10, 0, 'patient@edoc.com', '2024-08-16', '17:00:00', '18:00:00', '', ''),
(64, 23, 0, 'patient2@gmail.com', '2024-08-06', '11:00:00', '18:00:00', '', ''),
(63, 20, 0, 'patient2@gmail.com', '2024-08-04', '11:11:00', '12:12:00', '', ''),
(17, 16, 0, 'patient@edoc.com', '2024-08-21', '10:11:00', '11:11:00', '', ''),
(18, 16, 0, 'patient@edoc.com', '2024-08-21', '10:11:00', '11:11:00', '', ''),
(19, 16, 0, 'patient@edoc.com', '2024-08-21', '10:11:00', '11:11:00', '', ''),
(20, 18, 0, 'patient@edoc.com', '2024-08-09', '11:11:00', '12:22:00', '', ''),
(21, 10, 0, 'patient@edoc.com', '2024-08-16', '17:00:00', '18:00:00', '', ''),
(23, 11, 0, 'patient@edoc.com', '2024-08-16', '15:00:00', '16:00:00', '', ''),
(24, 16, 0, 'patient@edoc.com', '2024-08-21', '10:11:00', '11:11:00', '', ''),
(25, 19, 0, 'patient@edoc.com', '2024-08-09', '11:11:00', '12:22:00', '', ''),
(42, 21, 0, 'patient@edoc.com', '2024-08-03', '16:00:00', '17:00:00', '', ''),
(135, 13, 0, 'patient@edoc.com', '2024-08-23', '11:11:00', '12:11:00', 'Test Doctor', ''),
(152, 33, 0, 'patient@edoc.com', '2024-08-12', '12:00:00', '16:00:00', 'doctor', 'dentist'),
(48, 22, 0, 'patient@edoc.com', '2024-08-05', '22:00:00', '23:00:00', '', ''),
(47, 22, 0, 'patient@edoc.com', '2024-08-05', '22:00:00', '23:00:00', '', ''),
(38, 11, 0, 'patient@edoc.com', '2024-08-16', '15:00:00', '16:00:00', '', ''),
(39, 20, 0, 'patient@edoc.com', '2024-08-04', '11:11:00', '12:12:00', '', ''),
(40, 12, 0, 'patient@edoc.com', '2024-08-14', '12:34:00', '13:00:00', '', ''),
(41, 20, 0, 'patient@edoc.com', '2024-08-04', '11:11:00', '12:12:00', '', ''),
(43, 11, 0, 'patient@edoc.com', '2024-08-16', '15:00:00', '16:00:00', '', ''),
(44, 11, 0, 'patient@edoc.com', '2024-08-16', '15:00:00', '16:00:00', '', ''),
(45, 18, 0, 'patient@edoc.com', '2024-08-09', '11:11:00', '12:22:00', '', ''),
(46, 20, 0, 'patient@edoc.com', '2024-08-04', '11:11:00', '12:12:00', '', ''),
(49, 21, 0, 'patient@edoc.com', '2024-08-03', '16:00:00', '17:00:00', '', ''),
(50, 22, 0, 'patient@edoc.com', '2024-08-05', '22:00:00', '23:00:00', '', ''),
(51, 12, 0, 'patient@edoc.com', '2024-08-14', '12:34:00', '13:00:00', '', ''),
(52, 20, 0, 'patient@edoc.com', '2024-08-04', '11:11:00', '12:12:00', '', ''),
(54, 17, 0, 'patient@edoc.com', '2024-08-21', '10:11:00', '11:11:00', '', ''),
(55, 20, 0, 'patient@edoc.com', '2024-08-04', '11:11:00', '12:12:00', '', ''),
(56, 20, 0, 'patient@edoc.com', '2024-08-04', '11:11:00', '12:12:00', '', ''),
(57, 20, 0, 'patien2@gmail.com', '2024-08-04', '11:11:00', '12:12:00', '', ''),
(58, 19, 0, 'patien2@gmail.com', '2024-08-09', '11:11:00', '12:22:00', '', ''),
(59, 20, 0, 'patien2@gmail.com', '2024-08-04', '11:11:00', '12:12:00', '', ''),
(60, 20, 0, 'patient@edoc.com', '2024-08-04', '11:11:00', '12:12:00', '', ''),
(68, 20, 0, 'patient2@gmail.com', '2024-08-04', '11:11:00', '12:12:00', 'House', ''),
(73, 20, 0, 'patient2@gmail.com', '2024-08-04', '11:11:00', '12:12:00', 'House', ''),
(75, 20, 0, 'patient2@gmail.com', '2024-08-04', '11:11:00', '12:12:00', 'House', ''),
(79, 20, 0, 'patient2@gmail.com', '2024-08-04', '11:11:00', '12:12:00', 'House', ''),
(154, 33, 0, 'patient@gmail.com', '2024-08-12', '12:00:00', '16:00:00', 'doctor', 'dentist'),
(153, 33, 0, 'patient@edoc.com', '2024-08-12', '12:00:00', '16:00:00', 'doctor', 'dentist'),
(141, 32, 0, 'patient@edoc.com', '2024-08-09', '11:11:00', '23:00:00', 'House', 'chirurgo'),
(142, 0, 0, 'patient@edoc.com', '0000-00-00', '00:00:00', '00:00:00', '', 'chirurgo'),
(143, 0, 0, 'patient@edoc.com', '0000-00-00', '00:00:00', '00:00:00', '', 'chirurgo'),
(144, 0, 0, 'patient@edoc.com', '0000-00-00', '00:00:00', '00:00:00', '', 'chirurgo'),
(145, 0, 0, 'patient2@gmail.com', '0000-00-00', '00:00:00', '00:00:00', '', '1'),
(146, 0, 0, 'patient2@gmail.com', '0000-00-00', '00:00:00', '00:00:00', '', 'chirurgo'),
(147, 31, 0, 'patient2@gmail.com', '2024-08-10', '11:11:00', '13:33:00', 'House', 'chirurgo'),
(148, 0, 0, 'patient2@gmail.com', '0000-00-00', '00:00:00', '00:00:00', '', ''),
(149, 0, 0, 'patient2@gmail.com', '0000-00-00', '00:00:00', '00:00:00', '', 'chirurgo'),
(150, 0, 0, 'patient2@gmail.com', '0000-00-00', '00:00:00', '00:00:00', '', 'chirurgo'),
(151, 33, 0, 'patient@edoc.com', '2024-08-12', '12:00:00', '16:00:00', 'doctor', 'dentist'),
(155, 31, 0, 'patient@edoc.com', '2024-08-10', '11:11:00', '13:33:00', 'House', 'chirurgo'),
(162, 35, 0, 'stefano@gmail.com', '2024-08-09', '11:00:00', '13:00:00', 'Doctor3', 'Osteopath'),
(157, 33, 0, 'stefano@gmail.com', '2024-08-12', '12:00:00', '16:00:00', 'doctor', 'dentist'),
(158, 33, 0, 'stefano@gmail.com', '2024-08-12', '12:00:00', '16:00:00', 'doctor', 'dentist'),
(160, 34, 0, 'stefano@gmail.com', '2024-08-22', '10:00:00', '12:00:00', 'doctor', 'dentist'),
(161, 33, 0, 'stefano@gmail.com', '2024-08-12', '12:00:00', '16:00:00', 'doctor', 'dentist'),
(163, 37, 0, 'stefano@gmail.com', '2024-08-06', '21:00:00', '22:00:00', 'House', 'surgeon');

-- --------------------------------------------------------

--
-- Struttura della tabella `doctor`
--

CREATE TABLE `doctor` (
  `docid` int(11) NOT NULL,
  `docemail` varchar(255) DEFAULT NULL,
  `docname` varchar(255) DEFAULT NULL,
  `docpassword` varchar(255) DEFAULT NULL,
  `docnic` varchar(15) DEFAULT NULL,
  `doctel` varchar(15) DEFAULT NULL,
  `specialization` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dump dei dati per la tabella `doctor`
--

INSERT INTO `doctor` (`docid`, `docemail`, `docname`, `docpassword`, `docnic`, `doctel`, `specialization`) VALUES
(1, 'doctor@gmail.com', 'doctor', '1234', '000000000', '0110000000', 'dentist'),
(5, 'house@gmail.com', 'House', 'house', '1223455', '656988587', 'surgeon'),
(3, 'Doctor3@gmail.com', 'Doctor3', 'doctor3', '2334', '123456788', 'Osteopath');

-- --------------------------------------------------------

--
-- Struttura della tabella `patient`
--

CREATE TABLE `patient` (
  `pid` int(11) NOT NULL,
  `pemail` varchar(255) DEFAULT NULL,
  `pname` varchar(255) DEFAULT NULL,
  `ppassword` varchar(255) DEFAULT NULL,
  `paddress` varchar(255) DEFAULT NULL,
  `pnic` varchar(15) DEFAULT NULL,
  `pdob` date DEFAULT NULL,
  `ptel` varchar(15) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dump dei dati per la tabella `patient`
--

INSERT INTO `patient` (`pid`, `pemail`, `pname`, `ppassword`, `paddress`, `pnic`, `pdob`, `ptel`) VALUES
(1, 'patient@gmail.com', 'Test Patient', '123', 'Sri Lankas', '0000000000', '2000-01-01', '0120000000'),
(3, 'stefano@gmail.com', 'stefano developer', 'stefano', 'Los Angeles 5th Avenue ', '1669856', '1980-06-01', '0734567896'),
(4, 'patient2@gmail.com', 'alfonso barrero', '123', 'calle susin 1', '12456887', '2010-09-08', '0734567896'),
(5, 'prova1@gmail.com', 'robert ciao', '123', 'avenu 2', '1245566', '2006-02-02', '12345678'),
(6, 'patien2@gmail.com', 'giorgio rossi', '123', 'via italia 11', '123344556', '2005-11-11', '123445566');

-- --------------------------------------------------------

--
-- Struttura della tabella `schedule`
--

CREATE TABLE `schedule` (
  `scheduleid` int(11) NOT NULL,
  `docid` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `ora_inizio` time DEFAULT NULL,
  `ora_fine` time DEFAULT NULL,
  `docemail` varchar(50) NOT NULL,
  `specialization` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dump dei dati per la tabella `schedule`
--

INSERT INTO `schedule` (`scheduleid`, `docid`, `title`, `data`, `ora_inizio`, `ora_fine`, `docemail`, `specialization`) VALUES
(1, '1', 'Test Session', '2050-01-01', '18:00:00', '00:00:50', '', ''),
(9, '1', 'osteopata', '2024-08-08', '17:19:00', '00:00:20', '', ''),
(3, '1', '12', '2022-06-10', '20:33:00', '00:00:01', '', ''),
(4, '1', '1', '2022-06-10', '12:32:00', '00:00:01', '', ''),
(5, '1', '1', '2022-06-10', '20:35:00', '00:00:01', '', ''),
(6, '1', '12', '2022-06-10', '20:35:00', '00:00:01', '', ''),
(7, '1', '1', '2022-06-24', '20:36:00', '00:00:01', '', ''),
(8, '1', '12', '2022-06-10', '13:33:00', '00:00:01', '', ''),
(35, NULL, NULL, '2024-08-09', '11:00:00', '13:00:00', 'doctor3@gmail.com', 'Osteopath'),
(12, NULL, NULL, '2024-08-14', '12:34:00', '13:00:00', 'doctor@edoc.com', ''),
(13, NULL, NULL, '2024-08-23', '11:11:00', '12:11:00', 'doctor@edoc.com', ''),
(14, NULL, NULL, '2024-08-23', '11:11:00', '12:11:00', 'doctor@edoc.com', ''),
(15, NULL, NULL, '2024-08-23', '11:11:00', '12:11:00', 'doctor@edoc.com', ''),
(38, NULL, NULL, '2024-08-07', '10:00:00', '11:11:00', 'house@gmail.com', 'surgeon'),
(34, NULL, NULL, '2024-08-22', '10:00:00', '12:00:00', 'doctor@gmail.com', 'dentist'),
(33, NULL, NULL, '2024-08-12', '12:00:00', '16:00:00', 'doctor@gmail.com', 'dentist'),
(36, NULL, NULL, '2024-08-08', '10:00:00', '11:00:00', 'house@gmail.com', 'surgeon'),
(37, NULL, NULL, '2024-08-06', '21:00:00', '22:00:00', 'house@gmail.com', 'surgeon');

-- --------------------------------------------------------

--
-- Struttura della tabella `specialties`
--

CREATE TABLE `specialties` (
  `id` int(2) NOT NULL,
  `sname` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dump dei dati per la tabella `specialties`
--

INSERT INTO `specialties` (`id`, `sname`) VALUES
(1, 'Accident and emergency medicine'),
(2, 'Allergology'),
(3, 'Anaesthetics'),
(4, 'Biological hematology'),
(5, 'Cardiology'),
(6, 'Child psychiatry'),
(7, 'Clinical biology'),
(8, 'Clinical chemistry'),
(9, 'Clinical neurophysiology'),
(10, 'Clinical radiology'),
(11, 'Dental, oral and maxillo-facial surgery'),
(12, 'Dermato-venerology'),
(13, 'Dermatology'),
(14, 'Endocrinology'),
(15, 'Gastro-enterologic surgery'),
(16, 'Gastroenterology'),
(17, 'General hematology'),
(18, 'General Practice'),
(19, 'General surgery'),
(20, 'Geriatrics'),
(21, 'Immunology'),
(22, 'Infectious diseases'),
(23, 'Internal medicine'),
(24, 'Laboratory medicine'),
(25, 'Maxillo-facial surgery'),
(26, 'Microbiology'),
(27, 'Nephrology'),
(28, 'Neuro-psychiatry'),
(29, 'Neurology'),
(30, 'Neurosurgery'),
(31, 'Nuclear medicine'),
(32, 'Obstetrics and gynecology'),
(33, 'Occupational medicine'),
(34, 'Ophthalmology'),
(35, 'Orthopaedics'),
(36, 'Otorhinolaryngology'),
(37, 'Paediatric surgery'),
(38, 'Paediatrics'),
(39, 'Pathology'),
(40, 'Pharmacology'),
(41, 'Physical medicine and rehabilitation'),
(42, 'Plastic surgery'),
(43, 'Podiatric Medicine'),
(44, 'Podiatric Surgery'),
(45, 'Psychiatry'),
(46, 'Public health and Preventive Medicine'),
(47, 'Radiology'),
(48, 'Radiotherapy'),
(49, 'Respiratory medicine'),
(50, 'Rheumatology'),
(51, 'Stomatology'),
(52, 'Thoracic surgery'),
(53, 'Tropical medicine'),
(54, 'Urology'),
(55, 'Vascular surgery'),
(56, 'Venereology');

-- --------------------------------------------------------

--
-- Struttura della tabella `webuser`
--

CREATE TABLE `webuser` (
  `email` varchar(255) NOT NULL,
  `usertype` char(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dump dei dati per la tabella `webuser`
--

INSERT INTO `webuser` (`email`, `usertype`) VALUES
('admin@edoc.com', 'a'),
('doctor@gmail.com', 'd'),
('patient@gmail.com', 'p'),
('doctor3@gmail.com', 'd'),
('stefano@gmail.com', 'p'),
('house@gmail.com', 'd'),
('patient2@gmail.com', 'p'),
('prova1@gmail.com', 'p'),
('patien2@gmail.com', 'p');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`aemail`);

--
-- Indici per le tabelle `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`appoid`),
  ADD KEY `pid` (`docid`);

--
-- Indici per le tabelle `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`docid`),
  ADD KEY `specialties` (`specialization`);

--
-- Indici per le tabelle `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`pid`);

--
-- Indici per le tabelle `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`scheduleid`),
  ADD KEY `docid` (`docid`);

--
-- Indici per le tabelle `specialties`
--
ALTER TABLE `specialties`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `webuser`
--
ALTER TABLE `webuser`
  ADD PRIMARY KEY (`email`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `appointment`
--
ALTER TABLE `appointment`
  MODIFY `appoid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=164;

--
-- AUTO_INCREMENT per la tabella `doctor`
--
ALTER TABLE `doctor`
  MODIFY `docid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT per la tabella `patient`
--
ALTER TABLE `patient`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT per la tabella `schedule`
--
ALTER TABLE `schedule`
  MODIFY `scheduleid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
