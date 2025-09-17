-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 21, 2024 at 10:18 AM
-- Server version: 8.0.36
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cinetech`
--

-- --------------------------------------------------------

--
-- Table structure for table `commentaire`
--

CREATE TABLE `commentaire` (
  `id_commentaire` int NOT NULL,
  `content` text COLLATE utf8mb4_general_ci NOT NULL,
  `id_user` int NOT NULL,
  `id_media` int NOT NULL,
  `created_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `commentaire`
--

INSERT INTO `commentaire` (`id_commentaire`, `content`, `id_user`, `id_media`, `created_at`) VALUES
(5, 'test', 7, 257064, '2024-11-17 16:56:49'),
(7, 'bonjour', 1, 257064, '2024-11-17 18:55:13'),
(9, 'Film même pas térifiant , à évité de toutes urgence', 7, 1034541, '2024-11-19 13:50:19');

-- --------------------------------------------------------

--
-- Table structure for table `favoris`
--

CREATE TABLE `favoris` (
  `id_favori` int NOT NULL,
  `user_id` int NOT NULL,
  `element_id` int NOT NULL,
  `element_type` enum('movie','tv') COLLATE utf8mb4_general_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `poster_patch` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `favoris`
--

INSERT INTO `favoris` (`id_favori`, `user_id`, `element_id`, `element_type`, `title`, `poster_patch`) VALUES
(8, 1, 257064, 'tv', 'Volta por Cima', '/nyN8R0P1Hqwq7ksJz4O2BIAUd4W.jpg'),
(9, 1, 533535, 'movie', 'Deadpool & Wolverine', '/hw3Gfp8xLpoVewMbFyFVRsqyGlK.jpg'),
(10, 1, 1184918, 'movie', 'Le Robot sauvage', '/yJGZlmCmQGX6PGe9f0LtZffLHhZ.jpg'),
(11, 1, 124364, 'tv', 'FROM', '/cjXLrg4R7FRPFafvuQ3SSznQOd9.jpg'),
(12, 1, 1116490, 'movie', 'Cash Out', '/xBJnIvRdL0nDHgvivr6EgBQizes.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `firstname` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `firstname`, `lastname`, `email`, `password`, `created_at`) VALUES
(1, 'youssef', 'ghollamallah', 'youssef.gh@live.fr', '$2y$10$JHLMOdHTJtjpCZcom.Mq5.YwnvyBO2ZcCvvo55/uG9oXwOdz6qH0e', '2024-11-07 14:08:29'),
(5, 'youssef', 'ghollamallah', 'test@test.fr', '$2y$10$p6pqtzm6b1YeMK3eF6mrle/poyGBKr96.6c41VQwDGiNKGtawF9/O', '2024-11-08 10:08:18'),
(6, 'amin', 'matuer', 'amin@live.fr', '$2y$10$9DoyuSwtpGvGGQhiIGD22uoZFJ9g8/ES7PRXzMOm67kbbhPV6sQHi', '2024-11-08 15:28:25'),
(7, 'akaza', 'lunesuperieur', 'akaza@gmail.fr', '$2y$10$ILSDDOHowkGSUnhlbrxCDOKB71ff66ZPcUvNz.Qh.lO2qiRXUgHd.', '2024-11-17 14:14:05');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `commentaire`
--
ALTER TABLE `commentaire`
  ADD PRIMARY KEY (`id_commentaire`);

--
-- Indexes for table `favoris`
--
ALTER TABLE `favoris`
  ADD PRIMARY KEY (`id_favori`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `commentaire`
--
ALTER TABLE `commentaire`
  MODIFY `id_commentaire` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `favoris`
--
ALTER TABLE `favoris`
  MODIFY `id_favori` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
