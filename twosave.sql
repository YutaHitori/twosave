-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 10, 2026 at 06:17 AM
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
-- Database: `twosave`
--

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `REPORT_ID` int(11) NOT NULL,
  `USER_ID` int(11) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `severity` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `datetime` datetime DEFAULT current_timestamp(),
  `log` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `report`
--

INSERT INTO `report` (`REPORT_ID`, `USER_ID`, `title`, `details`, `severity`, `status`, `datetime`, `log`) VALUES
(8, 15, 'a', 'aa', NULL, 'pending', '2025-06-10 17:21:26', '2025-06-10 12:21:26pm\nreport created');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_log`
--

CREATE TABLE `transaction_log` (
  `LOG_ID` int(11) NOT NULL,
  `USER_ID` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `note` text DEFAULT '-'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction_log`
--

INSERT INTO `transaction_log` (`LOG_ID`, `USER_ID`, `date`, `amount`, `note`) VALUES
(43, 15, '2025-05-29', -15000.00, 'gitu'),
(44, 15, '2025-05-29', -15000.00, 'gitu'),
(45, 15, '2025-05-29', -15000.00, 'gitu'),
(46, 15, '2025-05-29', -15000.00, 'gitu'),
(47, 15, '2025-05-29', -15000.00, 'gitu'),
(48, 15, '2025-05-29', -15000.00, 'gitu'),
(49, 15, '2025-05-29', -15000.00, 'gitu'),
(50, 15, '2025-05-29', -15000.00, 'gitu'),
(51, 15, '2025-05-29', -15000.00, 'gitu'),
(52, 15, '2025-05-29', -15000.00, 'gitu'),
(53, 15, '2025-05-29', -15000.00, 'gitu'),
(54, 15, '2025-05-29', -15000.00, 'gitu'),
(55, 15, '2025-05-29', -15000.00, 'gitu'),
(56, 15, '2025-05-29', -15000.00, 'gitu'),
(57, 15, '2025-05-29', -15000.00, 'gitu'),
(58, 15, '2025-05-29', -15000.00, 'gitu'),
(59, 15, '2025-05-29', -15000.00, 'gitu'),
(60, 15, '2025-05-29', -15000.00, 'gitu'),
(61, 15, '2025-06-04', 100000.00, ''),
(62, 15, '2025-06-04', 300000.00, ''),
(63, 15, '2025-06-04', -72000.00, 'buku'),
(64, 15, '2025-06-18', 17000.00, 'makanan'),
(65, 15, '2025-06-18', 0.00, 'Fulfil wish felix'),
(66, 15, '2025-06-18', -11.11, 'Fulfil wish felix'),
(67, 15, '2025-06-24', 0.00, ''),
(68, 15, '2025-06-24', -45555.00, 'Fulfiled Wish \"1\"'),
(69, 15, '2025-07-07', -1.00, 'Fulfiled Wish \"a\"'),
(70, 15, '2025-07-07', 0.00, 'test'),
(71, 15, '2025-07-15', -1222.00, 'Fulfiled Wish \"awerwewe\"'),
(72, 15, '2025-07-15', -1222.00, 'Fulfiled Wish \"awerwewe\"'),
(73, 15, '2025-07-15', -1222.00, 'Fulfiled Wish \"awerwewe\"'),
(74, 15, '2025-07-15', -1222.00, 'Fulfiled Wish \"awerwewe\"'),
(75, 15, '2025-07-15', 150000.00, 'testaa'),
(76, 15, '2025-07-15', -4.00, 'Fulfiled Wish \"test \"\"'),
(77, 15, '2025-07-15', -133.00, 'Fulfiled Wish \":3 \"'),
(78, 15, '2025-07-15', -1.00, 'Fulfiled Wish \"<script>alert(1)</script>\"'),
(79, 15, '2025-07-15', -4.00, 'Fulfiled Wish \"\' \" ;\"'),
(80, 15, '2026-06-10', -45555.00, 'Fulfiled Wish \"1\"');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `USER_ID` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `role` varchar(10) DEFAULT 'member',
  `balance` decimal(10,2) DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `auth_token` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`USER_ID`, `name`, `email`, `password`, `role`, `balance`, `date_created`, `auth_token`) VALUES
(1, 'ADMIN', 'ADMIN@NIMDA', '$2y$10$2pN2MHKf9fdrH7hGVdlS/.S20UBcLFAbln91Jz0lzjcaozQFRKN/e', 'ADMIN', 0.00, '2025-06-18 21:57:19', '7d9b081077a9f0a4a8722e705ba1f39c05d41ef0abe1b098efc69de89488c0f9'),
(15, 'サファ', 'safa@gmail.com', '$2y$10$ioKiUAWgimiU1kmnl2h/ieS5bjn8tdsrgBB193yba2VW19ZlU6VIG', 'member', 105625.89, '2025-06-18 21:57:19', 'bd08a50ed4192956a107ef25f0d08fcc8f2836f4e5c5364b833787de52ddd983');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `WISHLIST_ID` int(11) NOT NULL,
  `USER_ID` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `priority` varchar(7) DEFAULT NULL,
  `target` date DEFAULT '9999-12-31',
  `goal` tinyint(1) NOT NULL DEFAULT 0,
  `fulfilled` tinyint(1) NOT NULL DEFAULT 0,
  `date_fulfilled` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`WISHLIST_ID`, `USER_ID`, `name`, `price`, `priority`, `target`, `goal`, `fulfilled`, `date_fulfilled`) VALUES
(2, 15, 'hapee', 12000000, 'medium', '2025-06-09', 0, 0, NULL),
(45, 15, '1', 45555, 'medium', '9999-12-31', 0, 1, '2026-06-10'),
(88, 15, 'felix', 11, 'high', '2025-07-30', 0, 1, '2025-06-18'),
(93, 15, 'mare', 666666, 'medium', '2025-07-30', 0, 0, NULL),
(101, 15, '<script>alert(1)</script>;', 1, 'unset', '9999-12-31', 0, 1, '2025-07-15'),
(103, 15, '\' \" ;', 4, 'unset', '9999-12-31', 0, 1, '2025-07-15'),
(104, 15, 'a', 0, 'unset', '9999-12-31', 0, 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`REPORT_ID`),
  ADD KEY `ID_USER` (`USER_ID`);

--
-- Indexes for table `transaction_log`
--
ALTER TABLE `transaction_log`
  ADD PRIMARY KEY (`LOG_ID`),
  ADD KEY `ID_USER` (`USER_ID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`USER_ID`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`WISHLIST_ID`),
  ADD KEY `ID_USER` (`USER_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `report`
--
ALTER TABLE `report`
  MODIFY `REPORT_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `transaction_log`
--
ALTER TABLE `transaction_log`
  MODIFY `LOG_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `USER_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `WISHLIST_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `report`
--
ALTER TABLE `report`
  ADD CONSTRAINT `report_ibfk_1` FOREIGN KEY (`USER_ID`) REFERENCES `user` (`USER_ID`);

--
-- Constraints for table `transaction_log`
--
ALTER TABLE `transaction_log`
  ADD CONSTRAINT `transaction_log_ibfk_1` FOREIGN KEY (`USER_ID`) REFERENCES `user` (`USER_ID`);

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`USER_ID`) REFERENCES `user` (`USER_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
