-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 30, 2024 at 07:32 PM
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
-- Database: `tour_fleet_management_system_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `login_id` int(11) NOT NULL,
  `login_username` varchar(80) NOT NULL,
  `login_password` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `login_status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`login_id`, `login_username`, `login_password`, `user_id`, `login_status`) VALUES
(1, 'kamal@esoft.lk', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `module`
--

CREATE TABLE `module` (
  `module_id` int(11) NOT NULL,
  `module_name` varchar(30) NOT NULL,
  `module_icon` varchar(50) NOT NULL,
  `module_url` text NOT NULL,
  `module_status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `module`
--

INSERT INTO `module` (`module_id`, `module_name`, `module_icon`, `module_url`, `module_status`) VALUES
(1, ' Booking management', 'test.png', 'booking.php', 1),
(2, ' Tours and tracking', 'test.png', 'tours.php', 1),
(3, ' Tender Management', 'test.png', 'tender.php', 1),
(4, 'Purchasing', 'test.png', 'purchasing.php', 1),
(5, ' Spare parts management', 'test.png', 'spareparts.php', 1),
(6, 'Bus management', 'test.png', 'busmanagement.php', 1),
(7, ' Bus maintenance', 'test.png', 'busmaintenance.php', 1),
(8, 'Customer', 'test.png', 'customer.php', 1),
(9, ' Finance management', 'test.png', 'finance.php', 1),
(10, ' User management', 'test.png', 'user.php', 1);

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(20) NOT NULL,
  `role_status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`role_id`, `role_name`, `role_status`) VALUES
(1, 'Director', 1);

-- --------------------------------------------------------

--
-- Table structure for table `role_module`
--

CREATE TABLE `role_module` (
  `role_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role_module`
--

INSERT INTO `role_module` (`role_id`, `module_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `user_fname` varchar(20) NOT NULL,
  `user_lname` varchar(30) NOT NULL,
  `user_dob` date NOT NULL,
  `user_nic` varchar(15) NOT NULL,
  `user_role` int(11) NOT NULL,
  `user_image` varchar(80) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_fname`, `user_lname`, `user_dob`, `user_nic`, `user_role`, `user_image`, `user_email`, `user_status`) VALUES
(1, 'Kamal', 'Perera', '2024-11-05', '956421442V', 1, '', 'kamal@esoft.lk', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`login_id`),
  ADD UNIQUE KEY `login_username` (`login_username`);

--
-- Indexes for table `module`
--
ALTER TABLE `module`
  ADD PRIMARY KEY (`module_id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `role_module`
--
ALTER TABLE `role_module`
  ADD PRIMARY KEY (`role_id`,`module_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `login_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `module`
--
ALTER TABLE `module`
  MODIFY `module_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
