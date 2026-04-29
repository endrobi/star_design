-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1:3306
-- Létrehozás ideje: 2026. Már 31. 08:16
-- Kiszolgáló verziója: 9.1.0
-- PHP verzió: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `adminoldal`
--
CREATE DATABASE IF NOT EXISTS `adminoldal` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_hungarian_ci;
USE `adminoldal`;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `admin_users`
--

DROP TABLE IF EXISTS `admin_users`;
CREATE TABLE IF NOT EXISTS `admin_users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `felhasznalonev` varchar(20) COLLATE utf8mb4_hungarian_ci NOT NULL,
  `jelszo` varchar(255) COLLATE utf8mb4_hungarian_ci NOT NULL,
  `jogkor` enum('admin','moderator') CHARACTER SET utf8mb4 COLLATE utf8mb4_hungarian_ci NOT NULL,
  `aktiv` varchar(1) COLLATE utf8mb4_hungarian_ci NOT NULL,
  `letrehozva` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `admin_users`
--

INSERT INTO `admin_users` (`id`, `felhasznalonev`, `jelszo`, `jogkor`, `aktiv`, `letrehozva`) VALUES
(6, 'moderator', '$2y$10$bhFBqNO2RNk6hrp0y8Ax7et40yiyFDHTJZi/a9eRWGkp0wmGcEp/6', 'moderator', '1', '2026-03-15 19:18:17'),
(5, 'admin', '$2y$10$ufigV491BUHFjXnYWC9Ef.F1TTgsMo7WQyCzipdTbC1XpvY2tuu2S', 'admin', '1', '2026-03-15 15:47:58'),
(4, 'admin', '$2y$10$ufigV491BUHFjXnYWC9Ef.F1TTgsMo7WQyCzipdTbC1XpvY2tuu2S', 'admin', '1', '2026-03-15 15:47:45');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `gallery`
--

DROP TABLE IF EXISTS `gallery`;
CREATE TABLE IF NOT EXISTS `gallery` (
  `id` int NOT NULL AUTO_INCREMENT,
  `file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_hungarian_ci NOT NULL,
  `title` varchar(150) COLLATE utf8mb4_hungarian_ci NOT NULL,
  `category` varchar(50) COLLATE utf8mb4_hungarian_ci NOT NULL,
  `upload_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `tervek`
--

DROP TABLE IF EXISTS `tervek`;
CREATE TABLE IF NOT EXISTS `tervek` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ugyfel_nev` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_hungarian_ci NOT NULL,
  `ugyfel_email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_hungarian_ci NOT NULL,
  `design_data` json NOT NULL,
  `allapot` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_hungarian_ci NOT NULL DEFAULT '''új terv''',
  `letrehozva` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `feldolgozva_ekkor` datetime DEFAULT NULL,
  `admin_megjegyzes` varchar(255) COLLATE utf8mb4_hungarian_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `tervek`
--

INSERT INTO `tervek` (`id`, `ugyfel_nev`, `ugyfel_email`, `design_data`, `allapot`, `letrehozva`, `feldolgozva_ekkor`, `admin_megjegyzes`) VALUES
(1, 'Vicus', 'vicus@gmail.hu', '{\"szin\": \"kek\", \"forma\": \"szogletes\", \"stilus\": \"modern\"}', 'feldolgozva', '2026-03-10 21:31:36', '2026-03-29 21:27:54', 'Válaszolva'),
(3, 'Éva', 'eva@gmail.com', '\"piros, zöld\"', 'feldolgozva', '2026-03-30 11:28:27', '2026-03-30 17:32:09', 'Válaszolva');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `uzenetek`
--

DROP TABLE IF EXISTS `uzenetek`;
CREATE TABLE IF NOT EXISTS `uzenetek` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ugyfel_nev` varchar(100) COLLATE utf8mb4_hungarian_ci NOT NULL,
  `ugyfel_email` varchar(100) COLLATE utf8mb4_hungarian_ci NOT NULL,
  `targy` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_hungarian_ci NOT NULL,
  `uzenet` text CHARACTER SET utf8mb4 COLLATE utf8mb4_hungarian_ci NOT NULL,
  `letrehozva` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `feldolgozva_ekkor` datetime DEFAULT NULL,
  `admin_megjegyzes` varchar(255) COLLATE utf8mb4_hungarian_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `uzenetek`
--

INSERT INTO `uzenetek` (`id`, `ugyfel_nev`, `ugyfel_email`, `targy`, `uzenet`, `letrehozva`, `feldolgozva_ekkor`, `admin_megjegyzes`) VALUES
(3, 'vicus', 'vicus@fremail.hu', 'teszt', 'hali', '2026-03-11 21:13:01', '2026-03-29 16:57:31', 'Válaszolva'),
(5, 'Vali', 'nnn@gmail.com', 'Próba', 'Ez egy próba üzenet', '2026-03-28 18:12:31', NULL, '');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
