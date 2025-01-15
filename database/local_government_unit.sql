-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 18, 2024 at 08:29 AM
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
-- Database: `local_government_unit`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(10) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_as` int(11) DEFAULT 2
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `fname`, `lname`, `email`, `password`, `role_as`) VALUES
(1, 'System', 'Administrator', 'admin@mail.com', '$2y$10$BdIfR4fT6w1R4/iwbdoHZO2BGFmfKTiNWoL2psKFWhTkUCBisWMYu', 2),
(5, 'system', 'Administrator', 'admin@gmail.com', '$2y$10$SAo60jZGYfhwdny55joxTOo2iRTVwULuT3KQf6UnLstcJ68txeqoK', 2);

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `id` int(10) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_as` tinyint(4) NOT NULL DEFAULT 0,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`id`, `fname`, `lname`, `email`, `phone`, `address`, `password`, `role_as`, `status`, `created_at`) VALUES
(2, 'Johnmichael', 'Badilla', 'johnmichael@mail.com', '0987654321', 'Manila', '$2y$10$EMeO8NQ/KQuXXwi83gLo6u2WawF5HzY1.HjAVpEVDzhvd6lCDye2u', 1, 0, '2024-09-15 06:59:14'),
(12, 'rickbrian', 'tepase', 'foreducation49@gmail.com', '09103045611', 'san diego', '$2y$10$Ext9UXN.b.06COTEeAkRH.jynU448BsABBeibhIdZlrQihEVHzsuq', 1, 1, '2024-10-09 18:58:57');

-- --------------------------------------------------------

--
-- Table structure for table `registration`
--

CREATE TABLE `registration` (
  `id` int(10) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `mname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `zip` varchar(10) NOT NULL,
  `business_name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `business_address` varchar(255) NOT NULL,
  `building_name` varchar(255) NOT NULL,
  `building_no` varchar(255) NOT NULL,
  `street` varchar(255) NOT NULL,
  `barangay` varchar(255) NOT NULL,
  `business_type` varchar(255) NOT NULL,
  `rent_per_month` varchar(255) NOT NULL,
  `period_date` date DEFAULT NULL,
  `date_application` date NOT NULL,
  `reciept` varchar(255) NOT NULL,
  `or_date` date NOT NULL,
  `amount_paid` decimal(10,2) DEFAULT NULL,
  `upload_dti` varchar(255) NOT NULL,
  `upload_store_picture` varchar(255) DEFAULT NULL,
  `food_security_clearance` varchar(255) DEFAULT NULL,
  `document_status` varchar(50) DEFAULT 'Pending',
  `permit_expiration` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registration`
--

INSERT INTO `registration` (`id`, `fname`, `mname`, `lname`, `address`, `zip`, `business_name`, `phone`, `email`, `business_address`, `building_name`, `building_no`, `street`, `barangay`, `business_type`, `rent_per_month`, `period_date`, `date_application`, `reciept`, `or_date`, `amount_paid`, `upload_dti`, `upload_store_picture`, `food_security_clearance`, `document_status`, `permit_expiration`) VALUES
(11, 'Edgeniel', 'qwer', 'Buhian', 'Manila', '9876', 'walmart', '09123456789', 'edgeniel@mail.com', 'Caloocan City', 'qwer', '12', 'qwe', 'qwer', 'shabu shabu', '500', '2024-09-19', '2024-09-19', '', '2024-09-19', 1000.00, '1726724434profile.png', NULL, NULL, 'Pending', '2025-09-19'),
(79, 'me', 'me ', 'hello', 'san diego', '1234', 'comporate', '2323213', 'james@gmail.com', 'the one ', 'green house', '114', 'dimakilala', 'pasong tamo', 'comporate', '1231231', '2024-10-15', '2024-10-15', 'NA', '2024-10-15', 1000.00, '1728935942olli-the-polite-cat.jpg', '1728935942images.jpg', '1728935942b9c47ef70bff06613d397abfce02c6e7.jpg', 'Pending', '2025-10-15'),
(80, 'brian', 'like', 'tepase', 'pasong tamo', '1234', 'comporate', '09303327150', 'briantepase@gmail.com', 'the one ', 'green house', '114', 'dimakilala', 'pasong tamo', 'comporate', '1231231', NULL, '0000-00-00', 'REC-202410160001', '2024-10-16', 1000.00, '1729039003629544.jpg', '1729039003629544.jpg', '1729039003629544.jpg', 'Pending', NULL),
(82, 'me', 'me ', 'hello', 'san diego', '1234', 'comporate', '2323213', 'james@gmail.com', 'the one ', 'green house', '114', 'dimakilala', 'pasong tamo', 'comporate', '1231231', NULL, '2024-10-18', 'REC-202410170001', '2024-10-18', 1000.00, '1729188008629544.jpg', '1729188008629544.jpg', '1729188008629544.jpg', 'Pending', NULL),
(83, 'me', 'me ', 'hello', 'san diego', '1234', 'comporate', '2323213', 'james@gmail.com', 'the one ', 'green house', '114', 'dimakilala', 'pasong tamo', 'comporate', '1231231', NULL, '2024-10-17', 'REC-202410170002', '2024-10-18', 1000.00, '1729189305629544.jpg', '1729189305629544.jpg', '1729189305629544.jpg', 'Pending', NULL),
(85, 'me', 'me ', 'hello', 'san diego', '1234', 'comporate', '2323213', 'james@gmail.com', 'the one ', 'green house', '114', 'dimakilala', 'pasong tamo', 'comporate', '1231231', NULL, '2024-10-17', 'REC-202410170003', '2024-10-18', 1000.00, '1729191373629544.jpg', '1729191373629544.jpg', '1729191373629544.jpg', 'Pending', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `renewal`
--

CREATE TABLE `renewal` (
  `id` int(10) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `mname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `zip` varchar(10) NOT NULL,
  `business_name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `business_address` varchar(255) DEFAULT NULL,
  `building_name` varchar(255) NOT NULL,
  `building_no` varchar(255) NOT NULL,
  `street` varchar(255) NOT NULL,
  `barangay` varchar(255) NOT NULL,
  `business_type` varchar(255) DEFAULT NULL,
  `rent_per_month` varchar(255) NOT NULL,
  `period_date` date DEFAULT NULL,
  `date_application` date NOT NULL,
  `reciept` varchar(255) NOT NULL,
  `or_date` date NOT NULL,
  `amount_paid` varchar(255) NOT NULL,
  `upload_dti` varchar(255) DEFAULT NULL,
  `upload_store_picture` varchar(255) DEFAULT NULL,
  `food_security_clearance` varchar(255) DEFAULT NULL,
  `upload_old_permit` varchar(255) DEFAULT NULL,
  `document_status` varchar(50) DEFAULT 'Pending',
  `permit_expiration` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `renewal`
--

INSERT INTO `renewal` (`id`, `fname`, `mname`, `lname`, `address`, `zip`, `business_name`, `phone`, `email`, `business_address`, `building_name`, `building_no`, `street`, `barangay`, `business_type`, `rent_per_month`, `period_date`, `date_application`, `reciept`, `or_date`, `amount_paid`, `upload_dti`, `upload_store_picture`, `food_security_clearance`, `upload_old_permit`, `document_status`, `permit_expiration`) VALUES
(1, 'John', 'Michael', 'Badilla', 'Quezon City', '1234', 'walmart', '09876543210', 'johnmichael@gmail.com', 'Manila', 'qwer', '12', 'qwer', 'qwer', 'shabu shabu', '1500', '2024-09-22', '2024-09-22', '', '2024-09-22', '1000', NULL, NULL, NULL, NULL, 'Pending', '2025-09-22'),
(15, 'will', 'like', 'smith', 'san francisco', '1234', 'comporate', '2323213', 'james@gmail.com', 'san diego', 'green house', '114', 'san diego', 'pasong tamo', '12321313', '1231231', '2024-10-08', '2024-10-08', 'NA', '2024-10-08', '1000', NULL, NULL, NULL, NULL, 'Pending', '2025-10-08'),
(16, 'will', 'like', 'smith', 'san francisco', '1234', 'comporate', '2323213', 'james@gmail.com', 'san diego', 'green house', '114', 'san diego', 'pasong tamo', '12321313', '1231231', '2024-10-08', '2024-10-08', 'NA', '2024-10-08', '1000', NULL, NULL, NULL, NULL, 'Pending', '2025-10-08'),
(26, 'me', 'me ', 'hello', 'san diego', '1234', 'comporate', '09303327150', 'james@gmail.com', 'the one ', 'green house', '114', 'dimakilala', 'pasong tamo', 'comporate', '1231231', NULL, '2024-10-17', 'REC-202410170001', '2024-10-18', '1000', '1729190869_olli-the-polite-cat.jpg', '1729190869_olli-the-polite-cat.jpg', '1729190869_olli-the-polite-cat.jpg', '1729190869_olli-the-polite-cat.jpg', 'Pending', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_as` tinyint(4) NOT NULL DEFAULT 0,
  `status` tinyint(4) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fname`, `lname`, `email`, `phone`, `address`, `password`, `role_as`, `status`, `created_at`) VALUES
(1, 'System', 'Administrator', 'admin@mail.com', '', '', '$2y$10$Ro2u.oJHlAxyHUkvBLgvFuh015othrcwZGxN2sMEZEfKhPnqK5GHW', 2, 0, '2024-09-15 02:57:13'),
(18, 'Ed', 'Fernandez', 'ed@mail.com', '09246897531', 'tanza', '$2y$10$Bmk0b9RM28rEXj8nIBwhWeGpqt2k0TO9ZkZ/PXVXOXc9BBHP1oL7.', 0, 0, '2024-09-22 09:18:50'),
(30, 'demo', 'demo', 'demo@gmail.com', '', '', '$2y$10$DLHXAew2rpzDd5DKcj7Nu..7Af4Hyawic7VGc47e1mPBDtxOalszu', 0, 0, '2024-10-04 18:13:37'),
(32, 'rick brian', 'te', 'foreducation48@gmail.com', '0213123123', 'san diego', '$2y$10$ttY5qRB0usP8HRtpiT.N3uaP4x1LLweHho9K56iWxiy39PfTW4joG', 0, 0, '2024-10-08 14:29:10'),
(33, 'brian', 'tepase', 'briantepase@gmail.com', '09303327150', 'pasong tamo', '$2y$10$w9A29u1sxnma58A2Y9kGyOayt2B.IqQqDM0YfB7nmCS.Wcy..gIMK', 0, 0, '2024-10-10 16:57:00'),
(34, 'Demon', 'slayer', 'demonslayer@gmail.com', '123123213', 'pasong tamo', '$2y$10$aR4crLzF1KVcQZTGY.mVAOjsfn.dFo1OqPTG7megbRMX5NYHRL5Di', 0, 0, '2024-10-17 14:46:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `registration`
--
ALTER TABLE `registration`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `renewal`
--
ALTER TABLE `renewal`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `registration`
--
ALTER TABLE `registration`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `renewal`
--
ALTER TABLE `renewal`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
