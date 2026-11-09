-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 09, 2026 at 01:42 PM
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
-- Database: `healtycare_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `purchasedetail`
--

CREATE TABLE `purchasedetail` (
  `detailID` int(11) NOT NULL,
  `purchaseID` int(11) NOT NULL,
  `medID` int(11) NOT NULL,
  `amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `purchasedetail`
--
ALTER TABLE `purchasedetail`
  ADD PRIMARY KEY (`detailID`),
  ADD KEY `purchaseID_fk` (`purchaseID`),
  ADD KEY `medID_fk` (`medID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `purchasedetail`
--
ALTER TABLE `purchasedetail`
  MODIFY `detailID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `purchasedetail`
--
ALTER TABLE `purchasedetail`
  ADD CONSTRAINT `medID_fk` FOREIGN KEY (`medID`) REFERENCES `meds` (`ID`),
  ADD CONSTRAINT `purchaseID_fk` FOREIGN KEY (`purchaseID`) REFERENCES `purchase` (`purchaseID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
