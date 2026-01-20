-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 20, 2026 at 02:40 PM
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
-- Database: `coffeeshop`
--

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `name`, `email`, `subject`, `message`, `created_at`, `is_read`) VALUES
(1, 'ghaddab', 'ruqyt913@gmail.com', 'about your product', 'u have the most beautiful product', '2026-01-03 15:16:15', 1),
(3, 'adam', 'adam@gmail.com', 'problem', 'problem about prices', '2026-01-08 08:57:50', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total` decimal(10,2) DEFAULT 0.00,
  `status` enum('pending','completed','canceled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total`, `status`, `created_at`) VALUES
(1, 4, 13.00, 'completed', '2026-01-02 14:51:15'),
(2, 3, 13.00, 'completed', '2026-01-02 14:55:58'),
(3, 1, 13.00, 'completed', '2026-01-02 15:13:58'),
(4, 1, 21.00, 'completed', '2026-01-03 19:33:53'),
(5, 5, 6.00, 'completed', '2026-01-05 09:23:20'),
(6, 5, 26.00, 'completed', '2026-01-05 09:25:00'),
(7, 4, 14.00, 'completed', '2026-01-05 21:16:41'),
(8, 4, 13.00, 'completed', '2026-01-07 20:15:29'),
(9, 5, 0.00, 'completed', '2026-01-08 00:29:03'),
(10, 4, 0.00, 'completed', '2026-01-08 07:39:54'),
(11, 6, 21.00, 'completed', '2026-01-08 08:58:32'),
(12, 1, 14.00, 'completed', '2026-01-08 10:14:35'),
(13, 1, 66.00, 'completed', '2026-01-08 10:36:33');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 2, 1, 0.00),
(2, 2, 2, 1, 0.00),
(3, 3, 2, 1, 0.00),
(4, 4, 8, 1, 0.00),
(5, 5, 16, 1, 0.00),
(6, 6, 2, 1, 0.00),
(7, 6, 12, 1, 0.00),
(8, 7, 13, 2, 0.00),
(9, 8, 12, 1, 0.00),
(10, 9, 2, 1, 0.00),
(11, 9, 12, 1, 0.00),
(12, 10, 12, 1, 0.00),
(13, 11, 13, 3, 0.00),
(14, 12, 13, 2, 0.00),
(15, 13, 7, 2, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(50) NOT NULL,
  `calories` int(11) DEFAULT NULL,
  `has_milk` tinyint(1) DEFAULT 0,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `description`, `category`, `calories`, `has_milk`, `image_url`, `created_at`) VALUES
(2, 'caffee-latte', 13.00, '', 'hot', 10012, 1, '1767317740_caffe-latte.png', '2026-01-02 01:35:40'),
(4, 'Ice Tea', 15.00, '', 'cold', 10, 0, '1767462872_Ice-tea.png', '2026-01-03 17:54:32'),
(5, 'Orange Juce', 23.00, '', 'cold', 1006, 0, '1767462916_orange1.png', '2026-01-03 17:55:16'),
(6, 'Brownie', 23.00, '', 'sweets', 10023, 0, '1767462976_brownie.png', '2026-01-03 17:56:16'),
(7, 'Chesse Cake ', 33.00, '', 'sweets', 40, 0, '1767463151_chessecake.png', '2026-01-03 17:59:11'),
(8, 'Donate', 21.00, '', 'sweets', 36, 0, '1767464161_donate.png', '2026-01-03 18:16:01'),
(10, 'Croissant', 6.00, '', 'sweets', 406, 0, '1767466710_croissant1.png', '2026-01-03 18:58:30'),
(11, 'Cookies', 80.00, '', 'sweets', 35, 0, '1767466876_cookie.png', '2026-01-03 19:01:16'),
(12, 'Hot Choclate', 13.00, '', 'hot', 170, 1, '1767466964_hotchocolate.png', '2026-01-03 19:02:44'),
(13, 'Esepresso', 7.00, '', 'hot', 4, 0, '1767467114_esepresso2.png', '2026-01-03 19:05:14'),
(14, 'Ginger Tea', 14.00, '', 'hot', 8, 0, '1767467182_ginger-tea1.png', '2026-01-03 19:06:22'),
(15, 'Muffen', 30.00, '', 'sweets', 340, 0, '1767467247_muffen.png', '2026-01-03 19:07:27'),
(16, 'Tea', 6.00, '', 'hot', 2, 0, '1767467346_tea.png', '2026-01-03 19:09:06'),
(17, 'Iced Match ', 10.00, '', 'cold', 68, 1, '1767467444_Ice-matcha.png', '2026-01-03 19:10:44'),
(18, 'Iced Popa Coffee', 13.00, '', 'cold', 122, 1, '1767467539_Ice-popa-coffee.png', '2026-01-03 19:12:19'),
(19, 'Iced Coffee', 80.00, '', 'cold', 60, 1, '1767467594_Ice-coffee.png', '2026-01-03 19:13:14'),
(20, 'Mint tea', 200.00, '', 'hot', 3, 0, '1767817167_mint-tea2.png', '2026-01-07 20:19:27');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `role`, `created_at`, `last_login`) VALUES
(1, 'ghaddab', 'ruqyt913@gmail.com', '$2y$10$i189X4OUfy.h9hKS8CbqeOmZe91rk9xYTxnImStvi6ASto0OjZE4W', 'admin', '2025-12-30 23:36:27', NULL),
(2, 'darine', 'rok@gamil.com', '$2y$10$ajrNWuVXw/mJLibVdH/6UOjR2N/utb6PYMrrRCvaCjchVXRyAiDh6', 'user', '2025-12-31 02:20:27', NULL),
(3, 'nara', 'nara@gmail.com', '$2y$10$Fx/qYwWSvnFlrbtBe9KYcuM3ArOFiIbScfNLqVKvcx0szCmwHnrRW', 'user', '2026-01-02 00:46:57', NULL),
(4, 'ahmed', 'ahmed@gmail.com', '$2y$10$hGc0rUdezGb7iL5wLIyF5.H30.avhj68IFVf6kCJLf2QBNyTnggVi', 'user', '2026-01-02 00:54:16', NULL),
(5, 'Fatima', 'fatima@gmail.com', '$2y$10$JYODHKKfXbYaD.F27vGt9.slNeMIfhhaPjuopT/KZ0WnGQyk.Rx3a', 'user', '2026-01-05 09:22:12', NULL),
(6, 'adam', 'adam@gmail.com', '$2y$10$mH0pEC1fnHtM.TXe4odOcOzh.TtG9qOf39lrSijcOFMNMgghYlku.', 'user', '2026-01-08 08:54:26', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
