-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 21, 2026 at 11:48 PM
-- Server version: 12.0.2-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `username` varchar(100) NOT NULL,
  `user_role` enum('admin','proponent','user','superadmin') NOT NULL,
  `action` enum('ADD','EDIT','DELETE','VIEW','ENROLL','COMPLETE','LOGIN','LOGOUT') NOT NULL,
  `table_name` varchar(50) NOT NULL,
  `record_id` int(11) DEFAULT NULL,
  `old_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_data`)),
  `new_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_data`)),
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `course_id`, `username`, `user_role`, `action`, `table_name`, `record_id`, `old_data`, `new_data`, `description`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 5, NULL, 'alvin', 'admin', 'ADD', 'courses', 20, NULL, '{\"id\":20,\"title\":\"Course\"}', 'Added course: Course', NULL, NULL, '2026-02-13 05:25:40'),
(2, 5, NULL, 'alvin', 'admin', 'ADD', 'courses', 21, NULL, '{\"id\":21,\"title\":\"Lorem Ipsum\"}', 'Added course: Lorem Ipsum', NULL, NULL, '2026-02-13 05:26:34'),
(3, 8, NULL, 'proponent', 'proponent', 'ADD', 'courses', 22, NULL, '{\"id\":22,\"title\":\"proponent\"}', 'Added course: proponent', NULL, NULL, '2026-02-13 05:27:15'),
(4, 8, NULL, 'proponent', 'proponent', 'ADD', 'courses', 23, NULL, '{\"id\":23,\"title\":\"Lorem ipsum\"}', 'Added course: Lorem ipsum', NULL, NULL, '2026-02-13 05:28:11'),
(5, 8, NULL, 'proponent', 'proponent', 'ADD', 'courses', 24, NULL, '{\"id\":24,\"title\":\"Lorem Ipsum pro max\"}', 'Added course: Lorem Ipsum pro max', NULL, NULL, '2026-02-13 05:28:49'),
(6, 22, NULL, 'pro', 'proponent', 'ADD', 'courses', 25, NULL, '{\"id\":25,\"title\":\"module one\"}', 'Added course: module one', NULL, NULL, '2026-02-18 07:46:38'),
(7, 24, NULL, 'admin', 'admin', 'ADD', 'courses', 26, NULL, '{\"id\":26,\"title\":\"dataset\"}', 'Added course: dataset', NULL, NULL, '2026-02-20 03:28:06'),
(8, 33, NULL, 'superadmin', 'superadmin', 'ADD', 'courses', 27, NULL, '{\"id\":27,\"title\":\"sdadadadasdsadsassssssssssssssssssssssssssssssssssssssssssssss\"}', 'Added course: sdadadadasdsadsassssssssssssssssssssssssssssssssssssssssssssss', NULL, NULL, '2026-02-21 06:32:22');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `is_replied` tinyint(1) DEFAULT 0,
  `admin_notes` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `subject`, `message`, `is_read`, `is_replied`, `admin_notes`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 'jaks', 'master@gmail.com', 'sadasdasd', 'asdasdasdsad', 1, 0, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', '2026-02-21 22:46:44');

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `id` int(11) NOT NULL,
  `user1_id` int(11) NOT NULL,
  `user2_id` int(11) NOT NULL,
  `last_message_id` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `proponent_id` int(11) NOT NULL,
  `file_pdf` varchar(255) DEFAULT NULL,
  `file_video` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` date DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `summary` varchar(2500) DEFAULT NULL,
  `edited_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `title`, `description`, `thumbnail`, `proponent_id`, `file_pdf`, `file_video`, `created_at`, `updated_at`, `expires_at`, `is_active`, `summary`, `edited_at`) VALUES
(20, 'Course', 'Lorem Ipsum', NULL, 5, '073dab716b0f7dd3.pdf', NULL, '2026-02-13 05:25:40', NULL, '2026-02-13', 1, 'ggggggggggggggggggggg', NULL),
(21, 'Lorem Ipsum', 'Lorem Ipsum', NULL, 5, '953ca1ae64427da4.pdf', '31ad844a6a1d0ccc.mp4', '2026-02-13 05:26:34', NULL, '2026-02-28', 1, '\r\nfreestar\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris tortor mauris, suscipit id dictum eget, tristique vel purus. Nulla et tortor eleifend, condimentum nulla sit amet, convallis justo. Vivamus lacinia semper nisl, id tincidunt enim faucibus non. Sed diam arcu, lobortis vel rutrum non, finibus fringilla neque. In vulputate mauris nec sapien egestas, ut ullamcorper neque porttitor. Vestibulum rutrum lorem sit amet metus luctus, nec malesuada arcu lacinia. Maecenas at est vitae ante interdum ornare in quis ex. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Morbi tristique pulvinar massa, in iaculis mauris cursus sed. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Nulla vestibulum maximus lacinia.\r\n\r\nEtiam quis pretium est. Mauris eget augue congue, volutpat dui eget, bibendum leo. Vestibulum non mattis enim, non scelerisque ex. In ultrices, urna et vestibulum luctus, magna lorem finibus nunc, non accumsan dui purus ut quam. Fusce vitae molestie tellus, ut varius quam. Nam dignissim elementum tristique. Proin sed est ut risus vehicula dictum a sed enim. Integer tempus dui quis interdum varius.', NULL),
(22, 'proponent', 'proponent', NULL, 8, NULL, 'd5c2cb58d9546ec2.mp4', '2026-02-13 05:27:15', NULL, '2026-02-28', 1, '\r\nfreestar\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris tortor mauris, suscipit id dictum eget, tristique vel purus. Nulla et tortor eleifend, condimentum nulla sit amet, convallis justo. Vivamus lacinia semper nisl, id tincidunt enim faucibus non. Sed diam arcu, lobortis vel rutrum non, finibus fringilla neque. In vulputate mauris nec sapien egestas, ut ullamcorper neque porttitor. Vestibulum rutrum lorem sit amet metus luctus, nec malesuada arcu lacinia. Maecenas at est vitae ante interdum ornare in quis ex. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Morbi tristique pulvinar massa, in iaculis mauris cursus sed. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Nulla vestibulum maximus lacinia.\r\n\r\nEtiam quis pretium est. Mauris eget augue congue, volutpat dui eget, bibendum leo. Vestibulum non mattis enim, non scelerisque ex. In ultrices, urna et vestibulum luctus, magna lorem finibus nunc, non accumsan dui purus ut quam. Fusce vitae molestie tellus, ut varius quam. Nam dignissim elementum tristique. Proin sed est ut risus vehicula dictum a sed enim. Integer tempus dui quis interdum varius.', NULL),
(23, 'Lorem ipsum', 'Lorem Ipsum', NULL, 8, NULL, '600b74d379fbb888.mp4', '2026-02-13 05:28:11', NULL, '2026-02-13', 1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris tortor mauris, suscipit id dictum eget, tristique vel purus. Nulla et tortor eleifend, condimentum nulla sit amet, convallis justo. Vivamus lacinia semper nisl, id tincidunt enim faucibus non. Sed diam arcu, lobortis vel rutrum non, finibus fringilla neque. In vulputate mauris nec sapien egestas, ut ullamcorper neque porttitor. Vestibulum rutrum lorem sit amet metus luctus, nec malesuada arcu lacinia. Maecenas at est vitae ante interdum ornare in quis ex. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Morbi tristique pulvinar massa, in iaculis mauris cursus sed. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Nulla vestibulum maximus lacinia.\r\n\r\nEtiam quis pretium est. Mauris eget augue congue, volutpat dui eget, bibendum leo. Vestibulum non mattis enim, non scelerisque ex. In ultrices, urna et vestibulum luctus, magna lorem finibus nunc, non accumsan dui purus ut quam. Fusce vitae molestie tellus, ut varius quam. Nam dignissim elementum tristique. Proin sed est ut risus vehicula dictum a sed enim. Integer tempus dui quis interdum varius.', NULL),
(24, 'Lorem Ipsum pro max', 'Lorem Ipsum', NULL, 8, 'c08d5db5752bd128.pdf', NULL, '2026-02-13 05:28:49', NULL, '2026-02-28', 1, '\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris tortor mauris, suscipit id dictum eget, tristique vel purus. Nulla et tortor eleifend, condimentum nulla sit amet, convallis justo. Vivamus lacinia semper nisl, id tincidunt enim faucibus non. Sed diam arcu, lobortis vel rutrum non, finibus fringilla neque. In vulputate mauris nec sapien egestas, ut ullamcorper neque porttitor. Vestibulum rutrum lorem sit amet metus luctus, nec malesuada arcu lacinia. Maecenas at est vitae ante interdum ornare in quis ex. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Morbi tristique pulvinar massa, in iaculis mauris cursus sed. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Nulla vestibulum maximus lacinia.\r\n\r\nEtiam quis pretium est. Mauris eget augue congue, volutpat dui eget, bibendum leo. Vestibulum non mattis enim, non scelerisque ex. In ultrices, urna et vestibulum luctus, magna lorem finibus nunc, non accumsan dui purus ut quam. Fusce vitae molestie tellus, ut varius quam. Nam dignissim elementum tristique. Proin sed est ut risus vehicula dictum a sed enim. Integer tempus dui quis interdum varius.', NULL),
(25, 'module one', 'uno', NULL, 22, NULL, NULL, '2026-02-18 07:46:38', NULL, NULL, 1, 'sinauna', NULL),
(26, 'dataset', 'dataset', NULL, 24, NULL, NULL, '2026-02-20 03:28:06', NULL, NULL, 1, 'dataset', NULL),
(27, 'sdadadadasdsadsassssssssssssssssssssssssssssssssssssssssssssss', 'sdadadadasdsadsassssssssssssssssssssssssssssssssssssssssssssss', NULL, 33, NULL, NULL, '2026-02-21 06:32:22', '2026-02-21 10:47:13', NULL, 1, 'nakopo', NULL),
(28, 'trailtrial', 'trailtrial', NULL, 33, NULL, NULL, '2026-02-21 06:37:51', NULL, NULL, 1, 'trailtrialtrailtrialtrailtrialtrailtrialtrailtrialtrailtrialtrailtrial', NULL),
(29, 'ccccccccccccccccccc', 'cccccccccccccccccccccc', NULL, 33, NULL, NULL, '2026-02-21 07:03:52', NULL, NULL, 1, 'ssssssssssssssssssssssss', NULL),
(30, 'supersupersuper', 'supersupersupersuper', NULL, 33, NULL, NULL, '2026-02-21 10:44:36', '2026-02-21 22:17:54', NULL, 1, 'supersupersupersupersupersupersupersupersuper', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `created_at`) VALUES
(1, 'Anesthetics', '2026-02-19 03:06:57'),
(2, 'Breast Screening', '2026-02-19 03:06:57'),
(3, 'Cardiology', '2026-02-19 03:06:57'),
(4, 'Ear, Nose and Throat (ENT)', '2026-02-19 03:06:57'),
(5, 'Elderly Services Department', '2026-02-19 03:06:57'),
(6, 'Gastroenterology', '2026-02-19 03:06:57'),
(7, 'General Surgery', '2026-02-19 03:06:57'),
(8, 'Gynecology', '2026-02-19 03:06:57');

-- --------------------------------------------------------

--
-- Table structure for table `edit`
--

CREATE TABLE `edit` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `proponent_id` int(11) NOT NULL,
  `file_pdf` varchar(255) DEFAULT NULL,
  `file_video` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` date DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `thumbnail` varchar(255) DEFAULT NULL,
  `summary` mediumtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `enrolled_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `completed_at` timestamp NULL DEFAULT NULL,
  `expired_at` date DEFAULT NULL,
  `progress` decimal(5,2) DEFAULT 0.00,
  `status` enum('ongoing','completed','expired') DEFAULT 'ongoing',
  `total_time_seconds` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollments`
--

INSERT INTO `enrollments` (`id`, `user_id`, `course_id`, `enrolled_at`, `completed_at`, `expired_at`, `progress`, `status`, `total_time_seconds`) VALUES
(18, 14, 24, '2026-02-13 05:45:54', NULL, NULL, 294.00, 'ongoing', 294),
(19, 15, 24, '2026-02-13 05:54:18', NULL, NULL, 1.00, 'ongoing', 1),
(20, 16, 22, '2026-02-13 05:54:38', NULL, NULL, 132.00, 'ongoing', 132),
(21, 14, 23, '2026-02-13 06:30:20', NULL, NULL, 5.00, 'ongoing', 5),
(22, 19, 23, '2026-02-13 07:50:55', NULL, NULL, 2.00, 'ongoing', 2),
(23, 17, 22, '2026-02-13 07:55:38', NULL, NULL, 2.00, 'ongoing', 2);

-- --------------------------------------------------------

--
-- Table structure for table `lessons`
--

CREATE TABLE `lessons` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `file_pdf` varchar(255) DEFAULT NULL,
  `file_video` varchar(255) DEFAULT NULL,
  `ord` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lesson_progress`
--

CREATE TABLE `lesson_progress` (
  `id` int(11) NOT NULL,
  `enrollment_id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `completed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `is_deleted_sender` tinyint(1) DEFAULT 0,
  `is_deleted_receiver` tinyint(1) DEFAULT 0,
  `parent_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `message_attachments`
--

CREATE TABLE `message_attachments` (
  `id` int(11) NOT NULL,
  `message_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `original_filename` varchar(255) NOT NULL,
  `filepath` varchar(500) NOT NULL,
  `filesize` int(11) NOT NULL,
  `filetype` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `body` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_published` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `body`, `created_by`, `created_at`, `is_published`) VALUES
(1, 'This is News', 'is this new?', 1, '2026-02-03 23:41:35', 1),
(3, 'asdasdad', 'asdasda', 5, '2026-02-12 19:50:05', 1),
(4, 'jakshdkjashfkhakshfkjaksdhjasdhkasdha', 'jdskalhdkhaskhd', 5, '2026-02-12 23:23:28', 1);

-- --------------------------------------------------------

--
-- Table structure for table `otp_verifications`
--

CREATE TABLE `otp_verifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `otp` varchar(10) DEFAULT NULL,
  `verified` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` timestamp NOT NULL DEFAULT (current_timestamp() + interval 10 minute)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `time_logs`
--

CREATE TABLE `time_logs` (
  `id` int(11) NOT NULL,
  `enrollment_id` int(11) NOT NULL,
  `start_ts` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `end_ts` timestamp NOT NULL DEFAULT current_timestamp(),
  `seconds` int(11) NOT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fname` varchar(100) DEFAULT NULL,
  `lname` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `role` enum('admin','proponent','user','superadmin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `otp_code` varchar(6) DEFAULT NULL,
  `otp_expires_at` datetime DEFAULT NULL,
  `status` enum('pending','confirmed') DEFAULT 'confirmed',
  `message_notifications` tinyint(1) DEFAULT 1,
  `email_notifications` tinyint(1) DEFAULT 1,
  `departments` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `fname`, `lname`, `email`, `role`, `created_at`, `updated_at`, `is_verified`, `otp_code`, `otp_expires_at`, `status`, `message_notifications`, `email_notifications`, `departments`) VALUES
(5, 'alvin', '$2y$10$p.0kdoyIco1ye14NdYTqY.FgdL3UF7Vpd/fo3rcEQEQo/qEhTATlO', 'Alvin', 'Lopez', 'traxcie21@gmail.com', 'admin', '2026-02-04 06:32:23', NULL, 0, NULL, NULL, 'confirmed', 1, 1, NULL),
(14, 'user', '$2y$10$eFkQsneyDNipGemuZI9qpO4HhBC/.Y1XyOKDOSvUn0T6pURNbTize', 'user', 'user', 'user@gmail.com', 'user', '2026-02-13 05:30:06', NULL, 0, NULL, NULL, 'confirmed', 1, 1, NULL),
(22, 'pro', '$2y$10$gpII/P2uzcDch.35MinEve7EO4uQD05eaIkHpTshPmszmeMPArXaO', 'pro', 'pro', 'pro@gmail.com', 'proponent', '2026-02-16 05:15:51', NULL, 0, NULL, NULL, 'confirmed', 1, 1, NULL),
(24, 'admin', '$2y$10$hG0raIRisWillWRvFq4y9uRtS5yj5q0OF8iv1yVL1cO.TfZiWzGKK', 'admin', 'admin', 'admin@gmail.com', 'admin', '2026-02-16 05:16:40', NULL, 0, NULL, NULL, 'confirmed', 1, 1, NULL),
(25, 'superadmin', '$2y$10$9L1NSLJ8xbyIVLvRHRuv7e/jNYOQVvVowcDRH7q07EyYD1FPIgzuq', 'superadmin', 'superadmin', 'superadmin@gmail.com', 'superadmin', '2026-02-16 08:33:09', NULL, 0, NULL, NULL, 'confirmed', 1, 1, NULL),

-- --------------------------------------------------------

--
-- Table structure for table `user_departments`
--dump old user_departments from main db(install.sql)

CREATE TABLE `user_departments` (
  `user_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_departments`
--

INSERT INTO `user_departments` (`user_id`, `department_id`) VALUES
(61, 1),
(62, 1),
(61, 2),
(63, 2),
(61, 3),
(62, 3),
(61, 4),
(61, 5),
(62, 5),
(62, 6);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_course` (`course_id`),
  ADD KEY `idx_action` (`action`),
  ADD KEY `idx_table` (`table_name`),
  ADD KEY `idx_created` (`created_at`),
  ADD KEY `idx_record` (`record_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_read` (`is_read`),
  ADD KEY `idx_created` (`created_at`);

--
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_conversation` (`user1_id`,`user2_id`),
  ADD KEY `user2_id` (`user2_id`),
  ADD KEY `last_message_id` (`last_message_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proponent_id` (`proponent_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `edit`
--
ALTER TABLE `edit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proponent_id` (`proponent_id`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ux_user_course` (`user_id`,`course_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `lesson_progress`
--
ALTER TABLE `lesson_progress`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ux_enroll_lesson` (`enrollment_id`,`lesson_id`),
  ADD KEY `lesson_id` (`lesson_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `idx_sender` (`sender_id`),
  ADD KEY `idx_receiver` (`receiver_id`),
  ADD KEY `idx_created` (`created_at`);

--
-- Indexes for table `message_attachments`
--
ALTER TABLE `message_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `message_id` (`message_id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `otp_verifications`
--
ALTER TABLE `otp_verifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `time_logs`
--
ALTER TABLE `time_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `enrollment_id` (`enrollment_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_departments`
--
ALTER TABLE `user_departments`
  ADD PRIMARY KEY (`user_id`,`department_id`),
  ADD KEY `department_id` (`department_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `edit`
--
ALTER TABLE `edit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `lessons`
--
ALTER TABLE `lessons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lesson_progress`
--
ALTER TABLE `lesson_progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `message_attachments`
--
ALTER TABLE `message_attachments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `otp_verifications`
--
ALTER TABLE `otp_verifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `time_logs`
--
ALTER TABLE `time_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `fk_audit_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_audit_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `conversations`
--
ALTER TABLE `conversations`
  ADD CONSTRAINT `conversations_ibfk_1` FOREIGN KEY (`user1_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `conversations_ibfk_2` FOREIGN KEY (`user2_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `conversations_ibfk_3` FOREIGN KEY (`last_message_id`) REFERENCES `messages` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`proponent_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `edit`
--
ALTER TABLE `edit`
  ADD CONSTRAINT `edit_ibfk_1` FOREIGN KEY (`proponent_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `enrollments_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lessons`
--
ALTER TABLE `lessons`
  ADD CONSTRAINT `lessons_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lesson_progress`
--
ALTER TABLE `lesson_progress`
  ADD CONSTRAINT `lesson_progress_ibfk_1` FOREIGN KEY (`enrollment_id`) REFERENCES `enrollments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lesson_progress_ibfk_2` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_3` FOREIGN KEY (`parent_id`) REFERENCES `messages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `message_attachments`
--
ALTER TABLE `message_attachments`
  ADD CONSTRAINT `message_attachments_ibfk_1` FOREIGN KEY (`message_id`) REFERENCES `messages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `time_logs`
--
ALTER TABLE `time_logs`
  ADD CONSTRAINT `time_logs_ibfk_1` FOREIGN KEY (`enrollment_id`) REFERENCES `enrollments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_departments`
--
ALTER TABLE `user_departments`
  ADD CONSTRAINT `user_departments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_departments_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
