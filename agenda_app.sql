-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 08, 2024 at 05:30 AM
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
-- Database: `agenda_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `thread_id` int(11) DEFAULT NULL,
  `content` text NOT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `thread_id`, `content`, `user_id`) VALUES
(1, 1, 'comment 1', 4),
(2, 7, 'Admin checking the coment', 4),
(3, 7, 'User view and reply to threads', 3),
(4, 1, 'user 3 commenting', 3),
(5, 7, 'user3 comment again', 3),
(6, 3, 'just check in', 3),
(7, 7, 'check in', 5),
(8, 11, 'admin has commented', 4),
(9, 11, 'user7 has commented', 7),
(13, 11, 'user9 comment, please review by admin', 9),
(14, 11, 'admin2 already reviewed the comment', 4);

-- --------------------------------------------------------

--
-- Table structure for table `threads`
--

CREATE TABLE `threads` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `threads`
--

INSERT INTO `threads` (`id`, `title`, `content`, `user_id`, `date`) VALUES
(1, 'Kelas pertama', 'Bahas FE BE', NULL, '2024-05-08 05:25:19'),
(2, 'Kelas Server and Network Administration', 'Testing Hosting Server', NULL, '2024-05-08 05:25:19'),
(3, 'Kelas SecProg 8 May 2024', 'Session setelah UTS mantap[edit test]', 4, '2024-05-08 05:25:19'),
(7, 'Session 10 - 22 May 2024', 'Finishing function and security', 4, '2024-05-22 05:40:27'),
(11, 'Checking function', 'use this threads for checking #this has been edited by admin2', 4, '2024-05-22 15:31:21');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin@mail.com', '$2y$10$iX7L6yK9UZ9cHnBNijab5uHs3q5qCMF50qZ4kQzjqJQY1Jtp0KBGu', 'admin'),
(2, 'user1@mail.com', '$2y$10$napGojMopkWVBfmKdVn.M.y2PyR8HmWxx11D1lf7fBAn1XlLoF1fu', 'user'),
(3, 'user3@mail.com', '$2y$10$HlYXBXO.Gb3Ap3EpW4L/XeZQyAIKRbZDTlUYFNWCKfqVqsvLmmENO', 'user'),
(4, 'admin2@mail.com', '$2y$10$QNkVvXPvBVl.7uB/Qm3NRuyCY.N0EQS1K72Hq1qzZPRKY.YhjwH/K', 'admin'),
(5, 'user5@mail.com', '$2y$10$s3/uXF6VmIssSgAZbdxiHesPS5hp60wnNiGEnNb9/6BTydgDdAX8u', 'user'),
(6, 'user6@mail.com', '$2y$10$tJzRgCa8oCieDXAZ.qCJ9.NOaowiQH1t5JXQCj5dwMngKf0N8O4c2', 'user'),
(7, 'user7mail.com', '$2y$10$eG7ZVMEsmpwtzGSbBwRUYOzj2lahrqGliMXz1h10LxAclNm5eN8jS', 'user'),
(8, 'user8@mail.com', '$2y$10$4efWEawikMGYgtzlH4nZcuCWPQSLS2IRL5GqBjp2abdGi9iEXs/vm', 'user'),
(9, 'user9@mail.com', '$2y$10$3XbW9iQGA5.uqLyICRz/i.muB46qck0vgPcx7dLZVLNq5H4UY9YI2', 'user'),
(10, 'user10@mail.com', '$2y$10$QDs70XXCfF9M1t5fHbzHJOnIR7m/pi4Z8kkFBUr9qAvSDsekHhW0a', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `thread_id` (`thread_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `threads`
--
ALTER TABLE `threads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `threads`
--
ALTER TABLE `threads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`thread_id`) REFERENCES `threads` (`id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `threads`
--
ALTER TABLE `threads`
  ADD CONSTRAINT `threads_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
