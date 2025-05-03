-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 09, 2022 at 11:13 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `projectdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customerEmail` varchar(50) NOT NULL,
  `customerName` varchar(100) NOT NULL,
  `phoneNumber` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customerEmail`, `customerName`, `phoneNumber`) VALUES
('eunice@gmail.com', 'Eunice', '61234567'),
('marvin@gmail.com', 'Marvin', '65432123'),
('mary@gmail.com', 'Mary', '58674321'),
('tom@gmail.com', 'Tom', '57568291');

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `itemID` int(11) NOT NULL,
  `itemName` varchar(255) NOT NULL,
  `itemDescription` text DEFAULT NULL,
  `stockQuantity` int(11) NOT NULL DEFAULT 0,
  `price` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`itemID`, `itemName`, `itemDescription`, `stockQuantity`, `price`) VALUES
(1, 'NOVEL NF4091 9”All-way Strong Wind Circulation Fan', 'Simple Design with 3D stereo blower Turbo super strong wind up', 30, 500),
(2, 'CS-RZ24YKA 2.5 HP \"Inverter\" Split Type Heat Pump Air-Conditioner', '2.5 HP (Heat Pump Model - With Remote Control)', 65, 20000),
(3, 'QN100B Neo QLED 2K LED LCD TV', 'Infinity Screen, More immersive viewing experience', 55, 13000),
(4, 'M33 5G Smartphone', '6.6” FHD+ Infinity-V Display, 120Hz refresh rate 50MP main camera equipped with small ', 30, 2000),
(5, 'iPhone  13 Pro', '6.1”', 9, 8499),
(6, 'iPhone 13 Pro Max', '6.7”', 10, 9399),
(7, 'Samsung Galaxy S22', ' Nightography camera, storage to hold all your night shots and a way beyond all-day battery', 9, 6298),
(8, 'SONY REON POCKET 3 Wearable Thermo Device', 'REON POCKET is a wearable thermal device that can directly cool or warm the part of the body that the device is in contact with.(Neckband 2 (sold separately))', 10, 1399),
(9, 'PANASONIC LX900H LED LCD TV', '4K Ultra HD 3,840 x 2,160/New Local Dimming Pro Intelligent technology/Support Dolby Vision IQ, automatically adjust the playback settings/HCX (Hollywood Cinema Experience) PRO AI processor/Grade 2 Energy Efficiency Label', 4, 31980),
(10, 'GARMIN Descent MK2i with T1 - Chinese Smart Watch', 'Monitor your gas and depth1 with the advanced, watch-style dive computer that comes with multisport training and smart features.', 0, 16499),
(11, 'GARMIN Forerunner 255 Smart Watch', 'You’re making strides. Now, let’s take the next step. Get the training and recovery insights you’ll need to notch your best time yet.', 9, 2999),
(12, 'PANASONIC EH-ND65 Hair Dryer', '2,000W/3 heat selections/Foldable handle for easy carry and storage/Automatic overheating protective device/AC 220-240V / 1,700-2,000W', 5, 299.9),
(13, 'DYSON V8 Slim™ Fluffy Stick Vacuum Cleaner', 'Lightweight cordless cleaning. Same powerful pickup.', 19, 3180);

-- --------------------------------------------------------

--
-- Table structure for table `itemorders`
--

CREATE TABLE `itemorders` (
  `orderID` varchar(255) NOT NULL,
  `itemID` int(11) NOT NULL,
  `orderQuantity` int(5) NOT NULL,
  `soldPrice` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `itemorders`
--

INSERT INTO `itemorders` (`orderID`, `itemID`, `orderQuantity`, `soldPrice`) VALUES
('1', 1, 2, 1000),
('1', 4, 1, 2000),
('10', 1, 20, 500),
('10', 2, 10, 20000),
('11', 4, 150, 2000),
('12', 12, 15, 299.9),
('13', 3, 20, 13000),
('2', 3, 1, 13000),
('3', 1, 1, 500),
('4', 1, 1, 500),
('4', 6, 1, 9399),
('5', 3, 5, 13000),
('5', 5, 11, 8499),
('6', 2, 25, 20000),
('7', 7, 1, 6298),
('7', 9, 1, 31980),
('7', 11, 1, 2999),
('7', 13, 1, 3180),
('8', 4, 120, 2000),
('9', 8, 10, 1399);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `orderID` varchar(255) NOT NULL,
  `customerEmail` varchar(50) NOT NULL,
  `staffID` varchar(50) NOT NULL,
  `dateTime` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deliveryAddress` varchar(255) DEFAULT NULL,
  `deliveryDate` date DEFAULT NULL,
  `orderAmount` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`orderID`, `customerEmail`, `staffID`, `dateTime`, `deliveryAddress`, `deliveryDate`, `orderAmount`) VALUES
('1', 'mary@gmail.com', 's0001', '2022-03-24 13:12:13', NULL, NULL, 2910),
('10', 'marvin@gmail.com', 's0003', '2022-07-09 15:06:11', NULL, NULL, 203700),
('11', 'marvin@gmail.com', 's0003', '2022-07-09 15:07:36', NULL, NULL, 291000),
('12', 'marvin@gmail.com', 's0003', '2022-07-09 15:09:03', NULL, NULL, 4363.545),
('13', 'marvin@gmail.com', 's0003', '2022-07-09 15:10:23', NULL, NULL, 252200),
('2', 'tom@gmail.com', 's0001', '2022-04-10 14:10:20', 'Flat 8, Chates Farm Court, John Street, Hong Kong', '2022-04-15', 11440),
('3', 'mary@gmail.com', 's0003', '2022-04-12 14:10:20', NULL, NULL, 500),
('4', 'eunice@gmail.com', 's0001', '2022-07-09 14:48:04', NULL, NULL, 9602.03),
('5', 'eunice@gmail.com', 's0001', '2022-07-09 15:03:32', NULL, NULL, 153734.33),
('6', 'marvin@gmail.com', 's0001', '2022-07-09 15:04:22', NULL, NULL, 485000),
('7', 'marvin@gmail.com', 's0001', '2022-07-09 15:04:44', 'HK', '2022-07-15', 43123.29),
('8', 'eunice@gmail.com', 's0001', '2022-07-09 15:05:05', NULL, NULL, 232800),
('9', 'marvin@gmail.com', 's0001', '2022-07-09 15:05:19', NULL, NULL, 13570.3);

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staffID` varchar(50) NOT NULL,
  `staffName` varchar(100) NOT NULL,
  `password` varchar(50) NOT NULL,
  `position` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staffID`, `staffName`, `password`, `position`) VALUES
('s0001', 'Chan Tai Man', 'a123', 'Staff'),
('s0002', 'Wong Ka Ho', 'b123', 'Manager'),
('s0003', 'Chan ka Chung', 'c123', 'Staff');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customerEmail`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`itemID`);

--
-- Indexes for table `itemorders`
--
ALTER TABLE `itemorders`
  ADD PRIMARY KEY (`orderID`,`itemID`),
  ADD KEY `FKItemOrders932280` (`itemID`),
  ADD KEY `FKItemOrders159103` (`orderID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orderID`),
  ADD KEY `FKOrders837071` (`customerEmail`),
  ADD KEY `FKOrders846725` (`staffID`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staffID`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `itemorders`
--
ALTER TABLE `itemorders`
  ADD CONSTRAINT `FKItemOrders159103` FOREIGN KEY (`orderID`) REFERENCES `orders` (`orderID`),
  ADD CONSTRAINT `FKItemOrders932280` FOREIGN KEY (`itemID`) REFERENCES `item` (`itemID`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `FKOrders837071` FOREIGN KEY (`customerEmail`) REFERENCES `customer` (`customerEmail`),
  ADD CONSTRAINT `FKOrders846725` FOREIGN KEY (`staffID`) REFERENCES `staff` (`staffID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
