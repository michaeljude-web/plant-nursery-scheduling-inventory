-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 20, 2025 at 01:06 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sads`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `passcode` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `passcode`) VALUES
(1, 'admin123'),
(2, 'admin123');

-- --------------------------------------------------------

--
-- Table structure for table `customer_info`
--

CREATE TABLE `customer_info` (
  `customer_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `contact_number` varchar(15) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer_info`
--

INSERT INTO `customer_info` (`customer_id`, `full_name`, `address`, `contact_number`, `date_added`) VALUES
(14, 'Michael Jude', 'Banga South Cotabato', '09068181822', '2025-04-07 11:00:17'),
(15, 'Cloe', 'Koronadal City', '09300784663', '2025-04-07 11:02:47'),
(16, 'Michael K', 'Banga South Cotabato', '09820384855', '2025-04-07 12:32:43'),
(17, 'Mike J', 'Koronadal City', '09070808733', '2025-04-07 21:21:51'),
(18, 'Tom', 'Isulan Sultan Kudarat', '09073737822', '2025-04-08 03:43:57'),
(19, 'mike k', 'Banga South Cotabato', '09751676544', '2025-04-09 03:12:45'),
(20, 'Maria', 'Banga South Cotabato', '09473535366', '2025-04-09 06:21:14'),
(21, 'f', 'f', '3', '2025-04-13 23:33:18'),
(22, 'f', 'f', '3', '2025-04-13 23:33:18'),
(23, 'f', 'f', '3', '2025-04-13 23:33:18'),
(24, 'Mikesss', 'banga', '09087654322', '2025-04-14 03:34:14'),
(25, 'das', 'dsds', '5345345', '2025-04-14 03:43:06'),
(26, 'michael jdu', 'Banga South Cotabato', '09070303033', '2025-04-14 03:51:34'),
(27, 'ffsfsdf', 'sffsdd', '34324242', '2025-04-14 04:00:01'),
(28, 'michael jdu', 'Banga South Cotabato', '09070303033', '2025-04-14 04:56:39'),
(29, 'Michael Jude', 'Banga South Cotabato', '09073838366', '2025-04-14 05:09:57'),
(30, 'Juan', 'Banga', '09048272622', '2025-04-16 01:39:25'),
(31, 'Juan Delacruz', 'Kornadal City', '09086363622', '2025-04-16 01:47:55'),
(32, 'Maria', 'Banga South Cotabato', '09092727277', '2025-04-20 23:02:31'),
(33, 'mikess', 'afsafsaf', '343432345', '2025-04-28 05:04:54'),
(34, 'dsdsadsa', 'sadadsa', '4323424', '2025-04-28 05:05:56'),
(35, 'fdsfsd', 'dsfsdf', '222', '2025-04-28 05:06:29'),
(36, 'fdfdsfdf', 'ffdfsdf', '324234', '2025-04-28 05:06:52'),
(37, 'fdsfdsfs', 'dsfdsfsdf', '3342342423', '2025-04-28 05:07:54'),
(38, 'rwestfdsg', 'hdfhdfh', '4543534543', '2025-04-28 05:09:20'),
(39, 'gfdffddddd', 'dddadfaf', '42424234', '2025-04-28 08:17:37'),
(40, 'mira', 'dsfd', '09069191922', '2025-05-12 23:48:56'),
(41, 'mira', 'dsfd', '09069191922', '2025-05-12 23:51:08'),
(42, 'miraa', 'arfe', '2342355653', '2025-05-12 23:51:27'),
(43, 'miraa', 'arfe', '2342355653', '2025-05-12 23:51:36'),
(44, 'miraa', 'arfe', '2342355653', '2025-05-12 23:53:08'),
(45, 'miraa', 'arfe', '2342355653', '2025-05-12 23:54:12'),
(46, 'dfsf', 'sdfsf', '421423424', '2025-05-12 23:58:18'),
(47, 'wert', 'rtwet', 'twet', '2025-05-13 01:42:03'),
(48, 'gtdsghg', 'ryteryr', '56457464', '2025-05-13 03:00:55'),
(49, 'mmmmmm', 'mmmmm', '8657867878', '2025-05-13 03:05:07'),
(50, 'mmmmmm', 'mmmmm', '8657867878', '2025-05-13 03:13:46'),
(51, 'mmmmmm', 'mmmmm', '8657867878', '2025-05-13 03:13:49'),
(52, 'gfdgfg', 'dgfdfg', '3534534', '2025-05-13 03:14:02'),
(53, 'erere', 'ere', '345342343', '2025-05-13 03:18:17'),
(54, 'fgdgg', 'dgdfgdgd', '999999999999', '2025-05-13 03:19:55'),
(55, 'fgdgg', 'dgdfgdgd', '999999999999', '2025-05-13 03:19:55'),
(56, 'mmmmm', 'mmmm', '999999', '2025-05-13 03:22:58'),
(57, 'mmmm', 'mmm', '6435454354', '2025-05-13 03:26:19'),
(58, 'dgdg', 'dgffg', '7777777777', '2025-05-13 03:28:03'),
(59, 'dgdg', 'dgffg', '7777777777', '2025-05-13 03:28:16'),
(60, 'yhuiuy', 'ygiujgy8658', '765757', '2025-05-13 03:28:31'),
(61, 'yhuiuy', 'ygiujgy8658', '765757', '2025-05-13 03:28:36'),
(62, 'yhuiuy', 'ygiujgy8658', '765757', '2025-05-13 04:04:43'),
(63, '5yerbhrh', 'eryt45', '7576576576577', '2025-05-13 04:06:38'),
(64, 'MARIA GATA', 'NEY YORK', '0992483243284', '2025-05-16 06:12:18'),
(65, 'dsad', 'sad', '21222', '2025-05-20 05:08:25');

-- --------------------------------------------------------

--
-- Table structure for table `delivery`
--

CREATE TABLE `delivery` (
  `delivery_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `delivery_status` enum('Pending','Delivered','Cancelled') NOT NULL,
  `reason` text DEFAULT NULL,
  `delivery_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `delivery`
--

INSERT INTO `delivery` (`delivery_id`, `order_id`, `delivery_status`, `reason`, `delivery_date`) VALUES
(1, 2, 'Cancelled', 't', '2025-05-13 09:24:35'),
(2, 7, 'Cancelled', 'hahahahgfhgf', '2025-05-13 12:15:15'),
(3, 6, 'Delivered', '', '2025-05-13 13:36:30');

-- --------------------------------------------------------

--
-- Table structure for table `employee_info`
--

CREATE TABLE `employee_info` (
  `id` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `age` int(11) NOT NULL,
  `contact_number` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `role` enum('Sales Team','Delivery Staff','Nutritionist') NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_info`
--

INSERT INTO `employee_info` (`id`, `firstname`, `lastname`, `age`, `contact_number`, `address`, `role`, `username`, `password`) VALUES
(1, 'Michael Jude', 'Garde', 44, '09300645881', 'Koronadal', 'Sales Team', 'qwer', '$2y$10$umwSG70KGR7AYnPfh0t1muu2T5e14cKsax0qpX8fiTXsLHEcddr7q'),
(2, 'Michael', 'Jude', 20, '09070101933', 'Banga', 'Sales Team', 'michaeljude', '$2y$10$tKA7xnX9agV5QuU4Pr5ITO.ODVJ3UhbXUWi6P6i3BE94r46.HBl6a'),
(3, 'Michael Jude', 'Gardd', 22, '09079191944', 'Banga South Cotabato', 'Sales Team', 'a', '$2y$10$KwQtIhfIFTpz5IVMnE.L2u0p9p5LlelezbTXZCAOVTepaBfLqNwCy'),
(4, 'Jude', 'Michael', 33, '09875544444', 'Koronadal City', 'Delivery Staff', 'b', '$2y$10$jcL6XpG3vWzhYEdoH1JYB.T59hyHHDwABB.55yrtT10RjxXd9Z1x6');

-- --------------------------------------------------------

--
-- Table structure for table `fertilizer_category`
--

CREATE TABLE `fertilizer_category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fertilizer_category`
--

INSERT INTO `fertilizer_category` (`category_id`, `category_name`) VALUES
(1, 'FERTILIZER FOR ROOT'),
(2, 'FERTILIZER FOR YOUNG');

-- --------------------------------------------------------

--
-- Table structure for table `fertilizer_deductions`
--

CREATE TABLE `fertilizer_deductions` (
  `deduction_id` int(11) NOT NULL,
  `fertilizer_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `date_deducted` datetime DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fertilizer_deductions`
--

INSERT INTO `fertilizer_deductions` (`deduction_id`, `fertilizer_id`, `quantity`, `image_path`, `date_deducted`, `employee_id`) VALUES
(6, 9, 1, 'uploads/Screenshot at 2025-04-18 13-28-29.png', '2025-04-20 11:20:42', 1),
(7, 9, 1, 'uploads/seed.png', '2025-04-20 12:07:11', 1),
(8, 12, 1, 'uploads/Screenshot at 2025-03-08 22-37-58.png', '2025-04-22 15:17:50', 1),
(9, 9, 1, 'uploads/Damping-off-625507793.jpg', '2025-05-15 06:19:34', 1),
(10, 9, 1, 'uploads/any-way-to-save-seedlings-damaged-while-hardening-off-v0-pswov72r4oza1-4145488105.png', '2025-05-20 13:07:05', 1);

-- --------------------------------------------------------

--
-- Table structure for table `fertilizer_inventory`
--

CREATE TABLE `fertilizer_inventory` (
  `inventory_id` int(11) NOT NULL,
  `fertilizer_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `date_added` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fertilizer_inventory`
--

INSERT INTO `fertilizer_inventory` (`inventory_id`, `fertilizer_id`, `quantity`, `date_added`) VALUES
(3, 9, 48, '2025-05-15 05:48:22'),
(4, 12, 1, '2025-05-15 05:48:22'),
(5, 12, 1, '2025-05-15 05:48:22'),
(6, 12, 1, '2025-05-15 05:48:22'),
(7, 9, 48, '2025-05-15 05:48:22'),
(8, 9, 48, '2025-05-15 05:48:22'),
(9, 9, 48, '2025-05-15 05:48:22'),
(10, 9, 48, '2025-05-14 23:49:42'),
(11, 9, 48, '2025-05-14 23:50:00'),
(12, 9, 48, '2025-05-16 04:10:07'),
(13, 12, 1, '2025-05-16 04:10:19');

-- --------------------------------------------------------

--
-- Table structure for table `fertilizer_schedule`
--

CREATE TABLE `fertilizer_schedule` (
  `schedule_id` int(11) NOT NULL,
  `seedling_id` int(11) NOT NULL,
  `fertilizer_id` int(11) NOT NULL,
  `unit` varchar(50) NOT NULL,
  `scheduled_date` datetime NOT NULL,
  `status` varchar(50) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fertilizer_schedule`
--

INSERT INTO `fertilizer_schedule` (`schedule_id`, `seedling_id`, `fertilizer_id`, `unit`, `scheduled_date`, `status`) VALUES
(6, 7, 9, '90ML', '2025-02-15 00:00:00', 'complete'),
(7, 7, 9, '100ML', '2025-04-20 04:09:00', 'complete'),
(8, 8, 9, '100ML', '2025-04-20 04:09:00', 'pending'),
(9, 7, 9, '200ML', '2026-02-15 09:08:00', 'pending'),
(10, 8, 9, '200ML', '2026-02-15 09:08:00', 'pending'),
(11, 7, 9, '900ML', '2027-02-15 22:02:00', 'pending'),
(12, 11, 9, '900ML', '2027-02-15 22:02:00', 'pending'),
(13, 11, 9, 'ddd', '2028-02-15 22:02:00', 'pending'),
(14, 12, 12, '21d', '2025-04-23 05:05:00', 'pending'),
(15, 11, 9, '00', '2025-02-22 03:03:00', 'pending'),
(16, 13, 12, '6h', '2025-04-22 04:04:00', 'complete'),
(17, 7, 9, '5', '2025-05-14 07:07:00', 'pending'),
(18, 8, 9, '5', '2025-05-14 07:07:00', 'pending'),
(19, 7, 9, '1', '2025-05-15 03:03:00', 'pending'),
(20, 8, 9, '1', '2025-05-15 03:03:00', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_reports`
--

CREATE TABLE `inventory_reports` (
  `id` int(11) NOT NULL,
  `plot_id` int(11) NOT NULL,
  `report_date` datetime NOT NULL,
  `damage_description` text NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `damaged_quantity` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory_reports`
--

INSERT INTO `inventory_reports` (`id`, `plot_id`, `report_date`, `damage_description`, `image_path`, `damaged_quantity`) VALUES
(46, 565, '2025-05-14 15:19:53', 'na sunog', 'uploads/1747228793_cucumber-plant-cold-injury-880354534.jpg', 1),
(47, 566, '2025-05-14 15:19:53', 'na sunog', NULL, 1),
(48, 485, '2025-05-14 17:03:47', 's', 'uploads/1747235027_Damping-off-625507793.jpg', 1),
(49, 486, '2025-05-14 17:03:47', 's', 'uploads/1747235027_cucumber-plant-cold-injury-880354534.jpg', 1),
(50, 487, '2025-05-14 17:03:47', 's', NULL, 1),
(51, 488, '2025-05-14 17:03:47', 's', NULL, 1),
(52, 489, '2025-05-14 17:03:47', 's', NULL, 1),
(53, 490, '2025-05-14 17:03:47', 's', NULL, 1),
(54, 491, '2025-05-14 17:03:47', 's', NULL, 1),
(55, 492, '2025-05-14 17:03:47', 's', NULL, 1),
(56, 493, '2025-05-14 17:03:47', 's', NULL, 1),
(57, 494, '2025-05-14 17:03:47', 's', NULL, 1),
(58, 495, '2025-05-14 17:03:47', 's', NULL, 1),
(59, 496, '2025-05-14 17:03:47', 's', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `seedling_variety_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  `customer_id` int(11) NOT NULL,
  `status` varchar(20) DEFAULT 'order'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `seedling_variety_id`, `quantity`, `date_added`, `customer_id`, `status`) VALUES
(1, 11, 1, '2025-05-12 23:54:12', 45, 'order'),
(2, 11, 1, '2025-05-12 23:58:18', 46, 'order'),
(3, 11, 1, '2025-05-13 01:42:03', 47, 'order'),
(4, 11, 1, '2025-05-13 03:00:55', 48, 'order'),
(6, 11, 1, '2025-05-13 04:20:53', 62, 'Pending'),
(7, 11, 2, '2025-05-13 04:06:38', 63, 'Pending'),
(8, 7, 1, '2025-05-16 06:14:11', 64, 'Pending'),
(9, 8, 1, '2025-05-20 05:08:25', 65, 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `planting_plot`
--

CREATE TABLE `planting_plot` (
  `plot_id` int(11) NOT NULL,
  `seedling_variety_id` int(11) NOT NULL,
  `quantity_limit` int(111) NOT NULL,
  `current_quantity` int(11) DEFAULT 0,
  `date_planted` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('Growing','Ready for Sale','Retired') DEFAULT 'Growing'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `planting_plot`
--

INSERT INTO `planting_plot` (`plot_id`, `seedling_variety_id`, `quantity_limit`, `current_quantity`, `date_planted`, `status`) VALUES
(485, 7, 50, 0, '2025-05-13 15:54:34', 'Growing'),
(486, 7, 50, 0, '2025-05-13 15:54:34', 'Growing'),
(487, 7, 50, 0, '2025-05-13 15:54:34', 'Growing'),
(488, 7, 50, 0, '2025-05-13 15:54:34', 'Growing'),
(489, 7, 50, 0, '2025-05-13 15:54:34', 'Growing'),
(490, 7, 50, 0, '2025-05-13 15:54:34', 'Growing'),
(491, 7, 50, 0, '2025-05-13 15:54:34', 'Growing'),
(492, 7, 50, 0, '2025-05-13 15:54:34', 'Growing'),
(493, 7, 50, 0, '2025-05-13 15:54:34', 'Growing'),
(494, 7, 50, 0, '2025-05-13 15:54:34', 'Growing'),
(495, 7, 50, 0, '2025-05-13 15:54:34', 'Growing'),
(496, 7, 50, 0, '2025-05-13 15:54:34', 'Growing'),
(497, 7, 50, 1, '2025-05-13 15:54:34', 'Growing'),
(498, 7, 50, 1, '2025-05-13 15:54:34', 'Growing'),
(499, 7, 50, 1, '2025-05-13 15:54:34', 'Growing'),
(500, 7, 50, 1, '2025-05-13 15:54:34', 'Growing'),
(501, 7, 50, 1, '2025-05-13 15:54:34', 'Growing'),
(502, 7, 50, 1, '2025-05-13 15:54:34', 'Growing'),
(503, 7, 50, 1, '2025-05-13 15:54:34', 'Growing'),
(504, 7, 50, 1, '2025-05-13 15:54:34', 'Growing'),
(505, 7, 50, 1, '2025-05-13 15:54:34', 'Growing'),
(506, 7, 50, 1, '2025-05-13 15:54:34', 'Growing'),
(507, 7, 50, 1, '2025-05-13 15:54:34', 'Growing'),
(508, 7, 50, 1, '2025-05-13 15:54:34', 'Growing'),
(509, 7, 50, 1, '2025-05-13 15:54:34', 'Growing'),
(510, 7, 50, 1, '2025-05-13 15:54:34', 'Growing'),
(511, 7, 50, 1, '2025-05-13 15:54:34', 'Growing'),
(512, 7, 50, 1, '2025-05-13 15:54:34', 'Growing'),
(513, 7, 50, 1, '2025-05-13 15:54:34', 'Growing'),
(514, 7, 50, 1, '2025-05-13 15:54:34', 'Growing'),
(515, 7, 50, 1, '2025-05-13 15:54:34', 'Growing'),
(516, 7, 50, 1, '2025-05-13 15:54:34', 'Growing'),
(517, 7, 50, 1, '2025-05-13 15:54:34', 'Growing'),
(518, 7, 50, 1, '2025-05-13 15:54:34', 'Growing'),
(519, 7, 50, 1, '2025-05-13 15:54:34', 'Growing'),
(520, 7, 50, 1, '2025-05-13 15:54:34', 'Growing'),
(521, 7, 50, 1, '2025-05-13 15:54:34', 'Growing'),
(522, 7, 50, 1, '2025-05-13 15:54:34', 'Growing'),
(523, 7, 50, 1, '2025-05-13 15:54:34', 'Growing'),
(524, 7, 50, 1, '2025-05-13 15:54:34', 'Growing'),
(525, 7, 50, 1, '2025-05-13 15:54:34', 'Growing'),
(526, 7, 50, 1, '2025-05-13 15:54:34', 'Growing'),
(527, 7, 50, 1, '2025-05-13 15:54:34', 'Growing'),
(528, 7, 50, 1, '2025-05-13 15:54:34', 'Growing'),
(529, 7, 50, 1, '2025-05-13 15:54:34', 'Growing'),
(530, 7, 50, 1, '2025-05-13 15:54:34', 'Growing'),
(531, 7, 50, 1, '2025-05-13 15:54:34', 'Growing'),
(532, 7, 50, 1, '2025-05-13 15:54:34', 'Growing'),
(533, 7, 50, 1, '2025-05-13 15:54:34', 'Growing'),
(534, 7, 50, 1, '2025-05-13 15:54:34', 'Growing'),
(535, 8, 50, 0, '2025-05-14 05:18:35', 'Growing'),
(536, 8, 50, 0, '2025-05-14 05:18:35', 'Growing'),
(537, 8, 50, 0, '2025-05-14 05:18:35', 'Growing'),
(538, 8, 50, 0, '2025-05-14 05:18:35', 'Growing'),
(539, 8, 50, 0, '2025-05-14 05:18:35', 'Growing'),
(540, 8, 50, 0, '2025-05-14 05:18:35', 'Growing'),
(541, 8, 50, 0, '2025-05-14 05:18:35', 'Growing'),
(542, 8, 50, 0, '2025-05-14 05:18:35', 'Growing'),
(543, 8, 50, 0, '2025-05-14 05:18:35', 'Growing'),
(544, 8, 50, 0, '2025-05-14 05:18:35', 'Growing'),
(545, 8, 50, 0, '2025-05-14 05:18:35', 'Growing'),
(546, 8, 50, 0, '2025-05-14 05:18:35', 'Growing'),
(547, 8, 50, 0, '2025-05-14 05:18:35', 'Growing'),
(548, 8, 50, 0, '2025-05-14 05:18:35', 'Growing'),
(549, 8, 50, 0, '2025-05-14 05:18:35', 'Growing'),
(550, 8, 50, 0, '2025-05-14 05:18:35', 'Growing'),
(551, 8, 50, 0, '2025-05-14 05:18:35', 'Growing'),
(552, 8, 50, 0, '2025-05-14 05:18:35', 'Growing'),
(553, 8, 50, 0, '2025-05-14 05:18:35', 'Growing'),
(554, 8, 50, 0, '2025-05-14 05:18:35', 'Growing'),
(555, 8, 50, 0, '2025-05-14 05:18:35', 'Growing'),
(556, 8, 50, 0, '2025-05-14 05:18:35', 'Growing'),
(557, 8, 50, 0, '2025-05-14 05:18:35', 'Growing'),
(558, 8, 50, 0, '2025-05-14 05:18:35', 'Growing'),
(559, 8, 50, 0, '2025-05-14 05:18:35', 'Growing'),
(560, 8, 50, 0, '2025-05-14 05:18:35', 'Growing'),
(561, 8, 50, 0, '2025-05-14 05:18:35', 'Growing'),
(562, 8, 50, 0, '2025-05-14 05:18:35', 'Growing'),
(563, 8, 50, 0, '2025-05-14 05:18:35', 'Growing'),
(564, 8, 50, 0, '2025-05-14 05:18:35', 'Growing'),
(565, 8, 50, 0, '2025-05-14 05:18:35', 'Growing'),
(566, 8, 50, 0, '2025-05-14 05:18:35', 'Growing'),
(567, 8, 50, 1, '2025-05-14 05:18:35', 'Growing'),
(568, 8, 50, 1, '2025-05-14 05:18:35', 'Growing'),
(569, 8, 50, 1, '2025-05-14 05:18:35', 'Growing'),
(570, 8, 50, 1, '2025-05-14 05:18:35', 'Growing'),
(571, 8, 50, 1, '2025-05-14 05:18:35', 'Growing'),
(572, 8, 50, 1, '2025-05-14 05:18:35', 'Growing'),
(573, 8, 50, 1, '2025-05-14 05:18:35', 'Growing'),
(574, 8, 50, 1, '2025-05-14 05:18:35', 'Growing'),
(575, 8, 50, 1, '2025-05-14 05:18:35', 'Growing'),
(576, 8, 50, 1, '2025-05-14 05:18:35', 'Growing'),
(577, 8, 50, 1, '2025-05-14 05:18:35', 'Growing'),
(578, 8, 50, 1, '2025-05-14 05:18:35', 'Growing'),
(579, 8, 50, 1, '2025-05-14 05:18:35', 'Growing'),
(580, 8, 50, 1, '2025-05-14 05:18:35', 'Growing'),
(581, 8, 50, 1, '2025-05-14 05:18:35', 'Growing'),
(582, 8, 50, 1, '2025-05-14 05:18:35', 'Growing'),
(583, 8, 50, 1, '2025-05-14 05:18:35', 'Growing'),
(584, 8, 50, 1, '2025-05-14 05:18:35', 'Growing'),
(585, 9, 50, 1, '2025-05-14 13:54:26', 'Growing'),
(586, 9, 50, 1, '2025-05-14 13:54:26', 'Growing'),
(587, 9, 50, 1, '2025-05-14 13:54:26', 'Growing'),
(588, 9, 50, 1, '2025-05-14 13:54:26', 'Growing'),
(589, 9, 50, 1, '2025-05-14 13:54:26', 'Growing'),
(590, 9, 50, 1, '2025-05-14 13:54:26', 'Growing'),
(591, 9, 50, 1, '2025-05-14 13:54:26', 'Growing'),
(592, 9, 50, 1, '2025-05-14 13:54:26', 'Growing'),
(593, 9, 50, 1, '2025-05-14 13:54:26', 'Growing'),
(594, 9, 50, 1, '2025-05-14 13:54:26', 'Growing'),
(595, 9, 50, 1, '2025-05-14 13:54:26', 'Growing'),
(596, 9, 50, 1, '2025-05-14 13:54:26', 'Growing'),
(597, 9, 50, 1, '2025-05-14 13:54:26', 'Growing'),
(598, 9, 50, 1, '2025-05-14 13:54:26', 'Growing'),
(599, 9, 50, 1, '2025-05-14 13:54:26', 'Growing'),
(600, 9, 50, 1, '2025-05-14 13:54:26', 'Growing'),
(601, 9, 50, 1, '2025-05-14 13:54:26', 'Growing'),
(602, 9, 50, 1, '2025-05-14 13:54:26', 'Growing'),
(603, 9, 50, 1, '2025-05-14 13:54:26', 'Growing'),
(604, 9, 50, 1, '2025-05-14 13:54:26', 'Growing');

-- --------------------------------------------------------

--
-- Table structure for table `seedling_category`
--

CREATE TABLE `seedling_category` (
  `id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seedling_category`
--

INSERT INTO `seedling_category` (`id`, `category_name`) VALUES
(4, 'Fruit Tress'),
(5, 'Ornamental Trees');

-- --------------------------------------------------------

--
-- Table structure for table `seedling_fertilizer`
--

CREATE TABLE `seedling_fertilizer` (
  `id` int(11) NOT NULL,
  `fertilizer_name` varchar(100) NOT NULL,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seedling_fertilizer`
--

INSERT INTO `seedling_fertilizer` (`id`, `fertilizer_name`, `category_id`) VALUES
(9, 'UREA', 1),
(12, '10-32', 2);

-- --------------------------------------------------------

--
-- Table structure for table `seedling_info`
--

CREATE TABLE `seedling_info` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `seed_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seedling_info`
--

INSERT INTO `seedling_info` (`id`, `category_id`, `seed_name`) VALUES
(3, 4, 'Durian'),
(4, 4, 'Mangosteen'),
(5, 4, 'Mango'),
(6, 5, 'Narra');

-- --------------------------------------------------------

--
-- Table structure for table `seedling_inventory`
--

CREATE TABLE `seedling_inventory` (
  `inventory_id` int(11) NOT NULL,
  `seedling_variety_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `status` enum('Available','Sold') DEFAULT 'Available',
  `date_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seedling_inventory`
--

INSERT INTO `seedling_inventory` (`inventory_id`, `seedling_variety_id`, `quantity`, `status`, `date_added`) VALUES
(13, 7, -11, 'Available', '2025-04-07 10:59:39'),
(14, 8, 0, 'Available', '2025-04-07 23:12:29'),
(15, 9, 0, 'Available', '2025-04-08 04:45:31'),
(16, 8, 1, 'Available', '2025-04-08 22:48:37'),
(17, 7, 8, 'Available', '2025-04-10 07:24:18'),
(18, 7, 3, 'Available', '2025-04-10 07:25:12'),
(19, 10, 2, 'Available', '2025-04-16 02:35:54'),
(20, 7, 1, 'Available', '2025-05-12 23:21:46'),
(21, 11, 3, 'Available', '2025-05-12 23:29:32'),
(22, 8, 20, 'Available', '2025-05-14 03:56:24');

-- --------------------------------------------------------

--
-- Table structure for table `seedling_variety`
--

CREATE TABLE `seedling_variety` (
  `id` int(11) NOT NULL,
  `seed_id` int(11) DEFAULT NULL,
  `variety_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seedling_variety`
--

INSERT INTO `seedling_variety` (`id`, `seed_id`, `variety_name`, `price`) VALUES
(7, 3, 'Arancillo', 250.00),
(8, 3, 'Duyaya', 150.00),
(9, 3, 'Puyat', 200.00),
(10, 5, 'Cebu', 50.00),
(11, 5, 'Kalabaw', 50.00),
(12, 5, 'Apple Mango', 60.00),
(13, 6, 'Green Narra', 10.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_info`
--
ALTER TABLE `customer_info`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `delivery`
--
ALTER TABLE `delivery`
  ADD PRIMARY KEY (`delivery_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `employee_info`
--
ALTER TABLE `employee_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fertilizer_category`
--
ALTER TABLE `fertilizer_category`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `fertilizer_deductions`
--
ALTER TABLE `fertilizer_deductions`
  ADD PRIMARY KEY (`deduction_id`),
  ADD KEY `fertilizer_id` (`fertilizer_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `fertilizer_inventory`
--
ALTER TABLE `fertilizer_inventory`
  ADD PRIMARY KEY (`inventory_id`),
  ADD KEY `fertilizer_id` (`fertilizer_id`);

--
-- Indexes for table `fertilizer_schedule`
--
ALTER TABLE `fertilizer_schedule`
  ADD PRIMARY KEY (`schedule_id`),
  ADD KEY `seedling_id` (`seedling_id`),
  ADD KEY `fertilizer_id` (`fertilizer_id`);

--
-- Indexes for table `inventory_reports`
--
ALTER TABLE `inventory_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `plot_id` (`plot_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `planting_plot`
--
ALTER TABLE `planting_plot`
  ADD PRIMARY KEY (`plot_id`),
  ADD KEY `seedling_variety_id` (`seedling_variety_id`);

--
-- Indexes for table `seedling_category`
--
ALTER TABLE `seedling_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seedling_fertilizer`
--
ALTER TABLE `seedling_fertilizer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `seedling_info`
--
ALTER TABLE `seedling_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `seedling_inventory`
--
ALTER TABLE `seedling_inventory`
  ADD PRIMARY KEY (`inventory_id`),
  ADD KEY `seedling_variety_id` (`seedling_variety_id`);

--
-- Indexes for table `seedling_variety`
--
ALTER TABLE `seedling_variety`
  ADD PRIMARY KEY (`id`),
  ADD KEY `seed_id` (`seed_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `customer_info`
--
ALTER TABLE `customer_info`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `delivery`
--
ALTER TABLE `delivery`
  MODIFY `delivery_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `employee_info`
--
ALTER TABLE `employee_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `fertilizer_category`
--
ALTER TABLE `fertilizer_category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `fertilizer_deductions`
--
ALTER TABLE `fertilizer_deductions`
  MODIFY `deduction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `fertilizer_inventory`
--
ALTER TABLE `fertilizer_inventory`
  MODIFY `inventory_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `fertilizer_schedule`
--
ALTER TABLE `fertilizer_schedule`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `inventory_reports`
--
ALTER TABLE `inventory_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `planting_plot`
--
ALTER TABLE `planting_plot`
  MODIFY `plot_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=605;

--
-- AUTO_INCREMENT for table `seedling_category`
--
ALTER TABLE `seedling_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `seedling_fertilizer`
--
ALTER TABLE `seedling_fertilizer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `seedling_info`
--
ALTER TABLE `seedling_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `seedling_inventory`
--
ALTER TABLE `seedling_inventory`
  MODIFY `inventory_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `seedling_variety`
--
ALTER TABLE `seedling_variety`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `delivery`
--
ALTER TABLE `delivery`
  ADD CONSTRAINT `delivery_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

--
-- Constraints for table `fertilizer_deductions`
--
ALTER TABLE `fertilizer_deductions`
  ADD CONSTRAINT `fertilizer_deductions_ibfk_1` FOREIGN KEY (`fertilizer_id`) REFERENCES `seedling_fertilizer` (`id`),
  ADD CONSTRAINT `fertilizer_deductions_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `employee_info` (`id`);

--
-- Constraints for table `fertilizer_inventory`
--
ALTER TABLE `fertilizer_inventory`
  ADD CONSTRAINT `fertilizer_inventory_ibfk_1` FOREIGN KEY (`fertilizer_id`) REFERENCES `seedling_fertilizer` (`id`);

--
-- Constraints for table `fertilizer_schedule`
--
ALTER TABLE `fertilizer_schedule`
  ADD CONSTRAINT `fertilizer_schedule_ibfk_1` FOREIGN KEY (`seedling_id`) REFERENCES `seedling_variety` (`id`),
  ADD CONSTRAINT `fertilizer_schedule_ibfk_2` FOREIGN KEY (`fertilizer_id`) REFERENCES `seedling_fertilizer` (`id`);

--
-- Constraints for table `inventory_reports`
--
ALTER TABLE `inventory_reports`
  ADD CONSTRAINT `inventory_reports_ibfk_1` FOREIGN KEY (`plot_id`) REFERENCES `planting_plot` (`plot_id`) ON DELETE CASCADE;

--
-- Constraints for table `planting_plot`
--
ALTER TABLE `planting_plot`
  ADD CONSTRAINT `planting_plot_ibfk_1` FOREIGN KEY (`seedling_variety_id`) REFERENCES `seedling_variety` (`id`);

--
-- Constraints for table `seedling_fertilizer`
--
ALTER TABLE `seedling_fertilizer`
  ADD CONSTRAINT `seedling_fertilizer_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `fertilizer_category` (`category_id`) ON DELETE SET NULL;

--
-- Constraints for table `seedling_info`
--
ALTER TABLE `seedling_info`
  ADD CONSTRAINT `seedling_info_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `seedling_category` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `seedling_inventory`
--
ALTER TABLE `seedling_inventory`
  ADD CONSTRAINT `seedling_inventory_ibfk_1` FOREIGN KEY (`seedling_variety_id`) REFERENCES `seedling_variety` (`id`);

--
-- Constraints for table `seedling_variety`
--
ALTER TABLE `seedling_variety`
  ADD CONSTRAINT `seedling_variety_ibfk_1` FOREIGN KEY (`seed_id`) REFERENCES `seedling_info` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
