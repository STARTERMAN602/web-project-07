-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 16, 2026 at 04:07 PM
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
-- Database: `healthycare_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `ID` int(11) NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('Admin','Customer') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`ID`, `username`, `email`, `password`, `role`) VALUES
(1, 'Arnold', 'arnold@gmail.com', 'Arnold123', 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `meds`
--

CREATE TABLE `meds` (
  `ID` int(11) NOT NULL,
  `Name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Category` enum('Paracetamol','Ibuprofen','Vitamins','Stomach Medication') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Type` enum('Tablet','Capsule','Syrup') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Price` int(11) NOT NULL,
  `Stock` int(11) NOT NULL,
  `Image` varchar(100) DEFAULT NULL,
  `isDeleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `meds`
--

INSERT INTO `meds` (`ID`, `Name`, `Category`, `Type`, `Price`, `Stock`, `Image`, `isDeleted`) VALUES
(1, 'Panadol', 'Paracetamol', 'Capsule', 8000, 50, '1794838898_6afb11725ffd5.jpg', 0),
(2, 'Paracetamol 500mg', 'Paracetamol', 'Tablet', 10000, 15, '1794838948_6afb11a45f9fa.webp', 0),
(3, 'Sanmol', 'Paracetamol', 'Tablet', 7000, 3, '1794839006_6afb11de8cb12.jpg', 0),
(4, 'Proris Ibuprofen 200mg', 'Ibuprofen', 'Tablet', 12000, 25, '1794839058_6afb12125a005.jpeg', 0),
(5, 'Proris Ibuprofen Sirup', 'Ibuprofen', 'Syrup', 18000, 15, '1794839115_6afb124b0db90.webp', 0),
(6, 'Zentarin', 'Ibuprofen', 'Tablet', 10000, 37, '1794839159_6afb12773086c.jpg', 0),
(7, 'Caviplex', 'Vitamins', 'Tablet', 14000, 13, '1794839207_6afb12a71b466.png', 0),
(8, 'VitalongC', 'Vitamins', 'Capsule', 18000, 7, '1794839256_6afb12d849910.jpg', 0),
(9, 'Imboost kid', 'Vitamins', 'Syrup', 20000, 0, '1794839315_6afb1313e6092.jpg', 0),
(10, 'Mylanta', 'Stomach Medication', 'Syrup', 12000, 15, '1794841510_6afb1ba6637a2.jpg', 0),
(11, 'Promag', 'Stomach Medication', 'Tablet', 8000, 45, '1794841545_6afb1bc9648a5.jpg', 0),
(12, 'Polysilane', 'Stomach Medication', 'Tablet', 9000, 21, '1794841591_6afb1bf746e0c.jpg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `purchase`
--

CREATE TABLE `purchase` (
  `purchaseID` int(11) NOT NULL,
  `customerID` int(11) NOT NULL,
  `purchaseTime` datetime NOT NULL,
  `totalPurchase` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `meds`
--
ALTER TABLE `meds`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Med_Name` (`Name`);

--
-- Indexes for table `purchase`
--
ALTER TABLE `purchase`
  ADD PRIMARY KEY (`purchaseID`),
  ADD KEY `customerID_fk` (`customerID`);

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
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `meds`
--
ALTER TABLE `meds`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `purchase`
--
ALTER TABLE `purchase`
  MODIFY `purchaseID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchasedetail`
--
ALTER TABLE `purchasedetail`
  MODIFY `detailID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `purchase`
--
ALTER TABLE `purchase`
  ADD CONSTRAINT `customerID_fk` FOREIGN KEY (`customerID`) REFERENCES `accounts` (`ID`);

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
