-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 14, 2025 at 04:31 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lms_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(6) UNSIGNED NOT NULL,
  `title` varchar(100) NOT NULL,
  `author` varchar(50) NOT NULL,
  `genre_id` int(6) UNSIGNED NOT NULL,
  `publication_year` int(4) NOT NULL,
  `available_copies` int(4) NOT NULL,
  `total_copies` int(4) NOT NULL,
  `isbn` varchar(50) NOT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `genre_id`, `publication_year`, `available_copies`, `total_copies`, `isbn`, `cover_image`, `created_at`) VALUES
(2, 'To kill a Mockingbird', 'Harper Lee', 11, 1960, 27, 27, '9780061120084', '../backend/assets/images/to_kill_a_mockingbirdentertainment-2016-02-18-main.webp', '2025-04-13 19:51:21'),
(3, '1984', 'George Orwell', 11, 1949, 36, 36, '978-0451524935', '../backend/assets/images/1984il_1080xn.2575556850_55iv.avif', '2025-04-13 19:58:32'),
(4, 'The Great Gatsby', 'F. Scott Fitzgerald', 11, 1925, 12, 12, '978-0743273565', '../backend/assets/images/the_great_gatsbycontent.jpg', '2025-04-14 11:33:44'),
(5, 'Pride and Prejudice', 'Jane Austen', 9, 1813, 8, 8, '978-1503290563', '../backend/assets/images/pride_and_prejudicepride-and-prejudice-krepg5.jpg', '2025-04-14 11:36:36');

-- --------------------------------------------------------

--
-- Table structure for table `borrowed_books`
--

CREATE TABLE `borrowed_books` (
  `id` int(6) UNSIGNED NOT NULL,
  `book_id` int(6) UNSIGNED NOT NULL,
  `user_id` int(6) UNSIGNED NOT NULL,
  `borrow_date` date NOT NULL,
  `return_date` date NOT NULL,
  `status` enum('pending','returned','approved','declined') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `borrow_settings`
--

CREATE TABLE `borrow_settings` (
  `id` int(6) UNSIGNED NOT NULL,
  `borrow_duration` int(4) NOT NULL DEFAULT 7,
  `fine_per_day` int(4) NOT NULL DEFAULT 1,
  `max_borrow_limit` int(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrow_settings`
--

INSERT INTO `borrow_settings` (`id`, `borrow_duration`, `fine_per_day`, `max_borrow_limit`, `created_at`) VALUES
(1, 3, 10, 2, '2025-04-13 20:08:08');

-- --------------------------------------------------------

--
-- Table structure for table `genres`
--

CREATE TABLE `genres` (
  `id` int(6) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `genres`
--

INSERT INTO `genres` (`id`, `name`, `created_at`) VALUES
(1, 'Action', '2025-04-04 21:19:45'),
(2, 'Adventure', '2025-04-04 21:19:45'),
(3, 'Comedy', '2025-04-04 21:19:45'),
(4, 'Drama', '2025-04-04 21:19:45'),
(5, 'Fantasy', '2025-04-04 21:19:45'),
(6, 'Historical', '2025-04-04 21:19:45'),
(7, 'Horror', '2025-04-04 21:19:45'),
(8, 'Mystery', '2025-04-04 21:19:45'),
(9, 'Romance', '2025-04-04 21:19:45'),
(10, 'Thriller', '2025-04-04 21:19:45'),
(11, 'Fiction', '2025-04-04 21:19:45'),
(12, 'Non-Fiction', '2025-04-04 21:19:45'),
(13, 'Self-Help', '2025-04-04 21:19:45'),
(14, 'Biography', '2025-04-04 21:19:45'),
(15, 'History', '2025-04-04 21:19:45'),
(16, 'Poetry', '2025-04-04 21:19:45');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(6) UNSIGNED NOT NULL,
  `fullname` varchar(30) NOT NULL,
  `username` varchar(30) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `username`, `email`, `phone`, `password`, `role`, `created_at`) VALUES
(1, 'Administrator', 'admin', 'admin@libraryms.com', '+44 7362 701161', 'e10adc3949ba59abbe56e057f20f883e', 'admin', '2025-04-04 21:19:45'),
(4, 'Aya', 'Ayaa', 'Aya@rgu.ac.uk', '08124785979', '8a5c9f3e7ec6de3dc552da1b8189bc91', 'user', '2025-04-13 20:15:30'),
(5, 'nwafor juliet', 'juliee', 'c.nwafor3@rgu.ac.uk', '07362701161', 'e10adc3949ba59abbe56e057f20f883e', 'user', '2025-04-14 10:30:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `genre_id` (`genre_id`);

--
-- Indexes for table `borrowed_books`
--
ALTER TABLE `borrowed_books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `borrow_settings`
--
ALTER TABLE `borrow_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `borrowed_books`
--
ALTER TABLE `borrowed_books`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `borrow_settings`
--
ALTER TABLE `borrow_settings`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `genres`
--
ALTER TABLE `genres`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`id`);

--
-- Constraints for table `borrowed_books`
--
ALTER TABLE `borrowed_books`
  ADD CONSTRAINT `borrowed_books_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`),
  ADD CONSTRAINT `borrowed_books_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
