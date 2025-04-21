-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 26, 2025 at 01:36 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mnhs`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `level_type` varchar(255) NOT NULL,
  `year_level` int(11) NOT NULL,
  `section` varchar(255) NOT NULL,
  `strand` varchar(255) DEFAULT NULL,
  `semester` int(11) DEFAULT NULL,
  `adviser_id` bigint(20) UNSIGNED DEFAULT NULL,
  `school_year` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `level_type`, `year_level`, `section`, `strand`, `semester`, `adviser_id`, `school_year`, `created_at`, `updated_at`) VALUES
(1, 'junior', 7, 'A', NULL, NULL, NULL, NULL, '2025-03-24 22:43:54', '2025-03-25 05:23:17'),
(2, 'senior', 11, 'A', 'STEM', 1, NULL, NULL, '2025-03-24 22:46:31', '2025-03-25 06:11:24'),
(3, 'junior', 8, 'A', NULL, NULL, 2, NULL, '2025-03-25 05:45:36', '2025-03-25 05:45:36'),
(4, 'senior', 12, 'A', 'STEM', 1, 6, NULL, '2025-03-25 06:12:36', '2025-03-25 06:12:36');

-- --------------------------------------------------------

--
-- Table structure for table `class_students`
--

CREATE TABLE `class_students` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `class_id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `is_promoted` tinyint(1) NOT NULL DEFAULT 0,
  `adviser_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `class_students`
--

INSERT INTO `class_students` (`id`, `class_id`, `student_id`, `is_promoted`, `adviser_name`, `created_at`, `updated_at`) VALUES
(1, 1, 13, 1, 'Ronald Monzon', '2025-03-24 22:43:54', '2025-03-25 05:23:17'),
(2, 1, 14, 1, 'Ronald Monzon', '2025-03-24 22:43:54', '2025-03-25 05:23:17'),
(3, 1, 15, 1, 'Ronald Monzon', '2025-03-24 22:43:54', '2025-03-25 05:23:17'),
(4, 1, 16, 1, 'Ronald Monzon', '2025-03-24 22:43:54', '2025-03-25 05:23:17'),
(5, 1, 17, 1, 'Ronald Monzon', '2025-03-24 22:43:54', '2025-03-25 05:23:17'),
(6, 1, 18, 1, 'Ronald Monzon', '2025-03-24 22:43:54', '2025-03-25 05:23:17'),
(7, 1, 19, 1, 'Ronald Monzon', '2025-03-24 22:43:54', '2025-03-25 05:23:17'),
(8, 1, 20, 1, 'Ronald Monzon', '2025-03-24 22:43:54', '2025-03-25 05:23:17'),
(9, 1, 21, 1, 'Ronald Monzon', '2025-03-24 22:43:54', '2025-03-25 05:23:17'),
(10, 1, 22, 1, 'Ronald Monzon', '2025-03-24 22:43:54', '2025-03-25 05:23:17'),
(11, 2, 23, 1, 'Mary Jun Palima', '2025-03-24 22:46:31', '2025-03-25 06:11:24'),
(12, 2, 24, 1, 'Mary Jun Palima', '2025-03-24 22:46:31', '2025-03-25 06:11:24'),
(13, 2, 25, 1, 'Mary Jun Palima', '2025-03-24 22:46:31', '2025-03-25 06:11:24'),
(14, 2, 26, 1, 'Mary Jun Palima', '2025-03-24 22:46:31', '2025-03-25 06:11:24'),
(15, 2, 27, 1, 'Mary Jun Palima', '2025-03-24 22:46:31', '2025-03-25 06:11:24'),
(16, 2, 28, 1, 'Mary Jun Palima', '2025-03-24 22:46:31', '2025-03-25 06:11:24'),
(17, 2, 29, 1, 'Mary Jun Palima', '2025-03-24 22:46:31', '2025-03-25 06:11:24'),
(18, 2, 30, 1, 'Mary Jun Palima', '2025-03-24 22:46:31', '2025-03-25 06:11:24'),
(19, 2, 31, 1, 'Mary Jun Palima', '2025-03-24 22:46:31', '2025-03-25 06:11:24'),
(20, 2, 32, 1, 'Mary Jun Palima', '2025-03-24 22:46:31', '2025-03-25 06:11:24'),
(21, 3, 14, 0, NULL, '2025-03-25 05:45:36', '2025-03-25 05:45:36'),
(22, 3, 22, 0, NULL, '2025-03-25 05:45:36', '2025-03-25 05:45:36'),
(23, 3, 15, 0, NULL, '2025-03-25 05:45:36', '2025-03-25 05:45:36'),
(24, 3, 20, 0, NULL, '2025-03-25 05:45:36', '2025-03-25 05:45:36'),
(25, 3, 13, 0, NULL, '2025-03-25 05:45:36', '2025-03-25 05:45:36'),
(26, 3, 16, 0, NULL, '2025-03-25 05:45:36', '2025-03-25 05:45:36'),
(27, 3, 18, 0, NULL, '2025-03-25 05:45:36', '2025-03-25 05:45:36'),
(28, 3, 21, 0, NULL, '2025-03-25 05:45:36', '2025-03-25 05:45:36'),
(29, 3, 19, 0, NULL, '2025-03-25 05:45:36', '2025-03-25 05:45:36'),
(30, 3, 17, 0, NULL, '2025-03-25 05:45:36', '2025-03-25 05:45:36'),
(31, 4, 23, 0, NULL, '2025-03-25 06:12:36', '2025-03-25 06:12:36'),
(32, 4, 24, 0, NULL, '2025-03-25 06:12:36', '2025-03-25 06:12:36'),
(33, 4, 25, 0, NULL, '2025-03-25 06:12:36', '2025-03-25 06:12:36'),
(34, 4, 26, 0, NULL, '2025-03-25 06:12:36', '2025-03-25 06:12:36'),
(35, 4, 27, 0, NULL, '2025-03-25 06:12:36', '2025-03-25 06:12:36'),
(36, 4, 28, 0, NULL, '2025-03-25 06:12:36', '2025-03-25 06:12:36'),
(37, 4, 29, 0, NULL, '2025-03-25 06:12:36', '2025-03-25 06:12:36'),
(38, 4, 30, 0, NULL, '2025-03-25 06:12:36', '2025-03-25 06:12:36'),
(39, 4, 31, 0, NULL, '2025-03-25 06:12:36', '2025-03-25 06:12:36'),
(40, 4, 32, 0, NULL, '2025-03-25 06:12:36', '2025-03-25 06:12:36');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

CREATE TABLE `grades` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `subject_id` bigint(20) UNSIGNED NOT NULL,
  `grade` decimal(5,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `period` int(11) NOT NULL DEFAULT 1,
  `period_type` varchar(255) NOT NULL DEFAULT 'quarter',
  `is_confirmed` tinyint(1) NOT NULL DEFAULT 0,
  `teacher_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `grades`
--

INSERT INTO `grades` (`id`, `student_id`, `subject_id`, `grade`, `created_at`, `updated_at`, `period`, `period_type`, `is_confirmed`, `teacher_name`) VALUES
(1, 18, 1, 95.00, '2025-03-25 05:09:32', '2025-03-25 05:23:17', 1, 'quarter', 1, 'Ronald Monzon'),
(2, 13, 1, 94.00, '2025-03-25 05:09:32', '2025-03-25 05:23:17', 1, 'quarter', 1, 'Ronald Monzon'),
(3, 17, 1, 95.00, '2025-03-25 05:09:32', '2025-03-25 05:23:17', 1, 'quarter', 1, 'Ronald Monzon'),
(4, 20, 1, 96.00, '2025-03-25 05:09:32', '2025-03-25 05:23:17', 1, 'quarter', 1, 'Ronald Monzon'),
(5, 21, 1, 97.00, '2025-03-25 05:09:32', '2025-03-25 05:23:17', 1, 'quarter', 1, 'Ronald Monzon'),
(6, 19, 1, 95.00, '2025-03-25 05:09:32', '2025-03-25 05:23:17', 1, 'quarter', 1, 'Ronald Monzon'),
(7, 22, 1, 95.00, '2025-03-25 05:09:32', '2025-03-25 05:23:17', 1, 'quarter', 1, 'Ronald Monzon'),
(8, 15, 1, 93.00, '2025-03-25 05:09:32', '2025-03-25 05:23:17', 1, 'quarter', 1, 'Ronald Monzon'),
(9, 16, 1, 92.00, '2025-03-25 05:09:32', '2025-03-25 05:23:17', 1, 'quarter', 1, 'Ronald Monzon'),
(10, 14, 1, 91.00, '2025-03-25 05:09:32', '2025-03-25 05:23:17', 1, 'quarter', 1, 'Ronald Monzon'),
(11, 18, 2, 96.00, '2025-03-25 05:10:12', '2025-03-25 05:23:17', 1, 'quarter', 1, 'Federico Grino'),
(12, 13, 2, 92.00, '2025-03-25 05:10:12', '2025-03-25 05:23:17', 1, 'quarter', 1, 'Federico Grino'),
(13, 17, 2, 91.00, '2025-03-25 05:10:12', '2025-03-25 05:23:17', 1, 'quarter', 1, 'Federico Grino'),
(14, 20, 2, 96.00, '2025-03-25 05:10:12', '2025-03-25 05:23:17', 1, 'quarter', 1, 'Federico Grino'),
(15, 21, 2, 97.00, '2025-03-25 05:10:12', '2025-03-25 05:23:17', 1, 'quarter', 1, 'Federico Grino'),
(16, 19, 2, 98.00, '2025-03-25 05:10:12', '2025-03-25 05:23:17', 1, 'quarter', 1, 'Federico Grino'),
(17, 22, 2, 93.00, '2025-03-25 05:10:12', '2025-03-25 05:23:17', 1, 'quarter', 1, 'Federico Grino'),
(18, 15, 2, 92.00, '2025-03-25 05:10:12', '2025-03-25 05:23:17', 1, 'quarter', 1, 'Federico Grino'),
(19, 16, 2, 95.00, '2025-03-25 05:10:12', '2025-03-25 05:23:17', 1, 'quarter', 1, 'Federico Grino'),
(20, 14, 2, 96.00, '2025-03-25 05:10:12', '2025-03-25 05:23:17', 1, 'quarter', 1, 'Federico Grino'),
(21, 18, 3, 97.00, '2025-03-25 05:10:42', '2025-03-25 05:23:17', 1, 'quarter', 1, 'Joseph Vistal'),
(22, 13, 3, 96.00, '2025-03-25 05:10:42', '2025-03-25 05:23:17', 1, 'quarter', 1, 'Joseph Vistal'),
(23, 17, 3, 95.00, '2025-03-25 05:10:42', '2025-03-25 05:23:17', 1, 'quarter', 1, 'Joseph Vistal'),
(24, 20, 3, 92.00, '2025-03-25 05:10:42', '2025-03-25 05:23:17', 1, 'quarter', 1, 'Joseph Vistal'),
(25, 21, 3, 93.00, '2025-03-25 05:10:42', '2025-03-25 05:23:17', 1, 'quarter', 1, 'Joseph Vistal'),
(26, 19, 3, 94.00, '2025-03-25 05:10:42', '2025-03-25 05:23:17', 1, 'quarter', 1, 'Joseph Vistal'),
(27, 22, 3, 95.00, '2025-03-25 05:10:42', '2025-03-25 05:23:17', 1, 'quarter', 1, 'Joseph Vistal'),
(28, 15, 3, 92.00, '2025-03-25 05:10:42', '2025-03-25 05:23:17', 1, 'quarter', 1, 'Joseph Vistal'),
(29, 16, 3, 91.00, '2025-03-25 05:10:42', '2025-03-25 05:23:17', 1, 'quarter', 1, 'Joseph Vistal'),
(30, 14, 3, 95.00, '2025-03-25 05:10:42', '2025-03-25 05:23:17', 1, 'quarter', 1, 'Joseph Vistal'),
(31, 18, 4, 91.00, '2025-03-25 05:11:26', '2025-03-25 05:23:17', 1, 'quarter', 1, 'Junell Bojocan'),
(32, 13, 4, 92.00, '2025-03-25 05:11:26', '2025-03-25 05:23:17', 1, 'quarter', 1, 'Junell Bojocan'),
(33, 17, 4, 94.00, '2025-03-25 05:11:26', '2025-03-25 05:23:17', 1, 'quarter', 1, 'Junell Bojocan'),
(34, 20, 4, 93.00, '2025-03-25 05:11:26', '2025-03-25 05:23:17', 1, 'quarter', 1, 'Junell Bojocan'),
(35, 21, 4, 90.00, '2025-03-25 05:11:26', '2025-03-25 05:23:17', 1, 'quarter', 1, 'Junell Bojocan'),
(36, 19, 4, 94.00, '2025-03-25 05:11:26', '2025-03-25 05:23:17', 1, 'quarter', 1, 'Junell Bojocan'),
(37, 22, 4, 95.00, '2025-03-25 05:11:26', '2025-03-25 05:23:17', 1, 'quarter', 1, 'Junell Bojocan'),
(38, 15, 4, 96.00, '2025-03-25 05:11:26', '2025-03-25 05:23:17', 1, 'quarter', 1, 'Junell Bojocan'),
(39, 16, 4, 90.00, '2025-03-25 05:11:26', '2025-03-25 05:23:17', 1, 'quarter', 1, 'Junell Bojocan'),
(40, 14, 4, 91.00, '2025-03-25 05:11:26', '2025-03-25 05:23:17', 1, 'quarter', 1, 'Junell Bojocan'),
(41, 18, 5, 92.00, '2025-03-25 05:11:57', '2025-03-25 05:23:17', 1, 'quarter', 1, 'Ryan Cuarez'),
(42, 13, 5, 93.00, '2025-03-25 05:11:57', '2025-03-25 05:23:17', 1, 'quarter', 1, 'Ryan Cuarez'),
(43, 17, 5, 94.00, '2025-03-25 05:11:57', '2025-03-25 05:23:17', 1, 'quarter', 1, 'Ryan Cuarez'),
(44, 20, 5, 92.00, '2025-03-25 05:11:57', '2025-03-25 05:23:17', 1, 'quarter', 1, 'Ryan Cuarez'),
(45, 21, 5, 91.00, '2025-03-25 05:11:57', '2025-03-25 05:23:17', 1, 'quarter', 1, 'Ryan Cuarez'),
(46, 19, 5, 90.00, '2025-03-25 05:11:57', '2025-03-25 05:23:17', 1, 'quarter', 1, 'Ryan Cuarez'),
(47, 22, 5, 94.00, '2025-03-25 05:11:57', '2025-03-25 05:23:17', 1, 'quarter', 1, 'Ryan Cuarez'),
(48, 15, 5, 95.00, '2025-03-25 05:11:57', '2025-03-25 05:23:17', 1, 'quarter', 1, 'Ryan Cuarez'),
(49, 16, 5, 95.00, '2025-03-25 05:11:57', '2025-03-25 05:23:17', 1, 'quarter', 1, 'Ryan Cuarez'),
(50, 14, 5, 95.00, '2025-03-25 05:11:57', '2025-03-25 05:23:17', 1, 'quarter', 1, 'Ryan Cuarez'),
(51, 18, 1, 94.00, '2025-03-25 05:13:19', '2025-03-25 05:23:17', 2, 'quarter', 1, 'Ronald Monzon'),
(52, 13, 1, 94.00, '2025-03-25 05:13:19', '2025-03-25 05:23:17', 2, 'quarter', 1, 'Ronald Monzon'),
(53, 17, 1, 95.00, '2025-03-25 05:13:19', '2025-03-25 05:23:17', 2, 'quarter', 1, 'Ronald Monzon'),
(54, 20, 1, 92.00, '2025-03-25 05:13:19', '2025-03-25 05:23:17', 2, 'quarter', 1, 'Ronald Monzon'),
(55, 21, 1, 91.00, '2025-03-25 05:13:19', '2025-03-25 05:23:17', 2, 'quarter', 1, 'Ronald Monzon'),
(56, 19, 1, 90.00, '2025-03-25 05:13:19', '2025-03-25 05:23:17', 2, 'quarter', 1, 'Ronald Monzon'),
(57, 22, 1, 95.00, '2025-03-25 05:13:19', '2025-03-25 05:23:17', 2, 'quarter', 1, 'Ronald Monzon'),
(58, 15, 1, 94.00, '2025-03-25 05:13:19', '2025-03-25 05:23:17', 2, 'quarter', 1, 'Ronald Monzon'),
(59, 16, 1, 96.00, '2025-03-25 05:13:19', '2025-03-25 05:23:17', 2, 'quarter', 1, 'Ronald Monzon'),
(60, 14, 1, 90.00, '2025-03-25 05:13:19', '2025-03-25 05:23:17', 2, 'quarter', 1, 'Ronald Monzon'),
(61, 18, 5, 96.00, '2025-03-25 05:13:42', '2025-03-25 05:23:17', 2, 'quarter', 1, 'Ryan Cuarez'),
(62, 13, 5, 94.00, '2025-03-25 05:13:42', '2025-03-25 05:23:17', 2, 'quarter', 1, 'Ryan Cuarez'),
(63, 17, 5, 92.00, '2025-03-25 05:13:42', '2025-03-25 05:23:17', 2, 'quarter', 1, 'Ryan Cuarez'),
(64, 20, 5, 90.00, '2025-03-25 05:13:42', '2025-03-25 05:23:17', 2, 'quarter', 1, 'Ryan Cuarez'),
(65, 21, 5, 95.00, '2025-03-25 05:13:42', '2025-03-25 05:23:17', 2, 'quarter', 1, 'Ryan Cuarez'),
(66, 19, 5, 94.00, '2025-03-25 05:13:42', '2025-03-25 05:23:17', 2, 'quarter', 1, 'Ryan Cuarez'),
(67, 22, 5, 91.00, '2025-03-25 05:13:42', '2025-03-25 05:23:17', 2, 'quarter', 1, 'Ryan Cuarez'),
(68, 15, 5, 90.00, '2025-03-25 05:13:42', '2025-03-25 05:23:17', 2, 'quarter', 1, 'Ryan Cuarez'),
(69, 16, 5, 94.00, '2025-03-25 05:13:42', '2025-03-25 05:23:17', 2, 'quarter', 1, 'Ryan Cuarez'),
(70, 14, 5, 96.00, '2025-03-25 05:13:42', '2025-03-25 05:23:17', 2, 'quarter', 1, 'Ryan Cuarez'),
(71, 18, 2, 96.00, '2025-03-25 05:14:19', '2025-03-25 05:23:17', 2, 'quarter', 1, 'Federico Grino'),
(72, 13, 2, 94.00, '2025-03-25 05:14:19', '2025-03-25 05:23:17', 2, 'quarter', 1, 'Federico Grino'),
(73, 17, 2, 92.00, '2025-03-25 05:14:19', '2025-03-25 05:23:17', 2, 'quarter', 1, 'Federico Grino'),
(74, 20, 2, 91.00, '2025-03-25 05:14:19', '2025-03-25 05:23:17', 2, 'quarter', 1, 'Federico Grino'),
(75, 21, 2, 90.00, '2025-03-25 05:14:19', '2025-03-25 05:23:17', 2, 'quarter', 1, 'Federico Grino'),
(76, 19, 2, 94.00, '2025-03-25 05:14:19', '2025-03-25 05:23:17', 2, 'quarter', 1, 'Federico Grino'),
(77, 22, 2, 95.00, '2025-03-25 05:14:19', '2025-03-25 05:23:17', 2, 'quarter', 1, 'Federico Grino'),
(78, 15, 2, 90.00, '2025-03-25 05:14:19', '2025-03-25 05:23:17', 2, 'quarter', 1, 'Federico Grino'),
(79, 16, 2, 98.00, '2025-03-25 05:14:19', '2025-03-25 05:23:17', 2, 'quarter', 1, 'Federico Grino'),
(80, 14, 2, 92.00, '2025-03-25 05:14:19', '2025-03-25 05:23:17', 2, 'quarter', 1, 'Federico Grino'),
(81, 18, 3, 98.00, '2025-03-25 05:14:47', '2025-03-25 05:23:17', 2, 'quarter', 1, 'Joseph Vistal'),
(82, 13, 3, 92.00, '2025-03-25 05:14:47', '2025-03-25 05:23:17', 2, 'quarter', 1, 'Joseph Vistal'),
(83, 17, 3, 94.00, '2025-03-25 05:14:47', '2025-03-25 05:23:17', 2, 'quarter', 1, 'Joseph Vistal'),
(84, 20, 3, 95.00, '2025-03-25 05:14:47', '2025-03-25 05:23:17', 2, 'quarter', 1, 'Joseph Vistal'),
(85, 21, 3, 96.00, '2025-03-25 05:14:47', '2025-03-25 05:23:17', 2, 'quarter', 1, 'Joseph Vistal'),
(86, 19, 3, 90.00, '2025-03-25 05:14:47', '2025-03-25 05:23:17', 2, 'quarter', 1, 'Joseph Vistal'),
(87, 22, 3, 92.00, '2025-03-25 05:14:47', '2025-03-25 05:23:17', 2, 'quarter', 1, 'Joseph Vistal'),
(88, 15, 3, 94.00, '2025-03-25 05:14:47', '2025-03-25 05:23:17', 2, 'quarter', 1, 'Joseph Vistal'),
(89, 16, 3, 95.00, '2025-03-25 05:14:47', '2025-03-25 05:23:17', 2, 'quarter', 1, 'Joseph Vistal'),
(90, 14, 3, 91.00, '2025-03-25 05:14:47', '2025-03-25 05:23:17', 2, 'quarter', 1, 'Joseph Vistal'),
(91, 18, 4, 97.00, '2025-03-25 05:15:27', '2025-03-25 05:23:17', 2, 'quarter', 1, 'Junell Bojocan'),
(92, 13, 4, 92.00, '2025-03-25 05:15:27', '2025-03-25 05:23:17', 2, 'quarter', 1, 'Junell Bojocan'),
(93, 17, 4, 95.00, '2025-03-25 05:15:27', '2025-03-25 05:23:17', 2, 'quarter', 1, 'Junell Bojocan'),
(94, 20, 4, 93.00, '2025-03-25 05:15:27', '2025-03-25 05:23:17', 2, 'quarter', 1, 'Junell Bojocan'),
(95, 21, 4, 90.00, '2025-03-25 05:15:27', '2025-03-25 05:23:17', 2, 'quarter', 1, 'Junell Bojocan'),
(96, 19, 4, 92.00, '2025-03-25 05:15:27', '2025-03-25 05:23:17', 2, 'quarter', 1, 'Junell Bojocan'),
(97, 22, 4, 80.00, '2025-03-25 05:15:27', '2025-03-25 05:23:17', 2, 'quarter', 1, 'Junell Bojocan'),
(98, 15, 4, 95.00, '2025-03-25 05:15:27', '2025-03-25 05:23:17', 2, 'quarter', 1, 'Junell Bojocan'),
(99, 16, 4, 82.00, '2025-03-25 05:15:27', '2025-03-25 05:23:17', 2, 'quarter', 1, 'Junell Bojocan'),
(100, 14, 4, 79.00, '2025-03-25 05:15:27', '2025-03-25 05:23:17', 2, 'quarter', 1, 'Junell Bojocan'),
(101, 18, 1, 98.00, '2025-03-25 05:16:27', '2025-03-25 05:23:17', 3, 'quarter', 1, 'Ronald Monzon'),
(102, 13, 1, 95.00, '2025-03-25 05:16:27', '2025-03-25 05:23:17', 3, 'quarter', 1, 'Ronald Monzon'),
(103, 17, 1, 94.00, '2025-03-25 05:16:27', '2025-03-25 05:23:17', 3, 'quarter', 1, 'Ronald Monzon'),
(104, 20, 1, 92.00, '2025-03-25 05:16:27', '2025-03-25 05:23:17', 3, 'quarter', 1, 'Ronald Monzon'),
(105, 21, 1, 91.00, '2025-03-25 05:16:27', '2025-03-25 05:23:17', 3, 'quarter', 1, 'Ronald Monzon'),
(106, 19, 1, 90.00, '2025-03-25 05:16:27', '2025-03-25 05:23:17', 3, 'quarter', 1, 'Ronald Monzon'),
(107, 22, 1, 91.00, '2025-03-25 05:16:27', '2025-03-25 05:23:17', 3, 'quarter', 1, 'Ronald Monzon'),
(108, 15, 1, 92.00, '2025-03-25 05:16:27', '2025-03-25 05:23:17', 3, 'quarter', 1, 'Ronald Monzon'),
(109, 16, 1, 94.00, '2025-03-25 05:16:27', '2025-03-25 05:23:17', 3, 'quarter', 1, 'Ronald Monzon'),
(110, 14, 1, 95.00, '2025-03-25 05:16:27', '2025-03-25 05:23:17', 3, 'quarter', 1, 'Ronald Monzon'),
(111, 18, 4, 96.00, '2025-03-25 05:16:48', '2025-03-25 05:23:17', 3, 'quarter', 1, 'Junell Bojocan'),
(112, 13, 4, 95.00, '2025-03-25 05:16:48', '2025-03-25 05:23:17', 3, 'quarter', 1, 'Junell Bojocan'),
(113, 17, 4, 94.00, '2025-03-25 05:16:48', '2025-03-25 05:23:17', 3, 'quarter', 1, 'Junell Bojocan'),
(114, 20, 4, 93.00, '2025-03-25 05:16:48', '2025-03-25 05:23:17', 3, 'quarter', 1, 'Junell Bojocan'),
(115, 21, 4, 92.00, '2025-03-25 05:16:48', '2025-03-25 05:23:17', 3, 'quarter', 1, 'Junell Bojocan'),
(116, 19, 4, 91.00, '2025-03-25 05:16:48', '2025-03-25 05:23:17', 3, 'quarter', 1, 'Junell Bojocan'),
(117, 22, 4, 90.00, '2025-03-25 05:16:48', '2025-03-25 05:23:17', 3, 'quarter', 1, 'Junell Bojocan'),
(118, 15, 4, 95.00, '2025-03-25 05:16:48', '2025-03-25 05:23:17', 3, 'quarter', 1, 'Junell Bojocan'),
(119, 16, 4, 93.00, '2025-03-25 05:16:48', '2025-03-25 05:23:17', 3, 'quarter', 1, 'Junell Bojocan'),
(120, 14, 4, 95.00, '2025-03-25 05:16:48', '2025-03-25 05:23:17', 3, 'quarter', 1, 'Junell Bojocan'),
(121, 18, 2, 96.00, '2025-03-25 05:17:26', '2025-03-25 05:23:17', 3, 'quarter', 1, 'Federico Grino'),
(122, 13, 2, 92.00, '2025-03-25 05:17:26', '2025-03-25 05:23:17', 3, 'quarter', 1, 'Federico Grino'),
(123, 17, 2, 91.00, '2025-03-25 05:17:26', '2025-03-25 05:23:17', 3, 'quarter', 1, 'Federico Grino'),
(124, 20, 2, 90.00, '2025-03-25 05:17:26', '2025-03-25 05:23:17', 3, 'quarter', 1, 'Federico Grino'),
(125, 21, 2, 95.00, '2025-03-25 05:17:26', '2025-03-25 05:23:17', 3, 'quarter', 1, 'Federico Grino'),
(126, 19, 2, 94.00, '2025-03-25 05:17:26', '2025-03-25 05:23:17', 3, 'quarter', 1, 'Federico Grino'),
(127, 22, 2, 93.00, '2025-03-25 05:17:26', '2025-03-25 05:23:17', 3, 'quarter', 1, 'Federico Grino'),
(128, 15, 2, 92.00, '2025-03-25 05:17:26', '2025-03-25 05:23:17', 3, 'quarter', 1, 'Federico Grino'),
(129, 16, 2, 90.00, '2025-03-25 05:17:26', '2025-03-25 05:23:17', 3, 'quarter', 1, 'Federico Grino'),
(130, 14, 2, 89.00, '2025-03-25 05:17:26', '2025-03-25 05:23:17', 3, 'quarter', 1, 'Federico Grino'),
(131, 18, 3, 96.00, '2025-03-25 05:17:52', '2025-03-25 05:23:17', 3, 'quarter', 1, 'Joseph Vistal'),
(132, 13, 3, 94.00, '2025-03-25 05:17:52', '2025-03-25 05:23:17', 3, 'quarter', 1, 'Joseph Vistal'),
(133, 17, 3, 92.00, '2025-03-25 05:17:52', '2025-03-25 05:23:17', 3, 'quarter', 1, 'Joseph Vistal'),
(134, 20, 3, 94.00, '2025-03-25 05:17:52', '2025-03-25 05:23:17', 3, 'quarter', 1, 'Joseph Vistal'),
(135, 21, 3, 90.00, '2025-03-25 05:17:52', '2025-03-25 05:23:17', 3, 'quarter', 1, 'Joseph Vistal'),
(136, 19, 3, 92.00, '2025-03-25 05:17:52', '2025-03-25 05:23:17', 3, 'quarter', 1, 'Joseph Vistal'),
(137, 22, 3, 94.00, '2025-03-25 05:17:52', '2025-03-25 05:23:17', 3, 'quarter', 1, 'Joseph Vistal'),
(138, 15, 3, 95.00, '2025-03-25 05:17:52', '2025-03-25 05:23:17', 3, 'quarter', 1, 'Joseph Vistal'),
(139, 16, 3, 96.00, '2025-03-25 05:17:52', '2025-03-25 05:23:17', 3, 'quarter', 1, 'Joseph Vistal'),
(140, 14, 3, 90.00, '2025-03-25 05:17:52', '2025-03-25 05:23:17', 3, 'quarter', 1, 'Joseph Vistal'),
(141, 18, 5, 94.00, '2025-03-25 05:18:19', '2025-03-25 05:23:17', 3, 'quarter', 1, 'Ryan Cuarez'),
(142, 13, 5, 94.00, '2025-03-25 05:18:19', '2025-03-25 05:23:17', 3, 'quarter', 1, 'Ryan Cuarez'),
(143, 17, 5, 92.00, '2025-03-25 05:18:19', '2025-03-25 05:23:17', 3, 'quarter', 1, 'Ryan Cuarez'),
(144, 20, 5, 91.00, '2025-03-25 05:18:19', '2025-03-25 05:23:17', 3, 'quarter', 1, 'Ryan Cuarez'),
(145, 21, 5, 90.00, '2025-03-25 05:18:19', '2025-03-25 05:23:17', 3, 'quarter', 1, 'Ryan Cuarez'),
(146, 19, 5, 95.00, '2025-03-25 05:18:19', '2025-03-25 05:23:17', 3, 'quarter', 1, 'Ryan Cuarez'),
(147, 22, 5, 96.00, '2025-03-25 05:18:19', '2025-03-25 05:23:17', 3, 'quarter', 1, 'Ryan Cuarez'),
(148, 15, 5, 90.00, '2025-03-25 05:18:19', '2025-03-25 05:23:17', 3, 'quarter', 1, 'Ryan Cuarez'),
(149, 16, 5, 89.00, '2025-03-25 05:18:19', '2025-03-25 05:23:17', 3, 'quarter', 1, 'Ryan Cuarez'),
(150, 14, 5, 94.00, '2025-03-25 05:18:19', '2025-03-25 05:23:17', 3, 'quarter', 1, 'Ryan Cuarez'),
(151, 18, 1, 98.00, '2025-03-25 05:19:04', '2025-03-25 05:23:17', 4, 'quarter', 1, 'Ronald Monzon'),
(152, 13, 1, 90.00, '2025-03-25 05:19:04', '2025-03-25 05:23:17', 4, 'quarter', 1, 'Ronald Monzon'),
(153, 17, 1, 92.00, '2025-03-25 05:19:04', '2025-03-25 05:23:17', 4, 'quarter', 1, 'Ronald Monzon'),
(154, 20, 1, 91.00, '2025-03-25 05:19:04', '2025-03-25 05:23:17', 4, 'quarter', 1, 'Ronald Monzon'),
(155, 21, 1, 90.00, '2025-03-25 05:19:04', '2025-03-25 05:23:17', 4, 'quarter', 1, 'Ronald Monzon'),
(156, 19, 1, 79.00, '2025-03-25 05:19:04', '2025-03-25 05:23:17', 4, 'quarter', 1, 'Ronald Monzon'),
(157, 22, 1, 92.00, '2025-03-25 05:19:04', '2025-03-25 05:23:17', 4, 'quarter', 1, 'Ronald Monzon'),
(158, 15, 1, 92.00, '2025-03-25 05:19:04', '2025-03-25 05:23:17', 4, 'quarter', 1, 'Ronald Monzon'),
(159, 16, 1, 95.00, '2025-03-25 05:19:04', '2025-03-25 05:23:17', 4, 'quarter', 1, 'Ronald Monzon'),
(160, 14, 1, 94.00, '2025-03-25 05:19:04', '2025-03-25 05:23:17', 4, 'quarter', 1, 'Ronald Monzon'),
(161, 18, 5, 99.00, '2025-03-25 05:19:24', '2025-03-25 05:23:17', 4, 'quarter', 1, 'Ryan Cuarez'),
(162, 13, 5, 95.00, '2025-03-25 05:19:24', '2025-03-25 05:23:17', 4, 'quarter', 1, 'Ryan Cuarez'),
(163, 17, 5, 94.00, '2025-03-25 05:19:24', '2025-03-25 05:23:17', 4, 'quarter', 1, 'Ryan Cuarez'),
(164, 20, 5, 92.00, '2025-03-25 05:19:24', '2025-03-25 05:23:17', 4, 'quarter', 1, 'Ryan Cuarez'),
(165, 21, 5, 91.00, '2025-03-25 05:19:24', '2025-03-25 05:23:17', 4, 'quarter', 1, 'Ryan Cuarez'),
(166, 19, 5, 89.00, '2025-03-25 05:19:24', '2025-03-25 05:23:17', 4, 'quarter', 1, 'Ryan Cuarez'),
(167, 22, 5, 95.00, '2025-03-25 05:19:24', '2025-03-25 05:23:17', 4, 'quarter', 1, 'Ryan Cuarez'),
(168, 15, 5, 90.00, '2025-03-25 05:19:24', '2025-03-25 05:23:17', 4, 'quarter', 1, 'Ryan Cuarez'),
(169, 16, 5, 90.00, '2025-03-25 05:19:24', '2025-03-25 05:23:17', 4, 'quarter', 1, 'Ryan Cuarez'),
(170, 14, 5, 91.00, '2025-03-25 05:19:24', '2025-03-25 05:23:17', 4, 'quarter', 1, 'Ryan Cuarez'),
(171, 18, 2, 98.00, '2025-03-25 05:19:53', '2025-03-25 05:23:17', 4, 'quarter', 1, 'Federico Grino'),
(172, 13, 2, 93.00, '2025-03-25 05:19:53', '2025-03-25 05:23:17', 4, 'quarter', 1, 'Federico Grino'),
(173, 17, 2, 94.00, '2025-03-25 05:19:53', '2025-03-25 05:23:17', 4, 'quarter', 1, 'Federico Grino'),
(174, 20, 2, 95.00, '2025-03-25 05:19:53', '2025-03-25 05:23:17', 4, 'quarter', 1, 'Federico Grino'),
(175, 21, 2, 90.00, '2025-03-25 05:19:53', '2025-03-25 05:23:17', 4, 'quarter', 1, 'Federico Grino'),
(176, 19, 2, 91.00, '2025-03-25 05:19:53', '2025-03-25 05:23:17', 4, 'quarter', 1, 'Federico Grino'),
(177, 22, 2, 92.00, '2025-03-25 05:19:53', '2025-03-25 05:23:17', 4, 'quarter', 1, 'Federico Grino'),
(178, 15, 2, 95.00, '2025-03-25 05:19:53', '2025-03-25 05:23:17', 4, 'quarter', 1, 'Federico Grino'),
(179, 16, 2, 90.00, '2025-03-25 05:19:53', '2025-03-25 05:23:17', 4, 'quarter', 1, 'Federico Grino'),
(180, 14, 2, 95.00, '2025-03-25 05:19:53', '2025-03-25 05:23:17', 4, 'quarter', 1, 'Federico Grino'),
(181, 18, 3, 98.00, '2025-03-25 05:20:38', '2025-03-25 05:23:17', 4, 'quarter', 1, 'Joseph Vistal'),
(182, 13, 3, 95.00, '2025-03-25 05:20:38', '2025-03-25 05:23:17', 4, 'quarter', 1, 'Joseph Vistal'),
(183, 17, 3, 92.00, '2025-03-25 05:20:38', '2025-03-25 05:23:17', 4, 'quarter', 1, 'Joseph Vistal'),
(184, 20, 3, 93.00, '2025-03-25 05:20:38', '2025-03-25 05:23:17', 4, 'quarter', 1, 'Joseph Vistal'),
(185, 21, 3, 91.00, '2025-03-25 05:20:38', '2025-03-25 05:23:17', 4, 'quarter', 1, 'Joseph Vistal'),
(186, 19, 3, 90.00, '2025-03-25 05:20:38', '2025-03-25 05:23:17', 4, 'quarter', 1, 'Joseph Vistal'),
(187, 22, 3, 95.00, '2025-03-25 05:20:38', '2025-03-25 05:23:17', 4, 'quarter', 1, 'Joseph Vistal'),
(188, 15, 3, 93.00, '2025-03-25 05:20:38', '2025-03-25 05:23:17', 4, 'quarter', 1, 'Joseph Vistal'),
(189, 16, 3, 92.00, '2025-03-25 05:20:38', '2025-03-25 05:23:17', 4, 'quarter', 1, 'Joseph Vistal'),
(190, 14, 3, 90.00, '2025-03-25 05:20:38', '2025-03-25 05:23:17', 4, 'quarter', 1, 'Joseph Vistal'),
(191, 18, 4, 95.00, '2025-03-25 05:21:05', '2025-03-25 05:23:17', 4, 'quarter', 1, 'Junell Bojocan'),
(192, 13, 4, 94.00, '2025-03-25 05:21:05', '2025-03-25 05:23:17', 4, 'quarter', 1, 'Junell Bojocan'),
(193, 17, 4, 92.00, '2025-03-25 05:21:05', '2025-03-25 05:23:17', 4, 'quarter', 1, 'Junell Bojocan'),
(194, 20, 4, 91.00, '2025-03-25 05:21:05', '2025-03-25 05:23:17', 4, 'quarter', 1, 'Junell Bojocan'),
(195, 21, 4, 80.00, '2025-03-25 05:21:05', '2025-03-25 05:23:17', 4, 'quarter', 1, 'Junell Bojocan'),
(196, 19, 4, 90.00, '2025-03-25 05:21:05', '2025-03-25 05:23:17', 4, 'quarter', 1, 'Junell Bojocan'),
(197, 22, 4, 92.00, '2025-03-25 05:21:05', '2025-03-25 05:23:17', 4, 'quarter', 1, 'Junell Bojocan'),
(198, 15, 4, 95.00, '2025-03-25 05:21:05', '2025-03-25 05:23:17', 4, 'quarter', 1, 'Junell Bojocan'),
(199, 16, 4, 94.00, '2025-03-25 05:21:05', '2025-03-25 05:23:17', 4, 'quarter', 1, 'Junell Bojocan'),
(200, 14, 4, 93.00, '2025-03-25 05:21:05', '2025-03-25 05:23:17', 4, 'quarter', 1, 'Junell Bojocan'),
(201, 29, 6, 98.00, '2025-03-25 06:06:40', '2025-03-25 06:11:24', 1, 'semester', 1, 'Mary Jun Palima'),
(202, 30, 6, 95.00, '2025-03-25 06:06:40', '2025-03-25 06:11:24', 1, 'semester', 1, 'Mary Jun Palima'),
(203, 28, 6, 93.00, '2025-03-25 06:06:40', '2025-03-25 06:11:24', 1, 'semester', 1, 'Mary Jun Palima'),
(204, 23, 6, 92.00, '2025-03-25 06:06:40', '2025-03-25 06:11:24', 1, 'semester', 1, 'Mary Jun Palima'),
(205, 25, 6, 91.00, '2025-03-25 06:06:40', '2025-03-25 06:11:24', 1, 'semester', 1, 'Mary Jun Palima'),
(206, 24, 6, 94.00, '2025-03-25 06:06:40', '2025-03-25 06:11:24', 1, 'semester', 1, 'Mary Jun Palima'),
(207, 27, 6, 95.00, '2025-03-25 06:06:40', '2025-03-25 06:11:24', 1, 'semester', 1, 'Mary Jun Palima'),
(208, 26, 6, 92.00, '2025-03-25 06:06:40', '2025-03-25 06:11:24', 1, 'semester', 1, 'Mary Jun Palima'),
(209, 31, 6, 90.00, '2025-03-25 06:06:40', '2025-03-25 06:11:24', 1, 'semester', 1, 'Mary Jun Palima'),
(210, 32, 6, 90.00, '2025-03-25 06:06:40', '2025-03-25 06:11:24', 1, 'semester', 1, 'Mary Jun Palima'),
(211, 29, 9, 95.00, '2025-03-25 06:07:04', '2025-03-25 06:11:24', 1, 'semester', 1, 'Nova Estenzo'),
(212, 30, 9, 90.00, '2025-03-25 06:07:04', '2025-03-25 06:11:24', 1, 'semester', 1, 'Nova Estenzo'),
(213, 28, 9, 98.00, '2025-03-25 06:07:04', '2025-03-25 06:11:24', 1, 'semester', 1, 'Nova Estenzo'),
(214, 23, 9, 94.00, '2025-03-25 06:07:04', '2025-03-25 06:11:24', 1, 'semester', 1, 'Nova Estenzo'),
(215, 25, 9, 92.00, '2025-03-25 06:07:04', '2025-03-25 06:11:24', 1, 'semester', 1, 'Nova Estenzo'),
(216, 24, 9, 94.00, '2025-03-25 06:07:04', '2025-03-25 06:11:24', 1, 'semester', 1, 'Nova Estenzo'),
(217, 27, 9, 95.00, '2025-03-25 06:07:04', '2025-03-25 06:11:24', 1, 'semester', 1, 'Nova Estenzo'),
(218, 26, 9, 91.00, '2025-03-25 06:07:04', '2025-03-25 06:11:24', 1, 'semester', 1, 'Nova Estenzo'),
(219, 31, 9, 90.00, '2025-03-25 06:07:04', '2025-03-25 06:11:24', 1, 'semester', 1, 'Nova Estenzo'),
(220, 32, 9, 95.00, '2025-03-25 06:07:04', '2025-03-25 06:11:24', 1, 'semester', 1, 'Nova Estenzo'),
(221, 29, 8, 90.00, '2025-03-25 06:07:36', '2025-03-25 06:11:24', 1, 'semester', 1, 'Donald Jasper Madrona'),
(222, 30, 8, 94.00, '2025-03-25 06:07:36', '2025-03-25 06:11:24', 1, 'semester', 1, 'Donald Jasper Madrona'),
(223, 28, 8, 98.00, '2025-03-25 06:07:36', '2025-03-25 06:11:24', 1, 'semester', 1, 'Donald Jasper Madrona'),
(224, 23, 8, 92.00, '2025-03-25 06:07:36', '2025-03-25 06:11:24', 1, 'semester', 1, 'Donald Jasper Madrona'),
(225, 25, 8, 90.00, '2025-03-25 06:07:36', '2025-03-25 06:11:24', 1, 'semester', 1, 'Donald Jasper Madrona'),
(226, 24, 8, 95.00, '2025-03-25 06:07:36', '2025-03-25 06:11:24', 1, 'semester', 1, 'Donald Jasper Madrona'),
(227, 27, 8, 92.00, '2025-03-25 06:07:36', '2025-03-25 06:11:24', 1, 'semester', 1, 'Donald Jasper Madrona'),
(228, 26, 8, 90.00, '2025-03-25 06:07:36', '2025-03-25 06:11:24', 1, 'semester', 1, 'Donald Jasper Madrona'),
(229, 31, 8, 90.00, '2025-03-25 06:07:36', '2025-03-25 06:11:24', 1, 'semester', 1, 'Donald Jasper Madrona'),
(230, 32, 8, 90.00, '2025-03-25 06:07:36', '2025-03-25 06:11:24', 1, 'semester', 1, 'Donald Jasper Madrona'),
(231, 29, 7, 90.00, '2025-03-25 06:08:13', '2025-03-25 06:11:24', 1, 'semester', 1, 'Rexon Timbal'),
(232, 30, 7, 92.00, '2025-03-25 06:08:13', '2025-03-25 06:11:24', 1, 'semester', 1, 'Rexon Timbal'),
(233, 28, 7, 100.00, '2025-03-25 06:08:13', '2025-03-25 06:11:24', 1, 'semester', 1, 'Rexon Timbal'),
(234, 23, 7, 92.00, '2025-03-25 06:08:13', '2025-03-25 06:11:24', 1, 'semester', 1, 'Rexon Timbal'),
(235, 25, 7, 94.00, '2025-03-25 06:08:13', '2025-03-25 06:11:24', 1, 'semester', 1, 'Rexon Timbal'),
(236, 24, 7, 95.00, '2025-03-25 06:08:13', '2025-03-25 06:11:24', 1, 'semester', 1, 'Rexon Timbal'),
(237, 27, 7, 90.00, '2025-03-25 06:08:13', '2025-03-25 06:11:24', 1, 'semester', 1, 'Rexon Timbal'),
(238, 26, 7, 95.00, '2025-03-25 06:08:13', '2025-03-25 06:11:24', 1, 'semester', 1, 'Rexon Timbal'),
(239, 31, 7, 92.00, '2025-03-25 06:08:13', '2025-03-25 06:11:24', 1, 'semester', 1, 'Rexon Timbal'),
(240, 32, 7, 90.00, '2025-03-25 06:08:13', '2025-03-25 06:11:24', 1, 'semester', 1, 'Rexon Timbal'),
(241, 29, 6, 95.00, '2025-03-25 06:09:02', '2025-03-25 06:11:24', 2, 'semester', 1, 'Mary Jun Palima'),
(242, 30, 6, 92.00, '2025-03-25 06:09:02', '2025-03-25 06:11:24', 2, 'semester', 1, 'Mary Jun Palima'),
(243, 28, 6, 94.00, '2025-03-25 06:09:02', '2025-03-25 06:11:24', 2, 'semester', 1, 'Mary Jun Palima'),
(244, 23, 6, 95.00, '2025-03-25 06:09:02', '2025-03-25 06:11:24', 2, 'semester', 1, 'Mary Jun Palima'),
(245, 25, 6, 92.00, '2025-03-25 06:09:02', '2025-03-25 06:11:24', 2, 'semester', 1, 'Mary Jun Palima'),
(246, 24, 6, 90.00, '2025-03-25 06:09:02', '2025-03-25 06:11:24', 2, 'semester', 1, 'Mary Jun Palima'),
(247, 27, 6, 91.00, '2025-03-25 06:09:02', '2025-03-25 06:11:24', 2, 'semester', 1, 'Mary Jun Palima'),
(248, 26, 6, 94.00, '2025-03-25 06:09:02', '2025-03-25 06:11:24', 2, 'semester', 1, 'Mary Jun Palima'),
(249, 31, 6, 93.00, '2025-03-25 06:09:02', '2025-03-25 06:11:24', 2, 'semester', 1, 'Mary Jun Palima'),
(250, 32, 6, 95.00, '2025-03-25 06:09:02', '2025-03-25 06:11:24', 2, 'semester', 1, 'Mary Jun Palima'),
(251, 29, 7, 92.00, '2025-03-25 06:09:16', '2025-03-25 06:11:24', 2, 'semester', 1, 'Rexon Timbal'),
(252, 30, 7, 94.00, '2025-03-25 06:09:16', '2025-03-25 06:11:24', 2, 'semester', 1, 'Rexon Timbal'),
(253, 28, 7, 100.00, '2025-03-25 06:09:16', '2025-03-25 06:11:24', 2, 'semester', 1, 'Rexon Timbal'),
(254, 23, 7, 94.00, '2025-03-25 06:09:16', '2025-03-25 06:11:24', 2, 'semester', 1, 'Rexon Timbal'),
(255, 25, 7, 92.00, '2025-03-25 06:09:16', '2025-03-25 06:11:24', 2, 'semester', 1, 'Rexon Timbal'),
(256, 24, 7, 90.00, '2025-03-25 06:09:16', '2025-03-25 06:11:24', 2, 'semester', 1, 'Rexon Timbal'),
(257, 27, 7, 94.00, '2025-03-25 06:09:16', '2025-03-25 06:11:24', 2, 'semester', 1, 'Rexon Timbal'),
(258, 26, 7, 92.00, '2025-03-25 06:09:16', '2025-03-25 06:11:24', 2, 'semester', 1, 'Rexon Timbal'),
(259, 31, 7, 91.00, '2025-03-25 06:09:16', '2025-03-25 06:11:24', 2, 'semester', 1, 'Rexon Timbal'),
(260, 32, 7, 90.00, '2025-03-25 06:09:16', '2025-03-25 06:11:24', 2, 'semester', 1, 'Rexon Timbal'),
(261, 29, 8, 98.00, '2025-03-25 06:09:55', '2025-03-25 06:11:24', 2, 'semester', 1, 'Donald Jasper Madrona'),
(262, 30, 8, 92.00, '2025-03-25 06:09:55', '2025-03-25 06:11:24', 2, 'semester', 1, 'Donald Jasper Madrona'),
(263, 28, 8, 95.00, '2025-03-25 06:09:55', '2025-03-25 06:11:24', 2, 'semester', 1, 'Donald Jasper Madrona'),
(264, 23, 8, 92.00, '2025-03-25 06:09:55', '2025-03-25 06:11:24', 2, 'semester', 1, 'Donald Jasper Madrona'),
(265, 25, 8, 90.00, '2025-03-25 06:09:55', '2025-03-25 06:11:24', 2, 'semester', 1, 'Donald Jasper Madrona'),
(266, 24, 8, 89.00, '2025-03-25 06:09:55', '2025-03-25 06:11:24', 2, 'semester', 1, 'Donald Jasper Madrona'),
(267, 27, 8, 92.00, '2025-03-25 06:09:55', '2025-03-25 06:11:24', 2, 'semester', 1, 'Donald Jasper Madrona'),
(268, 26, 8, 95.00, '2025-03-25 06:09:55', '2025-03-25 06:11:24', 2, 'semester', 1, 'Donald Jasper Madrona'),
(269, 31, 8, 94.00, '2025-03-25 06:09:55', '2025-03-25 06:11:24', 2, 'semester', 1, 'Donald Jasper Madrona'),
(270, 32, 8, 92.00, '2025-03-25 06:09:55', '2025-03-25 06:11:24', 2, 'semester', 1, 'Donald Jasper Madrona'),
(271, 29, 9, 98.00, '2025-03-25 06:10:23', '2025-03-25 06:11:24', 2, 'semester', 1, 'Nova Estenzo'),
(272, 30, 9, 92.00, '2025-03-25 06:10:23', '2025-03-25 06:11:24', 2, 'semester', 1, 'Nova Estenzo'),
(273, 28, 9, 96.00, '2025-03-25 06:10:23', '2025-03-25 06:11:24', 2, 'semester', 1, 'Nova Estenzo'),
(274, 23, 9, 93.00, '2025-03-25 06:10:23', '2025-03-25 06:11:24', 2, 'semester', 1, 'Nova Estenzo'),
(275, 25, 9, 90.00, '2025-03-25 06:10:23', '2025-03-25 06:11:24', 2, 'semester', 1, 'Nova Estenzo'),
(276, 24, 9, 92.00, '2025-03-25 06:10:23', '2025-03-25 06:11:24', 2, 'semester', 1, 'Nova Estenzo'),
(277, 27, 9, 90.00, '2025-03-25 06:10:23', '2025-03-25 06:11:24', 2, 'semester', 1, 'Nova Estenzo'),
(278, 26, 9, 80.00, '2025-03-25 06:10:23', '2025-03-25 06:11:24', 2, 'semester', 1, 'Nova Estenzo'),
(279, 31, 9, 92.00, '2025-03-25 06:10:23', '2025-03-25 06:11:24', 2, 'semester', 1, 'Nova Estenzo'),
(280, 32, 9, 95.00, '2025-03-25 06:10:23', '2025-03-25 06:11:24', 2, 'semester', 1, 'Nova Estenzo');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_03_01_132435_add_profile_image_to_users_table', 1),
(5, '2025_03_02_072622_add_user_fields', 1),
(6, '2025_03_05_043541_create_classes_table', 1),
(7, '2025_03_05_043608_create_subjects_table', 1),
(8, '2025_03_05_043628_create_class_students_table', 1),
(9, '2025_03_06_121946_create_grades_table', 1),
(10, '2025_03_08_054404_add_completed_to_subjects_table', 1),
(11, '2025_03_08_140138_add_quarter_semester_to_grades', 1),
(12, '2025_03_09_105444_fix_grades_table_constraints', 1),
(13, '2025_03_10_043049_add_is_confirmed_to_grades', 1),
(14, '2025_03_24_132711_add_is_promoted_to_class_students_table', 1),
(15, '2025_03_24_143703_make_adviser_id_nullable_in_classes_table', 1),
(16, '2025_03_25_002400_allow_null_teacher_id', 1),
(17, '2025_03_25_004237_add_school_year_to_classes', 1),
(18, '2025_03_25_052820_add_teacher_name_to_grades_table', 1),
(19, '2025_03_25_053323_add_adviser_name_to_class_students_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('4PnalFFMHYqJ35mTGLUw6O2r0d9JYJjaKjfj3cu3', 6, '192.168.2.123', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiS05pZ1o4RXZqN1VaWUg0NmZnR1VyWlZPenlLczdSeU45N2NlS0RhUiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODU6Imh0dHA6Ly8xOTIuMTY4LjIuMTIzOjgwMDAvYWNoaWV2ZXJzL2J5LXllYXI/bGV2ZWxfdHlwZT1zZW5pb3ImcGVyaW9kPTImeWVhcl9sZXZlbD1HMTEiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo2O30=', 1742911984),
('NIf4vhbL6eLqXKx1qJLGYZz6wmIDpNNik2OGOHci', 10, '192.168.2.123', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoieVNMaXF1VnJVbHZnYVhvMnFBa2s2MFNRNUN5MUFTUEZHU0VTYjhGMiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NzU6Imh0dHA6Ly8xOTIuMTY4LjIuMTIzOjgwMDAvc3ViamVjdHMvYnkteWVhcj9sZXZlbF90eXBlPXNlbmlvciZ5ZWFyX2xldmVsPUcxMSI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjEwO30=', 1742911824),
('oWnwGJj3KOBZxuRl8fLAMfLL9dgKA8lFur8KQE3R', 1, '192.168.2.123', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZ0lhb1E1ZnVobGxmd0NZV1pCUjVVRldiYTBERHhKZzJBUXd4UzNkVSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTMzOiJodHRwOi8vMTkyLjE2OC4yLjEyMzo4MDAwL2ZpbHRlcmVkLXN0dWRlbnRzP2dyYWRlX2xldmVsPUFMTCZsZXZlbF90eXBlPXNlbmlvciZzZWN0aW9uPUEmc2VtZXN0ZXI9MXN0JTIwU2VtJnN0cmFuZD1TVEVNJnllYXJfbGV2ZWw9QUxMIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1742912237),
('wo6LWzpXTIVuQFaYRTc9KlPxaAx7Ichlfhdgk9pl', 1, '192.168.2.213', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_1_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/134.0.6998.99 Mobile/15E148 Safari/604.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRGJjdnlpUThsTTV2QlVTR3dCWnNJaFlWeFZQODlJS0pUNktLR3lRYSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTMyOiJodHRwOi8vMTkyLjE2OC4yLjEyMzo4MDAwL2ZpbHRlcmVkLWNsYXNzZXM/Z3JhZGVfbGV2ZWw9QUxMJmxldmVsX3R5cGU9anVuaW9yJnNlY3Rpb249QSZzZW1lc3Rlcj0xc3QlMjBTZW0mc3RyYW5kPVNURU0meWVhcl9sZXZlbD1BTEwiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1742907001),
('xlgKTPPjCAvd9Pp94QRmhprH8dov9oFuRXaaqgZ5', 28, '192.168.2.123', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVURXRnQwSElrUnlWWG1kbU9PeUNnQThsbGRNaW96T1J3czZOcWZoNCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly8xOTIuMTY4LjIuMTIzOjgwMDAvc3R1ZGVudC9ncmFkZXMiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyODt9', 1742911962);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `class_id` bigint(20) UNSIGNED NOT NULL,
  `teacher_id` bigint(20) UNSIGNED DEFAULT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `name`, `class_id`, `teacher_id`, `completed`, `created_at`, `updated_at`) VALUES
(1, 'English', 1, NULL, 1, '2025-03-24 22:43:54', '2025-03-25 05:23:17'),
(2, 'Mathematics', 1, NULL, 1, '2025-03-24 22:43:54', '2025-03-25 05:23:17'),
(3, 'Science', 1, NULL, 1, '2025-03-24 22:43:54', '2025-03-25 05:23:17'),
(4, 'Filipino', 1, NULL, 1, '2025-03-24 22:43:54', '2025-03-25 05:23:17'),
(5, 'Mapeh', 1, NULL, 1, '2025-03-24 22:43:54', '2025-03-25 05:23:17'),
(6, 'Oral Communication', 2, NULL, 1, '2025-03-24 22:46:31', '2025-03-25 06:11:24'),
(7, '21st Century Literature from the Philippines and the World', 2, NULL, 1, '2025-03-24 22:46:31', '2025-03-25 06:11:24'),
(8, 'Earth and Life Science', 2, NULL, 1, '2025-03-24 22:46:31', '2025-03-25 06:11:24'),
(9, 'General Mathematics', 2, NULL, 1, '2025-03-24 22:46:31', '2025-03-25 06:11:24'),
(10, 'English', 3, 2, 0, '2025-03-25 05:45:36', '2025-03-25 05:45:36'),
(11, 'Mathematics', 3, 7, 0, '2025-03-25 05:45:36', '2025-03-25 05:45:36'),
(12, 'Earth and Life', 4, 6, 0, '2025-03-25 06:12:36', '2025-03-25 06:12:36'),
(13, 'PR-2', 4, 10, 0, '2025-03-25 06:12:36', '2025-03-25 06:12:36');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `lrn` varchar(12) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `profile_image` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `first_name`, `last_name`, `lrn`, `birthday`, `email`, `email_verified_at`, `password`, `role`, `profile_image`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', NULL, NULL, NULL, NULL, 'admin@admin.com', NULL, '$2y$12$3/C1rqnhI3YQYytJZmyZTuvtcw4xdK0.MzW2O3JywscLX5ru2jB7O', 'admin', NULL, NULL, '2025-03-24 22:22:19', '2025-03-24 22:22:19'),
(2, 'Ronald Monzon', 'Ronald', 'Monzon', NULL, NULL, 'ronald.monzon', NULL, '$2y$12$a5RpePPuwJ9SVOIgkEUp1etOCJl5SoqZMn9i/Js4fU4CIdxP2E1FS', 'teacher', NULL, NULL, '2025-03-24 22:41:08', '2025-03-25 05:05:07'),
(3, 'Rexon Timbal', 'Rexon', 'Timbal', NULL, NULL, 'rexon.timbal', NULL, '$2y$12$.B/eqcO5Xc8F61Vc/YdxOu/dmm7znlygNWA45hfyfnNnLpdj35xYi', 'teacher', NULL, NULL, '2025-03-24 22:41:09', '2025-03-25 06:04:56'),
(4, 'Federico Grino', 'Federico', 'Grino', NULL, NULL, 'federico.grino', NULL, '$2y$12$rEVHXk4kPd2utkUehV3yeOy/JsF1NrW2WGfzyhF4WT4Fybr9gWFbW', 'teacher', NULL, NULL, '2025-03-24 22:41:09', '2025-03-25 05:07:59'),
(5, 'Ryan Cuarez', 'Ryan', 'Cuarez', NULL, NULL, 'ryan.cuarez', NULL, '$2y$12$X84Wt5v5fY/3OP0NyhQdYOE7r.BQBf4OYGELUg9a4bqY0INaiS/iW', 'teacher', NULL, NULL, '2025-03-24 22:41:09', '2025-03-25 05:08:39'),
(6, 'Mary Jun Palima', 'Mary Jun', 'Palima', NULL, NULL, 'maryjun.palima', NULL, '$2y$12$CCtuRPPp83Q4ni5albvx1uKk8uJk1oIgs2pGfexBSwo9tgA2o2LS.', 'teacher', NULL, NULL, '2025-03-24 22:41:10', '2025-03-25 06:02:26'),
(7, 'Joseph Vistal', 'Joseph', 'Vistal', NULL, NULL, 'joseph.vistal', NULL, '$2y$12$ZuGfOTFvFhSU..2PRShTueWmvK8VeK3Rnc7I9Tj0bRUmj7oz/W4zy', 'teacher', NULL, NULL, '2025-03-24 22:41:10', '2025-03-25 05:08:14'),
(8, 'Junell Bojocan', 'Junell', 'Bojocan', NULL, NULL, 'junell.bojocan', NULL, '$2y$12$/scL5p9EzJibYnGv6ejhBODzE9lC1mSTYMPYgn/wyHzUsPGdAZmsm', 'teacher', NULL, NULL, '2025-03-24 22:41:11', '2025-03-25 05:08:27'),
(9, 'Donald Jasper Madrona', 'Donald Jasper', 'Madrona', NULL, NULL, 'donaldjasper.madrona', NULL, '$2y$12$iCCh4gb5zWGURA7uNJCao.aypgM/gipHeEJLpUueSU0AmN9ZfYznq', 'teacher', NULL, NULL, '2025-03-24 22:41:11', '2025-03-25 06:05:19'),
(10, 'Nova Estenzo', 'Nova', 'Estenzo', NULL, NULL, 'nova.estenzo', NULL, '$2y$12$7o87GxIzXgccjd2rAYOYJ.NaZStjHmjhuujrddKp6R0VIvDVxW6si', 'teacher', NULL, NULL, '2025-03-24 22:41:12', '2025-03-25 06:05:50'),
(11, 'Leonardo John Carrillo', 'Leonardo John', 'Carrillo', NULL, NULL, 'leonardojohn.carrillo', NULL, '$2y$12$YH5iTtKI.FI0/NWtJXJ4Y.NCvxBUlki1eDHOLhSQJwgK/qR5qMdA2', 'teacher', NULL, NULL, '2025-03-24 22:41:12', '2025-03-24 22:41:12'),
(12, 'Rolando Agujar', 'Rolando', 'Agujar', NULL, NULL, 'rolando.agujar', NULL, '$2y$12$j6HXEs9IupN9IVLMkAopy.SiAressGHCgclaMOySKO4OZm/OMp/yi', 'teacher', NULL, NULL, '2025-03-24 22:41:13', '2025-03-24 22:41:13'),
(13, 'Emma Johnson', 'Emma', 'Johnson', '123456789031', '2002-08-08', '123456789031', NULL, '$2y$12$eL6Dx/4uqU3VtZW49QK.z.um4QSnvH34ZBMve8wr6VG0Mczn12J..', 'student', NULL, NULL, '2025-03-24 22:41:44', '2025-03-24 22:41:44'),
(14, 'Sophia Brown', 'Sophia', 'Brown', '123456789032', '2002-08-01', '123456789032', NULL, '$2y$12$C6WjqRvkILz61lMKVsLDkew4MCuSfjIp0RXpGz5pXHPT/DJYFMsP2', 'student', NULL, NULL, '2025-03-24 22:41:45', '2025-03-24 22:41:45'),
(15, 'Noah Garcia', 'Noah', 'Garcia', '123456789034', '2002-08-10', '123456789034', NULL, '$2y$12$F7AqeJ4ntc2TJo4ra3qSN.wno8uzDeuzKFcd4PoWGRyYkKqzhuGDO', 'student', NULL, NULL, '2025-03-24 22:41:45', '2025-03-24 22:41:45'),
(16, 'Olivia Lee', 'Olivia', 'Lee', '123456789035', '2002-08-12', '123456789035', NULL, '$2y$12$QWHTUjaGEMTQFg3iey53peGnGWcK4gn8yxZVuNMg3HUtZRjzol1ES', 'student', NULL, NULL, '2025-03-24 22:41:46', '2025-03-24 22:41:46'),
(17, 'Ethan Wilson', 'Ethan', 'Wilson', '123456789036', '2002-08-14', '123456789036', NULL, '$2y$12$gxct3o3tMeFzCNbOGL9bq.yDLtcregdphlsblhJVYv7zgpHU3XA3a', 'student', NULL, NULL, '2025-03-24 22:41:46', '2025-03-24 22:41:46'),
(18, 'Ava Rodriguez', 'Ava', 'Rodriguez', '123456789037', '2002-08-15', '123456789037', NULL, '$2y$12$Yx2ARcATJidZPEoyhji48e.qe2bESCxtRGZcRFlnHTHJ11580w9YW', 'student', NULL, NULL, '2025-03-24 22:41:47', '2025-03-24 22:41:47'),
(19, 'Mason Taylor', 'Mason', 'Taylor', '123456789038', '2002-08-18', '123456789038', NULL, '$2y$12$BE1nw0.4HJmDao/u01XN/u5pSUbE5ndXmk.DM9o063WDPLgS.jmXe', 'student', NULL, NULL, '2025-03-24 22:41:47', '2025-03-24 22:41:47'),
(20, 'Isabella Hernandez', 'Isabella', 'Hernandez', '123456789039', '2002-08-19', '123456789039', NULL, '$2y$12$ICfX3F1V.rw0EUNfxGMXae1q6IBq8/zVLDWy01AuueEy3tkg8gZi.', 'student', NULL, NULL, '2025-03-24 22:41:48', '2025-03-24 22:41:48'),
(21, 'James Smith', 'James', 'Smith', '123456789040', '2002-08-20', '123456789040', NULL, '$2y$12$wUn3YpwbZY1GFGCxGTWuMuCHhaxlzKkCbb14nuY2oVujNgn9RaDRG', 'student', NULL, NULL, '2025-03-24 22:41:48', '2025-03-24 22:41:48'),
(22, 'Mia Clark', 'Mia', 'Clark', '123456789041', '2002-08-23', '123456789041', NULL, '$2y$12$dg4dIUZYHHjSNN5mpNEl0e/gmxQsXX7Dn8XCVTr26huk7fdJL3maG', 'student', NULL, NULL, '2025-03-24 22:41:48', '2025-03-24 22:41:48'),
(23, 'Erika Abellar', 'Erika', 'Abellar', '123456789011', '2002-11-22', '123456789011', NULL, '$2y$12$aOqvLmzzHufqhtYqdziswuaoGk9cSJOk5XUZwd58DO48wt798oB/u', 'student', NULL, NULL, '2025-03-24 22:41:58', '2025-03-24 22:41:58'),
(24, 'Kent Melvin Abenion', 'Kent Melvin', 'Abenion', '123456789012', '2003-12-21', '123456789012', NULL, '$2y$12$4gbMgoAkpDu49LediIldd.bJVGkZ8NY4moQGVGJHB1iyzMPMDd68i', 'student', NULL, NULL, '2025-03-24 22:41:59', '2025-03-24 22:41:59'),
(25, 'Irene Abucay', 'Irene', 'Abucay', '123456789013', '2002-12-31', '123456789013', NULL, '$2y$12$ZnxF6TP4T8vzsbn2sLL0S.0gI0.YX84tjIOOQiRCvSbXtaohlBmH2', 'student', NULL, NULL, '2025-03-24 22:41:59', '2025-03-24 22:41:59'),
(26, 'Marahoney Alpahando', 'Marahoney', 'Alpahando', '123456789014', '2001-08-20', '123456789014', NULL, '$2y$12$y7ViUchYT813FOtWXPUdTeZCiGvqBfmS.hu0oac0EK8WiaNkNnt62', 'student', NULL, NULL, '2025-03-24 22:42:00', '2025-03-24 22:42:00'),
(27, 'Mae Amado', 'Mae', 'Amado', '123456789015', '2000-03-28', '123456789015', NULL, '$2y$12$w4OapKnYcZJ.WM9aiV2CWuoTntttrUpWMvDPYfSdKgzS4dD2wriFW', 'student', NULL, NULL, '2025-03-24 22:42:00', '2025-03-24 22:42:00'),
(28, 'Angela Marie Ambion', 'Angela Marie', 'Ambion', '123456789016', '2003-05-04', '123456789016', NULL, '$2y$12$70g7Atu/sXyfohyjo7zzlusUDgA33ZEJAbSorrh8BSnuO8vz0vs2y', 'student', NULL, NULL, '2025-03-24 22:42:00', '2025-03-24 22:42:00'),
(29, 'Aicymae Andonga', 'Aicymae', 'Andonga', '123456789017', '2008-05-02', '123456789017', NULL, '$2y$12$yRFN0rJS4ka0bI.kL4XWOOfXQzVHTMicKWzDfs6aCGdpyRnjJp0ou', 'student', NULL, NULL, '2025-03-24 22:42:01', '2025-03-24 22:42:01'),
(30, 'Alvin Rey Andonga', 'Alvin Rey', 'Andonga', '123456789018', '2003-04-12', '123456789018', NULL, '$2y$12$yfnsqk5udoPzbZKUphZg4eauYUlyMMrFcTaK0.SFZTC2BUmob/bju', 'student', NULL, NULL, '2025-03-24 22:42:01', '2025-03-24 22:42:01'),
(31, 'Shella Mae Andonga', 'Shella Mae', 'Andonga', '123456789019', '2005-12-16', '123456789019', NULL, '$2y$12$XZRGN7u9hvyL5vwJTl8oFu86wq9aorjAsaffenpsbQHREhPtF3xXi', 'student', NULL, NULL, '2025-03-24 22:42:02', '2025-03-24 22:42:02'),
(32, 'Wilmar Anida', 'Wilmar', 'Anida', '123456789020', '2003-09-23', '123456789020', NULL, '$2y$12$wsZ9fjWfYiizC85Ocg2ixeZYdor3BQjAq4jvQpveRloP12RFWZBzi', 'student', NULL, NULL, '2025-03-24 22:42:02', '2025-03-24 22:42:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `classes_adviser_id_foreign` (`adviser_id`);

--
-- Indexes for table `class_students`
--
ALTER TABLE `class_students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_students_class_id_foreign` (`class_id`),
  ADD KEY `class_students_student_id_foreign` (`student_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `grades_unique_per_period` (`student_id`,`subject_id`,`period`,`period_type`),
  ADD KEY `grades_subject_id_foreign` (`subject_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subjects_class_id_foreign` (`class_id`),
  ADD KEY `subjects_teacher_id_foreign` (`teacher_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_lrn_unique` (`lrn`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `class_students`
--
ALTER TABLE `class_students`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `grades`
--
ALTER TABLE `grades`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=281;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `classes_adviser_id_foreign` FOREIGN KEY (`adviser_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `class_students`
--
ALTER TABLE `class_students`
  ADD CONSTRAINT `class_students_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `class_students_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `grades`
--
ALTER TABLE `grades`
  ADD CONSTRAINT `grades_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `grades_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `subjects_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subjects_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
