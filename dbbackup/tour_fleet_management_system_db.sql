-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 23, 2025 at 03:46 PM
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
-- Table structure for table `bus`
--

CREATE TABLE `bus` (
  `bus_id` int(10) NOT NULL,
  `category_id` int(10) NOT NULL,
  `vehicle_no` varchar(10) NOT NULL,
  `make` varchar(100) NOT NULL,
  `model` varchar(100) NOT NULL,
  `year` year(4) NOT NULL,
  `capacity` int(2) NOT NULL,
  `ac_available` char(1) NOT NULL,
  `service_interval_km` int(6) NOT NULL,
  `current_mileage_km` int(6) DEFAULT NULL,
  `current_mileage_as_at` datetime NOT NULL DEFAULT current_timestamp(),
  `last_service_mileage_km` int(6) NOT NULL,
  `service_interval_months` int(2) NOT NULL,
  `last_service_date` date NOT NULL,
  `bus_status` int(10) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bus`
--

INSERT INTO `bus` (`bus_id`, `category_id`, `vehicle_no`, `make`, `model`, `year`, `capacity`, `ac_available`, `service_interval_km`, `current_mileage_km`, `current_mileage_as_at`, `last_service_mileage_km`, `service_interval_months`, `last_service_date`, `bus_status`) VALUES
(1, 1, 'CAA-1234', 'Yutong', 'ZK6938HQ', '2022', 40, 'N', 15000, 44000, '2025-04-22 13:25:04', 38000, 6, '2024-11-15', 1),
(2, 2, 'NB-5678', 'Lanka Ashok Leyland', 'Viking', '2018', 54, 'N', 10000, 185000, '2025-04-21 22:57:00', 176500, 6, '2025-01-20', 1),
(3, 3, '62-9102', 'Toyota', 'Coaster', '2019', 29, 'Y', 12000, 94000, '2025-04-22 13:26:07', 89000, 5, '2024-09-01', 1),
(4, 2, 'CAB-1122', 'Tata', 'LP 909 / Starbus', '2020', 45, 'N', 10000, 115000, '2025-04-21 22:57:00', 108500, 6, '2025-02-10', 1),
(5, 3, 'NA-4567', 'Mitsubishi', 'Fuso Rosa', '2016', 25, 'Y', 10000, 250000, '2025-04-21 22:57:00', 241000, 4, '2024-05-01', 1),
(6, 2, 'NC-8899', 'Isuzu', 'Journey J', '2021', 42, 'N', 15000, 62000, '2025-04-21 22:57:00', 55000, 12, '2024-10-05', 1),
(7, 2, 'PE-1111', 'Lanka Ashok Leyland', 'Viking', '2017', 52, 'N', 10000, 210000, '2025-04-21 22:57:00', 201000, 6, '2025-03-01', -1),
(8, 1, 'CAC-8888', 'Yutong', 'ZK6122H', '2023', 45, 'Y', 20000, 35000, '2025-04-21 22:57:00', 20000, 12, '2024-10-10', 1),
(9, 3, 'NB-0123', 'Toyota', 'Coaster', '2021', 29, 'Y', 12000, 55000, '2025-04-21 22:57:00', 48000, 12, '2025-02-28', 1),
(10, 2, 'PA-9900', 'Tata', 'Marcopolo', '2019', 48, 'N', 10000, 150000, '2025-04-21 22:57:00', 141000, 6, '2024-12-15', -1),
(11, 3, 'PC-5566', 'Mitsubishi', 'Fuso Rosa', '2018', 25, 'Y', 10000, 130000, '2025-04-21 22:57:00', 122000, 12, '2024-08-20', -1),
(12, 2, 'CAD-5005', 'Isuzu', 'Journey J', '2022', 40, 'Y', 15000, 48000, '2025-04-21 22:57:00', 39000, 8, '2024-11-30', 1),
(13, 1, 'PE-7733', 'Hino', 'AK / Liesse', '2017', 35, 'Y', 15000, 195000, '2025-04-21 22:57:00', 181000, 6, '2025-01-05', -1),
(16, 2, 'NC-1212', 'Lanka Ashok Leyland', 'Viking', '2014', 49, 'N', 7000, 37000, '2025-04-22 10:47:01', 35000, 4, '2025-04-01', 1),
(17, 3, 'ABC-1527', 'Toyota', 'Coaster', '2019', 35, 'Y', 5000, 25000, '2025-04-23 14:07:44', 20000, 3, '2025-04-23', 1);

-- --------------------------------------------------------

--
-- Table structure for table `bus_category`
--

CREATE TABLE `bus_category` (
  `category_id` int(10) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `category_description` text DEFAULT NULL,
  `category_status` int(10) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bus_category`
--

INSERT INTO `bus_category` (`category_id`, `category_name`, `category_description`, `category_status`) VALUES
(1, 'Luxury', NULL, 1),
(2, 'Standard', NULL, 1),
(3, 'Mini Bus', NULL, 1);

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
(1, 1),
(1, 3),
(1, 5),
(2, 1),
(2, 3),
(2, 5),
(3, 1),
(3, 3),
(3, 5),
(4, 1),
(4, 5),
(5, 1),
(5, 5),
(6, 1),
(6, 3),
(6, 5),
(7, 1),
(7, 3),
(8, 1),
(9, 1),
(9, 5),
(10, 1),
(10, 3),
(11, 1),
(11, 3),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(24, 8),
(25, 1),
(26, 1),
(26, 7),
(27, 1),
(27, 7),
(28, 1),
(28, 7),
(29, 1),
(29, 7),
(30, 1),
(30, 7),
(31, 1),
(31, 7),
(32, 7),
(33, 7),
(34, 7),
(35, 1),
(35, 7),
(36, 1),
(36, 3),
(36, 5),
(37, 1),
(37, 3),
(37, 5),
(38, 1),
(38, 5),
(39, 1),
(39, 3),
(39, 5),
(40, 1),
(41, 1),
(42, 1),
(43, 1),
(44, 1),
(45, 1),
(46, 1),
(47, 1),
(48, 1);

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `login_id` int(10) NOT NULL,
  `login_username` varchar(255) NOT NULL,
  `login_password` text NOT NULL,
  `user_id` int(10) NOT NULL,
  `login_status` int(10) NOT NULL DEFAULT 1,
  `otp` text DEFAULT NULL,
  `otp_expiry` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`login_id`, `login_username`, `login_password`, `user_id`, `login_status`, `otp`, `otp_expiry`) VALUES
(1, '1', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 1, 1, '618788', '2025-04-16 15:45:44'),
(4, 'clintb@st.lk', '6abed34e62b96bc30d945cdb8cb39c4a4af7fd29', 3, 2, '545684', '2025-04-18 11:06:27'),
(6, 'stever@st.lk', '35fec01bf369d08599826e05d98e8c51acad1824', 5, 1, '', '0000-00-00 00:00:00'),
(8, 'tonys@st.lk', '35fec01bf369d08599826e05d98e8c51acad1824', 7, 1, '', '0000-00-00 00:00:00'),
(9, 'natashar@st.lk', '4b6c812cf500b82cece3de2162f037b4d99244dd', 8, 1, '', '0000-00-00 00:00:00');

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
(6, 'Bus management', 'busmanagement.png', 'bus-management.php', 1),
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
(1, 'Pooraka', 'Hasendra', '1998-01-08', '990080836V', 1, '', 'hasendra@st.lk', 1),
(3, 'Clint', 'Barton', '2025-02-12', '999999999V', 4, '1742137423_userimage3.jpg', 'clint@st.lk', 1),
(5, 'Steve', 'Rogers', '1996-01-25', '960250236V', 3, '', 'steve@st.lk', 1),
(7, 'Tony', 'Stark', '1996-12-15', '199512347521', 5, '1742137478_userimage5.jpg', 'tony@st.lk', 1),
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
(78, 1, '0736415732', 8),
(79, 2, '0112843951', 8),
(106, 1, '0734351355', 5),
(107, 2, '0112008888', 5),
(122, 1, '0779535000', 1),
(123, 2, '0114006319', 1),
(124, 1, '0772456456', 7),
(125, 2, '0312243581', 7),
(126, 1, '0778810839', 3),
(127, 2, '0112729729', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bus`
--
ALTER TABLE `bus`
  ADD PRIMARY KEY (`bus_id`);

--
-- Indexes for table `bus_category`
--
ALTER TABLE `bus_category`
  ADD PRIMARY KEY (`category_id`);

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
-- AUTO_INCREMENT for table `bus`
--
ALTER TABLE `bus`
  MODIFY `bus_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `bus_category`
--
ALTER TABLE `bus_category`
  MODIFY `category_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `function`
--
ALTER TABLE `function`
  MODIFY `function_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `login_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

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
  MODIFY `user_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `user_contact`
--
ALTER TABLE `user_contact`
  MODIFY `contact_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
