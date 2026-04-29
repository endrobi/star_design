-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1:3306
-- Létrehozás ideje: 2026. Ápr 29. 18:50
-- Kiszolgáló verziója: 8.4.7
-- PHP verzió: 8.3.28

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
CREATE DATABASE IF NOT EXISTS `adminoldal` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `adminoldal`;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `admin_users`
--

DROP TABLE IF EXISTS `admin_users`;
CREATE TABLE IF NOT EXISTS `admin_users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `felhasznalonev` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_hungarian_ci NOT NULL,
  `jelszo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_hungarian_ci NOT NULL,
  `jogkor` enum('admin','moderator') CHARACTER SET utf8mb4 COLLATE utf8mb4_hungarian_ci NOT NULL,
  `aktiv` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_hungarian_ci NOT NULL,
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
  `title` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_hungarian_ci NOT NULL,
  `category` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_hungarian_ci NOT NULL,
  `upload_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

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
  `admin_megjegyzes` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_hungarian_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `tervek`
--

INSERT INTO `tervek` (`id`, `ugyfel_nev`, `ugyfel_email`, `design_data`, `allapot`, `letrehozva`, `feldolgozva_ekkor`, `admin_megjegyzes`) VALUES
(1, 'Vicus', 'vicus@gmail.hu', '{\"szin\": \"kek\", \"forma\": \"szogletes\", \"stilus\": \"modern\"}', 'feldolgozva', '2026-03-10 21:31:36', '2026-03-29 21:27:54', 'Válaszolva'),
(3, 'Éva', 'eva@gmail.com', '\"piros, zöld\"', 'feldolgozva', '2026-03-30 11:28:27', '2026-03-30 17:32:09', 'Válaszolva'),
(5, 'Robi', 'endresz.robi@gmail.com', '{\"links\": \"\", \"notes\": \"Tesztelési folyamat vége.\", \"colors\": [\"#0972E1\", \"#08C95F\"], \"styles\": [\"bold\", \"modern\", \"elegant\"], \"hasText\": \"true\", \"categories\": [\"polo\"], \"description\": \"\", \"textContent\": \"Hello World!\", \"favoriteFonts\": [\"Comic Sans MS\", \"Brush Script MT\"]}', 'uj', '2026-04-27 19:53:30', NULL, ''),
(6, 'Robi', 'Endresz.robi@gmail.com', '{\"links\": \"\", \"notes\": \"\", \"colors\": [\"#353FB1\", \"#149F2B\"], \"styles\": [\"playful\", \"nature\", \"bold\"], \"hasText\": \"true\", \"categories\": [\"polo\"], \"description\": \"\", \"textContent\": \"Hello World!\", \"favoriteFonts\": [\"Comic Sans MS\", \"Brush Script MT\"]}', 'uj', '2026-04-28 16:16:20', NULL, '');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `uzenetek`
--

DROP TABLE IF EXISTS `uzenetek`;
CREATE TABLE IF NOT EXISTS `uzenetek` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ugyfel_nev` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_hungarian_ci NOT NULL,
  `ugyfel_email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_hungarian_ci NOT NULL,
  `targy` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_hungarian_ci NOT NULL,
  `uzenet` text CHARACTER SET utf8mb4 COLLATE utf8mb4_hungarian_ci NOT NULL,
  `letrehozva` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `feldolgozva_ekkor` datetime DEFAULT NULL,
  `admin_megjegyzes` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_hungarian_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `uzenetek`
--

INSERT INTO `uzenetek` (`id`, `ugyfel_nev`, `ugyfel_email`, `targy`, `uzenet`, `letrehozva`, `feldolgozva_ekkor`, `admin_megjegyzes`) VALUES
(3, 'vicus', 'vicus@fremail.hu', 'teszt', 'hali', '2026-03-11 21:13:01', '2026-03-29 16:57:31', 'Válaszolva'),
(5, 'Vali', 'nnn@gmail.com', 'Próba', 'Ez egy próba üzenet', '2026-03-28 18:12:31', NULL, ''),
(10, 'Robi', 'endresz.robi@gmail.com', 'Kapcsolati űrlap üzenet', 'Próbaüzenet', '2026-04-27 18:31:33', '2026-04-27 20:31:52', 'Sikeres teszt'),
(11, 'Robi', 'endresz.robi@gmail.com', 'Kapcsolati űrlap üzenet', 'Halihó', '2026-04-29 18:39:21', NULL, '');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
