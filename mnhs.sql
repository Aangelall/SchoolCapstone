-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 17, 2025 at 12:09 PM
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

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('ethan.suvillan|192.168.123.8', 'i:1;', 1744850401),
('ethan.suvillan|192.168.123.8:timer', 'i:1744850401;', 1744850401);

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
(1, 'junior', 7, '1', NULL, NULL, NULL, NULL, '2025-04-16 16:45:47', '2025-04-16 17:36:09'),
(2, 'junior', 7, '1', NULL, NULL, NULL, NULL, '2025-04-16 19:45:35', '2025-04-16 21:17:55'),
(3, 'junior', 7, '1', NULL, NULL, 8, NULL, '2025-04-16 22:29:40', '2025-04-16 22:29:40');

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
  `section_group` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `class_students`
--

INSERT INTO `class_students` (`id`, `class_id`, `student_id`, `is_promoted`, `adviser_name`, `section_group`, `created_at`, `updated_at`) VALUES
(1, 1, 70, 1, 'Anna Rose Supera', NULL, '2025-04-16 16:45:48', '2025-04-16 17:36:09'),
(2, 1, 71, 1, 'Anna Rose Supera', NULL, '2025-04-16 16:45:48', '2025-04-16 17:36:09'),
(3, 1, 72, 1, 'Anna Rose Supera', NULL, '2025-04-16 16:45:49', '2025-04-16 17:36:09'),
(4, 1, 73, 1, 'Anna Rose Supera', NULL, '2025-04-16 16:52:51', '2025-04-16 17:36:09'),
(5, 2, 70, 1, 'Anna Rose Supera', '1', '2025-04-16 19:45:36', '2025-04-16 21:17:55'),
(6, 2, 71, 1, 'Anna Rose Supera', '1', '2025-04-16 19:45:36', '2025-04-16 21:17:55'),
(7, 2, 72, 1, 'Anna Rose Supera', '1', '2025-04-16 19:45:37', '2025-04-16 21:17:55'),
(8, 3, 70, 0, NULL, NULL, '2025-04-16 22:29:41', '2025-04-16 22:29:41'),
(9, 3, 71, 0, NULL, NULL, '2025-04-16 22:29:42', '2025-04-16 22:29:42'),
(10, 3, 72, 0, NULL, NULL, '2025-04-16 22:29:42', '2025-04-16 22:29:42');

-- --------------------------------------------------------

--
-- Table structure for table `class_subjects`
--

CREATE TABLE `class_subjects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `class_id` bigint(20) UNSIGNED NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `teacher_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, 72, 7, 90.00, '2025-04-16 16:49:19', '2025-04-16 17:36:09', 1, 'quarter', 1, 'Irene Abucay'),
(2, 71, 7, 80.00, '2025-04-16 16:49:19', '2025-04-16 17:36:09', 1, 'quarter', 1, 'Irene Abucay'),
(3, 70, 7, 74.00, '2025-04-16 16:49:19', '2025-04-16 17:36:09', 1, 'quarter', 1, 'Irene Abucay'),
(4, 72, 1, 90.00, '2025-04-16 16:49:28', '2025-04-16 17:36:09', 1, 'quarter', 1, 'Irene Abucay'),
(5, 71, 1, 90.00, '2025-04-16 16:49:28', '2025-04-16 17:36:09', 1, 'quarter', 1, 'Irene Abucay'),
(6, 70, 1, 90.00, '2025-04-16 16:49:28', '2025-04-16 17:36:09', 1, 'quarter', 1, 'Irene Abucay'),
(7, 72, 3, 74.00, '2025-04-16 16:49:57', '2025-04-16 17:36:09', 1, 'quarter', 1, 'Irene Abucay'),
(8, 71, 3, 74.00, '2025-04-16 16:49:57', '2025-04-16 17:36:09', 1, 'quarter', 1, 'Irene Abucay'),
(9, 70, 3, 80.00, '2025-04-16 16:49:57', '2025-04-16 17:36:09', 1, 'quarter', 1, 'Irene Abucay'),
(10, 72, 5, 90.00, '2025-04-16 16:50:07', '2025-04-16 17:36:09', 1, 'quarter', 1, 'Irene Abucay'),
(11, 71, 5, 80.00, '2025-04-16 16:50:07', '2025-04-16 17:36:09', 1, 'quarter', 1, 'Irene Abucay'),
(12, 70, 5, 70.00, '2025-04-16 16:50:07', '2025-04-16 17:36:09', 1, 'quarter', 1, 'Irene Abucay'),
(13, 72, 2, 91.00, '2025-04-16 16:50:40', '2025-04-16 17:36:09', 1, 'quarter', 1, 'Jencill Arenza'),
(14, 71, 2, 90.00, '2025-04-16 16:50:40', '2025-04-16 17:36:09', 1, 'quarter', 1, 'Jencill Arenza'),
(15, 70, 2, 80.00, '2025-04-16 16:50:40', '2025-04-16 17:36:09', 1, 'quarter', 1, 'Jencill Arenza'),
(16, 72, 4, 90.00, '2025-04-16 16:50:52', '2025-04-16 17:36:09', 1, 'quarter', 1, 'Jencill Arenza'),
(17, 71, 4, 90.00, '2025-04-16 16:50:52', '2025-04-16 17:36:09', 1, 'quarter', 1, 'Jencill Arenza'),
(18, 70, 4, 80.00, '2025-04-16 16:50:52', '2025-04-16 17:36:09', 1, 'quarter', 1, 'Jencill Arenza'),
(19, 72, 6, 80.00, '2025-04-16 16:51:01', '2025-04-16 17:36:09', 1, 'quarter', 1, 'Jencill Arenza'),
(20, 71, 6, 90.00, '2025-04-16 16:51:01', '2025-04-16 17:36:09', 1, 'quarter', 1, 'Jencill Arenza'),
(21, 70, 6, 80.00, '2025-04-16 16:51:01', '2025-04-16 17:36:09', 1, 'quarter', 1, 'Jencill Arenza'),
(22, 72, 8, 90.00, '2025-04-16 16:51:14', '2025-04-16 17:36:09', 1, 'quarter', 1, 'Jencill Arenza'),
(23, 71, 8, 80.00, '2025-04-16 16:51:14', '2025-04-16 17:36:09', 1, 'quarter', 1, 'Jencill Arenza'),
(24, 70, 8, 80.00, '2025-04-16 16:51:14', '2025-04-16 17:36:09', 1, 'quarter', 1, 'Jencill Arenza'),
(25, 73, 5, 90.00, '2025-04-16 17:21:45', '2025-04-16 17:36:09', 1, 'quarter', 1, 'Irene Abucay'),
(26, 73, 2, 80.00, '2025-04-16 17:22:13', '2025-04-16 17:36:09', 1, 'quarter', 1, 'Jencill Arenza'),
(27, 73, 4, 80.00, '2025-04-16 17:22:20', '2025-04-16 17:36:09', 1, 'quarter', 1, 'Jencill Arenza'),
(28, 73, 6, 90.00, '2025-04-16 17:22:30', '2025-04-16 17:36:09', 1, 'quarter', 1, 'Jencill Arenza'),
(29, 73, 8, 80.00, '2025-04-16 17:22:38', '2025-04-16 17:36:09', 1, 'quarter', 1, 'Jencill Arenza'),
(30, 73, 1, 90.00, '2025-04-16 17:23:23', '2025-04-16 17:36:09', 1, 'quarter', 1, 'Irene Abucay'),
(31, 73, 3, 80.00, '2025-04-16 17:23:30', '2025-04-16 17:36:09', 1, 'quarter', 1, 'Irene Abucay'),
(32, 73, 7, 80.00, '2025-04-16 17:23:41', '2025-04-16 17:36:09', 1, 'quarter', 1, 'Irene Abucay'),
(33, 73, 7, 90.00, '2025-04-16 17:25:53', '2025-04-16 17:36:09', 2, 'quarter', 1, 'Irene Abucay'),
(34, 72, 7, 90.00, '2025-04-16 17:25:53', '2025-04-16 17:36:09', 2, 'quarter', 1, 'Irene Abucay'),
(35, 71, 7, 90.00, '2025-04-16 17:25:53', '2025-04-16 17:36:09', 2, 'quarter', 1, 'Irene Abucay'),
(36, 70, 7, 90.00, '2025-04-16 17:25:53', '2025-04-16 17:36:09', 2, 'quarter', 1, 'Irene Abucay'),
(37, 73, 3, 90.00, '2025-04-16 17:26:01', '2025-04-16 17:36:09', 2, 'quarter', 1, 'Irene Abucay'),
(38, 72, 3, 90.00, '2025-04-16 17:26:01', '2025-04-16 17:36:09', 2, 'quarter', 1, 'Irene Abucay'),
(39, 71, 3, 90.00, '2025-04-16 17:26:01', '2025-04-16 17:36:09', 2, 'quarter', 1, 'Irene Abucay'),
(40, 70, 3, 90.00, '2025-04-16 17:26:01', '2025-04-16 17:36:09', 2, 'quarter', 1, 'Irene Abucay'),
(41, 73, 1, 80.00, '2025-04-16 17:26:14', '2025-04-16 17:36:09', 2, 'quarter', 1, 'Irene Abucay'),
(42, 72, 1, 78.00, '2025-04-16 17:26:14', '2025-04-16 17:36:09', 2, 'quarter', 1, 'Irene Abucay'),
(43, 71, 1, 90.00, '2025-04-16 17:26:14', '2025-04-16 17:36:09', 2, 'quarter', 1, 'Irene Abucay'),
(44, 70, 1, 60.00, '2025-04-16 17:26:14', '2025-04-16 17:36:09', 2, 'quarter', 1, 'Irene Abucay'),
(45, 73, 5, 90.00, '2025-04-16 17:26:40', '2025-04-16 17:36:09', 2, 'quarter', 1, 'Irene Abucay'),
(46, 72, 5, 20.00, '2025-04-16 17:26:40', '2025-04-16 17:36:09', 2, 'quarter', 1, 'Irene Abucay'),
(47, 71, 5, 30.00, '2025-04-16 17:26:40', '2025-04-16 17:36:09', 2, 'quarter', 1, 'Irene Abucay'),
(48, 70, 5, 70.00, '2025-04-16 17:26:40', '2025-04-16 17:36:09', 2, 'quarter', 1, 'Irene Abucay'),
(49, 73, 5, 90.00, '2025-04-16 17:27:24', '2025-04-16 17:36:09', 3, 'quarter', 1, 'Irene Abucay'),
(50, 72, 5, 90.00, '2025-04-16 17:27:25', '2025-04-16 17:36:09', 3, 'quarter', 1, 'Irene Abucay'),
(51, 71, 5, 90.00, '2025-04-16 17:27:25', '2025-04-16 17:36:09', 3, 'quarter', 1, 'Irene Abucay'),
(52, 70, 5, 90.00, '2025-04-16 17:27:25', '2025-04-16 17:36:09', 3, 'quarter', 1, 'Irene Abucay'),
(53, 73, 1, 90.00, '2025-04-16 17:27:34', '2025-04-16 17:36:09', 3, 'quarter', 1, 'Irene Abucay'),
(54, 72, 1, 80.00, '2025-04-16 17:27:34', '2025-04-16 17:36:09', 3, 'quarter', 1, 'Irene Abucay'),
(55, 71, 1, 70.00, '2025-04-16 17:27:34', '2025-04-16 17:36:09', 3, 'quarter', 1, 'Irene Abucay'),
(56, 70, 1, 90.00, '2025-04-16 17:27:34', '2025-04-16 17:36:09', 3, 'quarter', 1, 'Irene Abucay'),
(57, 73, 3, 90.00, '2025-04-16 17:27:44', '2025-04-16 17:36:09', 3, 'quarter', 1, 'Irene Abucay'),
(58, 72, 3, 80.00, '2025-04-16 17:27:44', '2025-04-16 17:36:09', 3, 'quarter', 1, 'Irene Abucay'),
(59, 71, 3, 70.00, '2025-04-16 17:27:44', '2025-04-16 17:36:09', 3, 'quarter', 1, 'Irene Abucay'),
(60, 70, 3, 90.00, '2025-04-16 17:27:44', '2025-04-16 17:36:09', 3, 'quarter', 1, 'Irene Abucay'),
(61, 73, 7, 90.00, '2025-04-16 17:27:56', '2025-04-16 17:36:09', 3, 'quarter', 1, 'Irene Abucay'),
(62, 72, 7, 90.00, '2025-04-16 17:27:56', '2025-04-16 17:36:09', 3, 'quarter', 1, 'Irene Abucay'),
(63, 71, 7, 80.00, '2025-04-16 17:27:56', '2025-04-16 17:36:09', 3, 'quarter', 1, 'Irene Abucay'),
(64, 70, 7, 70.00, '2025-04-16 17:27:56', '2025-04-16 17:36:09', 3, 'quarter', 1, 'Irene Abucay'),
(65, 73, 2, 90.00, '2025-04-16 17:28:34', '2025-04-16 17:36:09', 2, 'quarter', 1, 'Jencill Arenza'),
(66, 72, 2, 80.00, '2025-04-16 17:28:34', '2025-04-16 17:36:09', 2, 'quarter', 1, 'Jencill Arenza'),
(67, 71, 2, 60.00, '2025-04-16 17:28:34', '2025-04-16 17:36:09', 2, 'quarter', 1, 'Jencill Arenza'),
(68, 70, 2, 80.00, '2025-04-16 17:28:34', '2025-04-16 17:36:09', 2, 'quarter', 1, 'Jencill Arenza'),
(69, 73, 2, 78.00, '2025-04-16 17:28:48', '2025-04-16 17:36:09', 3, 'quarter', 1, 'Jencill Arenza'),
(70, 72, 2, 79.00, '2025-04-16 17:28:48', '2025-04-16 17:36:09', 3, 'quarter', 1, 'Jencill Arenza'),
(71, 71, 2, 89.00, '2025-04-16 17:28:48', '2025-04-16 17:36:09', 3, 'quarter', 1, 'Jencill Arenza'),
(72, 70, 2, 90.00, '2025-04-16 17:28:48', '2025-04-16 17:36:09', 3, 'quarter', 1, 'Jencill Arenza'),
(73, 73, 2, 78.00, '2025-04-16 17:29:00', '2025-04-16 17:36:09', 4, 'quarter', 1, 'Jencill Arenza'),
(74, 72, 2, 90.00, '2025-04-16 17:29:00', '2025-04-16 17:36:09', 4, 'quarter', 1, 'Jencill Arenza'),
(75, 71, 2, 89.00, '2025-04-16 17:29:00', '2025-04-16 17:36:09', 4, 'quarter', 1, 'Jencill Arenza'),
(76, 70, 2, 90.00, '2025-04-16 17:29:00', '2025-04-16 17:36:09', 4, 'quarter', 1, 'Jencill Arenza'),
(77, 73, 7, 90.00, '2025-04-16 17:29:46', '2025-04-16 17:36:09', 4, 'quarter', 1, 'Irene Abucay'),
(78, 72, 7, 98.00, '2025-04-16 17:29:46', '2025-04-16 17:36:09', 4, 'quarter', 1, 'Irene Abucay'),
(79, 71, 7, 90.00, '2025-04-16 17:29:46', '2025-04-16 17:36:09', 4, 'quarter', 1, 'Irene Abucay'),
(80, 70, 7, 80.00, '2025-04-16 17:29:46', '2025-04-16 17:36:09', 4, 'quarter', 1, 'Irene Abucay'),
(81, 73, 1, 89.00, '2025-04-16 17:29:55', '2025-04-16 17:36:09', 4, 'quarter', 1, 'Irene Abucay'),
(82, 72, 1, 90.00, '2025-04-16 17:29:55', '2025-04-16 17:36:09', 4, 'quarter', 1, 'Irene Abucay'),
(83, 71, 1, 90.00, '2025-04-16 17:29:55', '2025-04-16 17:36:09', 4, 'quarter', 1, 'Irene Abucay'),
(84, 70, 1, 89.00, '2025-04-16 17:29:55', '2025-04-16 17:36:09', 4, 'quarter', 1, 'Irene Abucay'),
(85, 73, 3, 89.00, '2025-04-16 17:30:06', '2025-04-16 17:36:09', 4, 'quarter', 1, 'Irene Abucay'),
(86, 72, 3, 78.00, '2025-04-16 17:30:06', '2025-04-16 17:36:09', 4, 'quarter', 1, 'Irene Abucay'),
(87, 71, 3, 87.00, '2025-04-16 17:30:06', '2025-04-16 17:36:09', 4, 'quarter', 1, 'Irene Abucay'),
(88, 70, 3, 98.00, '2025-04-16 17:30:06', '2025-04-16 17:36:09', 4, 'quarter', 1, 'Irene Abucay'),
(89, 73, 5, 76.00, '2025-04-16 17:30:22', '2025-04-16 17:36:09', 4, 'quarter', 1, 'Irene Abucay'),
(90, 72, 5, 90.00, '2025-04-16 17:30:22', '2025-04-16 17:36:09', 4, 'quarter', 1, 'Irene Abucay'),
(91, 71, 5, 89.00, '2025-04-16 17:30:22', '2025-04-16 17:36:09', 4, 'quarter', 1, 'Irene Abucay'),
(92, 70, 5, 90.00, '2025-04-16 17:30:22', '2025-04-16 17:36:09', 4, 'quarter', 1, 'Irene Abucay'),
(93, 73, 4, 76.00, '2025-04-16 17:31:48', '2025-04-16 17:36:09', 2, 'quarter', 1, 'Jencill Arenza'),
(94, 72, 4, 87.00, '2025-04-16 17:31:48', '2025-04-16 17:36:09', 2, 'quarter', 1, 'Jencill Arenza'),
(95, 71, 4, 98.00, '2025-04-16 17:31:48', '2025-04-16 17:36:09', 2, 'quarter', 1, 'Jencill Arenza'),
(96, 70, 4, 97.00, '2025-04-16 17:31:48', '2025-04-16 17:36:09', 2, 'quarter', 1, 'Jencill Arenza'),
(97, 73, 4, 90.00, '2025-04-16 17:32:05', '2025-04-16 17:36:09', 3, 'quarter', 1, 'Jencill Arenza'),
(98, 72, 4, 89.00, '2025-04-16 17:32:05', '2025-04-16 17:36:09', 3, 'quarter', 1, 'Jencill Arenza'),
(99, 71, 4, 78.00, '2025-04-16 17:32:05', '2025-04-16 17:36:09', 3, 'quarter', 1, 'Jencill Arenza'),
(100, 70, 4, 87.00, '2025-04-16 17:32:05', '2025-04-16 17:36:09', 3, 'quarter', 1, 'Jencill Arenza'),
(101, 73, 4, 98.00, '2025-04-16 17:32:15', '2025-04-16 17:36:09', 4, 'quarter', 1, 'Jencill Arenza'),
(102, 72, 4, 89.00, '2025-04-16 17:32:15', '2025-04-16 17:36:09', 4, 'quarter', 1, 'Jencill Arenza'),
(103, 71, 4, 78.00, '2025-04-16 17:32:15', '2025-04-16 17:36:09', 4, 'quarter', 1, 'Jencill Arenza'),
(104, 70, 4, 87.00, '2025-04-16 17:32:15', '2025-04-16 17:36:09', 4, 'quarter', 1, 'Jencill Arenza'),
(105, 73, 6, 90.00, '2025-04-16 17:32:54', '2025-04-16 17:36:09', 2, 'quarter', 1, 'Jencill Arenza'),
(106, 72, 6, 89.00, '2025-04-16 17:32:54', '2025-04-16 17:36:09', 2, 'quarter', 1, 'Jencill Arenza'),
(107, 71, 6, 98.00, '2025-04-16 17:32:54', '2025-04-16 17:36:09', 2, 'quarter', 1, 'Jencill Arenza'),
(108, 70, 6, 78.00, '2025-04-16 17:32:54', '2025-04-16 17:36:09', 2, 'quarter', 1, 'Jencill Arenza'),
(109, 73, 6, 78.00, '2025-04-16 17:33:07', '2025-04-16 17:36:09', 3, 'quarter', 1, 'Jencill Arenza'),
(110, 72, 6, 87.00, '2025-04-16 17:33:07', '2025-04-16 17:36:09', 3, 'quarter', 1, 'Jencill Arenza'),
(111, 71, 6, 98.00, '2025-04-16 17:33:07', '2025-04-16 17:36:09', 3, 'quarter', 1, 'Jencill Arenza'),
(112, 70, 6, 98.00, '2025-04-16 17:33:07', '2025-04-16 17:36:09', 3, 'quarter', 1, 'Jencill Arenza'),
(113, 73, 8, 90.00, '2025-04-16 17:33:20', '2025-04-16 17:36:09', 2, 'quarter', 1, 'Jencill Arenza'),
(114, 72, 8, 98.00, '2025-04-16 17:33:20', '2025-04-16 17:36:09', 2, 'quarter', 1, 'Jencill Arenza'),
(115, 71, 8, 78.00, '2025-04-16 17:33:20', '2025-04-16 17:36:09', 2, 'quarter', 1, 'Jencill Arenza'),
(116, 70, 8, 98.00, '2025-04-16 17:33:20', '2025-04-16 17:36:09', 2, 'quarter', 1, 'Jencill Arenza'),
(117, 73, 8, 90.00, '2025-04-16 17:33:36', '2025-04-16 17:36:09', 3, 'quarter', 1, 'Jencill Arenza'),
(118, 72, 8, 97.00, '2025-04-16 17:33:36', '2025-04-16 17:36:09', 3, 'quarter', 1, 'Jencill Arenza'),
(119, 71, 8, 89.00, '2025-04-16 17:33:36', '2025-04-16 17:36:09', 3, 'quarter', 1, 'Jencill Arenza'),
(120, 70, 8, 78.00, '2025-04-16 17:33:36', '2025-04-16 17:36:09', 3, 'quarter', 1, 'Jencill Arenza'),
(121, 73, 8, 89.00, '2025-04-16 17:34:42', '2025-04-16 17:36:09', 4, 'quarter', 1, 'Jencill Arenza'),
(122, 72, 8, 87.00, '2025-04-16 17:34:42', '2025-04-16 17:36:09', 4, 'quarter', 1, 'Jencill Arenza'),
(123, 71, 8, 98.00, '2025-04-16 17:34:42', '2025-04-16 17:36:09', 4, 'quarter', 1, 'Jencill Arenza'),
(124, 70, 8, 89.00, '2025-04-16 17:34:42', '2025-04-16 17:36:09', 4, 'quarter', 1, 'Jencill Arenza'),
(125, 73, 6, 90.00, '2025-04-16 17:35:10', '2025-04-16 17:36:09', 4, 'quarter', 1, 'Jencill Arenza'),
(126, 72, 6, 98.00, '2025-04-16 17:35:10', '2025-04-16 17:36:09', 4, 'quarter', 1, 'Jencill Arenza'),
(127, 71, 6, 78.00, '2025-04-16 17:35:10', '2025-04-16 17:36:09', 4, 'quarter', 1, 'Jencill Arenza'),
(128, 70, 6, 78.00, '2025-04-16 17:35:10', '2025-04-16 17:36:09', 4, 'quarter', 1, 'Jencill Arenza'),
(129, 72, 16, 90.00, '2025-04-16 19:55:43', '2025-04-16 21:17:55', 1, 'quarter', 1, 'Irene Abucay'),
(130, 71, 16, 80.00, '2025-04-16 19:55:43', '2025-04-16 21:17:55', 1, 'quarter', 1, 'Irene Abucay'),
(131, 70, 16, 70.00, '2025-04-16 19:55:43', '2025-04-16 21:17:55', 1, 'quarter', 1, 'Irene Abucay'),
(132, 71, 14, 90.00, '2025-04-16 20:20:25', '2025-04-16 21:17:55', 1, 'quarter', 1, 'Irene Abucay'),
(133, 70, 14, 74.00, '2025-04-16 20:20:25', '2025-04-16 21:17:55', 1, 'quarter', 1, 'Irene Abucay'),
(134, 72, 14, 90.00, '2025-04-16 20:23:27', '2025-04-16 21:17:55', 1, 'quarter', 1, 'Irene Abucay'),
(135, 72, 9, 90.00, '2025-04-16 20:39:20', '2025-04-16 21:17:55', 1, 'quarter', 1, 'Irene Abucay'),
(136, 71, 9, 78.00, '2025-04-16 20:39:20', '2025-04-16 21:17:55', 1, 'quarter', 1, 'Irene Abucay'),
(137, 70, 9, 76.00, '2025-04-16 20:39:20', '2025-04-16 21:17:55', 1, 'quarter', 1, 'Irene Abucay'),
(138, 72, 9, 98.00, '2025-04-16 20:39:36', '2025-04-16 21:17:55', 2, 'quarter', 1, 'Irene Abucay'),
(139, 71, 9, 78.00, '2025-04-16 20:39:36', '2025-04-16 21:17:55', 2, 'quarter', 1, 'Irene Abucay'),
(140, 70, 9, 90.00, '2025-04-16 20:39:36', '2025-04-16 21:17:55', 2, 'quarter', 1, 'Irene Abucay'),
(141, 72, 9, 78.00, '2025-04-16 20:39:46', '2025-04-16 21:17:55', 3, 'quarter', 1, 'Irene Abucay'),
(142, 71, 9, 87.00, '2025-04-16 20:39:46', '2025-04-16 21:17:55', 3, 'quarter', 1, 'Irene Abucay'),
(143, 70, 9, 98.00, '2025-04-16 20:39:46', '2025-04-16 21:17:55', 3, 'quarter', 1, 'Irene Abucay'),
(144, 72, 9, 98.00, '2025-04-16 20:40:00', '2025-04-16 21:17:55', 4, 'quarter', 1, 'Irene Abucay'),
(145, 71, 9, 78.00, '2025-04-16 20:40:00', '2025-04-16 21:17:55', 4, 'quarter', 1, 'Irene Abucay'),
(146, 70, 9, 90.00, '2025-04-16 20:40:00', '2025-04-16 21:17:55', 4, 'quarter', 1, 'Irene Abucay'),
(147, 72, 10, 89.00, '2025-04-16 20:40:25', '2025-04-16 21:17:55', 1, 'quarter', 1, 'Irene Abucay'),
(148, 71, 10, 78.00, '2025-04-16 20:40:25', '2025-04-16 21:17:55', 1, 'quarter', 1, 'Irene Abucay'),
(149, 70, 10, 95.00, '2025-04-16 20:40:25', '2025-04-16 21:17:55', 1, 'quarter', 1, 'Irene Abucay'),
(150, 72, 10, 78.00, '2025-04-16 20:40:36', '2025-04-16 21:17:55', 2, 'quarter', 1, 'Irene Abucay'),
(151, 71, 10, 87.00, '2025-04-16 20:40:36', '2025-04-16 21:17:55', 2, 'quarter', 1, 'Irene Abucay'),
(152, 70, 10, 89.00, '2025-04-16 20:40:36', '2025-04-16 21:17:55', 2, 'quarter', 1, 'Irene Abucay'),
(153, 72, 10, 87.00, '2025-04-16 20:40:48', '2025-04-16 21:17:55', 3, 'quarter', 1, 'Irene Abucay'),
(154, 71, 10, 100.00, '2025-04-16 20:40:48', '2025-04-16 21:17:55', 3, 'quarter', 1, 'Irene Abucay'),
(155, 70, 10, 67.00, '2025-04-16 20:40:48', '2025-04-16 21:17:55', 3, 'quarter', 1, 'Irene Abucay'),
(156, 72, 10, 89.00, '2025-04-16 20:41:07', '2025-04-16 21:17:55', 4, 'quarter', 1, 'Irene Abucay'),
(157, 71, 10, 98.00, '2025-04-16 20:41:07', '2025-04-16 21:17:55', 4, 'quarter', 1, 'Irene Abucay'),
(158, 70, 10, 99.00, '2025-04-16 20:41:07', '2025-04-16 21:17:55', 4, 'quarter', 1, 'Irene Abucay'),
(159, 72, 11, 78.00, '2025-04-16 20:41:41', '2025-04-16 21:17:55', 1, 'quarter', 1, 'Irene Abucay'),
(160, 71, 11, 75.00, '2025-04-16 20:41:41', '2025-04-16 21:17:55', 1, 'quarter', 1, 'Irene Abucay'),
(161, 70, 11, 90.00, '2025-04-16 20:41:41', '2025-04-16 21:17:55', 1, 'quarter', 1, 'Irene Abucay'),
(162, 72, 11, 78.00, '2025-04-16 20:41:52', '2025-04-16 21:17:55', 2, 'quarter', 1, 'Irene Abucay'),
(163, 71, 11, 100.00, '2025-04-16 20:41:52', '2025-04-16 21:17:55', 2, 'quarter', 1, 'Irene Abucay'),
(164, 70, 11, 67.00, '2025-04-16 20:41:52', '2025-04-16 21:17:55', 2, 'quarter', 1, 'Irene Abucay'),
(165, 72, 11, 87.00, '2025-04-16 20:42:04', '2025-04-16 21:17:55', 3, 'quarter', 1, 'Irene Abucay'),
(166, 71, 11, 98.00, '2025-04-16 20:42:04', '2025-04-16 21:17:55', 3, 'quarter', 1, 'Irene Abucay'),
(167, 70, 11, 90.00, '2025-04-16 20:42:04', '2025-04-16 21:17:55', 3, 'quarter', 1, 'Irene Abucay'),
(168, 72, 11, 89.00, '2025-04-16 20:42:13', '2025-04-16 21:17:55', 4, 'quarter', 1, 'Irene Abucay'),
(169, 71, 11, 78.00, '2025-04-16 20:42:13', '2025-04-16 21:17:55', 4, 'quarter', 1, 'Irene Abucay'),
(170, 70, 11, 98.00, '2025-04-16 20:42:13', '2025-04-16 21:17:55', 4, 'quarter', 1, 'Irene Abucay'),
(171, 72, 12, 67.00, '2025-04-16 20:42:33', '2025-04-16 21:17:55', 1, 'quarter', 1, 'Irene Abucay'),
(172, 71, 12, 76.00, '2025-04-16 20:42:33', '2025-04-16 21:17:55', 1, 'quarter', 1, 'Irene Abucay'),
(173, 70, 12, 87.00, '2025-04-16 20:42:33', '2025-04-16 21:17:55', 1, 'quarter', 1, 'Irene Abucay'),
(174, 72, 12, 78.00, '2025-04-16 20:42:42', '2025-04-16 21:17:55', 2, 'quarter', 1, 'Irene Abucay'),
(175, 71, 12, 87.00, '2025-04-16 20:42:42', '2025-04-16 21:17:55', 2, 'quarter', 1, 'Irene Abucay'),
(176, 70, 12, 98.00, '2025-04-16 20:42:42', '2025-04-16 21:17:55', 2, 'quarter', 1, 'Irene Abucay'),
(177, 72, 12, 87.00, '2025-04-16 20:42:51', '2025-04-16 21:17:55', 3, 'quarter', 1, 'Irene Abucay'),
(178, 71, 12, 68.00, '2025-04-16 20:42:51', '2025-04-16 21:17:55', 3, 'quarter', 1, 'Irene Abucay'),
(179, 70, 12, 98.00, '2025-04-16 20:42:51', '2025-04-16 21:17:55', 3, 'quarter', 1, 'Irene Abucay'),
(180, 72, 12, 78.00, '2025-04-16 20:43:00', '2025-04-16 21:17:55', 4, 'quarter', 1, 'Irene Abucay'),
(181, 71, 12, 87.00, '2025-04-16 20:43:00', '2025-04-16 21:17:55', 4, 'quarter', 1, 'Irene Abucay'),
(182, 70, 12, 98.00, '2025-04-16 20:43:00', '2025-04-16 21:17:55', 4, 'quarter', 1, 'Irene Abucay'),
(183, 72, 13, 89.00, '2025-04-16 20:43:19', '2025-04-16 21:17:55', 1, 'quarter', 1, 'Irene Abucay'),
(184, 71, 13, 89.00, '2025-04-16 20:43:19', '2025-04-16 21:17:55', 1, 'quarter', 1, 'Irene Abucay'),
(185, 70, 13, 78.00, '2025-04-16 20:43:19', '2025-04-16 21:17:55', 1, 'quarter', 1, 'Irene Abucay'),
(186, 72, 13, 89.00, '2025-04-16 20:43:38', '2025-04-16 21:17:55', 2, 'quarter', 1, 'Irene Abucay'),
(187, 71, 13, 89.00, '2025-04-16 20:43:38', '2025-04-16 21:17:55', 2, 'quarter', 1, 'Irene Abucay'),
(188, 70, 13, 78.00, '2025-04-16 20:43:38', '2025-04-16 21:17:55', 2, 'quarter', 1, 'Irene Abucay'),
(189, 72, 13, 98.00, '2025-04-16 20:43:48', '2025-04-16 21:17:55', 3, 'quarter', 1, 'Irene Abucay'),
(190, 71, 13, 100.00, '2025-04-16 20:43:48', '2025-04-16 21:17:55', 3, 'quarter', 1, 'Irene Abucay'),
(191, 70, 13, 98.00, '2025-04-16 20:43:48', '2025-04-16 21:17:55', 3, 'quarter', 1, 'Irene Abucay'),
(192, 72, 13, 67.00, '2025-04-16 20:43:59', '2025-04-16 21:17:55', 4, 'quarter', 1, 'Irene Abucay'),
(193, 71, 13, 65.00, '2025-04-16 20:43:59', '2025-04-16 21:17:55', 4, 'quarter', 1, 'Irene Abucay'),
(194, 70, 13, 87.00, '2025-04-16 20:43:59', '2025-04-16 21:17:55', 4, 'quarter', 1, 'Irene Abucay'),
(195, 72, 14, 89.00, '2025-04-16 20:44:26', '2025-04-16 21:17:55', 2, 'quarter', 1, 'Irene Abucay'),
(196, 71, 14, 76.00, '2025-04-16 20:44:26', '2025-04-16 21:17:55', 2, 'quarter', 1, 'Irene Abucay'),
(197, 70, 14, 98.00, '2025-04-16 20:44:26', '2025-04-16 21:17:55', 2, 'quarter', 1, 'Irene Abucay'),
(198, 72, 14, 98.00, '2025-04-16 20:44:36', '2025-04-16 21:17:55', 3, 'quarter', 1, 'Irene Abucay'),
(199, 71, 14, 100.00, '2025-04-16 20:44:36', '2025-04-16 21:17:55', 3, 'quarter', 1, 'Irene Abucay'),
(200, 70, 14, 78.00, '2025-04-16 20:44:36', '2025-04-16 21:17:55', 3, 'quarter', 1, 'Irene Abucay'),
(201, 72, 14, 98.00, '2025-04-16 20:44:44', '2025-04-16 21:17:55', 4, 'quarter', 1, 'Irene Abucay'),
(202, 71, 14, 89.00, '2025-04-16 20:44:44', '2025-04-16 21:17:55', 4, 'quarter', 1, 'Irene Abucay'),
(203, 70, 14, 87.00, '2025-04-16 20:44:44', '2025-04-16 21:17:55', 4, 'quarter', 1, 'Irene Abucay'),
(204, 72, 15, 78.00, '2025-04-16 20:44:58', '2025-04-16 21:17:55', 1, 'quarter', 1, 'Irene Abucay'),
(205, 71, 15, 87.00, '2025-04-16 20:44:58', '2025-04-16 21:17:55', 1, 'quarter', 1, 'Irene Abucay'),
(206, 70, 15, 98.00, '2025-04-16 20:44:58', '2025-04-16 21:17:55', 1, 'quarter', 1, 'Irene Abucay'),
(207, 72, 15, 89.00, '2025-04-16 20:48:11', '2025-04-16 21:17:55', 2, 'quarter', 1, 'Irene Abucay'),
(208, 71, 15, 87.00, '2025-04-16 20:48:11', '2025-04-16 21:17:55', 2, 'quarter', 1, 'Irene Abucay'),
(209, 70, 15, 78.00, '2025-04-16 20:48:11', '2025-04-16 21:17:55', 2, 'quarter', 1, 'Irene Abucay'),
(210, 72, 15, 89.00, '2025-04-16 20:48:20', '2025-04-16 21:17:55', 3, 'quarter', 1, 'Irene Abucay'),
(211, 71, 15, 78.00, '2025-04-16 20:48:20', '2025-04-16 21:17:55', 3, 'quarter', 1, 'Irene Abucay'),
(212, 70, 15, 78.00, '2025-04-16 20:48:20', '2025-04-16 21:17:55', 3, 'quarter', 1, 'Irene Abucay'),
(213, 72, 15, 98.00, '2025-04-16 20:48:29', '2025-04-16 21:17:55', 4, 'quarter', 1, 'Irene Abucay'),
(214, 71, 15, 78.00, '2025-04-16 20:48:29', '2025-04-16 21:17:55', 4, 'quarter', 1, 'Irene Abucay'),
(215, 70, 15, 76.00, '2025-04-16 20:48:29', '2025-04-16 21:17:55', 4, 'quarter', 1, 'Irene Abucay'),
(216, 72, 16, 89.00, '2025-04-16 20:48:38', '2025-04-16 21:17:55', 2, 'quarter', 1, 'Irene Abucay'),
(217, 71, 16, 78.00, '2025-04-16 20:48:38', '2025-04-16 21:17:55', 2, 'quarter', 1, 'Irene Abucay'),
(218, 70, 16, 78.00, '2025-04-16 20:48:38', '2025-04-16 21:17:55', 2, 'quarter', 1, 'Irene Abucay'),
(219, 72, 16, 78.00, '2025-04-16 20:48:49', '2025-04-16 21:17:55', 3, 'quarter', 1, 'Irene Abucay'),
(220, 71, 16, 98.00, '2025-04-16 20:48:49', '2025-04-16 21:17:55', 3, 'quarter', 1, 'Irene Abucay'),
(221, 70, 16, 67.00, '2025-04-16 20:48:49', '2025-04-16 21:17:55', 3, 'quarter', 1, 'Irene Abucay'),
(222, 72, 16, 67.00, '2025-04-16 20:49:02', '2025-04-16 21:17:55', 4, 'quarter', 1, 'Irene Abucay'),
(223, 71, 16, 67.00, '2025-04-16 20:49:02', '2025-04-16 21:17:55', 4, 'quarter', 1, 'Irene Abucay'),
(224, 70, 16, 87.00, '2025-04-16 20:49:02', '2025-04-16 21:17:55', 4, 'quarter', 1, 'Irene Abucay');

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
(19, '2025_03_25_053323_add_adviser_name_to_class_students_table', 1),
(20, '2025_04_11_003015_sections', 2),
(21, '2025_04_12_070519_add_columns_to_sections_table', 3),
(22, '2025_04_12_104702_create_strands_table', 4),
(23, '2025_04_12_105942_add_grade_level_to_sections_table', 5),
(24, '2025_04_15_230114_create_class_subjects_table', 6),
(25, '2025_04_16_135556_make_email_nullable_in_users_table', 7),
(26, '2025_04_16_180927_add_description_to_subjects_table', 8),
(27, '2025_05_01_000000_add_section_group_to_class_students_table', 9);

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
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `grade_level` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `strand_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `name`, `grade_level`, `created_at`, `updated_at`, `strand_id`) VALUES
(1, 'Peridot', 7, '2025-04-16 16:44:14', '2025-04-16 16:44:14', NULL),
(2, 'Olivine', 8, '2025-04-16 16:44:24', '2025-04-16 16:44:24', NULL),
(3, 'Garnett', 9, '2025-04-16 16:44:30', '2025-04-16 16:44:30', NULL),
(4, 'Aluminum', 10, '2025-04-16 16:44:40', '2025-04-16 16:44:40', NULL),
(5, 'Hehe', 11, '2025-04-16 17:39:21', '2025-04-16 17:39:21', 1),
(6, 'huhu', 12, '2025-04-16 17:39:31', '2025-04-16 17:39:31', 1);

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
('HnoRwAazfOpYEc3mEZCnpA7wuWrBp9dowBfgGU9B', 1, '192.168.123.8', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiSWpRVWpEeFBhU01BcW8xb2RTTk80bzhGVzM5TlBsclducm1hN1A2aSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MDoiaHR0cDovLzE5Mi4xNjguMTIzLjg6ODAwMC9hZHZpc29yeS1jbGFzcyI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQwOiJodHRwOi8vMTkyLjE2OC4xMjMuODo4MDAwL2FkZGNsYXNzanVuaW9yIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1744875078),
('p18R1K59n3Z76GNDktGZxkUvZB2GSXP9Oj0dR7Jy', 5, '192.168.123.8', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiUzVYWHhTRGhmd1BYYjdjb3dkT3lia1B3T0RXRm9XZVlnWU0xNGtnVCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNDoiaHR0cDovLzE5Mi4xNjguMTIzLjg6ODAwMC9zdWJqZWN0cyI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQwOiJodHRwOi8vMTkyLjE2OC4xMjMuODo4MDAwL2Fkdmlzb3J5LWNsYXNzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NTt9', 1744869289),
('uVBn67zMSksX9orZ1sR8hw0YZQ6uKrO6g3dEinYt', 8, '192.168.123.8', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoicXludExyQ2FqT21QWVhnOFJnQmJiRzV2Yno3SHd4elFnZHR2S0dJUCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly8xOTIuMTY4LjEyMy44OjgwMDAvYWR2aXNvcnktY2xhc3MiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo4O30=', 1744875223);

-- --------------------------------------------------------

--
-- Table structure for table `strands`
--

CREATE TABLE `strands` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `strands`
--

INSERT INTO `strands` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'ABM', '2025-04-16 17:39:03', '2025-04-16 17:39:03');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `class_id` bigint(20) UNSIGNED NOT NULL,
  `teacher_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` text DEFAULT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `name`, `class_id`, `teacher_id`, `description`, `completed`, `created_at`, `updated_at`) VALUES
(1, 'Filipino', 1, NULL, NULL, 1, '2025-04-16 16:45:47', '2025-04-16 17:36:09'),
(2, 'English', 1, NULL, NULL, 1, '2025-04-16 16:45:47', '2025-04-16 17:36:09'),
(3, 'Mathematics', 1, NULL, NULL, 1, '2025-04-16 16:45:47', '2025-04-16 17:36:09'),
(4, 'Science', 1, NULL, NULL, 1, '2025-04-16 16:45:47', '2025-04-16 17:36:09'),
(5, 'Araling Panlipunan', 1, NULL, NULL, 1, '2025-04-16 16:45:47', '2025-04-16 17:36:09'),
(6, 'Edukasyon sa Pagpapakatao', 1, NULL, NULL, 1, '2025-04-16 16:45:47', '2025-04-16 17:36:09'),
(7, 'Technology and Livelihood Education', 1, NULL, NULL, 1, '2025-04-16 16:45:47', '2025-04-16 17:36:09'),
(8, 'MAPEH', 1, NULL, NULL, 1, '2025-04-16 16:45:47', '2025-04-16 17:36:09'),
(9, 'Filipino', 2, NULL, NULL, 1, '2025-04-16 19:45:35', '2025-04-16 21:17:55'),
(10, 'English', 2, NULL, NULL, 1, '2025-04-16 19:45:35', '2025-04-16 21:17:55'),
(11, 'Mathematics', 2, NULL, NULL, 1, '2025-04-16 19:45:35', '2025-04-16 21:17:55'),
(12, 'Science', 2, NULL, NULL, 1, '2025-04-16 19:45:35', '2025-04-16 21:17:55'),
(13, 'Araling Panlipunan', 2, NULL, NULL, 1, '2025-04-16 19:45:35', '2025-04-16 21:17:55'),
(14, 'Edukasyon sa Pagpapakatao', 2, NULL, NULL, 1, '2025-04-16 19:45:35', '2025-04-16 21:17:55'),
(15, 'Technology and Livelihood Education', 2, NULL, NULL, 1, '2025-04-16 19:45:35', '2025-04-16 21:17:55'),
(16, 'MAPEH', 2, NULL, NULL, 1, '2025-04-16 19:45:35', '2025-04-16 21:17:55'),
(17, 'Filipino', 3, 8, NULL, 0, '2025-04-16 22:29:41', '2025-04-16 22:29:41'),
(18, 'English', 3, 8, NULL, 0, '2025-04-16 22:29:41', '2025-04-16 22:29:41'),
(19, 'Mathematics', 3, 8, NULL, 0, '2025-04-16 22:29:41', '2025-04-16 22:29:41'),
(20, 'Science', 3, 8, NULL, 0, '2025-04-16 22:29:41', '2025-04-16 22:29:41'),
(21, 'Araling Panlipunan', 3, 8, NULL, 0, '2025-04-16 22:29:41', '2025-04-16 22:29:41'),
(22, 'Edukasyon sa Pagpapakatao', 3, 8, NULL, 0, '2025-04-16 22:29:41', '2025-04-16 22:29:41'),
(23, 'Technology and Livelihood Education', 3, 8, NULL, 0, '2025-04-16 22:29:41', '2025-04-16 22:29:41'),
(24, 'MAPEH', 3, 8, NULL, 0, '2025-04-16 22:29:41', '2025-04-16 22:29:41');

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
  `email` varchar(255) DEFAULT NULL,
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
(1, 'Admin', NULL, NULL, NULL, NULL, 'admin@admin.com', NULL, '$2y$12$M0sP2bATcuRDSWVwlAIO3u0jBmA.8sIitOXFlGoFSq3NkUxLpuiY2', 'admin', NULL, NULL, '2025-03-26 18:00:03', '2025-03-26 18:00:03'),
(4, 'Anna Rose Supera', 'Anna Rose', 'Supera', NULL, NULL, 'annarose.supera', NULL, '$2y$12$9ckA/doAcRL73daVrwIx1er25b6aCzwswMNS8vbIOvgu2lb.3D/F2', 'teacher', 'profile_images/1744287702.png', NULL, '2025-03-26 22:06:20', '2025-04-10 17:54:02'),
(5, 'Irene Abucay', 'Irene', 'Abucay', NULL, NULL, 'irene.abucay', NULL, '$2y$12$PBNr85LgQRKd/xQ6x4N7VeIM33hAuZgO2UIgEop4d28bQKHeEJvBO', 'teacher', 'profile_images/1743055621.jpg', NULL, '2025-03-26 22:07:01', '2025-03-26 22:07:01'),
(8, 'Jencill Arenza', 'Jencill', 'Arenza', NULL, NULL, 'jencill.arenza', NULL, '$2y$12$lMxS6qbLTAkL.NH7b8EbZ.7HiaVj19vct3p7/XIHlb4o7YaA3lg0.', 'teacher', NULL, NULL, '2025-03-26 22:20:22', '2025-04-09 22:32:15'),
(9, 'Ronald Monzon', 'Ronald', 'Monzon', NULL, NULL, 'ronald.monzon', NULL, '$2y$12$Jvm8kp4FEUNe.s4a1uD47.nNn9NsBd9saMrNc7w4fA.TNPAlzvLVi', 'teacher', NULL, NULL, '2025-03-26 22:26:36', '2025-04-09 22:53:01'),
(10, 'Rexon Timbal', 'Rexon', 'Timbal', NULL, NULL, 'rexon.timbal', NULL, '$2y$12$vmhn6aikuXOEwA0SNYs9CeULBzmFVMXKIICrHQHr.mqxZfJDS9.iK', 'teacher', NULL, NULL, '2025-03-26 22:26:37', '2025-04-06 02:02:01'),
(11, 'Federico Grino', 'Federico', 'Grino', NULL, NULL, 'federico.grino', NULL, '$2y$12$YL9g9.N4Y5gSLZQ0PZbJ8O1hiOiuqGhITLMyByVOcEc99M2jWlw3m', 'teacher', NULL, NULL, '2025-03-26 22:26:37', '2025-04-06 02:58:28'),
(12, 'Ryan Cuarez', 'Ryan', 'Cuarez', NULL, NULL, 'ryan.cuarez', NULL, '$2y$12$sfXEPN2lhHayJ8TJkrfKvumh4I1r4ZcqO9tRFSJ99HJ0ymOYmnxtK', 'teacher', NULL, NULL, '2025-03-26 22:26:38', '2025-03-26 22:26:38'),
(13, 'Mary Jun Palima', 'Mary Jun', 'Palima', NULL, NULL, 'maryjun.palima', NULL, '$2y$12$ZWZjhhSz51aGhhLoqPl39.e5p1cgfeT8mSReH/d4nXei0jBTUon0a', 'teacher', NULL, NULL, '2025-03-26 22:26:38', '2025-03-26 22:26:38'),
(14, 'Joseph Vistal', 'Joseph', 'Vistal', NULL, NULL, 'joseph.vistal', NULL, '$2y$12$bt.rlnr.LjFMivR6BW7GAuo.aZvmEkov1gsAd.fHe9HjWXCGvCcvu', 'teacher', NULL, NULL, '2025-03-26 22:26:38', '2025-03-26 22:26:38'),
(15, 'Junell Bojocan', 'Junell', 'Bojocan', NULL, NULL, 'junell.bojocan', NULL, '$2y$12$mc4FFq1N.FAcoi68QEkQT.gyKDeqiSs3WsmyUUbUzl0JsEywdOq.i', 'teacher', NULL, NULL, '2025-03-26 22:26:39', '2025-04-09 22:58:07'),
(16, 'Donald Jasper Madrona', 'Donald Jasper', 'Madrona', NULL, NULL, 'donaldjasper.madrona', NULL, '$2y$12$GF7GgmO8zXGA3gviOqL.7.pzga2H.I4Yh8e0umaSunSYaoO9GJMKG', 'teacher', NULL, NULL, '2025-03-26 22:26:39', '2025-04-09 22:57:42'),
(17, 'Nova Estenzo', 'Nova', 'Estenzo', NULL, NULL, 'nova.estenzo', NULL, '$2y$12$QMfNWBoae0czbemlVHd3YOsKJyNIdXUxL5hg8yY6WdsDE8aIP4oG6', 'teacher', NULL, NULL, '2025-03-26 22:26:40', '2025-04-11 20:03:54'),
(18, 'Leonardo John Carrillo', 'Leonardo John', 'Carrillo', NULL, NULL, 'leonardojohn.carrillo', NULL, '$2y$12$XjU9Kq.POFGZJ/27Rk5/1uTV0R1zEa7YsRvUoRjWECTcqerTf1Wey', 'teacher', NULL, NULL, '2025-03-26 22:26:40', '2025-04-11 20:05:54'),
(19, 'Rolando Agujar', 'Rolando', 'Agujar', NULL, NULL, 'rolando.agujar', NULL, '$2y$12$M2.M6FMVg5v1SjkfTn8nc.LpFsaOBGVL63gNy5gNT2RNYOBKIASAa', 'teacher', NULL, NULL, '2025-03-26 22:26:40', '2025-04-09 23:00:37'),
(42, 'Precious Hearth', 'Precious', 'Hearth', NULL, NULL, 'precious.hearth', NULL, '$2y$12$NRSCWaf8GD6NnK1B7i9Ca.4lWFUM0ModbJGb8jd.EfrP/R5mdzenu', 'teacher', NULL, NULL, '2025-04-11 17:45:43', '2025-04-11 17:45:43'),
(43, 'Marie Mare', 'Marie', 'Mare', NULL, NULL, 'marie.mare', NULL, '$2y$12$ezN2mgMq3iDjBP0mJ/yfAejeKhcxD0NAJzTVArJABhOwiwGreCT4C', 'teacher', NULL, NULL, '2025-04-11 19:18:37', '2025-04-15 15:22:44'),
(44, 'ambot ambot', 'ambot', 'ambot', NULL, NULL, 'ambot.ambot', NULL, '$2y$12$NftPIkLxRqccBp3OXxISG.PKPprLRLHmSMtDWM/DpgWcSW0U6z.hW', 'teacher', NULL, NULL, '2025-04-11 19:32:33', '2025-04-11 19:32:33'),
(48, 'Arvin Arvin', 'Arvin', 'Arvin', NULL, NULL, 'arvin.arvin', NULL, '$2y$12$xGqKMQcCJdPX0X8zvMctCe2u5LsyuSccoG3lkxBROnHOOCfgL8hUS', 'teacher', NULL, NULL, '2025-04-14 08:44:18', '2025-04-14 08:44:18'),
(49, 'Angela Ambionnn', 'Angela', 'Ambionnn', NULL, NULL, 'angela.ambionnn', NULL, '$2y$12$x2pe8i8xj3FOVAklbBCo5upcLrtATgytenT2vWlEtEqexDP.YvWPS', 'teacher', NULL, NULL, '2025-04-14 09:03:09', '2025-04-14 09:03:09'),
(50, 'Mayyyy Amads', 'Mayyyy', 'Amads', NULL, NULL, 'mayyyy.amads', NULL, '$2y$12$D32M6cGxtdVPaJC7Bf6rSOqql3mzVIe5vpaUhT4OGnSlKuaUsinNu', 'teacher', NULL, NULL, '2025-04-14 09:03:09', '2025-04-14 09:03:09'),
(51, 'Jenc Arenza', 'Jenc', 'Arenza', NULL, NULL, 'jenc.arenza', NULL, '$2y$12$mahLbQEcGqbmEwNDVaTQHeMYZ7dWe5SRiPmJDzsJCgnL0QQcVBcm6', 'teacher', NULL, NULL, '2025-04-14 09:03:10', '2025-04-14 09:03:10'),
(52, 'Marrr Ambionnn', 'Marrr', 'Ambionnn', NULL, NULL, 'marrr.ambionnn', NULL, '$2y$12$6mchWlwIMDKh/LPSv4/KAOX152aw6ymoSBGckw7afzK32gLt/lz8q', 'teacher', NULL, NULL, '2025-04-14 09:21:04', '2025-04-14 09:21:04'),
(53, 'hello hello', 'hello', 'hello', NULL, NULL, 'hello.hello', NULL, '$2y$12$HAVfl/cCwH05JEgwkynjfeCcoppI3X9qCiSNOXPjsqVLA12H6Hzly', 'teacher', NULL, NULL, '2025-04-14 18:59:54', '2025-04-14 18:59:54'),
(60, 'Ethan Sullivan', 'Ethan', 'Sullivan', NULL, NULL, 'ethan.sullivan', NULL, '$2y$12$9HlDi0XDkBsLpSnwChC7iO8Cot5JO2cEBwGYhvKKFGG55GDstj5Si', 'teacher', NULL, NULL, '2025-04-16 06:17:25', '2025-04-16 06:17:25'),
(61, 'Mia Rodriguez', 'Mia', 'Rodriguez', NULL, NULL, 'mia.rodriguez', NULL, '$2y$12$ERkQv9oWjCNlvdbZOIWt.eQ9jVoxhC6FWWBXGnsV2L.zVe/rYTury', 'teacher', NULL, NULL, '2025-04-16 06:17:25', '2025-04-16 06:17:25'),
(62, 'Liam Bennett', 'Liam', 'Bennett', NULL, NULL, 'liam.bennett', NULL, '$2y$12$FR5S7srkOzmpEZJvE/StpuYAXLkxlefRg.qWsNiUKjkNraaDMQirC', 'teacher', NULL, NULL, '2025-04-16 06:17:26', '2025-04-16 06:17:26'),
(63, 'Ava Castillo', 'Ava', 'Castillo', NULL, NULL, 'ava.castillo', NULL, '$2y$12$MVliM4hUX0BxNYB7PE34/Opkk2Q3cVmXkGHzEl82oiYJlpygOD9kq', 'teacher', NULL, NULL, '2025-04-16 06:17:26', '2025-04-16 06:17:26'),
(64, 'Noah Reynolds', 'Noah', 'Reynolds', NULL, NULL, 'noah.reynolds', NULL, '$2y$12$KJzfVDjNGa5WiAvn1/xziebYSowflJTkHBwnTr5ek3USLEiOyEgGu', 'teacher', NULL, NULL, '2025-04-16 06:17:27', '2025-04-16 06:17:27'),
(65, 'Chloe Parker', 'Chloe', 'Parker', NULL, NULL, 'chloe.parker', NULL, '$2y$12$d5iv7B3X9dz6OtVKt0/Wfui4gnfhmRon7B9q3tI1/s8eL9d.GgSty', 'teacher', NULL, NULL, '2025-04-16 06:17:27', '2025-04-16 06:17:27'),
(66, 'Lucas Torres', 'Lucas', 'Torres', NULL, NULL, 'lucas.torres', NULL, '$2y$12$zgYoHtdom90mnXSeyGMiW./CWrHBlXfEUWDdaHnI0eGhsXx2I8pU2', 'teacher', NULL, NULL, '2025-04-16 06:17:27', '2025-04-16 06:17:27'),
(67, 'Sophia Murphy', 'Sophia', 'Murphy', NULL, NULL, 'sophia.murphy', NULL, '$2y$12$ENgAuFZuyDHswdy8AhQQBuJZitWDm.G9IVl9zMmEanzORVY2tm2Fm', 'teacher', NULL, NULL, '2025-04-16 06:17:27', '2025-04-16 06:17:27'),
(68, 'Elijah Patterson', 'Elijah', 'Patterson', NULL, NULL, 'elijah.patterson', NULL, '$2y$12$jQu9Q6uWVR5/xNfoZz9v3uAVafthxNSFT2R2.AREGo2VnNE.gcC5O', 'teacher', NULL, NULL, '2025-04-16 06:17:28', '2025-04-16 06:17:28'),
(69, 'Isabella Coleman', 'Isabella', 'Coleman', NULL, NULL, 'isabella.coleman', NULL, '$2y$12$tJCchmT6/GCiS3CgyHwuKusgQ8EcLOOi4dZ/aqEL5wM4Cz5EZGqa2', 'teacher', NULL, NULL, '2025-04-16 06:17:28', '2025-04-16 06:18:41'),
(70, 'Sophia Martinez', 'Sophia', 'Martinez', '583920174652', '2001-01-01', '583920174652', NULL, '$2y$12$z8bV.RKtD.DoQr8PSZYxb.4ztCImoBNAd0rBMnztYw/eJNCztBj6q', 'student', NULL, NULL, '2025-04-16 16:45:48', '2025-04-16 22:29:41'),
(71, 'Liam Johnson', 'Liam', 'Johnson', '274619583209', '2003-07-29', '274619583209', NULL, '$2y$12$Ce4MkVxlukK3vHWLituRCu7l8QRxAsqu6yDyqbw0y7bdYPca0r9gi', 'student', NULL, NULL, '2025-04-16 16:45:48', '2025-04-16 22:29:42'),
(72, 'Isabella Cruz', 'Isabella', 'Cruz', '194837265011', '2002-01-02', '194837265011', NULL, '$2y$12$zBPh2uxxhwmfyxRuMmDJ/uclR.sxleyikCw/bILFFiXec4NdmFIny', 'student', NULL, NULL, '2025-04-16 16:45:49', '2025-04-16 22:29:42'),
(73, 'Angela Angela', 'Angela', 'Angela', '872635482651', '2003-03-07', '872635482651', NULL, '$2y$12$2xKgITjOEo5IHg60Hafs9uK.fnb2iKHvADaSJzY3xD981BZ8Dj0Xy', 'student', NULL, NULL, '2025-04-16 16:52:51', '2025-04-16 16:52:51');

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
-- Indexes for table `class_subjects`
--
ALTER TABLE `class_subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_subjects_class_id_foreign` (`class_id`),
  ADD KEY `class_subjects_teacher_id_foreign` (`teacher_id`);

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
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sections_strand_id_foreign` (`strand_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `strands`
--
ALTER TABLE `strands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `strands_name_unique` (`name`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `class_students`
--
ALTER TABLE `class_students`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `class_subjects`
--
ALTER TABLE `class_subjects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `grades`
--
ALTER TABLE `grades`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=225;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `strands`
--
ALTER TABLE `strands`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

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
-- Constraints for table `class_subjects`
--
ALTER TABLE `class_subjects`
  ADD CONSTRAINT `class_subjects_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `class_subjects_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `grades`
--
ALTER TABLE `grades`
  ADD CONSTRAINT `grades_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `grades_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sections`
--
ALTER TABLE `sections`
  ADD CONSTRAINT `sections_strand_id_foreign` FOREIGN KEY (`strand_id`) REFERENCES `strands` (`id`) ON DELETE SET NULL;

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
