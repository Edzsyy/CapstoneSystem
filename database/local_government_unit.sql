-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 20, 2025 at 08:00 AM
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
-- Table structure for table `ai_settings`
--

CREATE TABLE `ai_settings` (
  `id` int(11) NOT NULL,
  `notify_days` int(11) NOT NULL,
  `notify_message` text NOT NULL,
  `expired_message` text NOT NULL,
  `organization_name` varchar(255) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `organization_address` text NOT NULL,
  `website_link` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ai_settings`
--

INSERT INTO `ai_settings` (`id`, `notify_days`, `notify_message`, `expired_message`, `organization_name`, `contact_number`, `organization_address`, `website_link`, `created_at`, `updated_at`) VALUES
(1, 39, 'Subject: Important Notice: Business Permit Renewal Reminder\n\nDear [Business Owner Name],\n\nThis message serves as a notification that your business permit with unifiedlgu is set to expire in 39 days, on [Expiration Date].  To ensure uninterrupted operation of your business, we kindly urge you to renew your permit as soon as possible.\n\nEarly renewal will prevent any potential delays or disruptions to your business activities.  You can conveniently renew your permit online through our website: https://businesspermit.unifiedlgu.com/\n\nFor assistance or inquiries regarding the renewal process, please do not hesitate to contact us at 09152130678 or visit our office at k1 brgy pansol Q.C.\n\nWe appreciate your cooperation and look forward to assisting you with your renewal.\n\n\nSincerely,\n\nThe unifiedlgu Team', 'Subject: Urgent: Business Permit Renewal Required for [Business Name]\n\nDear [Business Owner Name],\n\nThis letter serves as an official notification that the business permit for [Business Name], located at [Business Address], has expired on [Expiration Date].  Failure to renew your permit promptly may result in penalties and potential legal repercussions.\n\nWe understand that managing a business requires significant attention, and we want to assist you in maintaining compliance.  To ensure uninterrupted operation, we urge you to renew your business permit immediately.\n\nYou can renew your permit online through our website: https://businesspermit.unifiedlgu.com/ or visit our office at k1 brgy pansol Q.C.  For any inquiries or assistance, please do not hesitate to contact us at 09152130678.\n\nWe appreciate your prompt attention to this matter.  Please renew your permit within [Number] days to avoid penalties.\n\nSincerely,\n\nThe unifiedlgu Team', 'unifiedlgu', '09152130678', 'k1 brgy pansol Q.C', 'https://businesspermit.unifiedlgu.com/', '2025-02-20 06:36:13', '2025-02-20 06:36:13');

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
  `date_application` date NOT NULL,
  `application_number` varchar(50) DEFAULT NULL,
  `upload_dti` varchar(255) NOT NULL,
  `upload_store_picture` varchar(255) DEFAULT NULL,
  `food_security_clearance` varchar(255) DEFAULT NULL,
  `document_status` varchar(50) DEFAULT 'Pending',
  `permit_expiration` date DEFAULT NULL,
  `needs_resubmission` tinyint(1) DEFAULT 0,
  `expiration_date` date DEFAULT NULL,
  `application_status` varchar(50) DEFAULT NULL,
  `remark` text DEFAULT NULL,
  `release_date` datetime DEFAULT NULL,
  `released_by` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registration`
--

INSERT INTO `registration` (`id`, `fname`, `mname`, `lname`, `address`, `zip`, `business_name`, `phone`, `email`, `business_address`, `building_name`, `building_no`, `street`, `barangay`, `business_type`, `rent_per_month`, `date_application`, `application_number`, `upload_dti`, `upload_store_picture`, `food_security_clearance`, `document_status`, `permit_expiration`, `needs_resubmission`, `expiration_date`, `application_status`, `remark`, `release_date`, `released_by`) VALUES
(11, 'Edgeniel', 'qwer', 'Buhian', 'Manila', '9876', 'walmart', '09123456789', 'edgeniel@mail.com', 'Caloocan City', 'qwer', '12', 'qwe', 'qwer', 'shabu shabu', '500', '2024-09-19', '', '1726724434profile.png', NULL, NULL, 'Rejected', '2025-09-19', 1, NULL, NULL, NULL, NULL, NULL),
(85, 'me', 'me ', 'hello', 'san diego', '1234', 'comporate', '2323213', 'james@gmail.com', 'the one ', 'green house', '114', 'dimakilala', 'pasong tamo', 'comporate', '1231231', '2024-10-17', 'REC-202410170003', '1729191373629544.jpg', '1729191373629544.jpg', '1729191373629544.jpg', 'Approved', NULL, 0, NULL, NULL, NULL, NULL, NULL),
(87, 'brian', 'like', 'tepase', 'pasong tamo', '1234', 'comporate', '09303327150', 'briantepase@gmail.com', 'the one ', 'green house', '114', 'dimakilala', 'pasong tamo', 'comporate', '1231231', '2024-10-25', 'REC-202410250002', '1729874940olli-the-polite-cat.jpg', '1729874940olli-the-polite-cat.jpg', '1729874940olli-the-polite-cat.jpg', 'Rejected', NULL, 1, NULL, NULL, NULL, NULL, NULL),
(91, 'for', 'me ', 'education', 'san diego', '1234', 'comporate', '2323213', 'james@gmail.com', 'the one ', 'green house', '114', 'dimakilala', 'pasong tamo', 'comporate', '1231231', '0000-00-00', 'APP-202501250001', '1737836756629544.jpg', '1737836756629544.jpg', '1737836756629544.jpg', 'Approved', NULL, 0, NULL, 'Released', NULL, NULL, NULL),
(92, 'for', 'me ', 'education', 'san diego', '1234', 'comporate', '2323213', 'james@gmail.com', 'the one ', 'green house', '114', 'dimakilala', 'pasong tamo', 'comporate', '1231231', '0000-00-00', 'APP-202501250002', '1737836795629544.jpg', '1737836795629544.jpg', '1737836795b9c47ef70bff06613d397abfce02c6e7.jpg', 'Approved', NULL, 0, NULL, 'Released', NULL, NULL, NULL),
(93, 'for', 'me ', 'education', 'san diego', '1234', 'comporate', '2323213', 'james@gmail.com', 'the one ', 'green house', '114', 'dimakilala', 'pasong tamo', 'comporate', '1231231', '0000-00-00', 'APP-202501310001', '1738310672629544.jpg', '1738310672629544.jpg', '1738310672629544.jpg', 'Approved', NULL, 0, NULL, 'Released', NULL, NULL, NULL),
(94, 'brian', 'like', 'tepase', 'pasong tamo', '1234', 'comporate', '09303327150', 'briantepase@gmail.com', 'the one ', 'green house', '114', 'dimakilala', 'pasong tamo', 'comporate', '1231231', '0000-00-00', 'APP-202501310002', '1738312843b9c47ef70bff06613d397abfce02c6e7.jpg', '1738312843b9c47ef70bff06613d397abfce02c6e7.jpg', '1738312843b9c47ef70bff06613d397abfce02c6e7.jpg', 'Rejected', NULL, 0, NULL, 'released', NULL, '2025-02-19 06:09:29', NULL),
(97, 'will', 'like', 'smith', 'san francisco', '1234', 'comporate', '2323213', 'james@gmail.com', 'the one ', 'green house', '114', 'dimakilala', 'pasong tamo', 'comporate', '1231231', '2025-02-10', 'APP-202502100001', '1739174832_629544.jpg', '1739174832_629544.jpg', '1739174832_629544.jpg', 'Rejected', NULL, 0, NULL, 'released', NULL, '2025-02-19 06:29:57', NULL),
(98, 'will', 'like', 'smith', 'san francisco', '1234', 'comporate', '2323213', 'james@gmail.com', 'the one ', 'green house', '114', 'dimakilala', 'pasong tamo', 'comporate', '1231231', '2025-02-10', 'APP-202502100002', '1739174951_olli-the-polite-cat.jpg', '1739174951_olli-the-polite-cat.jpg', '1739174951_olli-the-polite-cat.jpg', 'Approved', NULL, 0, NULL, 'Released', NULL, NULL, NULL),
(99, 'will', 'like', 'smith', 'san francisco', '1234', 'comporate', '2323213', 'james@gmail.com', 'the one ', 'green house', '114', 'dimakilala', 'pasong tamo', 'comporate', '1231231', '2025-02-10', 'APP-202502100003', '1739175038_olli-the-polite-cat.jpg', '1739175038_olli-the-polite-cat.jpg', '1739175038_olli-the-polite-cat.jpg', 'Approved', NULL, 0, NULL, 'Released', NULL, NULL, NULL),
(100, 'will', 'like', 'smith', 'san francisco', '1234', 'comporate', '2323213', 'james@gmail.com', 'the one ', 'green house', '114', 'dimakilala', 'pasong tamo', 'comporate', '1231231', '2025-02-10', 'APP-202502100004', '1739175916_olli-the-polite-cat.jpg', '1739175916_olli-the-polite-cat.jpg', '1739175916_olli-the-polite-cat.jpg', 'Approved', NULL, 0, NULL, 'Released', NULL, NULL, NULL),
(101, 'will', 'like', 'smith', 'san francisco', '1234', 'comporate', '2323213', 'james@gmail.com', 'the one ', 'green house', '114', 'dimakilala', 'pasong tamo', 'comporate', '1231231', '2025-02-10', 'APP-202502100005', '1739200291_maxresdefault.jpg', '1739200291_maxresdefault.jpg', '1739200291_maxresdefault.jpg', 'Rejected', NULL, 0, NULL, 'Needs Correction', NULL, NULL, NULL);

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
  `date_application` date NOT NULL,
  `application_number` varchar(50) DEFAULT NULL,
  `upload_dti` varchar(255) DEFAULT NULL,
  `upload_store_picture` varchar(255) DEFAULT NULL,
  `food_security_clearance` varchar(255) DEFAULT NULL,
  `upload_old_permit` varchar(255) DEFAULT NULL,
  `document_status` varchar(50) DEFAULT 'Pending',
  `permit_expiration` date DEFAULT NULL,
  `needs_resubmission` tinyint(1) DEFAULT 0,
  `expiration_date` date DEFAULT NULL,
  `application_status` varchar(50) DEFAULT NULL,
  `remark` text DEFAULT NULL,
  `release_date` datetime DEFAULT NULL,
  `released_by` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `renewal`
--

INSERT INTO `renewal` (`id`, `fname`, `mname`, `lname`, `address`, `zip`, `business_name`, `phone`, `email`, `business_address`, `building_name`, `building_no`, `street`, `barangay`, `business_type`, `rent_per_month`, `date_application`, `application_number`, `upload_dti`, `upload_store_picture`, `food_security_clearance`, `upload_old_permit`, `document_status`, `permit_expiration`, `needs_resubmission`, `expiration_date`, `application_status`, `remark`, `release_date`, `released_by`) VALUES
(1, 'John', 'Michael', 'Badilla', 'Quezon City', '1234', 'walmart', '09876543210', 'johnmichael@gmail.com', 'Manila', 'qwer', '12', 'qwer', 'qwer', 'shabu shabu', '1500', '2024-09-22', '', NULL, NULL, NULL, NULL, 'Rejected', '2025-09-22', 1, NULL, NULL, NULL, NULL, NULL),
(26, 'me', 'me ', 'hello', 'san diego', '1234', 'comporate', '09303327150', 'james@gmail.com', 'the one ', 'green house', '114', 'dimakilala', 'pasong tamo', 'comporate', '1231231', '2024-10-17', 'REC-202410170001', '1729190869_olli-the-polite-cat.jpg', '1729190869_olli-the-polite-cat.jpg', '1729190869_olli-the-polite-cat.jpg', '1729190869_olli-the-polite-cat.jpg', 'Approved', NULL, 1, NULL, 'Released', NULL, NULL, NULL),
(27, 'brian', 'like', 'tepase', 'pasong tamo', '1234', 'comporate', '09303327150', 'briantepase@gmail.com', 'the one ', 'green house', '114', 'dimakilala', 'pasong tamo', 'comporate', '1231231', '2025-01-31', 'APP-202501310001', '1738320127_b9c47ef70bff06613d397abfce02c6e7.jpg', '1738320127_b9c47ef70bff06613d397abfce02c6e7.jpg', '1738320127_b9c47ef70bff06613d397abfce02c6e7.jpg', '1738320127_b9c47ef70bff06613d397abfce02c6e7.jpg', 'Rejected', NULL, 0, NULL, 'Needs Correction', NULL, NULL, NULL),
(29, 'HGJGJGH', 'me ', 'education', 'san diego', '1234', 'comporate', '2323213', 'james@gmail.com', 'the one ', 'green house', '114', 'dimakilala', 'pasong tamo', 'comporate', '1231231', '2025-01-31', 'APP-202501310003', '1738322740_images.jpg', '1738322740_images.jpg', '1738322740_images.jpg', '1738322740_images.jpg', 'Approved', NULL, 0, NULL, 'Released', NULL, NULL, NULL),
(30, '213131', 'me ', 'education', 'san diego', '1234', 'comporate', '2323213', 'james@gmail.com', 'the one ', 'green house', '114', 'dimakilala', 'pasong tamo', 'comporate', '1231231', '2025-01-31', 'APP-202501310004', '1738323437_olli-the-polite-cat.jpg', '1738323437_olli-the-polite-cat.jpg', '1738323437_olli-the-polite-cat.jpg', '1738323437_olli-the-polite-cat.jpg', 'Approved', NULL, 0, NULL, 'Released', NULL, NULL, NULL),
(31, '213131', 'me ', 'education', 'san diego', '1234', 'comporate', '2323213', 'james@gmail.com', 'the one ', 'green house', '114', 'dimakilala', 'pasong tamo', 'comporate', '1231231', '2025-01-31', 'APP-202501310005', '1738323489_olli-the-polite-cat.jpg', '1738323489_olli-the-polite-cat.jpg', '1738323489_olli-the-polite-cat.jpg', '1738323489_olli-the-polite-cat.jpg', 'Approved', NULL, 0, NULL, 'Released', NULL, NULL, NULL),
(39, 'will', 'like', 'smith', 'san francisco', '1234', 'comporate', '2323213', 'james@gmail.com', 'the one ', 'green house', '114', 'dimakilala', 'pasong tamo', 'comporate', '1231231', '2025-02-10', 'APP-202502100001', '1739175784_629544.jpg', '1739175784_olli-the-polite-cat.jpg', '1739175784_629544.jpg', '1739175784_629544.jpg', 'Approved', NULL, 0, NULL, 'Released', NULL, NULL, NULL),
(43, 'will', 'like', 'smith', 'san francisco', '1234', 'comporate', '2323213', 'james@gmail.com', 'the one ', 'green house', '114', 'dimakilala', 'pasong tamo', 'comporate', '1231231', '2025-02-13', 'APP-202502130001', '1739449820_maxresdefault.jpg', '1739449820_maxresdefault.jpg', '1739449820_maxresdefault.jpg', '1739449820_maxresdefault.jpg', 'Approved', NULL, 0, NULL, 'Released', NULL, NULL, NULL),
(44, 'will', 'like', 'smith', 'san francisco', '1234', 'comporate', '2323213', 'james@gmail.com', 'the one ', 'green house', '114', 'dimakilala', 'pasong tamo', 'comporate', '1231231', '2025-02-13', 'APP-202502130002', '1739452796_maxresdefault.jpg', '1739452796_maxresdefault.jpg', '1739452796_maxresdefault.jpg', '1739452796_maxresdefault.jpg', 'Approved', NULL, 0, NULL, 'Released', NULL, NULL, NULL),
(45, 'will', 'like', 'smith', 'san francisco', '1234', 'comporate', '2323213', 'james@gmail.com', 'the one ', 'green house', '114', 'dimakilala', 'pasong tamo', 'comporate', '1231231', '2025-02-13', 'APP-202502130003', '1739453043_maxresdefault.jpg', '1739453043_maxresdefault.jpg', '1739453043_maxresdefault.jpg', '1739453043_maxresdefault.jpg', 'Rejected', NULL, 0, NULL, 'Needs Correction', NULL, NULL, NULL);

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
(36, 'for', 'education', 'foreducation49@gmail.com', '09103045611', 'pasong tamo', '$2y$10$ZIvV3BHLj66l9JpaL4kmPeYQGb1FIXIi92YeImR/pYGFS7I/lwUUK', 0, 0, '2024-10-27 07:45:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ai_settings`
--
ALTER TABLE `ai_settings`
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
-- AUTO_INCREMENT for table `ai_settings`
--
ALTER TABLE `ai_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `registration`
--
ALTER TABLE `registration`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `renewal`
--
ALTER TABLE `renewal`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
