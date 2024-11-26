-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 26, 2024 at 10:07 AM
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
-- Database: `goal7`
--

-- --------------------------------------------------------

--
-- Table structure for table `areas`
--

CREATE TABLE `areas` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `futsal_bookings`
--

CREATE TABLE `futsal_bookings` (
  `id` int(11) NOT NULL,
  `futsal_court_id` int(11) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `total_duration` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `booking_code` varchar(20) NOT NULL,
  `booking_date` datetime DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `futsal_courts`
--

CREATE TABLE `futsal_courts` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `location` text NOT NULL,
  `image` text DEFAULT NULL,
  `features` text DEFAULT NULL,
  `price_per_hour` float NOT NULL,
  `max_players` int(11) NOT NULL,
  `availability_status` tinyint(1) DEFAULT 1,
  `owner_id` int(11) DEFAULT NULL,
  `area_id` int(11) NOT NULL,
  `start_hour` time NOT NULL,
  `end_hour` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `futsal_court_slots`
--

CREATE TABLE `futsal_court_slots` (
  `id` int(11) NOT NULL,
  `futsal_court_id` int(11) DEFAULT NULL,
  `slot_hour` time NOT NULL,
  `is_booked` tinyint(1) DEFAULT 0,
  `booked_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `futsal_reviews`
--

CREATE TABLE `futsal_reviews` (
  `id` int(11) NOT NULL,
  `futsal_court_id` int(11) NOT NULL,
  `player_name` varchar(255) NOT NULL,
  `review_text` text NOT NULL,
  `review_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `stars` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `profile_image_url` varchar(255) DEFAULT NULL,
  `current_area` varchar(100) DEFAULT NULL,
  `role` enum('client','customer') DEFAULT 'customer',
  `verified` tinyint(1) DEFAULT 0,
  `promo_count` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `areas`
--
ALTER TABLE `areas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `futsal_bookings`
--
ALTER TABLE `futsal_bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `booking_code` (`booking_code`),
  ADD KEY `futsal_court_id` (`futsal_court_id`);

--
-- Indexes for table `futsal_courts`
--
ALTER TABLE `futsal_courts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `futsal_court_slots`
--
ALTER TABLE `futsal_court_slots`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `futsal_reviews`
--
ALTER TABLE `futsal_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `futsal_court_id` (`futsal_court_id`);

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
-- AUTO_INCREMENT for table `areas`
--
ALTER TABLE `areas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `futsal_bookings`
--
ALTER TABLE `futsal_bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `futsal_courts`
--
ALTER TABLE `futsal_courts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `futsal_court_slots`
--
ALTER TABLE `futsal_court_slots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `futsal_reviews`
--
ALTER TABLE `futsal_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `futsal_bookings`
--
ALTER TABLE `futsal_bookings`
  ADD CONSTRAINT `futsal_bookings_ibfk_1` FOREIGN KEY (`futsal_court_id`) REFERENCES `futsal_courts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `futsal_reviews`
--
ALTER TABLE `futsal_reviews`
  ADD CONSTRAINT `futsal_reviews_ibfk_1` FOREIGN KEY (`futsal_court_id`) REFERENCES `futsal_courts` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
