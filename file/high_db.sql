-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 21, 2026 at 09:24 PM
-- Server version: 11.8.5-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `high_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `owner` int(11) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `content`, `owner`, `status`, `created_at`) VALUES
(1, 'new', 'dayjhfuyg iugiug', 2, 'active', '2026-02-21 19:03:06'),
(2, 'new', 'dayjhfuyg iugiug', 2, 'active', '2026-02-21 19:03:14'),
(4, 'diwali', 'happy dewali', 2, 'active', '2026-02-21 19:08:57'),
(5, 'sadmin', 'ith njan ondakkitha', 1, 'active', '2026-02-21 19:30:18'),
(6, 'admin2 test post', 'first post confirm edit', 3, 'active', '2026-02-21 19:49:12');

-- --------------------------------------------------------

--
-- Table structure for table `post_assignments`
--

CREATE TABLE `post_assignments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `assigned_by` int(11) NOT NULL,
  `editor_id` int(11) NOT NULL,
  `assigned_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `post_assignments`
--

INSERT INTO `post_assignments` (`id`, `post_id`, `assigned_by`, `editor_id`, `assigned_at`) VALUES
(1, 5, 1, 4, '2026-02-21 19:38:34'),
(2, 6, 3, 4, '2026-02-21 19:55:59');

-- --------------------------------------------------------

--
-- Table structure for table `post_permissions`
--

CREATE TABLE `post_permissions` (
  `id` int(11) NOT NULL,
  `assign_id` int(11) NOT NULL,
  `can_edit` tinyint(1) DEFAULT 0,
  `can_delete` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `post_permissions`
--

INSERT INTO `post_permissions` (`id`, `assign_id`, `can_edit`, `can_delete`) VALUES
(1, 1, 1, 1),
(2, 2, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(2, 'admin'),
(3, 'editor'),
(1, 'super_admin'),
(4, 'viewer');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role_id`, `status`, `created_at`) VALUES
(1, 'super admin', 'superadmin@gmail.com', '1234', 1, 1, '2026-02-21 16:55:56'),
(2, 'admin1', 'admin1@gmail.com', '123', 2, 1, '2026-02-21 16:57:35'),
(3, 'admin2', 'admin2@gmail.com', '123', 2, 1, '2026-02-21 16:57:35'),
(4, 'editor1', 'ed1@gmail.com', 'e1', 3, 1, '2026-02-21 16:59:44'),
(5, 'editor2', 'ed2@gmail.com', 'e2', 3, 1, '2026-02-21 17:00:42'),
(6, 'v1', 'v1@gmail.com', 'v1', 4, 1, '2026-02-21 17:01:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_posts_owner` (`owner`);

--
-- Indexes for table `post_assignments`
--
ALTER TABLE `post_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_assign_post` (`post_id`),
  ADD KEY `fk_assign_by` (`assigned_by`),
  ADD KEY `fk_assign_editor` (`editor_id`);

--
-- Indexes for table `post_permissions`
--
ALTER TABLE `post_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_permission_assign` (`assign_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_users_role` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `post_assignments`
--
ALTER TABLE `post_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `post_permissions`
--
ALTER TABLE `post_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `fk_posts_owner` FOREIGN KEY (`owner`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `post_assignments`
--
ALTER TABLE `post_assignments`
  ADD CONSTRAINT `fk_assign_by` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_assign_editor` FOREIGN KEY (`editor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_assign_post` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `post_permissions`
--
ALTER TABLE `post_permissions`
  ADD CONSTRAINT `fk_permission_assign` FOREIGN KEY (`assign_id`) REFERENCES `post_assignments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
