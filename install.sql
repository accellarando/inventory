-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 12, 2022 at 05:16 PM
-- Server version: 10.7.3-MariaDB
-- PHP Version: 7.4.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `apple`
--

CREATE TABLE `apple` (
  `id` int(10) UNSIGNED NOT NULL,
  `store` int(11) NOT NULL,
  `class` int(11) NOT NULL,
  `sku` int(11) NOT NULL,
  `vendorStyleNum` varchar(30) DEFAULT NULL,
  `description` varchar(80) DEFAULT NULL,
  `QTY` int(11) NOT NULL,
  `vendorNum` varchar(20) DEFAULT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `apple_scanned`
--

CREATE TABLE `apple_scanned` (
  `id` int(10) UNSIGNED NOT NULL,
  `sku` varchar(22) DEFAULT NULL,
  `scanner_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `QTY` int(11) NOT NULL,
  `vendorStyleNum` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `bad_tags`
--

CREATE TABLE `bad_tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `sku` varchar(22) NOT NULL,
  `qty` int(11) NOT NULL,
  `date_scanned` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `completed_fixtures`
--

CREATE TABLE `completed_fixtures` (
  `id` int(11) NOT NULL,
  `store` int(11) NOT NULL,
  `department` int(11) NOT NULL,
  `fixture` text NOT NULL,
  `time_completed` timestamp NOT NULL DEFAULT current_timestamp(),
  `scanner_id` int(11) NOT NULL,
  `comments` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `fromfile`
--

CREATE TABLE `fromfile` (
  `id` int(11) NOT NULL,
  `SKU` varchar(22) NOT NULL,
  `classCode` int(11) NOT NULL,
  `retailPrice` varchar(8) NOT NULL,
  `description` varchar(80) NOT NULL,
  `usedPrice` varchar(80) DEFAULT NULL,
  `storeNumber` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;


--
-- Table structure for table `scanned`
--

CREATE TABLE `scanned` (
  `id` int(11) NOT NULL,
  `dept` varchar(50) NOT NULL,
  `store` int(11) NOT NULL,
  `fixtureNum` varchar(20) NOT NULL,
  `shelfNum` varchar(20) DEFAULT NULL,
  `boxNum` varchar(20) DEFAULT NULL,
  `QTY` int(11) NOT NULL,
  `sku` varchar(22) NOT NULL,
  `scanner_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(25) NOT NULL,
  `password` varchar(60) NOT NULL,
  `name` varchar(70) NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT current_timestamp(),
  `clearance` int(11) NOT NULL DEFAULT 0,
  `manager_id` int(11) NOT NULL DEFAULT 1,
  `temp_password` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `apple`
--
ALTER TABLE `apple`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `apple_scanned`
--
ALTER TABLE `apple_scanned`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bad_tags`
--
ALTER TABLE `bad_tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `completed_fixtures`
--
ALTER TABLE `completed_fixtures`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fromfile`
--
ALTER TABLE `fromfile`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `fromfile` ADD FULLTEXT KEY `SKU` (`SKU`);

--
-- Indexes for table `scanned`
--
ALTER TABLE `scanned`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `scanned` ADD FULLTEXT KEY `sku` (`sku`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `apple`
--
ALTER TABLE `apple`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `apple_scanned`
--
ALTER TABLE `apple_scanned`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bad_tags`
--
ALTER TABLE `bad_tags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `completed_fixtures`
--
ALTER TABLE `completed_fixtures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fromfile`
--
ALTER TABLE `fromfile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `scanned`
--
ALTER TABLE `scanned`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
