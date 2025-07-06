-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 06, 2025 at 05:19 PM
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
-- Table structure for table `bid`
--

CREATE TABLE `bid` (
  `bid_id` int(10) NOT NULL,
  `tender_id` int(10) NOT NULL,
  `supplier_id` int(10) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL COMMENT 'The price offered per single unit of the part',
  `bid_date` date NOT NULL DEFAULT current_timestamp() COMMENT 'Bid submitted date',
  `bid_status` int(10) NOT NULL DEFAULT 1 COMMENT '-1:Removed,\r\n1: Submitted, 2: Awarded, 3:PO Generated'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bid`
--

INSERT INTO `bid` (`bid_id`, `tender_id`, `supplier_id`, `unit_price`, `bid_date`, `bid_status`) VALUES
(1, 1, 3, 3500.00, '2025-04-08', -1),
(2, 1, 4, 3450.00, '2025-04-10', 3),
(3, 2, 2, 8900.00, '2025-06-05', 2),
(4, 3, 3, 4750.00, '2025-07-05', 3),
(5, 3, 4, 4560.25, '2025-07-05', 1),
(6, 4, 5, 2375.00, '2025-07-05', 3),
(7, 5, 5, 2850.00, '2025-07-06', 3),
(8, 6, 5, 2230.00, '2025-07-06', 3);

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
  `bus_status` int(10) NOT NULL DEFAULT 1 COMMENT '-1 = Removed,\r\n0 = Out of Service,\r\n1 = Operational,\r\n2 = Service Due,\r\n3 = In Service,\r\n4 = Broken Down',
  `removed_by` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bus`
--

INSERT INTO `bus` (`bus_id`, `category_id`, `vehicle_no`, `make`, `model`, `year`, `capacity`, `ac_available`, `service_interval_km`, `current_mileage_km`, `current_mileage_as_at`, `last_service_mileage_km`, `service_interval_months`, `last_service_date`, `bus_status`, `removed_by`) VALUES
(1, 1, 'CAA-1234', 'Yutong', 'ZK6938HQ', '2022', 40, 'N', 15000, 50000, '2025-06-19 12:50:46', 50000, 6, '2025-06-19', 1, NULL),
(2, 2, 'NB-5678', 'Lanka Ashok Leyland', 'Viking', '2018', 54, 'N', 10000, 186000, '2025-06-19 20:04:33', 186000, 6, '2025-06-19', 1, NULL),
(3, 3, '62-9102', 'Toyota', 'Coaster', '2019', 29, 'Y', 12000, 95000, '2025-04-25 14:18:59', 94001, 5, '2025-04-25', 1, NULL),
(4, 2, 'CAB-1122', 'Tata', 'LP 909 / Starbus', '2020', 45, 'N', 10000, 117520, '2025-07-02 10:51:00', 117520, 6, '2025-07-02', 1, NULL),
(5, 3, 'NA-4567', 'Mitsubishi', 'Fuso Rosa', '2016', 25, 'Y', 10000, 23000, '2025-04-28 10:35:00', 23000, 4, '2025-04-29', 3, NULL),
(6, 2, 'NC-8899', 'Isuzu', 'Journey J', '2021', 42, 'N', 15000, 2400, '2025-05-01 19:01:04', 2400, 12, '2025-05-07', 1, NULL),
(7, 2, 'PE-1111', 'Lanka Ashok Leyland', 'Viking', '2017', 52, 'N', 10000, 210000, '2025-04-21 22:57:00', 201000, 6, '2025-03-01', 1, NULL),
(8, 1, 'CAC-8888', 'Yutong', 'ZK6122H', '2023', 45, 'Y', 20000, 18245, '2025-06-25 18:23:45', 18245, 1, '2025-06-25', 1, NULL),
(9, 3, 'NB-0123', 'Toyota', 'Coaster', '2021', 29, 'Y', 12000, 55001, '2025-06-24 18:16:45', 48000, 3, '2025-02-28', 2, NULL),
(10, 2, 'PA-9900', 'Tata', 'Marcopolo', '2019', 48, 'N', 10000, 13257, '2025-06-23 12:01:51', 13257, 6, '2025-06-23', 1, NULL),
(11, 3, 'PC-5566', 'Mitsubishi', 'Fuso Rosa', '2018', 25, 'Y', 10000, 130000, '2025-04-21 22:57:00', 122000, 12, '2024-08-20', 1, NULL),
(12, 2, 'CAD-5005', 'Isuzu', 'Journey J', '2022', 40, 'Y', 15000, 40052, '2025-06-19 13:26:00', 40052, 1, '2025-06-19', 1, NULL),
(13, 1, 'PE-7733', 'Hino', 'AK / Liesse', '2017', 35, 'Y', 15000, 195000, '2025-04-21 22:57:00', 181000, 3, '2025-01-05', 2, NULL),
(16, 2, 'NC-1212', 'Lanka Ashok Leyland', 'Viking', '2014', 49, 'N', 7000, 38000, '2025-05-18 01:50:58', 35000, 1, '2025-04-01', 2, NULL),
(17, 3, 'ABC-1527', 'Toyota', 'Coaster', '2019', 35, 'Y', 5000, 15748, '2025-05-07 12:49:36', 15748, 3, '2025-05-07', 1, NULL),
(18, 3, 'QEW-7514', 'Toyota', 'Coaster', '2019', 32, 'Y', 4500, 0, '2025-06-27 00:34:43', 0, 3, '2025-06-27', 1, NULL),
(19, 3, 'BAI-2435', 'Toyota', 'Coaster', '2013', 33, 'Y', 3600, 3241, '2025-06-28 09:13:30', 3241, 3, '2025-06-28', 1, NULL);

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

--
-- Dumping data for table `bus_tour`
--

INSERT INTO `bus_tour` (`bus_id`, `tour_id`) VALUES
(1, 1),
(1, 2),
(4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `checklist_item`
--

CREATE TABLE `checklist_item` (
  `item_id` int(10) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `item_description` text DEFAULT NULL,
  `item_status` int(10) NOT NULL DEFAULT 1 COMMENT '1: Active, 0: Inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `checklist_item`
--

INSERT INTO `checklist_item` (`item_id`, `item_name`, `item_description`, `item_status`) VALUES
(1, 'Check engine oil level', 'Ensure oil is between the minimum and maximum marks on the dipstick.', 1),
(2, 'Check coolant level', 'Verify coolant level in the reservoir.', 1),
(3, 'Inspect tire pressure and condition', 'Check for correct inflation and look for any visible damage or excessive wear.', 1),
(4, 'Test all exterior lights', 'Includes headlights (high/low beams), tail lights, brake lights, and turn signals.', 1),
(5, 'Check horn operation', 'Ensure the horn is audible.', 1),
(6, 'Inspect brake system', 'Visual check for leaks and listen for unusual noises.', 1),
(7, 'Verify first aid kit presence and contents', 'Ensure the kit is present and fully stocked.', 1),
(8, 'Check fire extinguisher', 'Ensure it is present, charged, and within its expiry date.', 1),
(9, 'Inspect interior cleanliness', 'Check seats, floors, and windows for cleanliness.', 1),
(10, 'Test air conditioning (A/C) unit', 'For applicable buses, ensure A/C blows cold air effectively.', 1),
(11, 'Check passenger seat belts', 'Inspect for proper function and any visible damage.', 1),
(12, 'Verify onboard entertainment system', 'For luxury buses, check if TVs and audio systems are working.', 1);

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
(4, '199865401234', 'Fathima', 'Rizwan', 'fathima.r@email.com', 1),
(5, '953254147V', 'Dilshan', 'Gamage', 'dilshang@ymail.com', 1),
(6, '901234567V', 'Sunil', 'Perera', 'sunil.perera@example.com', 1);

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
(18, 2, '0112258632', 4),
(19, 1, '0714525851', 5),
(20, 2, '0114006319', 5),
(21, 1, '0771234567', 6),
(22, 2, '0112345678', 6);

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
  `invoice_status` int(11) NOT NULL DEFAULT 1 COMMENT '-1=Invoice Cancelled,\r\n1=Initial Invoice & waiting for tour assignment,\r\n2=Tour assigned,\r\n3=Tour completed and payment pending,\r\n4=Paid',
  `invoice_description` text DEFAULT NULL,
  `tour_start_date` date NOT NULL COMMENT 'The tour start date as billed/quoted to the customer.',
  `tour_end_date` date NOT NULL COMMENT 'The tour end date as billed/quoted to the customer.',
  `pickup_location` varchar(255) NOT NULL,
  `destination` varchar(255) NOT NULL,
  `dropoff_location` varchar(255) NOT NULL,
  `round_trip_mileage` int(4) NOT NULL,
  `actual_fare` decimal(10,2) DEFAULT NULL,
  `actual_mileage` int(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer_invoice`
--

INSERT INTO `customer_invoice` (`invoice_id`, `invoice_number`, `quotation_id`, `invoice_date`, `invoice_amount`, `customer_id`, `invoice_status`, `invoice_description`, `tour_start_date`, `tour_end_date`, `pickup_location`, `destination`, `dropoff_location`, `round_trip_mileage`, `actual_fare`, `actual_mileage`) VALUES
(1, 'SKT-14K', 1, '2025-06-01', 75000.00, 1, 4, 'One day trip from Athurugiriya to Galle and back', '2025-06-12', '2025-06-13', 'Athurugiriya', 'Galle', 'Athurugiriya', 240, 78350.00, 258),
(2, 'SKT-20250703-3', 3, '2025-07-03', 95000.00, 6, 1, 'Two-day trip to Kandy', '2025-07-10', '2025-07-12', 'Colombo', 'Kandy', 'Nugegoda', 200, NULL, NULL),
(3, 'SKT-20250703-4', 4, '2025-07-03', 235145.36, 4, 2, 'Two Days, One Night Trip to Ella and Back', '2025-07-10', '2025-07-12', 'Maharagama', 'Ella', 'Maharagama', 420, NULL, NULL);

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
(2, 1, 2, 1),
(3, 2, 2, 1),
(4, 3, 1, 1);

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
-- Table structure for table `grn`
--

CREATE TABLE `grn` (
  `grn_id` int(10) NOT NULL,
  `grn_number` varchar(50) DEFAULT NULL COMMENT 'Optional number from the physical GRN document',
  `po_id` int(10) NOT NULL COMMENT 'The PO this delivery is for',
  `grn_quantity_received` int(10) NOT NULL COMMENT 'The quantity of the part received in this specific delivery',
  `yet_to_receive` int(10) NOT NULL COMMENT 'Quantity Yet To Receive',
  `grn_received_date` date NOT NULL DEFAULT curdate(),
  `inspected_by` int(10) NOT NULL COMMENT 'User who received and inspected the goods',
  `grn_notes` text DEFAULT NULL COMMENT 'Optional',
  `grn_status` int(10) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grn`
--

INSERT INTO `grn` (`grn_id`, `grn_number`, `po_id`, `grn_quantity_received`, `yet_to_receive`, `grn_received_date`, `inspected_by`, `grn_notes`, `grn_status`) VALUES
(1, 'Test-1', 1, 20, 0, '2025-05-02', 8, NULL, 1),
(2, 'Test-2', 2, 15, 0, '2025-06-20', 8, NULL, 1),
(3, 'GRN-0706762F-3', 3, 2, 8, '2025-07-06', 3, '2 of the ordered air filters received. Appears in good condition.', 1),
(4, 'GRN-0706B414-3', 3, 5, 3, '2025-07-06', 3, 'Appeares in good condition', 1),
(5, 'GRN-0706BD32-3', 3, 3, 0, '2025-07-06', 3, 'All Received', 1),
(6, 'GRN-07065744-6', 6, 5, 0, '2025-07-06', 3, 'All received', 1),
(7, 'GRN-07065590-4', 4, 20, 5, '2025-07-06', 3, '20 Items received', 1),
(8, 'GRN-0706F784-4', 4, 5, 0, '2025-07-06', 3, 'All done', 1),
(9, 'GRN-0706AEDA-7', 7, 7, 0, '2025-07-06', 3, 'All received', 1),
(10, 'GRN-0706A649-5', 5, 20, 0, '2025-07-06', 3, 'No Defects', 1);

-- --------------------------------------------------------

--
-- Table structure for table `inspection`
--

CREATE TABLE `inspection` (
  `inspection_id` int(10) NOT NULL,
  `bus_id` int(10) NOT NULL,
  `inspection_date` date NOT NULL,
  `inspection_result` int(10) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `inspected_by` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inspection`
--

INSERT INTO `inspection` (`inspection_id`, `bus_id`, `inspection_date`, `inspection_result`, `comment`, `inspected_by`) VALUES
(1, 8, '2025-06-29', 0, 'A/C unit requires service. See checklist for details.', 7),
(2, 2, '2025-06-29', 1, 'All checks passed.', 7),
(3, 3, '2025-06-05', 1, 'Bus is ready for tour.', 7),
(4, 6, '2025-06-10', 1, 'All checks passed, ready for deployment.', 7),
(5, 13, '2025-06-15', 0, 'Brake system requires immediate attention.', 7),
(6, 9, '2025-06-22', 1, 'All checks passed.', 7),
(7, 12, '2025-06-28', 0, 'Front-left tire shows excessive wear. Recommend replacement.', 7);

-- --------------------------------------------------------

--
-- Table structure for table `inspection_checklist_response`
--

CREATE TABLE `inspection_checklist_response` (
  `response_id` int(10) NOT NULL,
  `inspection_id` int(10) NOT NULL,
  `item_id` int(10) NOT NULL,
  `response_value` int(10) NOT NULL COMMENT 'e.g., ''1-Pass'', ''0-Fail'', ''2-OK'', ',
  `comments` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inspection_checklist_response`
--

INSERT INTO `inspection_checklist_response` (`response_id`, `inspection_id`, `item_id`, `response_value`, `comments`) VALUES
(1, 1, 1, 1, ''),
(2, 1, 2, 1, 'Level is OK'),
(3, 1, 3, 1, ''),
(4, 1, 4, 1, ''),
(5, 1, 6, 1, ''),
(6, 1, 9, 1, 'Interior cleaned and sanitized.'),
(7, 1, 10, 0, 'A/C is not blowing cold air. Possible refrigerant leak.'),
(8, 1, 11, 1, ''),
(9, 1, 12, 1, 'TV and audio system functioning correctly.'),
(10, 2, 1, 1, ''),
(11, 2, 3, 1, 'All tires at correct PSI.'),
(12, 2, 4, 1, ''),
(13, 2, 5, 1, ''),
(14, 2, 7, 1, ''),
(15, 2, 8, 1, 'Checked and in place.'),
(16, 3, 1, 1, ''),
(17, 3, 2, 1, ''),
(18, 3, 3, 1, 'Tire pressure adjusted.'),
(19, 3, 4, 1, ''),
(20, 3, 6, 1, ''),
(21, 3, 9, 1, ''),
(22, 3, 10, 1, 'A/C is working well.'),
(23, 3, 11, 1, ''),
(24, 4, 1, 1, ''),
(25, 4, 3, 1, ''),
(26, 4, 4, 1, ''),
(27, 4, 5, 1, ''),
(28, 4, 7, 1, 'First aid kit restocked.'),
(29, 4, 8, 1, ''),
(30, 5, 1, 1, ''),
(31, 5, 2, 1, ''),
(32, 5, 3, 1, ''),
(33, 5, 4, 1, ''),
(34, 5, 6, 0, 'Squeaking noise from rear brakes during test. Needs mechanical check.'),
(35, 5, 9, 1, ''),
(36, 5, 10, 1, ''),
(37, 5, 11, 1, ''),
(38, 5, 12, 1, ''),
(39, 6, 1, 1, ''),
(40, 6, 2, 1, ''),
(41, 6, 3, 1, ''),
(42, 6, 4, 1, ''),
(43, 6, 6, 1, ''),
(44, 6, 9, 1, 'Interior is immaculate.'),
(45, 6, 10, 1, ''),
(46, 6, 11, 1, ''),
(47, 7, 1, 1, 'Oil level OK.'),
(48, 7, 3, 0, 'Front-left tire tread is below minimum safe level.'),
(49, 7, 4, 1, ''),
(50, 7, 5, 1, ''),
(51, 7, 7, 1, ''),
(52, 7, 8, 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `inspection_checklist_template`
--

CREATE TABLE `inspection_checklist_template` (
  `template_id` int(10) NOT NULL,
  `template_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `category_id` int(10) NOT NULL,
  `template_status` int(10) NOT NULL DEFAULT 1 COMMENT '1: Active, 0: Inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inspection_checklist_template`
--

INSERT INTO `inspection_checklist_template` (`template_id`, `template_name`, `description`, `category_id`, `template_status`) VALUES
(1, 'Luxury Bus Pre-Tour Checklist', 'Standard pre-tour inspection checklist for all Luxury buses.', 1, 1),
(2, 'Standard Bus Pre-Tour Checklist', 'Standard pre-tour inspection checklist for all Standard category buses.', 2, 1),
(3, 'Mini Bus Pre-Tour Checklist', 'Standard pre-tour inspection checklist for all Mini Buses.', 3, 1);

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
(8, 'tonys@st.lk', 'f1707f87b7662b61ea627b9769338d60aa852e16', 7, 1, '', '0000-00-00 00:00:00'),
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
-- Table structure for table `part_transaction`
--

CREATE TABLE `part_transaction` (
  `transaction_id` int(10) NOT NULL,
  `part_id` int(10) NOT NULL,
  `transaction_type` int(10) NOT NULL COMMENT '1: Initial Stock Load,2: Purchase, 3: Issue to Bus, 4: Remove from Warehouse',
  `quantity` int(10) NOT NULL COMMENT 'The absolute quantity for this transaction (e.g., 5)',
  `bus_id` int(10) DEFAULT NULL COMMENT 'The bus involved, if applicable (for Issue to Bus)',
  `grn_id` int(10) DEFAULT NULL COMMENT 'The GRN involved, if applicable (for Purchases)',
  `part_transaction_notes` text DEFAULT NULL COMMENT 'Reason for removal, serial number, etc.',
  `transacted_by` int(10) NOT NULL,
  `transacted_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `part_transaction`
--

INSERT INTO `part_transaction` (`transaction_id`, `part_id`, `transaction_type`, `quantity`, `bus_id`, `grn_id`, `part_transaction_notes`, `transacted_by`, `transacted_at`) VALUES
(1, 1, 1, 10, NULL, NULL, 'Initial stock load', 1, '2025-03-15 10:00:00'),
(2, 2, 1, 4, NULL, NULL, 'Initial stock load', 1, '2025-03-15 10:00:00'),
(3, 3, 1, 15, NULL, NULL, 'Initial stock load', 1, '2025-03-15 10:00:00'),
(4, 4, 1, 20, NULL, NULL, 'Initial stock load', 1, '2025-03-15 10:00:00'),
(5, 2, 1, 20, NULL, 1, NULL, 8, '2025-05-02 10:00:00'),
(6, 1, 1, 15, NULL, 2, NULL, 8, '2025-06-20 14:00:00'),
(7, 3, 3, 1, 9, NULL, 'Routine service replacement', 8, '2025-06-25 11:00:00'),
(8, 4, 4, 1, NULL, NULL, 'Scrapped due to factory defect.', 8, '2025-06-26 09:30:00'),
(9, 3, 2, 2, NULL, 3, '2 of the ordered air filters received. Appears in good condition.', 3, '2025-07-06 09:30:35'),
(10, 3, 2, 5, NULL, 4, 'Appeares in good condition', 3, '2025-07-06 09:31:20'),
(11, 3, 2, 3, NULL, 5, 'All Received', 3, '2025-07-06 09:31:35'),
(12, 4, 3, 1, 3, NULL, '1 Wiper Blade Issued', 3, '2025-07-06 14:33:32'),
(13, 4, 4, 8, NULL, NULL, 'Defects Blades Found', 3, '2025-07-06 15:07:11'),
(14, 4, 2, 5, NULL, 6, 'All received', 3, '2025-07-06 15:26:56'),
(15, 4, 2, 20, NULL, 7, '20 Items received', 3, '2025-07-06 15:29:22'),
(16, 4, 2, 5, NULL, 8, 'All done', 3, '2025-07-06 15:29:32'),
(17, 4, 2, 7, NULL, 9, 'All received', 3, '2025-07-06 15:29:40'),
(18, 2, 2, 20, NULL, 10, 'No Defects', 3, '2025-07-06 15:29:49');

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
  `category_id` int(10) NOT NULL,
  `po_id` int(10) DEFAULT NULL,
  `payment_document` varchar(255) NOT NULL,
  `paid_by` int(10) NOT NULL,
  `payment_status` int(10) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `date`, `amount`, `reference`, `payment_method`, `category_id`, `po_id`, `payment_document`, `paid_by`, `payment_status`) VALUES
(1, '2025-06-23', 24112.25, '562584', 'cheque', 1, NULL, 'svspmt_6858676d5f02a.pdf', 3, 1),
(2, '2025-06-23', 24452.00, 'TRF522114', 'transfer', 1, NULL, 'svspmt_685868f8dcb64.pdf', 3, 1),
(3, '2025-06-23', 25432.25, '254745', 'cheque', 1, NULL, 'svspmt_68586a67a651c.pdf', 3, 1),
(4, '2025-06-23', 53914.51, 'dfhdh', 'transfer', 1, NULL, 'svspmt_68586b9a5f020.pdf', 3, 1),
(5, '2025-05-20', 69000.00, 'CHQ-554321', 'cheque', 2, 1, 'svspmt_68586b9a5f020.pdf', 3, 1),
(6, '2025-06-28', 133500.00, 'TRF-AX-8891', 'transfer', 2, 2, 'svspmt_68586b9a5f020.pdf', 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order`
--

CREATE TABLE `purchase_order` (
  `po_id` int(10) NOT NULL,
  `po_number` varchar(50) NOT NULL COMMENT 'A unique number for business reference ',
  `bid_id` int(10) NOT NULL COMMENT 'The winning bid that authorized this PO',
  `part_id` int(10) NOT NULL COMMENT 'The part being ordered',
  `quantity_ordered` int(10) NOT NULL,
  `quantity_received` int(10) NOT NULL DEFAULT 0 COMMENT 'Total quantity received so far for this PO',
  `po_unit_price` decimal(10,2) NOT NULL COMMENT 'The price per unit, captured from the winning bid',
  `total_amount` decimal(12,2) NOT NULL COMMENT 'quantity_ordered * unit_price',
  `order_date` date NOT NULL DEFAULT curdate() COMMENT 'Defaults to the current date of PO creation',
  `po_status` int(10) NOT NULL DEFAULT 1 COMMENT '-1:Rejected, 1: Generated, 2:Approved,3:Supplier Invoice Attached, 4: Partially Received, 5: Completed, 6: Paid',
  `created_by` int(10) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'The exact date and time the PO was generated',
  `approved_by` int(10) DEFAULT NULL,
  `rejected_by` int(10) DEFAULT NULL,
  `supplier_invoice` varchar(255) DEFAULT NULL,
  `supplier_invoice_number` varchar(255) DEFAULT NULL,
  `po_payment_id` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_order`
--

INSERT INTO `purchase_order` (`po_id`, `po_number`, `bid_id`, `part_id`, `quantity_ordered`, `quantity_received`, `po_unit_price`, `total_amount`, `order_date`, `po_status`, `created_by`, `created_at`, `approved_by`, `rejected_by`, `supplier_invoice`, `supplier_invoice_number`, `po_payment_id`) VALUES
(1, 'PO-2025-001', 2, 2, 20, 20, 3450.00, 69000.00, '2025-04-18', 6, 1, '2025-06-29 10:29:07', NULL, NULL, NULL, NULL, 5),
(2, 'PO-2025-002', 3, 1, 15, 15, 8900.00, 133500.00, '2025-06-12', 6, 1, '2025-06-29 10:33:12', NULL, NULL, NULL, NULL, 6),
(3, 'ST-PO-9AF1-3', 4, 3, 10, 10, 4750.00, 47500.00, '2025-07-05', 5, 3, '2025-07-05 13:06:05', 3, NULL, '1751720671_Test PDF.pdf', 'bkjvu-ss', NULL),
(4, 'ST-PO-63B4-4', 6, 4, 25, 25, 2375.00, 59375.00, '2025-07-05', 5, 3, '2025-07-05 18:50:34', 3, NULL, '1751721763_Test PDF.pdf', 'TestINV652', NULL),
(5, 'ST-PO-E420-1', 2, 2, 20, 20, 3450.00, 69000.00, '2025-07-06', 5, 3, '2025-07-06 11:07:05', 3, NULL, '1751795944_Test PDF.pdf', 'asdwav', NULL),
(6, 'ST-PO-34DB-5', 7, 4, 5, 5, 2850.00, 14250.00, '2025-07-06', 5, 3, '2025-07-06 15:17:33', 3, NULL, '1751795280_Test PDF.pdf', 'Ijkn3', NULL),
(7, 'ST-PO-A0D7-6', 8, 4, 7, 7, 2230.00, 15610.00, '2025-07-06', 5, 3, '2025-07-06 15:28:29', 3, NULL, '1751795934_Test PDF.pdf', 'c32v', NULL);

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
  `destination` varchar(255) DEFAULT NULL,
  `dropoff_location` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `round_trip_mileage` int(4) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `quotation_status` int(10) NOT NULL DEFAULT 1 COMMENT '-1=Removed,\r\n1= pending approval to generate invoice,\r\n2 = approved and invoice generated.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quotation`
--

INSERT INTO `quotation` (`quotation_id`, `issued_date`, `customer_id`, `tour_start_date`, `tour_end_date`, `pickup_location`, `destination`, `dropoff_location`, `description`, `round_trip_mileage`, `total_amount`, `quotation_status`) VALUES
(1, '2025-06-01', 1, '2025-06-12', '2025-06-13', 'Athurugiriya', 'Galle', 'Athurugiriya', 'One night trip from Athurugiriya to Galle and back', 240, 75000.00, 2),
(2, '2025-06-04', 2, '2025-06-08', '2025-06-09', 'Malabe', 'Nuwara Eliya', 'Malabe', 'One night trip to Nuwara-Eliya', 300, 120000.00, -1),
(3, '2025-06-29', 6, '2025-07-10', '2025-07-12', 'Colombo', 'Kandy', 'Nugegoda', 'Two-day trip to Kandy', 200, 95000.00, 2),
(4, '2025-07-03', 4, '2025-07-10', '2025-07-12', 'Maharagama', 'Ella', 'Maharagama', 'Two Days, One Night Trip to Ella and Back', 420, 235145.36, 2);

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
(2, 1, 2, 1),
(3, 3, 2, 1),
(6, 4, 1, 1);

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
(1, 'ServiceDueBuses', '2025-06-29');

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
  `service_status` int(10) NOT NULL DEFAULT 1 COMMENT '-1=Cancelled,1=Ongoing,2=Completed,3=Completed & Paid',
  `initiated_by` int(10) NOT NULL,
  `cancelled_by` int(10) DEFAULT NULL,
  `completed_by` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_detail`
--

INSERT INTO `service_detail` (`service_id`, `bus_id`, `previous_bus_status`, `service_station_id`, `start_date`, `completed_date`, `cancelled_date`, `mileage_at_service`, `cost`, `invoice`, `invoice_number`, `payment_id`, `service_status`, `initiated_by`, `cancelled_by`, `completed_by`) VALUES
(6, 1, 0, 1, '2025-04-28', '2025-04-28', NULL, 48000, 12000.00, 'svsinv_6810b675b14df.jpg', 'gbfb33', 2, 3, 1, NULL, 1),
(9, 10, 1, 1, '2025-04-29', '2025-04-29', NULL, 12758, 12452.00, 'svsinv_6810bb166af37.jpg', 'dfgvdb', 2, 3, 3, NULL, 1),
(12, 17, 2, 2, '2025-05-07', '2025-05-07', NULL, 15748, 24112.25, 'svsinv_681b09afde3ba.pdf', '2222', 1, 3, 3, NULL, 3),
(18, 2, 1, 2, '2025-06-19', '2025-06-19', NULL, 186000, 25432.25, 'svsinv_68541ff971466.jpg', 'hujsn', 3, 3, 1, NULL, 1),
(19, 8, 2, 1, '2025-06-23', '2025-06-23', NULL, 17000, 32512.96, 'svsinv_68586ab9c8fd7.pdf', '8451kl', 4, 3, 3, NULL, 3),
(20, 4, 1, 1, '2025-06-23', '2025-06-23', NULL, 116000, 21401.55, 'svsinv_68586b4cc3f19.pdf', 'jhvhjcas', 4, 3, 3, NULL, 1),
(21, 10, 1, 2, '2025-06-23', '2025-06-23', NULL, 13257, 12547.00, 'svsinv_6858f4d7898d4.pdf', 'kijh777', NULL, 2, 3, NULL, 1),
(22, 8, 2, 2, '2025-06-25', '2025-06-25', NULL, 18245, 18254.23, 'svsinv_685bf1599037e.pdf', 'fbfb77', NULL, 2, 3, NULL, 1),
(23, 5, 1, 1, '2025-06-26', NULL, NULL, 24000, NULL, NULL, NULL, NULL, 1, 3, NULL, NULL),
(24, 4, 1, 2, '2025-06-27', '2025-07-02', NULL, 117520, 32363.65, 'svsinv_6864c1bc631bf.pdf', 'OKL55422', NULL, 2, 3, NULL, 3),
(25, 19, 1, 2, '2025-06-28', '2025-06-28', NULL, 3241, 7053.78, 'svsinv_685f64e2cd0c6.pdf', '458IL4', NULL, 2, 3, NULL, 1);

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
-- Table structure for table `spare_part`
--

CREATE TABLE `spare_part` (
  `part_id` int(10) NOT NULL,
  `part_number` varchar(100) NOT NULL COMMENT 'Manufacturer or internal part number (Required)',
  `part_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `quantity_on_hand` int(10) NOT NULL DEFAULT 0,
  `reorder_level` int(10) NOT NULL,
  `part_status` int(10) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `spare_part`
--

INSERT INTO `spare_part` (`part_id`, `part_number`, `part_name`, `description`, `quantity_on_hand`, `reorder_level`, `part_status`) VALUES
(1, 'LAL-BP-VK01', 'LAL Viking Brake Pad Set', 'Front brake pads for Lanka Ashok Leyland Viking models.', 25, 5, 1),
(2, 'YT-OF-ZK6938', 'Yutong ZK6938HQ Oil Filter', 'Standard oil filter for Yutong ZK6938HQ engines.', 44, 5, 1),
(3, 'TC-AF-CSTR', 'Toyota Coaster Air Filter', 'Engine air filter for Toyota Coaster models.', 24, 10, 1),
(4, 'GEN-WB-18', 'Generic Wiper Blade 18\"', 'Standard 18-inch wiper blade, fits multiple models.', 47, 15, 1);

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `supplier_id` int(10) NOT NULL,
  `supplier_name` varchar(255) NOT NULL,
  `supplier_contact` varchar(10) NOT NULL,
  `supplier_email` varchar(255) NOT NULL,
  `supplier_status` int(10) NOT NULL DEFAULT 1 COMMENT '-1=removed,0=deactivated, 1=Active '
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`supplier_id`, `supplier_name`, `supplier_contact`, `supplier_email`, `supplier_status`) VALUES
(1, 'United Motors Lanka', '0112448112', 'info@unitedmotors.lk', 1),
(2, 'Lanka Ashok Leyland PLC - Spare Parts', '0112867435', 'parts@lal.lk', 1),
(3, 'Japan Auto Parts Colombo', '0777321654', 'sales@japanautoparts.lk', 1),
(4, 'Dragon Auto Supplies', '0718989765', 'contact@dragonauto.com', 1),
(5, 'General Auto Traders', '0332255889', 'gat@email.com', 1);

-- --------------------------------------------------------

--
-- Table structure for table `template_item_link`
--

CREATE TABLE `template_item_link` (
  `template_id` int(10) NOT NULL,
  `item_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `template_item_link`
--

INSERT INTO `template_item_link` (`template_id`, `item_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 6),
(1, 9),
(1, 10),
(1, 11),
(1, 12),
(2, 1),
(2, 3),
(2, 4),
(2, 5),
(2, 7),
(2, 8),
(3, 1),
(3, 2),
(3, 3),
(3, 4),
(3, 6),
(3, 9),
(3, 10),
(3, 11);

-- --------------------------------------------------------

--
-- Table structure for table `tender`
--

CREATE TABLE `tender` (
  `tender_id` int(10) NOT NULL,
  `part_id` int(10) NOT NULL,
  `quantity_required` int(10) NOT NULL COMMENT 'The quantity of the part required.',
  `tender_description` text DEFAULT NULL,
  `advertisement_file_name` varchar(255) DEFAULT NULL COMMENT 'Filename of the scanned newspaper ad or document',
  `open_date` date NOT NULL,
  `close_date` date NOT NULL,
  `tender_status` int(10) NOT NULL DEFAULT 1 COMMENT '-1:Cancelled,1: Open, 2: Closed, 3: Awarded',
  `created_by` int(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `awarded_bid` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tender`
--

INSERT INTO `tender` (`tender_id`, `part_id`, `quantity_required`, `tender_description`, `advertisement_file_name`, `open_date`, `close_date`, `tender_status`, `created_by`, `created_at`, `awarded_bid`) VALUES
(1, 2, 20, 'Procurement of Yutong ZK6938HQ Oil Filters', NULL, '2025-04-05', '2025-04-15', 3, 1, '2025-06-29 04:58:51', 2),
(2, 1, 15, 'Urgent need for LAL Viking Brake Pads', NULL, '2025-06-01', '2025-06-10', 3, 1, '2025-06-29 05:02:27', NULL),
(3, 3, 10, '10 Toyota Coaster Air Filters are required urgently', '1751638956_Test PDF.pdf', '2025-07-04', '2025-07-07', 3, 3, '2025-07-04 14:22:36', 4),
(4, 4, 25, '25 Generic Wiper Blades are needed to stock.', '1751694412_Test PDF.pdf', '2025-07-05', '2025-07-08', 3, 3, '2025-07-05 05:46:52', 6),
(5, 4, 5, 'WB 5 Needed', '1751795211_Test PDF.pdf', '2025-07-06', '2025-07-06', 3, 3, '2025-07-06 09:46:51', 7),
(6, 4, 7, '7WB Needed', '1751795869_Test PDF.pdf', '2025-07-06', '2025-07-06', 3, 3, '2025-07-06 09:57:49', 8);

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
  `tour_status` int(10) NOT NULL DEFAULT 1 COMMENT '-1=Cancelled, 1=Assigned, 2=Ongoing, 3=Completed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tour`
--

INSERT INTO `tour` (`tour_id`, `invoice_id`, `start_date`, `end_date`, `destination`, `tour_status`) VALUES
(1, 1, '2025-06-12', '2025-06-13', 'Galle', 3),
(2, 3, '2025-07-10', '2025-07-12', 'Ella', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tour_income`
--

CREATE TABLE `tour_income` (
  `tour_income_id` int(10) NOT NULL,
  `invoice_id` int(10) NOT NULL COMMENT 'The customer invoice this payment is for',
  `payment_date` date NOT NULL,
  `paid_amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL COMMENT 'e.g., ''Cash'', ''Funds Transfer''',
  `payment_proof` varchar(255) DEFAULT NULL COMMENT 'Filename of the uploaded proof',
  `received_by` int(10) NOT NULL COMMENT 'The user who accepted and recorded the payment',
  `payment_status` int(10) NOT NULL DEFAULT 1 COMMENT 'e.g., 1: Received, 2: Verified'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tour_income`
--

INSERT INTO `tour_income` (`tour_income_id`, `invoice_id`, `payment_date`, `paid_amount`, `payment_method`, `payment_proof`, `received_by`, `payment_status`) VALUES
(1, 1, '2025-07-04', 78350.00, 'cash', '1751610219_Test PDF.pdf', 3, 1);

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
(1, 'Service Payment', 'd', 1),
(2, 'Supplier Payment', 'd', 1);

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
(134, 1, '0778810839', 3),
(135, 2, '0112729729', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bid`
--
ALTER TABLE `bid`
  ADD PRIMARY KEY (`bid_id`),
  ADD KEY `fk_bid_tender` (`tender_id`),
  ADD KEY `fk_bid_supplier` (`supplier_id`);

--
-- Indexes for table `bus`
--
ALTER TABLE `bus`
  ADD PRIMARY KEY (`bus_id`),
  ADD UNIQUE KEY `vehicle_no` (`vehicle_no`),
  ADD KEY `fk_bus_bus_category` (`category_id`),
  ADD KEY `fk_bus_removed_by_user` (`removed_by`);

--
-- Indexes for table `bus_category`
--
ALTER TABLE `bus_category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `bus_tour`
--
ALTER TABLE `bus_tour`
  ADD PRIMARY KEY (`bus_id`,`tour_id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Indexes for table `checklist_item`
--
ALTER TABLE `checklist_item`
  ADD PRIMARY KEY (`item_id`),
  ADD UNIQUE KEY `item_name` (`item_name`);

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
  ADD PRIMARY KEY (`contact_id`),
  ADD KEY `fk_customer_contact_customer` (`customer_id`);

--
-- Indexes for table `customer_invoice`
--
ALTER TABLE `customer_invoice`
  ADD PRIMARY KEY (`invoice_id`),
  ADD UNIQUE KEY `invoice_number` (`invoice_number`),
  ADD KEY `fk_invoice_quotation` (`quotation_id`),
  ADD KEY `fk_invoice_customer` (`customer_id`);

--
-- Indexes for table `customer_invoice_item`
--
ALTER TABLE `customer_invoice_item`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `fk_invoice_item_invoice` (`invoice_id`),
  ADD KEY `fk_invoice_item_bus_category` (`category_id`);

--
-- Indexes for table `function`
--
ALTER TABLE `function`
  ADD PRIMARY KEY (`function_id`),
  ADD KEY `fk_function_module` (`module_id`);

--
-- Indexes for table `function_user`
--
ALTER TABLE `function_user`
  ADD PRIMARY KEY (`function_id`,`user_id`),
  ADD KEY `fk_function_user_user` (`user_id`);

--
-- Indexes for table `grn`
--
ALTER TABLE `grn`
  ADD PRIMARY KEY (`grn_id`),
  ADD UNIQUE KEY `grn_number` (`grn_number`),
  ADD KEY `fk_grn_po` (`po_id`),
  ADD KEY `fk_grn_user` (`inspected_by`);

--
-- Indexes for table `inspection`
--
ALTER TABLE `inspection`
  ADD PRIMARY KEY (`inspection_id`),
  ADD KEY `fk_inspection_bus` (`bus_id`),
  ADD KEY `fk_inspection_user` (`inspected_by`);

--
-- Indexes for table `inspection_checklist_response`
--
ALTER TABLE `inspection_checklist_response`
  ADD PRIMARY KEY (`response_id`),
  ADD KEY `fk_response_item` (`item_id`),
  ADD KEY `idx_inspection_id` (`inspection_id`);

--
-- Indexes for table `inspection_checklist_template`
--
ALTER TABLE `inspection_checklist_template`
  ADD PRIMARY KEY (`template_id`),
  ADD KEY `fk_template_bus_category` (`category_id`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`login_id`),
  ADD UNIQUE KEY `login_username` (`login_username`),
  ADD KEY `fk_login_user` (`user_id`);

--
-- Indexes for table `module`
--
ALTER TABLE `module`
  ADD PRIMARY KEY (`module_id`);

--
-- Indexes for table `part_transaction`
--
ALTER TABLE `part_transaction`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `fk_pt_part` (`part_id`),
  ADD KEY `fk_pt_bus` (`bus_id`),
  ADD KEY `fk_pt_grn` (`grn_id`),
  ADD KEY `fk_pt_user` (`transacted_by`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `fk_payment_transaction_category` (`category_id`),
  ADD KEY `fk_payment_paid_by_user` (`paid_by`),
  ADD KEY `date` (`date`);

--
-- Indexes for table `purchase_order`
--
ALTER TABLE `purchase_order`
  ADD PRIMARY KEY (`po_id`),
  ADD UNIQUE KEY `po_number` (`po_number`),
  ADD KEY `fk_po_bid` (`bid_id`),
  ADD KEY `fk_po_part` (`part_id`),
  ADD KEY `fk_po_user` (`created_by`);

--
-- Indexes for table `quotation`
--
ALTER TABLE `quotation`
  ADD PRIMARY KEY (`quotation_id`),
  ADD KEY `fk_quotation_customer` (`customer_id`);

--
-- Indexes for table `quotation_item`
--
ALTER TABLE `quotation_item`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `fk_quotation_item_quotation` (`quotation_id`),
  ADD KEY `fk_quotation_item_bus_category` (`category_id`);

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
  ADD PRIMARY KEY (`role_id`,`module_id`),
  ADD KEY `fk_role_module_module` (`module_id`);

--
-- Indexes for table `service_detail`
--
ALTER TABLE `service_detail`
  ADD PRIMARY KEY (`service_id`),
  ADD KEY `fk_service_detail_bus` (`bus_id`),
  ADD KEY `fk_service_detail_station` (`service_station_id`),
  ADD KEY `fk_service_detail_initiated_by` (`initiated_by`),
  ADD KEY `fk_service_detail_completed_by` (`completed_by`),
  ADD KEY `fk_service_detail_cancelled_by` (`cancelled_by`),
  ADD KEY `fk_service_detail_payment` (`payment_id`);

--
-- Indexes for table `service_station`
--
ALTER TABLE `service_station`
  ADD PRIMARY KEY (`service_station_id`);

--
-- Indexes for table `service_station_contact`
--
ALTER TABLE `service_station_contact`
  ADD PRIMARY KEY (`service_station_contact_id`),
  ADD KEY `fk_ss_contact_station` (`service_station_id`);

--
-- Indexes for table `spare_part`
--
ALTER TABLE `spare_part`
  ADD PRIMARY KEY (`part_id`),
  ADD UNIQUE KEY `part_number` (`part_number`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `template_item_link`
--
ALTER TABLE `template_item_link`
  ADD PRIMARY KEY (`template_id`,`item_id`),
  ADD KEY `fk_link_item` (`item_id`);

--
-- Indexes for table `tender`
--
ALTER TABLE `tender`
  ADD PRIMARY KEY (`tender_id`),
  ADD KEY `fk_tender_user` (`created_by`),
  ADD KEY `fk_tender_part` (`part_id`),
  ADD KEY `fk_tender_awarded_bid` (`awarded_bid`);

--
-- Indexes for table `tour`
--
ALTER TABLE `tour`
  ADD PRIMARY KEY (`tour_id`),
  ADD KEY `fk_tour_invoice` (`invoice_id`);

--
-- Indexes for table `tour_income`
--
ALTER TABLE `tour_income`
  ADD PRIMARY KEY (`tour_income_id`),
  ADD UNIQUE KEY `unique_invoice_payment` (`invoice_id`),
  ADD KEY `fk_ti_user` (`received_by`);

--
-- Indexes for table `transaction_category`
--
ALTER TABLE `transaction_category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_nic` (`user_nic`),
  ADD UNIQUE KEY `user_email` (`user_email`),
  ADD KEY `fk_user_role` (`user_role`);

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
-- AUTO_INCREMENT for table `bid`
--
ALTER TABLE `bid`
  MODIFY `bid_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `bus`
--
ALTER TABLE `bus`
  MODIFY `bus_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `bus_category`
--
ALTER TABLE `bus_category`
  MODIFY `category_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `checklist_item`
--
ALTER TABLE `checklist_item`
  MODIFY `item_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `customer_contact`
--
ALTER TABLE `customer_contact`
  MODIFY `contact_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `customer_invoice`
--
ALTER TABLE `customer_invoice`
  MODIFY `invoice_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `customer_invoice_item`
--
ALTER TABLE `customer_invoice_item`
  MODIFY `item_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `function`
--
ALTER TABLE `function`
  MODIFY `function_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `grn`
--
ALTER TABLE `grn`
  MODIFY `grn_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `inspection`
--
ALTER TABLE `inspection`
  MODIFY `inspection_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `inspection_checklist_response`
--
ALTER TABLE `inspection_checklist_response`
  MODIFY `response_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `inspection_checklist_template`
--
ALTER TABLE `inspection_checklist_template`
  MODIFY `template_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
-- AUTO_INCREMENT for table `part_transaction`
--
ALTER TABLE `part_transaction`
  MODIFY `transaction_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `purchase_order`
--
ALTER TABLE `purchase_order`
  MODIFY `po_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `quotation`
--
ALTER TABLE `quotation`
  MODIFY `quotation_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `quotation_item`
--
ALTER TABLE `quotation_item`
  MODIFY `item_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
  MODIFY `service_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

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
-- AUTO_INCREMENT for table `spare_part`
--
ALTER TABLE `spare_part`
  MODIFY `part_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tender`
--
ALTER TABLE `tender`
  MODIFY `tender_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tour`
--
ALTER TABLE `tour`
  MODIFY `tour_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tour_income`
--
ALTER TABLE `tour_income`
  MODIFY `tour_income_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transaction_category`
--
ALTER TABLE `transaction_category`
  MODIFY `category_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bid`
--
ALTER TABLE `bid`
  ADD CONSTRAINT `fk_bid_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`supplier_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_bid_tender` FOREIGN KEY (`tender_id`) REFERENCES `tender` (`tender_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `bus`
--
ALTER TABLE `bus`
  ADD CONSTRAINT `fk_bus_bus_category` FOREIGN KEY (`category_id`) REFERENCES `bus_category` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_bus_removed_by_user` FOREIGN KEY (`removed_by`) REFERENCES `user` (`user_id`) ON UPDATE CASCADE;

--
-- Constraints for table `bus_tour`
--
ALTER TABLE `bus_tour`
  ADD CONSTRAINT `bus_tour_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`tour_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_bus_tour_bus` FOREIGN KEY (`bus_id`) REFERENCES `bus` (`bus_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `customer_contact`
--
ALTER TABLE `customer_contact`
  ADD CONSTRAINT `fk_customer_contact_customer` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `customer_invoice`
--
ALTER TABLE `customer_invoice`
  ADD CONSTRAINT `fk_invoice_customer` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_invoice_quotation` FOREIGN KEY (`quotation_id`) REFERENCES `quotation` (`quotation_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `customer_invoice_item`
--
ALTER TABLE `customer_invoice_item`
  ADD CONSTRAINT `fk_invoice_item_bus_category` FOREIGN KEY (`category_id`) REFERENCES `bus_category` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_invoice_item_invoice` FOREIGN KEY (`invoice_id`) REFERENCES `customer_invoice` (`invoice_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `function`
--
ALTER TABLE `function`
  ADD CONSTRAINT `fk_function_module` FOREIGN KEY (`module_id`) REFERENCES `module` (`module_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `function_user`
--
ALTER TABLE `function_user`
  ADD CONSTRAINT `fk_function_user_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `function_user_ibfk_1` FOREIGN KEY (`function_id`) REFERENCES `function` (`function_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `grn`
--
ALTER TABLE `grn`
  ADD CONSTRAINT `fk_grn_po` FOREIGN KEY (`po_id`) REFERENCES `purchase_order` (`po_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_grn_user` FOREIGN KEY (`inspected_by`) REFERENCES `user` (`user_id`) ON UPDATE CASCADE;

--
-- Constraints for table `inspection`
--
ALTER TABLE `inspection`
  ADD CONSTRAINT `fk_inspection_bus` FOREIGN KEY (`bus_id`) REFERENCES `bus` (`bus_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_inspection_user` FOREIGN KEY (`inspected_by`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `inspection_checklist_response`
--
ALTER TABLE `inspection_checklist_response`
  ADD CONSTRAINT `fk_response_inspection` FOREIGN KEY (`inspection_id`) REFERENCES `inspection` (`inspection_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_response_item` FOREIGN KEY (`item_id`) REFERENCES `checklist_item` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `inspection_checklist_template`
--
ALTER TABLE `inspection_checklist_template`
  ADD CONSTRAINT `fk_template_bus_category` FOREIGN KEY (`category_id`) REFERENCES `bus_category` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `login`
--
ALTER TABLE `login`
  ADD CONSTRAINT `fk_login_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `part_transaction`
--
ALTER TABLE `part_transaction`
  ADD CONSTRAINT `fk_pt_bus` FOREIGN KEY (`bus_id`) REFERENCES `bus` (`bus_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pt_grn` FOREIGN KEY (`grn_id`) REFERENCES `grn` (`grn_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pt_part` FOREIGN KEY (`part_id`) REFERENCES `spare_part` (`part_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pt_user` FOREIGN KEY (`transacted_by`) REFERENCES `user` (`user_id`) ON UPDATE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `fk_payment_paid_by_user` FOREIGN KEY (`paid_by`) REFERENCES `user` (`user_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_payment_po` FOREIGN KEY (`po_id`) REFERENCES `purchase_order` (`po_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_payment_transaction_category` FOREIGN KEY (`category_id`) REFERENCES `transaction_category` (`category_id`) ON UPDATE CASCADE;

--
-- Constraints for table `purchase_order`
--
ALTER TABLE `purchase_order`
  ADD CONSTRAINT `fk_po_bid` FOREIGN KEY (`bid_id`) REFERENCES `bid` (`bid_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_po_part` FOREIGN KEY (`part_id`) REFERENCES `spare_part` (`part_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_po_user` FOREIGN KEY (`created_by`) REFERENCES `user` (`user_id`) ON UPDATE CASCADE;

--
-- Constraints for table `quotation`
--
ALTER TABLE `quotation`
  ADD CONSTRAINT `fk_quotation_customer` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `quotation_item`
--
ALTER TABLE `quotation_item`
  ADD CONSTRAINT `fk_quotation_item_bus_category` FOREIGN KEY (`category_id`) REFERENCES `bus_category` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_quotation_item_quotation` FOREIGN KEY (`quotation_id`) REFERENCES `quotation` (`quotation_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `role_module`
--
ALTER TABLE `role_module`
  ADD CONSTRAINT `fk_role_module_module` FOREIGN KEY (`module_id`) REFERENCES `module` (`module_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_role_module_role` FOREIGN KEY (`role_id`) REFERENCES `role` (`role_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `service_detail`
--
ALTER TABLE `service_detail`
  ADD CONSTRAINT `fk_service_detail_bus` FOREIGN KEY (`bus_id`) REFERENCES `bus` (`bus_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_service_detail_cancelled_by` FOREIGN KEY (`cancelled_by`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_service_detail_completed_by` FOREIGN KEY (`completed_by`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_service_detail_initiated_by` FOREIGN KEY (`initiated_by`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_service_detail_payment` FOREIGN KEY (`payment_id`) REFERENCES `payment` (`payment_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_service_detail_station` FOREIGN KEY (`service_station_id`) REFERENCES `service_station` (`service_station_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `service_station_contact`
--
ALTER TABLE `service_station_contact`
  ADD CONSTRAINT `fk_ss_contact_station` FOREIGN KEY (`service_station_id`) REFERENCES `service_station` (`service_station_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `template_item_link`
--
ALTER TABLE `template_item_link`
  ADD CONSTRAINT `fk_link_item` FOREIGN KEY (`item_id`) REFERENCES `checklist_item` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_link_template` FOREIGN KEY (`template_id`) REFERENCES `inspection_checklist_template` (`template_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tender`
--
ALTER TABLE `tender`
  ADD CONSTRAINT `fk_tender_awarded_bid` FOREIGN KEY (`awarded_bid`) REFERENCES `bid` (`bid_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tender_part` FOREIGN KEY (`part_id`) REFERENCES `spare_part` (`part_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tender_user` FOREIGN KEY (`created_by`) REFERENCES `user` (`user_id`) ON UPDATE CASCADE;

--
-- Constraints for table `tour`
--
ALTER TABLE `tour`
  ADD CONSTRAINT `fk_tour_invoice` FOREIGN KEY (`invoice_id`) REFERENCES `customer_invoice` (`invoice_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tour_income`
--
ALTER TABLE `tour_income`
  ADD CONSTRAINT `fk_ti_invoice` FOREIGN KEY (`invoice_id`) REFERENCES `customer_invoice` (`invoice_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ti_user` FOREIGN KEY (`received_by`) REFERENCES `user` (`user_id`) ON UPDATE CASCADE;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_user_role` FOREIGN KEY (`user_role`) REFERENCES `role` (`role_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_contact`
--
ALTER TABLE `user_contact`
  ADD CONSTRAINT `user_contact_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
