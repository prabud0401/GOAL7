-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 26, 2024 at 01:25 PM
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

--
-- Dumping data for table `areas`
--

INSERT INTO `areas` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'North', '2024-11-25 14:46:34', '2024-11-25 14:46:34'),
(2, 'South', '2024-11-25 14:46:34', '2024-11-25 14:46:34'),
(3, 'East', '2024-11-25 14:46:34', '2024-11-25 14:46:34'),
(4, 'West', '2024-11-25 14:46:34', '2024-11-25 14:46:34'),
(5, 'Colombo', '2024-11-25 14:46:34', '2024-11-25 14:46:34'),
(6, 'Kandy', '2024-11-25 14:46:34', '2024-11-25 14:46:34'),
(7, 'Galle', '2024-11-25 14:46:34', '2024-11-25 14:46:34'),
(8, 'Matara', '2024-11-25 14:46:34', '2024-11-25 14:46:34'),
(9, 'Colombo', '2024-11-26 05:26:17', '2024-11-26 05:26:17'),
(10, 'Kandy', '2024-11-26 05:26:17', '2024-11-26 05:26:17'),
(11, 'Galle', '2024-11-26 05:26:17', '2024-11-26 05:26:17'),
(12, 'Jaffna', '2024-11-26 05:26:17', '2024-11-26 05:26:17'),
(13, 'Matara', '2024-11-26 05:26:17', '2024-11-26 05:26:17');

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `futsal_courts`
--

INSERT INTO `futsal_courts` (`id`, `name`, `location`, `image`, `features`, `price_per_hour`, `max_players`, `availability_status`, `owner_id`, `area_id`, `start_hour`, `end_hour`, `created_at`, `start_date`, `end_date`) VALUES
(1, 'Urban Kick Futsal', '123 Soccer St, Colombo', 'https://via.placeholder.com/300x200?text=Futsal+Court', 'Synthetic Turf, Night Lights, Covered Facility', 1500, 10, 1, 1, 1, '08:00:00', '22:00:00', '2024-11-26 05:26:42', NULL, NULL),
(2, 'Pro Play Futsal', '456 Sports Ave, Kandy', 'https://via.placeholder.com/300x200?text=Futsal+Court', 'Artificial Grass, Covered Court', 1200, 8, 1, 2, 2, '09:00:00', '21:00:00', '2024-11-26 05:26:42', NULL, NULL),
(3, 'South Coast Futsal', '789 Beach Rd, Galle', 'https://via.placeholder.com/300x200?text=Futsal+Court', 'Synthetic Turf, Beachside Facility', 1800, 12, 1, 1, 3, '10:00:00', '23:00:00', '2024-11-26 05:26:42', NULL, NULL),
(4, 'Northern Lights Futsal', '101 North St, Jaffna', 'https://via.placeholder.com/300x200?text=Futsal+Court', 'Indoor, Bright Lights', 2000, 15, 1, 3, 4, '07:00:00', '20:00:00', '2024-11-26 05:26:42', NULL, NULL),
(5, 'Southern Breeze Futsal', '555 Coastal Rd, Matara', 'https://via.placeholder.com/300x200?text=Futsal+Court', 'Outdoor, Grass, Sea View', 1100, 6, 1, 2, 5, '06:00:00', '19:00:00', '2024-11-26 05:26:42', NULL, NULL),
(6, 'Urban Kick Futsal', '123 Soccer St, Colombo', 'https://via.placeholder.com/300x200?text=Futsal+Court', '0', 1500, 10, 1, 1, 3, '08:00:00', '22:00:00', '2024-11-26 05:32:41', NULL, NULL),
(7, 'Green Turf Futsal - Colombo', 'Colombo, Sri Lanka', 'https://via.placeholder.com/300x200?text=Futsal+Court', 'Synthetic Turf, Night Lights, Indoor Facility', 2000, 10, 1, 1, 3, '06:00:00', '22:00:00', '2024-11-26 05:38:51', NULL, NULL),
(8, 'Pro Play Futsal - Kandy', 'Kandy, Sri Lanka', 'https://via.placeholder.com/300x200?text=Futsal+Court', 'Artificial Grass, Covered Court', 1800, 8, 1, 1, 2, '07:00:00', '21:00:00', '2024-11-26 05:38:51', NULL, NULL),
(9, 'Urban Kick Futsal', '123 Soccer St, Colombo', 'https://via.placeholder.com/300x200?text=Futsal+Court', '0', 1500, 10, 1, 1, 3, '08:00:00', '22:00:00', '2024-11-26 05:43:28', NULL, NULL),
(10, 'https://sallysbakingaddiction.com/wp-content/uploads/2013/04/triple-chocolate-cake-4.jpg', 'https://sallysbakingaddiction.com/wp-content/uploads/2013/04/triple-chocolate-cake-4.jpg', 'https://sallysbakingaddiction.com/wp-content/uploads/2013/04/triple-chocolate-cake-4.jpg', '0', 1202, 16, NULL, 1, 2, '11:18:00', '23:18:00', '2024-11-26 05:48:21', NULL, NULL),
(11, 'Urban Kick Futsal', '123 Soccer St, Colombo', 'https://via.placeholder.com/300x200?text=Futsal+Court', '0', 1500, 10, 1, 1, 3, '08:00:00', '22:00:00', '2024-11-26 05:49:09', NULL, NULL),
(12, 'Urban Kick Futsal', '123 Soccer St, Colombo', NULL, '0', 1500, 10, NULL, 1, 3, '08:00:00', '22:00:00', '2024-11-26 05:53:04', NULL, NULL),
(13, 'Urban Kick Futsal', '123 Soccer St, Colombo', 'https://sallysbakingaddiction.com/wp-content/uploads/2013/04/triple-chocolate-cake-4.jpg', '0', 1212, 16, 1, 1, 5, '11:28:00', '23:28:00', '2024-11-26 05:58:36', NULL, NULL),
(14, 'Refrigerato', 'Refrigerato', 'https://c.ndtvimg.com/2023-09/u113o4r_medu-vada_625x300_06_September_23.jpg', '0', 21515, 16, 0, 1, 7, '11:36:00', '23:36:00', '2024-11-26 06:06:38', NULL, NULL),
(15, 'https://sallysbakingaddiction.com/wp-content/uploads/2013/04/triple-chocolate-cake-4.jpg', 'https://sallysbakingaddiction.com/wp-content/uploads/2013/04/triple-chocolate-cake-4.jpg', 'https://sallysbakingaddiction.com/wp-content/uploads/2013/04/triple-chocolate-cake-4.jpg', '0', 1200, 12, 1, 1, 10, '17:53:00', '05:53:00', '2024-11-26 12:23:16', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `futsal_court_slots`
--

CREATE TABLE `futsal_court_slots` (
  `id` int(11) NOT NULL,
  `futsal_court_id` int(11) DEFAULT NULL,
  `slot_hour` time NOT NULL,
  `is_booked` tinyint(1) DEFAULT 0,
  `booked_by` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_id` varchar(255) DEFAULT NULL,
  `slot_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `futsal_court_slots`
--

INSERT INTO `futsal_court_slots` (`id`, `futsal_court_id`, `slot_hour`, `is_booked`, `booked_by`, `created_at`, `payment_id`, `slot_date`) VALUES
(1, 1, '08:00:00', 1, 'prabud0401@gmail.com', '2024-11-26 05:26:42', 'Goal7-do2GsfzifW5w', NULL),
(2, 1, '09:00:00', 1, 'prabud0401@gmail.com', '2024-11-26 05:26:42', 'Goal7-do2GsfzifW5w', NULL),
(3, 1, '10:00:00', 1, 'prabud0401@gmail.com', '2024-11-26 05:26:42', 'Goal7-bBq7of2d93i2', NULL),
(4, 1, '11:00:00', 1, 'prabud0401@gmail.com', '2024-11-26 05:26:42', 'Goal7-bBq7of2d93i2', NULL),
(5, 1, '12:00:00', 1, 'prabud0401@gmail.com', '2024-11-26 05:26:42', 'Goal7-xW1EX9cQm1v9', NULL),
(6, 1, '13:00:00', 1, 'prabud0401@gmail.com', '2024-11-26 05:26:42', 'Goal7-fwl1A542XeMp', NULL),
(7, 1, '14:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(8, 1, '15:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(9, 1, '16:00:00', 1, 'prabud0401@gmail.com', '2024-11-26 05:26:42', 'Goal7-F2yWZGSPLn2W', NULL),
(10, 1, '17:00:00', 1, 'prabud0401@gmail.com', '2024-11-26 05:26:42', 'Goal7-F2yWZGSPLn2W', NULL),
(11, 1, '18:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(12, 1, '19:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(13, 1, '20:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(14, 1, '21:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(15, 1, '22:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(16, 2, '09:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(17, 2, '10:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(18, 2, '11:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(19, 2, '12:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(20, 2, '13:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(21, 2, '14:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(22, 2, '15:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(23, 2, '16:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(24, 2, '17:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(25, 2, '18:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(26, 2, '19:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(27, 2, '20:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(28, 2, '21:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(29, 3, '10:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(30, 3, '11:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(31, 3, '12:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(32, 3, '13:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(33, 3, '14:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(34, 3, '15:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(35, 3, '16:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(36, 3, '17:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(37, 3, '18:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(38, 3, '19:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(39, 3, '20:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(40, 3, '21:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(41, 3, '22:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(42, 4, '07:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(43, 4, '08:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(44, 4, '09:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(45, 4, '10:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(46, 4, '11:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(47, 4, '12:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(48, 4, '13:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(49, 4, '14:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(50, 4, '15:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(51, 4, '16:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(52, 4, '17:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(53, 4, '18:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(54, 4, '19:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(55, 4, '20:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(56, 5, '06:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(57, 5, '07:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(58, 5, '08:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(59, 5, '09:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(60, 5, '10:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(61, 5, '11:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(62, 5, '12:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(63, 5, '13:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(64, 5, '14:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(65, 5, '15:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(66, 5, '16:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(67, 5, '17:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(68, 5, '18:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(69, 5, '19:00:00', 0, NULL, '2024-11-26 05:26:42', NULL, NULL),
(70, 6, '08:00:00', 0, NULL, '2024-11-26 05:32:41', NULL, NULL),
(71, 6, '09:00:00', 0, NULL, '2024-11-26 05:32:41', NULL, NULL),
(72, 6, '10:00:00', 0, NULL, '2024-11-26 05:32:41', NULL, NULL),
(73, 6, '11:00:00', 0, NULL, '2024-11-26 05:32:41', NULL, NULL),
(74, 6, '12:00:00', 0, NULL, '2024-11-26 05:32:41', NULL, NULL),
(75, 6, '13:00:00', 0, NULL, '2024-11-26 05:32:41', NULL, NULL),
(76, 6, '14:00:00', 0, NULL, '2024-11-26 05:32:41', NULL, NULL),
(77, 6, '15:00:00', 0, NULL, '2024-11-26 05:32:41', NULL, NULL),
(78, 6, '16:00:00', 0, NULL, '2024-11-26 05:32:41', NULL, NULL),
(79, 6, '17:00:00', 0, NULL, '2024-11-26 05:32:41', NULL, NULL),
(80, 6, '18:00:00', 0, NULL, '2024-11-26 05:32:41', NULL, NULL),
(81, 6, '19:00:00', 0, NULL, '2024-11-26 05:32:41', NULL, NULL),
(82, 6, '20:00:00', 0, NULL, '2024-11-26 05:32:41', NULL, NULL),
(83, 6, '21:00:00', 0, NULL, '2024-11-26 05:32:41', NULL, NULL),
(84, 1, '06:00:00', 1, NULL, '2024-11-26 05:38:51', NULL, NULL),
(85, 1, '07:00:00', 1, NULL, '2024-11-26 05:38:51', NULL, NULL),
(86, 1, '08:00:00', 0, NULL, '2024-11-26 05:38:51', NULL, NULL),
(87, 2, '07:00:00', 0, NULL, '2024-11-26 05:38:51', NULL, NULL),
(88, 2, '08:00:00', 1, NULL, '2024-11-26 05:38:51', NULL, NULL),
(89, 2, '09:00:00', 0, NULL, '2024-11-26 05:38:51', NULL, NULL),
(90, 9, '08:00:00', 0, NULL, '2024-11-26 05:43:28', NULL, NULL),
(91, 9, '09:00:00', 0, NULL, '2024-11-26 05:43:28', NULL, NULL),
(92, 9, '10:00:00', 0, NULL, '2024-11-26 05:43:28', NULL, NULL),
(93, 9, '11:00:00', 0, NULL, '2024-11-26 05:43:28', NULL, NULL),
(94, 9, '12:00:00', 0, NULL, '2024-11-26 05:43:28', NULL, NULL),
(95, 9, '13:00:00', 0, NULL, '2024-11-26 05:43:28', NULL, NULL),
(96, 9, '14:00:00', 0, NULL, '2024-11-26 05:43:28', NULL, NULL),
(97, 9, '15:00:00', 0, NULL, '2024-11-26 05:43:28', NULL, NULL),
(98, 9, '16:00:00', 0, NULL, '2024-11-26 05:43:28', NULL, NULL),
(99, 9, '17:00:00', 0, NULL, '2024-11-26 05:43:28', NULL, NULL),
(100, 9, '18:00:00', 0, NULL, '2024-11-26 05:43:28', NULL, NULL),
(101, 9, '19:00:00', 0, NULL, '2024-11-26 05:43:28', NULL, NULL),
(102, 9, '20:00:00', 0, NULL, '2024-11-26 05:43:28', NULL, NULL),
(103, 9, '21:00:00', 0, NULL, '2024-11-26 05:43:28', NULL, NULL),
(104, 10, '11:18:00', 0, NULL, '2024-11-26 05:48:21', NULL, NULL),
(105, 10, '12:18:00', 0, NULL, '2024-11-26 05:48:21', NULL, NULL),
(106, 10, '13:18:00', 0, NULL, '2024-11-26 05:48:21', NULL, NULL),
(107, 10, '14:18:00', 0, NULL, '2024-11-26 05:48:21', NULL, NULL),
(108, 10, '15:18:00', 0, NULL, '2024-11-26 05:48:21', NULL, NULL),
(109, 10, '16:18:00', 0, NULL, '2024-11-26 05:48:21', NULL, NULL),
(110, 10, '17:18:00', 0, NULL, '2024-11-26 05:48:21', NULL, NULL),
(111, 10, '18:18:00', 0, NULL, '2024-11-26 05:48:21', NULL, NULL),
(112, 10, '19:18:00', 0, NULL, '2024-11-26 05:48:21', NULL, NULL),
(113, 10, '20:18:00', 0, NULL, '2024-11-26 05:48:21', NULL, NULL),
(114, 10, '21:18:00', 0, NULL, '2024-11-26 05:48:21', NULL, NULL),
(115, 10, '22:18:00', 0, NULL, '2024-11-26 05:48:21', NULL, NULL),
(116, 11, '08:00:00', 0, NULL, '2024-11-26 05:49:09', NULL, NULL),
(117, 11, '09:00:00', 0, NULL, '2024-11-26 05:49:09', NULL, NULL),
(118, 11, '10:00:00', 0, NULL, '2024-11-26 05:49:09', NULL, NULL),
(119, 11, '11:00:00', 0, NULL, '2024-11-26 05:49:09', NULL, NULL),
(120, 11, '12:00:00', 0, NULL, '2024-11-26 05:49:09', NULL, NULL),
(121, 11, '13:00:00', 0, NULL, '2024-11-26 05:49:09', NULL, NULL),
(122, 11, '14:00:00', 0, NULL, '2024-11-26 05:49:09', NULL, NULL),
(123, 11, '15:00:00', 0, NULL, '2024-11-26 05:49:09', NULL, NULL),
(124, 11, '16:00:00', 0, NULL, '2024-11-26 05:49:09', NULL, NULL),
(125, 11, '17:00:00', 0, NULL, '2024-11-26 05:49:09', NULL, NULL),
(126, 11, '18:00:00', 0, NULL, '2024-11-26 05:49:09', NULL, NULL),
(127, 11, '19:00:00', 0, NULL, '2024-11-26 05:49:09', NULL, NULL),
(128, 11, '20:00:00', 0, NULL, '2024-11-26 05:49:09', NULL, NULL),
(129, 11, '21:00:00', 0, NULL, '2024-11-26 05:49:09', NULL, NULL),
(130, 12, '08:00:00', 0, NULL, '2024-11-26 05:53:04', NULL, NULL),
(131, 12, '09:00:00', 0, NULL, '2024-11-26 05:53:04', NULL, NULL),
(132, 12, '10:00:00', 0, NULL, '2024-11-26 05:53:04', NULL, NULL),
(133, 12, '11:00:00', 0, NULL, '2024-11-26 05:53:04', NULL, NULL),
(134, 12, '12:00:00', 0, NULL, '2024-11-26 05:53:04', NULL, NULL),
(135, 12, '13:00:00', 0, NULL, '2024-11-26 05:53:04', NULL, NULL),
(136, 12, '14:00:00', 0, NULL, '2024-11-26 05:53:04', NULL, NULL),
(137, 12, '15:00:00', 0, NULL, '2024-11-26 05:53:04', NULL, NULL),
(138, 12, '16:00:00', 0, NULL, '2024-11-26 05:53:04', NULL, NULL),
(139, 12, '17:00:00', 0, NULL, '2024-11-26 05:53:04', NULL, NULL),
(140, 12, '18:00:00', 0, NULL, '2024-11-26 05:53:04', NULL, NULL),
(141, 12, '19:00:00', 0, NULL, '2024-11-26 05:53:04', NULL, NULL),
(142, 12, '20:00:00', 0, NULL, '2024-11-26 05:53:04', NULL, NULL),
(143, 12, '21:00:00', 0, NULL, '2024-11-26 05:53:04', NULL, NULL),
(144, 13, '11:28:00', 0, NULL, '2024-11-26 05:58:36', NULL, NULL),
(145, 13, '12:28:00', 0, NULL, '2024-11-26 05:58:36', NULL, NULL),
(146, 13, '13:28:00', 0, NULL, '2024-11-26 05:58:36', NULL, NULL),
(147, 13, '14:28:00', 0, NULL, '2024-11-26 05:58:36', NULL, NULL),
(148, 13, '15:28:00', 0, NULL, '2024-11-26 05:58:36', NULL, NULL),
(149, 13, '16:28:00', 0, NULL, '2024-11-26 05:58:36', NULL, NULL),
(150, 13, '17:28:00', 0, NULL, '2024-11-26 05:58:36', NULL, NULL),
(151, 13, '18:28:00', 0, NULL, '2024-11-26 05:58:36', NULL, NULL),
(152, 13, '19:28:00', 0, NULL, '2024-11-26 05:58:36', NULL, NULL),
(153, 13, '20:28:00', 0, NULL, '2024-11-26 05:58:36', NULL, NULL),
(154, 13, '21:28:00', 0, NULL, '2024-11-26 05:58:36', NULL, NULL),
(155, 13, '22:28:00', 0, NULL, '2024-11-26 05:58:36', NULL, NULL),
(156, 14, '11:36:00', 0, NULL, '2024-11-26 06:06:38', NULL, NULL),
(157, 14, '12:36:00', 0, NULL, '2024-11-26 06:06:38', NULL, NULL),
(158, 14, '13:36:00', 0, NULL, '2024-11-26 06:06:38', NULL, NULL),
(159, 14, '14:36:00', 0, NULL, '2024-11-26 06:06:38', NULL, NULL),
(160, 14, '15:36:00', 0, NULL, '2024-11-26 06:06:38', NULL, NULL),
(161, 14, '16:36:00', 0, NULL, '2024-11-26 06:06:38', NULL, NULL),
(162, 14, '17:36:00', 0, NULL, '2024-11-26 06:06:38', NULL, NULL),
(163, 14, '18:36:00', 0, NULL, '2024-11-26 06:06:38', NULL, NULL),
(164, 14, '19:36:00', 0, NULL, '2024-11-26 06:06:38', NULL, NULL),
(165, 14, '20:36:00', 0, NULL, '2024-11-26 06:06:38', NULL, NULL),
(166, 14, '21:36:00', 0, NULL, '2024-11-26 06:06:38', NULL, NULL),
(167, 14, '22:36:00', 0, NULL, '2024-11-26 06:06:38', NULL, NULL);

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

--
-- Dumping data for table `futsal_reviews`
--

INSERT INTO `futsal_reviews` (`id`, `futsal_court_id`, `player_name`, `review_text`, `review_date`, `stars`) VALUES
(1, 1, 'John D.', 'Great court! Smooth turf and well-lit for evening matches. The staff is friendly and helpful. Highly recommend this venue for a fun time with friends.', '2024-11-26 06:18:58', 0),
(2, 1, 'Emily R.', 'The court is fantastic with its indoor facility and night lights. However, the pricing could be slightly lower for regular bookings. Overall, great experience!', '2024-11-26 06:18:58', 0),
(3, 2, 'Mike T.', 'Pro Play Futsal is an amazing spot in Kandy. The artificial grass gives a realistic feel, and the covered court is a lifesaver on rainy days. Friendly staff and great service.', '2024-11-26 06:18:58', 0),
(4, 2, 'Anna P.', 'The facility was great, but I encountered some issues with booking times. Still, the court was in excellent condition, and I will be back again soon.', '2024-11-26 06:18:58', 0),
(5, 3, 'Sam W.', 'Urban Kick Futsal in Colombo is one of the best courts I’ve played on. The turf is top-notch, and the facilities are modern. Worth every penny.', '2024-11-26 06:18:58', 0),
(6, 3, 'Lily S.', 'I’ve played here multiple times. The location is perfect, and the court is always well-maintained. I just wish there were more available time slots on weekends.', '2024-11-26 06:18:58', 0);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `payment_id` varchar(50) NOT NULL,
  `method` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `slots` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`slots`)),
  `username` varchar(255) NOT NULL,
  `futsal_id` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('pending','completed','failed') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `payment_id`, `method`, `amount`, `slots`, `username`, `futsal_id`, `created_at`, `updated_at`, `status`) VALUES
(1, 'Goal7-HPS6tLQM70tz', 'bank', 3000.00, '[\"1\",\"2\"]', 'prabud0401@gmail.com', '1', '2024-11-26 11:08:39', '2024-11-26 11:08:39', 'pending'),
(2, 'Goal7-2WzCcKua1Hpe', 'bank', 3000.00, '[\"1\",\"2\"]', 'prabud0401@gmail.com', '1', '2024-11-26 11:15:17', '2024-11-26 11:15:17', 'pending'),
(3, 'Goal7-QeyXObUM2LTL', 'bank', 3000.00, '[\"1\",\"2\"]', 'prabud0401@gmail.com', '1', '2024-11-26 11:20:15', '2024-11-26 11:20:15', 'pending'),
(4, 'Goal7-TCt8ziMvTl1O', 'bank', 3000.00, '[\"1\",\"2\"]', 'prabud0401@gmail.com', '1', '2024-11-26 11:21:31', '2024-11-26 11:21:31', 'pending'),
(5, 'Goal7-do2GsfzifW5w', 'bank', 3000.00, '[\"1\",\"2\"]', 'prabud0401@gmail.com', '1', '2024-11-26 11:27:47', '2024-11-26 11:27:47', 'pending'),
(6, 'Goal7-bBq7of2d93i2', 'bank', 3000.00, '[\"3\",\"4\"]', 'prabud0401@gmail.com', '1', '2024-11-26 11:28:52', '2024-11-26 11:28:52', 'pending'),
(7, 'Goal7-xW1EX9cQm1v9', 'bank', 1500.00, '[\"5\"]', 'prabud0401@gmail.com', '1', '2024-11-26 11:32:40', '2024-11-26 11:32:40', 'pending'),
(8, 'Goal7-fwl1A542XeMp', 'bank', 1500.00, '[\"6\"]', 'prabud0401@gmail.com', '1', '2024-11-26 11:34:55', '2024-11-26 11:34:55', 'pending'),
(9, 'Goal7-F2yWZGSPLn2W', 'bank', 3000.00, '[\"10\",\"9\"]', 'prabud0401@gmail.com', '1', '2024-11-26 11:53:34', '2024-11-26 11:53:34', 'pending');

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
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `name`, `phone`, `address`, `profile_image_url`, `current_area`, `role`, `verified`, `promo_count`, `created_at`, `updated_at`) VALUES
(1, 'prabud0401@gmail.com', 'prabud0401@gmail.com', '$2y$10$99l9NUID5t9pMw5r3hM8yeT4/D/3uvVy7p1iaWaMugaFZdzIPJa56', 'prabu prabu', '0760700491', 'No:15, Amherst Estate\r\nUdapussellawa', 'https://static.wixstatic.com/media/869a04_8e235ccd41844003915d3b0a8d7d8572~mv2.jpg/v1/fill/w_640,h_400,al_c,q_80,usm_0.66_1.00_0.01,enc_auto/869a04_8e235ccd41844003915d3b0a8d7d8572~mv2.jpg', '5', 'customer', 0, 4, '2024-11-26 07:14:29', '2024-11-26 11:53:34');

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
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_id` (`payment_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `futsal_bookings`
--
ALTER TABLE `futsal_bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `futsal_courts`
--
ALTER TABLE `futsal_courts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `futsal_court_slots`
--
ALTER TABLE `futsal_court_slots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=168;

--
-- AUTO_INCREMENT for table `futsal_reviews`
--
ALTER TABLE `futsal_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
