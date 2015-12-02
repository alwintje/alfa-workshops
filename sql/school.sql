-- phpMyAdmin SQL Dump
-- version 4.5.0.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Gegenereerd op: 01 dec 2015 om 22:39
-- Serverversie: 10.0.17-MariaDB
-- PHP-versie: 5.6.14

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

CREATE TABLE `aw_events` (
  `id` int(5) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` longtext NOT NULL,
  `rating` tinyint(1) NOT NULL DEFAULT '1',
  `event_date` date NOT NULL,
  `startdate_registration` date NOT NULL,
  `enddate_registration` date NOT NULL,
  `mail_confirm` tinyint(1) NOT NULL DEFAULT '1',
  `max_registrations` int(2) NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `aw_events`
--

INSERT INTO `aw_events` (`id`, `name`, `description`, `rating`, `event_date`, `startdate_registration`, `enddate_registration`, `mail_confirm`, `max_registrations`, `active`) VALUES
(1, 'Kerst workshop', 'Deze kerstwork shop is epic', 1, '2015-11-20', '2015-11-21', '2015-11-27', 1, 0, 1),
(2, 'Kerst workshop deel 2', 'Deel 2 van de workshop', 1, '2015-11-18', '2015-11-23', '2015-11-28', 1, 0, 0);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `aw_registrations`
--

CREATE TABLE `aw_registrations` (
  `user_id` int(10) NOT NULL,
  `workshop_id` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `aw_registrations`
--

INSERT INTO `aw_registrations` (`user_id`, `workshop_id`) VALUES
(1, 2);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `aw_settings`
--

CREATE TABLE `aw_settings` (
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

CREATE TABLE `aw_users` (
  `id` int(10) NOT NULL,
  `email` varchar(100) NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `password` varchar(32) NOT NULL,
  `role` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `aw_users`
--

INSERT INTO `aw_users` (`id`, `email`, `firstname`, `lastname`, `password`, `role`) VALUES
(0, 'yaron@student.alfa-college.nl', 'yaron', 'lambers', '4137619fa4feb48fc20db2829f639292', 0);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `aw_workshops`
--

CREATE TABLE `aw_workshops` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` longtext NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `max_registration` int(5) NOT NULL,
  `location` varchar(40) NOT NULL,
  `event` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `aw_workshops`
--

INSERT INTO `aw_workshops` (`id`, `name`, `description`, `start_time`, `end_time`, `max_registration`, `location`, `event`) VALUES
(1, 'Fietsen met de kerstman', 'Lekker op een crosstrainer', '13:00:00', '15:00:00', 20, 'coevorden', 1),
(2, 'Kleien met de kerstman', 'Kleien met de kerstman', '13:00:00', '15:00:00', 20, 'coevorden', 2),
(3, 'Fietsen', 'owhjeahdsfsdfsfdfsd', '09:21:00', '18:26:00', 15, 'hardenberg', 2);

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `aw_events`
--
ALTER TABLE `aw_events`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `aw_users`
--
ALTER TABLE `aw_users`
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
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT voor een tabel `aw_workshops`
--
ALTER TABLE `aw_workshops`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
