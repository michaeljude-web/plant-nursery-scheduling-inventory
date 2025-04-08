-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 08, 2025 at 06:59 AM
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
(18, 'Tom', 'Isulan Sultan Kudarat', '09073737822', '2025-04-08 03:43:57');

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
(2, 'Michael', 'Jude', 20, '09070101933', 'Banga', 'Sales Team', 'michaeljude', '$2y$10$tKA7xnX9agV5QuU4Pr5ITO.ODVJ3UhbXUWi6P6i3BE94r46.HBl6a');

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
(7, 175, '2025-04-08 06:07:33', 'Seedlings damaged during transport', 'uploads/1744085253_Damping-off-625507793.jpg', 1),
(8, 176, '2025-04-08 06:07:33', 'Seedlings damaged during transport', 'uploads/1744085253_Damping-off-625507793.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `planting_plot`
--

CREATE TABLE `planting_plot` (
  `plot_id` int(11) NOT NULL,
  `seedling_variety_id` int(11) NOT NULL,
  `quantity_limit` int(111) NOT NULL,
  `current_quantity` int(11) DEFAULT 0,
  `date_planted` date NOT NULL DEFAULT curdate(),
  `status` enum('Growing','Ready for Sale','Retired') DEFAULT 'Growing'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `planting_plot`
--

INSERT INTO `planting_plot` (`plot_id`, `seedling_variety_id`, `quantity_limit`, `current_quantity`, `date_planted`, `status`) VALUES
(155, 7, 50, 0, '2025-04-07', 'Growing'),
(156, 7, 50, 0, '2025-04-07', 'Growing'),
(157, 7, 50, 0, '2025-04-07', 'Growing'),
(158, 7, 50, 0, '2025-04-07', 'Growing'),
(159, 7, 50, 0, '2025-04-07', 'Growing'),
(160, 7, 50, 0, '2025-04-07', 'Growing'),
(161, 7, 50, 0, '2025-04-07', 'Growing'),
(162, 7, 50, 0, '2025-04-07', 'Growing'),
(163, 7, 50, 0, '2025-04-07', 'Growing'),
(164, 7, 50, 0, '2025-04-07', 'Growing'),
(165, 7, 50, 0, '2025-04-07', 'Growing'),
(166, 7, 50, 0, '2025-04-07', 'Growing'),
(167, 7, 50, 0, '2025-04-07', 'Growing'),
(168, 7, 50, 0, '2025-04-07', 'Growing'),
(169, 7, 50, 0, '2025-04-07', 'Growing'),
(170, 7, 50, 0, '2025-04-07', 'Growing'),
(171, 7, 50, 0, '2025-04-07', 'Growing'),
(172, 7, 50, 0, '2025-04-07', 'Growing'),
(173, 7, 50, 0, '2025-04-07', 'Growing'),
(174, 7, 50, 0, '2025-04-07', 'Growing'),
(175, 7, 50, 0, '2025-04-07', 'Growing'),
(176, 7, 50, 0, '2025-04-07', 'Growing'),
(177, 7, 50, 1, '2025-04-07', 'Growing'),
(178, 7, 50, 1, '2025-04-07', 'Growing'),
(179, 7, 50, 1, '2025-04-07', 'Growing'),
(180, 7, 50, 1, '2025-04-07', 'Growing'),
(181, 7, 50, 1, '2025-04-07', 'Growing'),
(182, 7, 50, 1, '2025-04-07', 'Growing'),
(183, 7, 50, 1, '2025-04-07', 'Growing'),
(184, 7, 50, 1, '2025-04-07', 'Growing'),
(185, 7, 50, 1, '2025-04-07', 'Growing'),
(186, 7, 50, 1, '2025-04-07', 'Growing'),
(187, 7, 50, 1, '2025-04-07', 'Growing'),
(188, 7, 50, 1, '2025-04-07', 'Growing'),
(189, 7, 50, 1, '2025-04-07', 'Growing'),
(190, 7, 50, 1, '2025-04-07', 'Growing'),
(191, 7, 50, 1, '2025-04-07', 'Growing'),
(192, 7, 50, 1, '2025-04-07', 'Growing'),
(193, 7, 50, 1, '2025-04-07', 'Growing'),
(194, 7, 50, 1, '2025-04-07', 'Growing'),
(195, 7, 50, 1, '2025-04-07', 'Growing'),
(196, 7, 50, 1, '2025-04-07', 'Growing'),
(197, 7, 50, 1, '2025-04-07', 'Growing'),
(198, 7, 50, 1, '2025-04-07', 'Growing'),
(199, 7, 50, 1, '2025-04-07', 'Growing'),
(200, 7, 50, 1, '2025-04-07', 'Growing'),
(201, 7, 50, 1, '2025-04-07', 'Growing'),
(202, 7, 50, 1, '2025-04-07', 'Growing'),
(203, 7, 50, 1, '2025-04-07', 'Growing'),
(204, 7, 50, 1, '2025-04-07', 'Growing'),
(205, 8, 50, 0, '2025-04-08', 'Growing'),
(206, 8, 50, 0, '2025-04-08', 'Growing'),
(207, 8, 50, 0, '2025-04-08', 'Growing'),
(208, 8, 50, 0, '2025-04-08', 'Growing'),
(209, 8, 50, 0, '2025-04-08', 'Growing'),
(210, 8, 50, 0, '2025-04-08', 'Growing'),
(211, 8, 50, 0, '2025-04-08', 'Growing'),
(212, 8, 50, 0, '2025-04-08', 'Growing'),
(213, 8, 50, 0, '2025-04-08', 'Growing'),
(214, 8, 50, 0, '2025-04-08', 'Growing'),
(215, 8, 50, 0, '2025-04-08', 'Growing'),
(216, 8, 50, 0, '2025-04-08', 'Growing'),
(217, 8, 50, 0, '2025-04-08', 'Growing'),
(218, 8, 50, 0, '2025-04-08', 'Growing'),
(219, 8, 50, 0, '2025-04-08', 'Growing'),
(220, 8, 50, 0, '2025-04-08', 'Growing'),
(221, 8, 50, 0, '2025-04-08', 'Growing'),
(222, 8, 50, 0, '2025-04-08', 'Growing'),
(223, 8, 50, 1, '2025-04-08', 'Growing'),
(224, 8, 50, 1, '2025-04-08', 'Growing'),
(225, 8, 50, 1, '2025-04-08', 'Growing'),
(226, 8, 50, 1, '2025-04-08', 'Growing'),
(227, 8, 50, 1, '2025-04-08', 'Growing'),
(228, 8, 50, 1, '2025-04-08', 'Growing'),
(229, 8, 50, 1, '2025-04-08', 'Growing'),
(230, 8, 50, 1, '2025-04-08', 'Growing'),
(231, 8, 50, 1, '2025-04-08', 'Growing'),
(232, 8, 50, 1, '2025-04-08', 'Growing'),
(233, 8, 50, 1, '2025-04-08', 'Growing'),
(234, 8, 50, 1, '2025-04-08', 'Growing'),
(235, 9, 50, 0, '2025-04-08', 'Growing'),
(236, 9, 50, 0, '2025-04-08', 'Growing'),
(237, 9, 50, 0, '2025-04-08', 'Growing'),
(238, 9, 50, 0, '2025-04-08', 'Growing'),
(239, 9, 50, 1, '2025-04-08', 'Growing'),
(240, 9, 50, 1, '2025-04-08', 'Growing'),
(241, 9, 50, 1, '2025-04-08', 'Growing'),
(242, 9, 50, 1, '2025-04-08', 'Growing'),
(243, 9, 50, 1, '2025-04-08', 'Growing'),
(244, 9, 50, 1, '2025-04-08', 'Growing'),
(245, 9, 50, 1, '2025-04-08', 'Growing'),
(246, 9, 50, 1, '2025-04-08', 'Growing'),
(247, 9, 50, 1, '2025-04-08', 'Growing'),
(248, 9, 50, 1, '2025-04-08', 'Growing'),
(249, 9, 50, 1, '2025-04-08', 'Growing'),
(250, 9, 50, 1, '2025-04-08', 'Growing'),
(251, 9, 50, 1, '2025-04-08', 'Growing'),
(252, 9, 50, 1, '2025-04-08', 'Growing'),
(253, 9, 50, 1, '2025-04-08', 'Growing'),
(254, 9, 50, 1, '2025-04-08', 'Growing'),
(255, 9, 50, 1, '2025-04-08', 'Growing'),
(256, 9, 50, 1, '2025-04-08', 'Growing'),
(257, 9, 50, 1, '2025-04-08', 'Growing'),
(258, 9, 50, 1, '2025-04-08', 'Growing'),
(259, 9, 50, 1, '2025-04-08', 'Growing'),
(260, 9, 50, 1, '2025-04-08', 'Growing'),
(261, 9, 50, 1, '2025-04-08', 'Growing'),
(262, 9, 50, 1, '2025-04-08', 'Growing'),
(263, 9, 50, 1, '2025-04-08', 'Growing'),
(264, 9, 50, 1, '2025-04-08', 'Growing'),
(265, 9, 50, 1, '2025-04-08', 'Growing'),
(266, 9, 50, 1, '2025-04-08', 'Growing'),
(267, 9, 50, 1, '2025-04-08', 'Growing'),
(268, 9, 50, 1, '2025-04-08', 'Growing'),
(269, 9, 50, 1, '2025-04-08', 'Growing'),
(270, 9, 50, 1, '2025-04-08', 'Growing'),
(271, 9, 50, 1, '2025-04-08', 'Growing'),
(272, 9, 50, 1, '2025-04-08', 'Growing'),
(273, 9, 50, 1, '2025-04-08', 'Growing'),
(274, 9, 50, 1, '2025-04-08', 'Growing'),
(275, 9, 50, 1, '2025-04-08', 'Growing'),
(276, 9, 50, 1, '2025-04-08', 'Growing'),
(277, 9, 50, 1, '2025-04-08', 'Growing'),
(278, 9, 50, 1, '2025-04-08', 'Growing'),
(279, 9, 50, 1, '2025-04-08', 'Growing'),
(280, 9, 50, 1, '2025-04-08', 'Growing'),
(281, 9, 50, 1, '2025-04-08', 'Growing'),
(282, 9, 50, 1, '2025-04-08', 'Growing'),
(283, 9, 50, 1, '2025-04-08', 'Growing'),
(284, 9, 50, 1, '2025-04-08', 'Growing');

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
-- Table structure for table `seedling_for_sale`
--

CREATE TABLE `seedling_for_sale` (
  `sale_id` int(11) NOT NULL,
  `seedling_variety_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  `customer_id` int(11) NOT NULL,
  `status` enum('Pending','Delivered','Cancelled') DEFAULT 'Pending',
  `reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seedling_for_sale`
--

INSERT INTO `seedling_for_sale` (`sale_id`, `seedling_variety_id`, `quantity`, `date_added`, `customer_id`, `status`, `reason`) VALUES
(8, 7, 1, '2025-04-07 12:32:43', 16, 'Delivered', NULL),
(9, 7, 2, '2025-04-07 21:21:51', 17, 'Cancelled', 'wala'),
(10, 7, 1, '2025-04-08 03:43:57', 18, 'Pending', NULL);

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
(4, 4, 'Mangosteen');

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
(13, 7, 13, 'Available', '2025-04-07 10:59:39'),
(14, 8, 18, 'Available', '2025-04-07 23:12:29'),
(15, 9, 4, 'Available', '2025-04-08 04:45:31');

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
(9, 3, 'Puyat', 200.00);

-- --------------------------------------------------------

--
-- Table structure for table `stock_in`
--

CREATE TABLE `stock_in` (
  `stock_in_id` int(11) NOT NULL,
  `seedling_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `received_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Indexes for table `employee_info`
--
ALTER TABLE `employee_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory_reports`
--
ALTER TABLE `inventory_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `plot_id` (`plot_id`);

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
-- Indexes for table `seedling_for_sale`
--
ALTER TABLE `seedling_for_sale`
  ADD PRIMARY KEY (`sale_id`),
  ADD KEY `seedling_variety_id` (`seedling_variety_id`),
  ADD KEY `customer_id` (`customer_id`);

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
-- Indexes for table `stock_in`
--
ALTER TABLE `stock_in`
  ADD PRIMARY KEY (`stock_in_id`),
  ADD KEY `seedling_id` (`seedling_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_info`
--
ALTER TABLE `customer_info`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `employee_info`
--
ALTER TABLE `employee_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `inventory_reports`
--
ALTER TABLE `inventory_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `planting_plot`
--
ALTER TABLE `planting_plot`
  MODIFY `plot_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=285;

--
-- AUTO_INCREMENT for table `seedling_category`
--
ALTER TABLE `seedling_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `seedling_for_sale`
--
ALTER TABLE `seedling_for_sale`
  MODIFY `sale_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `seedling_info`
--
ALTER TABLE `seedling_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `seedling_inventory`
--
ALTER TABLE `seedling_inventory`
  MODIFY `inventory_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `seedling_variety`
--
ALTER TABLE `seedling_variety`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `stock_in`
--
ALTER TABLE `stock_in`
  MODIFY `stock_in_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints for dumped tables
--

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
-- Constraints for table `seedling_for_sale`
--
ALTER TABLE `seedling_for_sale`
  ADD CONSTRAINT `seedling_for_sale_ibfk_1` FOREIGN KEY (`seedling_variety_id`) REFERENCES `seedling_variety` (`id`),
  ADD CONSTRAINT `seedling_for_sale_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customer_info` (`customer_id`) ON DELETE CASCADE;

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