-- phpMyAdmin SQL Dump
-- version 4.4.12
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Gegenereerd op: 18 nov 2015 om 11:32
-- Serverversie: 5.6.25
-- PHP-versie: 5.6.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `school`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `aw_events`
--

CREATE TABLE IF NOT EXISTS `aw_events` (
  `id` int(5) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` longtext NOT NULL,
  `rating` tinyint(1) NOT NULL DEFAULT '1',
  `date` date NOT NULL,
  `startdata_registration` date NOT NULL,
  `enddate_registration` date NOT NULL,
  `mail_confirm` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `aw_registrations`
--

CREATE TABLE IF NOT EXISTS `aw_registrations` (
  `user_id` int(10) NOT NULL,
  `workshop_id` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `aw_settings`
--

CREATE TABLE IF NOT EXISTS `aw_settings` (
  `id` int(3) NOT NULL,
  `setting_name` varchar(30) NOT NULL,
  `email_hosts` text NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `aw_settings`
--

INSERT INTO `aw_settings` (`id`, `setting_name`, `email_hosts`, `active`) VALUES
(1, 'default', 'student.alfa-college.nl,alfa-college.nl', 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `aw_users`
--

CREATE TABLE IF NOT EXISTS `aw_users` (
  `id` int(10) NOT NULL,
  `email` varchar(100) NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `password` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `aw_users`
--

INSERT INTO `aw_users` (`id`, `email`, `firstname`, `lastname`, `password`) VALUES
(0, 'yaron@student.alfa-college.nl', 'yaron', 'lambers', '4137619fa4feb48fc20db2829f639292');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `aw_workshops`
--

CREATE TABLE IF NOT EXISTS `aw_workshops` (
  `id` int(11) NOT NULL,
  `description` longtext NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `max_registration` int(5) NOT NULL,
  `location` varchar(40) NOT NULL,
  `event` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `aw_events`
--
ALTER TABLE `aw_events`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `aw_workshops`
--
ALTER TABLE `aw_workshops`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `aw_events`
--
ALTER TABLE `aw_events`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `aw_workshops`
--
ALTER TABLE `aw_workshops`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
