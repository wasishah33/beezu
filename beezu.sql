-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 15, 2025 at 05:09 PM
-- Server version: 5.7.33
-- PHP Version: 8.2.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `beezu`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text,
  `color` varchar(7) DEFAULT '#007bff',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int(11) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `color`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Technology', 'technology', 'Latest technology news and updates', '#007bff', 1, 0, '2025-09-07 20:15:57', '2025-09-07 20:15:57'),
(2, 'Web Development', 'web-development', 'Web development tutorials and tips', '#28a745', 1, 0, '2025-09-07 20:15:57', '2025-09-07 20:15:57'),
(3, 'PHP', 'php', 'PHP programming and frameworks', '#6f42c1', 1, 0, '2025-09-07 20:15:57', '2025-09-07 20:15:57'),
(4, 'JavaScript', 'javascript', 'JavaScript and frontend development', '#fd7e14', 1, 0, '2025-09-07 20:15:57', '2025-09-07 20:15:57'),
(6, 'John Realtorg', 'john-realtorg', 'sdafad dfasd fsadf sadfsadf', '#007bff', 1, 0, '2025-09-07 20:50:55', '2025-09-07 20:51:13'),
(7, 'Karina Maynard', 'Dolore sunt quas asp', 'Quis iste obcaecati', '#b65cde', 1, 29, '2025-09-08 04:58:34', '2025-09-08 04:58:34');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `excerpt` text,
  `template` varchar(100) DEFAULT 'default',
  `status` enum('draft','published','archived') NOT NULL DEFAULT 'draft',
  `is_homepage` tinyint(1) NOT NULL DEFAULT '0',
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text,
  `author_id` int(10) UNSIGNED NOT NULL,
  `published_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `title`, `slug`, `content`, `excerpt`, `template`, `status`, `is_homepage`, `meta_title`, `meta_description`, `author_id`, `published_at`, `created_at`, `updated_at`) VALUES
(1, 'Ut in eum id ad veli', 'Quis non in ut irure', 'Aut vero minim persp', 'Eveniet aspernatur', 'landing', 'published', 0, 'Illo incidunt sed u', 'Eos mollitia suscipi', 1, '2025-09-08 04:58:46', '2025-09-08 04:58:46', '2025-09-08 04:58:46'),
(2, 'Deserunt delectus i', 'Nihil quis quia exer', 'Quae reprehenderit ', 'Reiciendis ipsum aut', 'landing', 'published', 0, 'Blanditiis aut aute', 'Modi libero vel in s', 1, '2025-09-08 05:14:05', '2025-09-08 04:58:50', '2025-09-08 05:14:05'),
(3, 'Et reiciendis nisi s', 'Dignissimos in fugia', 'Repellendus Tenetur', 'Non ullamco vitae qu', 'sidebar', 'draft', 0, 'Eaque in doloremque', 'Nisi officia cupidat', 1, NULL, '2025-09-08 05:13:58', '2025-09-08 05:14:11'),
(4, 'Voluptatem veniam q', 'Irure qui mollitia m', 'Illum quis maxime q', 'Illum aut sit reru', 'landing', 'published', 0, 'Reprehenderit et nat', 'Dicta ut officia aut', 1, '2025-09-13 14:34:12', '2025-09-13 14:34:12', '2025-09-13 14:34:12');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` text,
  `content` longtext NOT NULL,
  `featured_image` varchar(500) DEFAULT NULL,
  `category_id` int(10) UNSIGNED DEFAULT NULL,
  `author_id` int(10) UNSIGNED NOT NULL,
  `status` enum('draft','published','archived') NOT NULL DEFAULT 'draft',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text,
  `published_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `slug`, `excerpt`, `content`, `featured_image`, `category_id`, `author_id`, `status`, `is_featured`, `meta_title`, `meta_description`, `published_at`, `created_at`, `updated_at`) VALUES
(1, 'Non sint porro nobis', 'Est dignissimos elig', 'A aliquip eligendi d', 'Nulla amet voluptat', 'https://www.wytonyqikir.net', 2, 1, 'archived', 1, 'Ipsam consequatur A', 'Ullam vel deserunt q', NULL, '2025-09-08 04:45:44', '2025-09-08 04:45:44'),
(2, 'Mollit et labore lab', 'Nihil voluptates vol', 'Dolor dolorum incidi', 'Autem in quia deleni', 'https://www.tokydumono.com', 6, 1, 'published', 0, 'Ex placeat ut et vo', 'Minim error eum ad e', '2025-09-08 04:58:19', '2025-09-08 04:58:19', '2025-09-08 04:58:19'),
(3, 'Aut vitae dolor mole', 'Voluptatem minus ita', 'Pariatur Dolores ut', 'Minus assumenda impe', 'https://www.porul.com.au', 7, 1, 'archived', 1, 'Alias doloremque sed', 'Vitae perferendis el', NULL, '2025-09-13 14:33:53', '2025-09-13 14:33:53'),
(4, 'Quis est aperiam te', 'Dolor quaerat porro', 'Qui voluptate et et', 'Ratione porro eaque ', 'https://www.wisomij.cc', 6, 1, 'published', 0, 'Non officiis volupta', 'Error in quis neque', '2025-09-13 14:33:58', '2025-09-13 14:33:58', '2025-09-13 14:33:58');

-- --------------------------------------------------------

--
-- Table structure for table `post_tags`
--

CREATE TABLE `post_tags` (
  `post_id` int(10) UNSIGNED NOT NULL,
  `tag_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `color` varchar(7) DEFAULT '#6c757d',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `name`, `slug`, `color`, `created_at`, `updated_at`) VALUES
(1, 'Tutorial', 'tutorial', '#17a2b8', '2025-09-07 20:15:57', '2025-09-07 20:15:57'),
(2, 'News', 'news', '#dc3545', '2025-09-07 20:15:57', '2025-09-07 20:15:57'),
(3, 'Tips', 'tips', '#ffc107', '2025-09-07 20:15:57', '2025-09-07 20:15:57'),
(4, 'Framework', 'framework', '#6f42c1', '2025-09-07 20:15:57', '2025-09-07 20:15:57'),
(5, 'Beginner', 'beginner', '#28a745', '2025-09-07 20:15:57', '2025-09-07 20:15:57');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(190) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'admin',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `last_login_at` datetime DEFAULT NULL,
  `password_updated_at` datetime DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `role`, `is_active`, `last_login_at`, `password_updated_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@example.com', '$2y$12$nIMzuqtMVxUFebhPIyhJCexfPduzbfigWRn/87ShnX9uDk/4IOXpK', 'admin', 1, '2025-09-13 14:33:36', '2025-09-07 19:40:33', NULL, '2025-09-07 13:23:20', '2025-09-13 14:33:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `author_id` (`author_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_is_homepage` (`is_homepage`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_published_at` (`published_at`),
  ADD KEY `idx_category_id` (`category_id`),
  ADD KEY `idx_author_id` (`author_id`);

--
-- Indexes for table `post_tags`
--
ALTER TABLE `post_tags`
  ADD PRIMARY KEY (`post_id`,`tag_id`),
  ADD KEY `tag_id` (`tag_id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pages`
--
ALTER TABLE `pages`
  ADD CONSTRAINT `pages_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `post_tags`
--
ALTER TABLE `post_tags`
  ADD CONSTRAINT `post_tags_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `post_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
