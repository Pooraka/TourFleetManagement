-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 24, 2025 at 01:48 AM
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
  `bus_status` int(10) NOT NULL DEFAULT 1,
  `removed_by` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bus`
--

INSERT INTO `bus` (`bus_id`, `category_id`, `vehicle_no`, `make`, `model`, `year`, `capacity`, `ac_available`, `service_interval_km`, `current_mileage_km`, `current_mileage_as_at`, `last_service_mileage_km`, `service_interval_months`, `last_service_date`, `bus_status`, `removed_by`) VALUES
(1, 1, 'CAA-1234', 'Yutong', 'ZK6938HQ', '2022', 40, 'N', 15000, 50000, '2025-06-19 12:50:46', 50000, 6, '2025-06-19', 1, NULL),
(2, 2, 'NB-5678', 'Lanka Ashok Leyland', 'Viking', '2018', 54, 'N', 10000, 186000, '2025-06-19 20:04:33', 186000, 6, '2025-06-19', 1, NULL),
(3, 3, '62-9102', 'Toyota', 'Coaster', '2019', 29, 'Y', 12000, 95000, '2025-04-25 14:18:59', 94001, 5, '2025-04-25', 1, NULL),
(4, 2, 'CAB-1122', 'Tata', 'LP 909 / Starbus', '2020', 45, 'N', 10000, 116000, '2025-06-23 02:15:00', 116000, 6, '2025-06-23', 1, NULL),
(5, 3, 'NA-4567', 'Mitsubishi', 'Fuso Rosa', '2016', 25, 'Y', 10000, 23000, '2025-04-28 10:35:00', 23000, 4, '2025-04-29', 1, NULL),
(6, 2, 'NC-8899', 'Isuzu', 'Journey J', '2021', 42, 'N', 15000, 2400, '2025-05-01 19:01:04', 2400, 12, '2025-05-07', 1, NULL),
(7, 2, 'PE-1111', 'Lanka Ashok Leyland', 'Viking', '2017', 52, 'N', 10000, 210000, '2025-04-21 22:57:00', 201000, 6, '2025-03-01', 1, NULL),
(8, 1, 'CAC-8888', 'Yutong', 'ZK6122H', '2023', 45, 'Y', 20000, 17000, '2025-06-23 02:12:33', 17000, 3, '2025-06-23', 1, NULL),
(9, 3, 'NB-0123', 'Toyota', 'Coaster', '2021', 29, 'Y', 12000, 55000, '2025-04-21 22:57:00', 48000, 12, '2025-02-28', 1, NULL),
(10, 2, 'PA-9900', 'Tata', 'Marcopolo', '2019', 48, 'N', 10000, 13257, '2025-06-23 12:01:51', 13257, 6, '2025-06-23', 1, NULL),
(11, 3, 'PC-5566', 'Mitsubishi', 'Fuso Rosa', '2018', 25, 'Y', 10000, 130000, '2025-04-21 22:57:00', 122000, 12, '2024-08-20', 1, NULL),
(12, 2, 'CAD-5005', 'Isuzu', 'Journey J', '2022', 40, 'Y', 15000, 40052, '2025-06-19 13:26:00', 40052, 1, '2025-06-19', -1, 3),
(13, 1, 'PE-7733', 'Hino', 'AK / Liesse', '2017', 35, 'Y', 15000, 195000, '2025-04-21 22:57:00', 181000, 6, '2025-01-05', 1, NULL),
(16, 2, 'NC-1212', 'Lanka Ashok Leyland', 'Viking', '2014', 49, 'N', 7000, 38000, '2025-05-18 01:50:58', 35000, 4, '2025-04-01', 1, NULL),
(17, 3, 'ABC-1527', 'Toyota', 'Coaster', '2019', 35, 'Y', 5000, 15748, '2025-05-07 12:49:36', 15748, 3, '2025-05-07', 1, NULL);

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
-- Table structure for table `bus_tour`
--

CREATE TABLE `bus_tour` (
  `bus_id` int(10) NOT NULL,
  `tour_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(10) NOT NULL,
  `customer_nic` varchar(12) NOT NULL,
  `customer_fname` varchar(50) NOT NULL,
  `customer_lname` varchar(50) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_status` int(10) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `customer_nic`, `customer_fname`, `customer_lname`, `customer_email`, `customer_status`) VALUES
(1, '791253595V', 'Kamal', 'Fernando', 'kamal@mail.com', 1),
(2, '965325141V', 'Jehan', 'Peter', 'peter@mail.com', 1),
(3, '942150889V', 'Nimal', 'Silva', 'nimal.s@email.com', 1),
(4, '199865401234', 'Fathima', 'Rizwan', 'fathima.r@email.com', 1);

-- --------------------------------------------------------

--
-- Table structure for table `customer_contact`
--

CREATE TABLE `customer_contact` (
  `contact_id` int(10) NOT NULL,
  `contact_type` int(10) NOT NULL,
  `contact_number` varchar(10) NOT NULL,
  `customer_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer_contact`
--

INSERT INTO `customer_contact` (`contact_id`, `contact_type`, `contact_number`, `customer_id`) VALUES
(12, 1, '0712347582', 1),
(13, 2, '0114006319', 1),
(14, 1, '0769542365', 2),
(15, 1, '0771234567', 3),
(16, 2, '0112589632', 3),
(17, 1, '0718956234', 4),
(18, 2, '0112258632', 4);

-- --------------------------------------------------------

--
-- Table structure for table `customer_invoice`
--

CREATE TABLE `customer_invoice` (
  `invoice_id` int(10) NOT NULL,
  `invoice_number` varchar(100) NOT NULL,
  `quotation_id` int(10) NOT NULL,
  `invoice_date` date NOT NULL,
  `invoice_amount` decimal(10,2) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `invoice_status` int(11) NOT NULL DEFAULT 1,
  `invoice_description` text DEFAULT NULL,
  `tour_start_date` date NOT NULL,
  `tour_end_date` date NOT NULL,
  `pickup_location` varchar(255) NOT NULL,
  `dropoff_location` varchar(255) NOT NULL,
  `round_trip_mileage` int(4) NOT NULL,
  `actual_fare` decimal(10,2) DEFAULT NULL,
  `actual_mileage` int(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer_invoice`
--

INSERT INTO `customer_invoice` (`invoice_id`, `invoice_number`, `quotation_id`, `invoice_date`, `invoice_amount`, `customer_id`, `invoice_status`, `invoice_description`, `tour_start_date`, `tour_end_date`, `pickup_location`, `dropoff_location`, `round_trip_mileage`, `actual_fare`, `actual_mileage`) VALUES
(1, 'inv1', 1, '2025-06-01', 75000.00, 1, 1, 'One day trip from Athurugiriya to Galle and back', '2025-06-12', '2025-06-13', 'Athurugiriya', 'Athurugiriya', 240, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customer_invoice_item`
--

CREATE TABLE `customer_invoice_item` (
  `item_id` int(10) NOT NULL,
  `invoice_id` int(10) NOT NULL,
  `category_id` int(10) NOT NULL,
  `quantity` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer_invoice_item`
--

INSERT INTO `customer_invoice_item` (`item_id`, `invoice_id`, `category_id`, `quantity`) VALUES
(1, 1, 1, 1),
(2, 1, 2, 1);

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
(4, 3),
(4, 5),
(5, 1),
(5, 3),
(5, 5),
(6, 1),
(6, 3),
(6, 5),
(7, 1),
(7, 3),
(8, 1),
(8, 3),
(9, 1),
(9, 3),
(9, 5),
(10, 1),
(10, 3),
(11, 1),
(11, 3),
(12, 1),
(12, 3),
(13, 1),
(13, 3),
(14, 1),
(14, 3),
(15, 1),
(15, 3),
(16, 1),
(16, 3),
(17, 1),
(17, 3),
(18, 1),
(18, 3),
(19, 1),
(19, 3),
(20, 1),
(20, 3),
(21, 1),
(21, 3),
(22, 1),
(23, 1),
(24, 1),
(24, 8),
(25, 1),
(26, 1),
(26, 3),
(26, 7),
(27, 1),
(27, 3),
(27, 7),
(28, 1),
(28, 3),
(28, 7),
(29, 1),
(29, 3),
(29, 7),
(30, 1),
(30, 3),
(30, 7),
(31, 1),
(31, 3),
(31, 7),
(32, 3),
(32, 7),
(33, 3),
(33, 7),
(34, 3),
(34, 7),
(35, 1),
(35, 3),
(35, 7),
(36, 1),
(36, 3),
(36, 5),
(37, 1),
(37, 3),
(37, 5),
(38, 1),
(38, 3),
(38, 5),
(39, 1),
(39, 3),
(39, 5),
(40, 1),
(40, 3),
(41, 1),
(41, 3),
(42, 1),
(42, 3),
(43, 1),
(43, 3),
(44, 1),
(44, 3),
(45, 1),
(45, 3),
(46, 1),
(46, 3),
(47, 1),
(47, 3),
(48, 1),
(48, 3);

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
(1, '1', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 1, 1, '', '0000-00-00 00:00:00'),
(4, 'clintb@st.lk', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 3, 1, '', '0000-00-00 00:00:00'),
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
(2, 'Tours and tracking', 'tours.png', 'tour-management.php', 1),
(3, 'Tender management', 'tender.png', 'tender.php', 1),
(4, 'Purchasing', 'purchasing.png', 'purchasing.php', 1),
(5, 'Spare parts management', 'spareparts.png', 'spareparts.php', 1),
(6, 'Bus management', 'busmanagement.png', 'bus-management.php', 1),
(7, 'Bus maintenance', 'busmaintenance.png', 'bus-maintenance.php', 1),
(8, 'Customer', 'customer.png', 'customer.php', 1),
(9, 'Finance management', 'finance.png', 'finance.php', 1),
(10, 'User management', 'user.png', 'user.php', 1);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(10) NOT NULL,
  `date` date NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `reference` varchar(255) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `category_id` int(11) NOT NULL,
  `payment_document` varchar(255) NOT NULL,
  `paid_by` int(10) NOT NULL,
  `payment_status` int(10) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `date`, `amount`, `reference`, `payment_method`, `category_id`, `payment_document`, `paid_by`, `payment_status`) VALUES
(1, '2025-06-23', 24112.25, '562584', 'cheque', 1, 'svspmt_6858676d5f02a.pdf', 3, 1),
(2, '2025-06-23', 24452.00, 'TRF522114', 'transfer', 1, 'svspmt_685868f8dcb64.pdf', 3, 1),
(3, '2025-06-23', 25432.25, '254745', 'cheque', 1, 'svspmt_68586a67a651c.pdf', 3, 1),
(4, '2025-06-23', 53914.51, 'dfhdh', 'transfer', 1, 'svspmt_68586b9a5f020.pdf', 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `quotation`
--

CREATE TABLE `quotation` (
  `quotation_id` int(10) NOT NULL,
  `issued_date` date NOT NULL,
  `customer_id` int(10) NOT NULL,
  `tour_start_date` date NOT NULL,
  `tour_end_date` date NOT NULL,
  `pickup_location` varchar(255) NOT NULL,
  `dropoff_location` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `round_trip_mileage` int(4) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `quotation_status` int(10) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quotation`
--

INSERT INTO `quotation` (`quotation_id`, `issued_date`, `customer_id`, `tour_start_date`, `tour_end_date`, `pickup_location`, `dropoff_location`, `description`, `round_trip_mileage`, `total_amount`, `quotation_status`) VALUES
(1, '2025-06-01', 1, '2025-06-12', '2025-06-13', 'Athurugiriya', 'Athurugiriya', 'One day trip from Athurugiriya to Galle and back', 240, 75000.00, 2),
(2, '2025-06-04', 2, '2025-06-08', '2025-06-09', 'Malabe', 'Malabe', 'One night trip to Nuwara-Eliya', 300, 120000.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `quotation_item`
--

CREATE TABLE `quotation_item` (
  `item_id` int(10) NOT NULL,
  `quotation_id` int(10) NOT NULL,
  `category_id` int(10) NOT NULL,
  `quantity` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quotation_item`
--

INSERT INTO `quotation_item` (`item_id`, `quotation_id`, `category_id`, `quantity`) VALUES
(1, 1, 1, 1),
(2, 1, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `reminder`
--

CREATE TABLE `reminder` (
  `reminder_id` int(10) NOT NULL,
  `reminder_type` varchar(255) NOT NULL,
  `sent_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reminder`
--

INSERT INTO `reminder` (`reminder_id`, `reminder_type`, `sent_date`) VALUES
(1, 'ServiceDueBuses', '2025-06-22');

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
-- Table structure for table `service_detail`
--

CREATE TABLE `service_detail` (
  `service_id` int(10) NOT NULL,
  `bus_id` int(10) NOT NULL,
  `previous_bus_status` int(10) NOT NULL,
  `service_station_id` int(10) NOT NULL,
  `start_date` date NOT NULL,
  `completed_date` date DEFAULT NULL,
  `cancelled_date` date DEFAULT NULL,
  `mileage_at_service` int(11) NOT NULL,
  `cost` decimal(10,2) DEFAULT NULL,
  `invoice` varchar(255) DEFAULT NULL,
  `invoice_number` varchar(255) DEFAULT NULL,
  `payment_id` int(10) DEFAULT NULL,
  `service_status` int(10) NOT NULL DEFAULT 1,
  `initiated_by` int(10) NOT NULL,
  `cancelled_by` int(10) DEFAULT NULL,
  `completed_by` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_detail`
--

INSERT INTO `service_detail` (`service_id`, `bus_id`, `previous_bus_status`, `service_station_id`, `start_date`, `completed_date`, `cancelled_date`, `mileage_at_service`, `cost`, `invoice`, `invoice_number`, `payment_id`, `service_status`, `initiated_by`, `cancelled_by`, `completed_by`) VALUES
(6, 1, 0, 1, '2025-04-28', '2025-04-28', NULL, 48000, 12000.00, 'svsinv_6810b675b14df.jpg', 'gbfb33', 2, 3, 1, NULL, 1),
(9, 10, 1, 1, '2025-04-29', '2025-04-29', NULL, 12758, 12452.00, 'svsinv_6810bb166af37.jpg', 'dfgvdb', 2, 3, 3, NULL, 3),
(12, 17, 2, 2, '2025-05-07', '2025-05-07', NULL, 15748, 24112.25, 'svsinv_681b09afde3ba.pdf', '2222', 1, 3, 3, NULL, 3),
(18, 2, 1, 2, '2025-06-19', '2025-06-19', NULL, 186000, 25432.25, 'svsinv_68541ff971466.jpg', 'hujsn', 3, 3, 1, NULL, 1),
(19, 8, 2, 1, '2025-06-23', '2025-06-23', NULL, 17000, 32512.96, 'svsinv_68586ab9c8fd7.pdf', '8451kl', 4, 3, 3, NULL, 3),
(20, 4, 1, 1, '2025-06-23', '2025-06-23', NULL, 116000, 21401.55, 'svsinv_68586b4cc3f19.pdf', 'jhvhjcas', 4, 3, 3, NULL, 3),
(21, 10, 1, 2, '2025-06-23', '2025-06-23', NULL, 13257, 12547.00, 'svsinv_6858f4d7898d4.pdf', 'kijh777', NULL, 2, 3, NULL, 3);

-- --------------------------------------------------------

--
-- Table structure for table `service_station`
--

CREATE TABLE `service_station` (
  `service_station_id` int(10) NOT NULL,
  `service_station_name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `service_station_status` int(10) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_station`
--

INSERT INTO `service_station` (`service_station_id`, `service_station_name`, `address`, `service_station_status`) VALUES
(1, 'High Level Auto Servicing', 'No. 123, High Level Road, Pahathgama, Hanwella', 1),
(2, 'Vinko Auto Repair', 'No. 526, Avissawella Road, Embulgama, Hanwella', 1);

-- --------------------------------------------------------

--
-- Table structure for table `service_station_contact`
--

CREATE TABLE `service_station_contact` (
  `service_station_contact_id` int(10) NOT NULL,
  `service_station_contact_number` varchar(10) NOT NULL,
  `contact_type` int(11) NOT NULL,
  `service_station_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_station_contact`
--

INSERT INTO `service_station_contact` (`service_station_contact_id`, `service_station_contact_number`, `contact_type`, `service_station_id`) VALUES
(1, '0751235841', 1, 1),
(2, '0366532148', 2, 1),
(9, '0751231247', 1, 2),
(10, '0366531234', 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `supplier_id` int(10) NOT NULL,
  `supplier_name` varchar(255) NOT NULL,
  `supplier_contact` varchar(10) NOT NULL,
  `supplier_email` int(11) NOT NULL,
  `supplier_status` int(10) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tour`
--

CREATE TABLE `tour` (
  `tour_id` int(10) NOT NULL,
  `invoice_id` int(10) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `destination` varchar(255) NOT NULL,
  `tour_status` int(10) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaction_category`
--

CREATE TABLE `transaction_category` (
  `category_id` int(10) NOT NULL,
  `category` varchar(255) NOT NULL,
  `debit_credit_flag` char(1) NOT NULL,
  `category_status` int(10) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction_category`
--

INSERT INTO `transaction_category` (`category_id`, `category`, `debit_credit_flag`, `category_status`) VALUES
(1, 'Service Payment', 'd', 1);

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
(3, 'Clint', 'Barton', '2025-02-12', '999999999V', 1, '1742137423_userimage3.jpg', 'clint@st.lk', 1),
(5, 'Steve', 'Rogers', '1996-01-25', '960250236V', 3, '', 'steve@st.lk', 1),
(7, 'Tony', 'Stark', '1996-12-15', '199512347521', 5, '1742137478_userimage5.jpg', 'tony@st.lk', 1),
(8, 'Natasha', 'Romanov', '1999-03-16', '990362581V', 7, '1742135511_userimage4w.jpg', 'natasha@st.lk', 0);

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
(134, 1, '0778810839', 3),
(135, 2, '0112729729', 3);

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
-- Indexes for table `bus_tour`
--
ALTER TABLE `bus_tour`
  ADD PRIMARY KEY (`bus_id`,`tour_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `customer_nic` (`customer_nic`);

--
-- Indexes for table `customer_contact`
--
ALTER TABLE `customer_contact`
  ADD PRIMARY KEY (`contact_id`);

--
-- Indexes for table `customer_invoice`
--
ALTER TABLE `customer_invoice`
  ADD PRIMARY KEY (`invoice_id`),
  ADD UNIQUE KEY `invoice_number` (`invoice_number`);

--
-- Indexes for table `customer_invoice_item`
--
ALTER TABLE `customer_invoice_item`
  ADD PRIMARY KEY (`item_id`);

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
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `quotation`
--
ALTER TABLE `quotation`
  ADD PRIMARY KEY (`quotation_id`);

--
-- Indexes for table `quotation_item`
--
ALTER TABLE `quotation_item`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `reminder`
--
ALTER TABLE `reminder`
  ADD PRIMARY KEY (`reminder_id`);

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
-- Indexes for table `service_detail`
--
ALTER TABLE `service_detail`
  ADD PRIMARY KEY (`service_id`);

--
-- Indexes for table `service_station`
--
ALTER TABLE `service_station`
  ADD PRIMARY KEY (`service_station_id`);

--
-- Indexes for table `service_station_contact`
--
ALTER TABLE `service_station_contact`
  ADD PRIMARY KEY (`service_station_contact_id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `tour`
--
ALTER TABLE `tour`
  ADD PRIMARY KEY (`tour_id`);

--
-- Indexes for table `transaction_category`
--
ALTER TABLE `transaction_category`
  ADD PRIMARY KEY (`category_id`);

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
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `customer_contact`
--
ALTER TABLE `customer_contact`
  MODIFY `contact_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `customer_invoice`
--
ALTER TABLE `customer_invoice`
  MODIFY `invoice_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customer_invoice_item`
--
ALTER TABLE `customer_invoice_item`
  MODIFY `item_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `quotation`
--
ALTER TABLE `quotation`
  MODIFY `quotation_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `quotation_item`
--
ALTER TABLE `quotation_item`
  MODIFY `item_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `reminder`
--
ALTER TABLE `reminder`
  MODIFY `reminder_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `role_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `service_detail`
--
ALTER TABLE `service_detail`
  MODIFY `service_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `service_station`
--
ALTER TABLE `service_station`
  MODIFY `service_station_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `service_station_contact`
--
ALTER TABLE `service_station_contact`
  MODIFY `service_station_contact_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tour`
--
ALTER TABLE `tour`
  MODIFY `tour_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaction_category`
--
ALTER TABLE `transaction_category`
  MODIFY `category_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `user_contact`
--
ALTER TABLE `user_contact`
  MODIFY `contact_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
