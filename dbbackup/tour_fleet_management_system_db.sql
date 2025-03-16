-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 16, 2025 at 04:07 PM
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
-- Database: `tour_fleet_management_system_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `function`
--

CREATE TABLE `function` (
  `function_id` int(10) NOT NULL,
  `function_name` varchar(50) NOT NULL,
  `module_id` int(10) NOT NULL,
  `function_status` int(10) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `function`
--

INSERT INTO `function` (`function_id`, `function_name`, `module_id`, `function_status`) VALUES
(1, 'View Generated Quotations', 1, 1),
(2, 'Generate Customer Quotations', 1, 1),
(3, 'Generate Invoice', 1, 1),
(4, 'View Pending Invoices', 1, 1),
(5, 'Send payment reminders', 1, 1),
(6, 'Accept Customer Payments', 1, 1),
(7, 'Track Ongoing Tour Bus Location', 2, 1),
(8, 'View Tours', 2, 1),
(9, 'Create Tours', 2, 1),
(10, 'Update Tours', 2, 1),
(11, 'Remove Tours', 2, 1),
(12, 'Create Tender', 3, 1),
(13, 'View Tender', 3, 1),
(14, 'Maintain Tender Status', 3, 1),
(15, 'Add Bids', 3, 1),
(16, 'Award Bids', 3, 1),
(17, 'View Awarded Bids', 4, 1),
(18, 'Generate Purchase Order', 4, 1),
(19, 'Add Supplier Invoice', 4, 1),
(20, 'View Purchase Orders', 4, 1),
(21, 'Approve/Reject Purchase Orders', 4, 1),
(22, 'Add Goods Received Notice (GRN)', 5, 1),
(23, 'View Spare Parts', 5, 1),
(24, 'Issue Spare Parts', 5, 1),
(25, 'Remove Spare Parts', 5, 1),
(26, 'View Bus Details', 6, 1),
(27, 'Remove Bus', 6, 1),
(28, 'Update Bus Details', 6, 1),
(29, 'Generate Bus Performance Report', 6, 1),
(30, 'Register Bus', 6, 1),
(31, 'View Service Details', 7, 1),
(32, 'Trigger Buses For Services', 7, 1),
(33, 'Manage Service Station Details', 7, 1),
(34, 'Maintain Bus Inspection Status', 7, 1),
(35, 'Manage Inspection Checklist', 7, 1),
(36, 'View Customer', 8, 1),
(37, 'Update Customer Details', 8, 1),
(38, 'Register Customer', 8, 1),
(39, 'Remove Customer', 8, 1),
(40, 'Generate Financial Reports', 9, 1),
(41, 'View Pending Supplier Payments', 9, 1),
(42, 'Make Supplier Payments', 9, 1),
(43, 'View Customer Invoices', 9, 1),
(44, 'Validate Paid Invoices', 9, 1),
(45, 'View User', 10, 1),
(46, 'Add User', 10, 1),
(47, 'Update User', 10, 1),
(48, 'Generate User Reports', 10, 1);

-- --------------------------------------------------------

--
-- Table structure for table `function_user`
--

CREATE TABLE `function_user` (
  `function_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `function_user`
--

INSERT INTO `function_user` (`function_id`, `user_id`) VALUES
(1, 5),
(2, 5),
(3, 5),
(4, 5),
(5, 5),
(6, 5),
(12, 3),
(13, 3),
(14, 3),
(16, 3),
(22, 4),
(22, 7),
(22, 8),
(23, 4),
(23, 8),
(24, 4),
(24, 8),
(25, 7),
(36, 5),
(37, 5),
(38, 5),
(39, 5),
(40, 1),
(43, 1);

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `login_id` int(10) NOT NULL,
  `login_username` varchar(255) NOT NULL,
  `login_password` text NOT NULL,
  `user_id` int(10) NOT NULL,
  `login_status` int(10) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`login_id`, `login_username`, `login_password`, `user_id`, `login_status`) VALUES
(1, 'hasendra@skylinetours.lk', '51eac6b471a284d3341d8c0c63d0f1a286262a18', 1, 1),
(4, 'clint@st.lk', '52fbd35f82a73848807154640259deb53f8f4f75', 3, 1),
(5, 'tvseriesmail4@gmail.com', '32bbc738414b75dc9685218bda9932c520a27afd', 4, 1),
(6, 'steve@st.lk', 'b8b79503ca8995225bd8763591a462f7a13d2bf3', 5, 1),
(8, 'tony@st.lk', '951a7754b460aeda0b333899faa93ee009a8c624', 7, 1),
(9, 'natasha@st.lk', '5b2395136ceda7a531c8737b1693f853065b6aac', 8, 1);

-- --------------------------------------------------------

--
-- Table structure for table `module`
--

CREATE TABLE `module` (
  `module_id` int(10) NOT NULL,
  `module_name` varchar(30) NOT NULL,
  `module_icon` varchar(50) NOT NULL,
  `module_url` text NOT NULL,
  `module_status` int(10) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `module`
--

INSERT INTO `module` (`module_id`, `module_name`, `module_icon`, `module_url`, `module_status`) VALUES
(1, 'Booking management', 'booking.png', 'booking.php', 1),
(2, 'Tours and tracking', 'tours.png', 'tours.php', 1),
(3, 'Tender management', 'tender.png', 'tender.php', 1),
(4, 'Purchasing', 'purchasing.png', 'purchasing.php', 1),
(5, 'Spare parts management', 'spareparts.png', 'spareparts.php', 1),
(6, 'Bus management', 'busmanagement.png', 'busmanagement.php', 1),
(7, 'Bus maintenance', 'busmaintenance.png', 'busmaintenance.php', 1),
(8, 'Customer', 'customer.png', 'customer.php', 1),
(9, 'Finance management', 'finance.png', 'finance.php', 1),
(10, 'User management', 'user.png', 'user.php', 1);

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `role_id` int(10) NOT NULL,
  `role_name` varchar(30) NOT NULL,
  `role_status` int(10) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`role_id`, `role_name`, `role_status`) VALUES
(1, 'Director', 1),
(2, 'Finance Officer', 1),
(3, 'Customer Service Officer', 1),
(4, 'Operations Manager', 1),
(5, 'Fleet Manager', 1),
(6, 'Service Technician', 1),
(7, 'Warehouse Controller', 1),
(8, 'Procurement Officer', 1);

-- --------------------------------------------------------

--
-- Table structure for table `role_module`
--

CREATE TABLE `role_module` (
  `role_id` int(10) NOT NULL,
  `module_id` int(10) NOT NULL
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
(1, 8),
(1, 9),
(1, 10),
(2, 4),
(2, 9),
(3, 1),
(3, 2),
(3, 8),
(4, 1),
(4, 2),
(4, 8),
(5, 6),
(5, 7),
(6, 7),
(7, 5),
(8, 3);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(10) NOT NULL,
  `user_fname` varchar(50) NOT NULL,
  `user_lname` varchar(50) NOT NULL,
  `user_dob` date NOT NULL,
  `user_nic` varchar(12) NOT NULL,
  `user_role` int(10) NOT NULL,
  `user_image` varchar(200) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_status` int(10) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_fname`, `user_lname`, `user_dob`, `user_nic`, `user_role`, `user_image`, `user_email`, `user_status`) VALUES
(1, 'Pooraka', 'Hasendra', '1998-01-08', '990080836V', 2, '', 'hasendra@skylinetours.lk', 1),
(3, 'Clint', 'Barton', '2025-02-12', '999999999V', 8, '1742137423_userimage3.jpg', 'clint@st.lk', 1),
(4, 'Shevan', 'Fernando', '2025-02-18', '999555777V', 7, '', 'tvseriesmail4@gmail.com', 1),
(5, 'Steve', 'Rogers', '1996-01-25', '960250236V', 3, '', 'steve@st.lk', 1),
(7, 'Tony', 'Stark', '1996-12-15', '963753148V', 7, '1742137478_userimage5.jpg', 'tony@st.lk', 1),
(8, 'Natasha', 'Romanov', '1999-03-16', '990362581V', 7, '1742135511_userimage4w.jpg', 'natasha@st.lk', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_contact`
--

CREATE TABLE `user_contact` (
  `contact_id` int(10) NOT NULL,
  `contact_type` int(10) NOT NULL,
  `contact_number` varchar(10) NOT NULL,
  `user_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_contact`
--

INSERT INTO `user_contact` (`contact_id`, `contact_type`, `contact_number`, `user_id`) VALUES
(1, 1, '0779530148', 1),
(2, 2, '0114006319', 1),
(4, 2, '0112008888', 5),
(46, 1, '0736415732', 8),
(47, 2, '0112843951', 8),
(50, 1, '0796385245', 7),
(51, 2, '0312243581', 7),
(52, 1, '0778810839', 3),
(53, 2, '0112729729', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `function`
--
ALTER TABLE `function`
  ADD PRIMARY KEY (`function_id`);

--
-- Indexes for table `function_user`
--
ALTER TABLE `function_user`
  ADD PRIMARY KEY (`function_id`,`user_id`);

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
-- Indexes for table `user_contact`
--
ALTER TABLE `user_contact`
  ADD PRIMARY KEY (`contact_id`),
  ADD UNIQUE KEY `unique_contact_type_per_userid` (`user_id`,`contact_type`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `function`
--
ALTER TABLE `function`
  MODIFY `function_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `login_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `module`
--
ALTER TABLE `module`
  MODIFY `module_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `role_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user_contact`
--
ALTER TABLE `user_contact`
  MODIFY `contact_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
