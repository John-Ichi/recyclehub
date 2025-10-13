-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 13, 2025 at 03:20 AM
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
-- Database: `recyclehub`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbimages`
--

CREATE TABLE `tbimages` (
  `imageId` int(11) NOT NULL,
  `postId` int(11) NOT NULL,
  `image` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbimages`
--

INSERT INTO `tbimages` (`imageId`, `postId`, `image`) VALUES
(5, 18, 'uploads/img_68e4a10c35c6b3.84478987.jpg'),
(6, 18, 'uploads/img_68e4a10c362ac9.94242979.jpg'),
(7, 19, 'uploads/img_68e71155419bf3.36525092.jpg'),
(8, 19, 'uploads/img_68e71155425d82.08101148.jpg'),
(9, 20, 'uploads/img_68e711f84709a4.96621234.jpg'),
(10, 20, 'uploads/img_68e711f8477fe7.29873698.jpg'),
(11, 21, 'uploads/img_68e8a8ae325f60.17801515.jpg'),
(12, 21, 'uploads/img_68e8a8ae32c9f8.23487542.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tblogininfo`
--

CREATE TABLE `tblogininfo` (
  `userId` int(11) NOT NULL,
  `userEmail` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblogininfo`
--

INSERT INTO `tblogininfo` (`userId`, `userEmail`, `username`, `password`) VALUES
(17, 'dummyemail@mail.com', 'dummyuser', '$2y$10$lcoQsaPAsjIt6j8prvYYse/c2KTyx4FjccM5iFcFeXKK/nsnFmEUu'),
(18, 'dummyemail1@mail.com', 'dummyuser1', '$2y$10$BSVDMbZB/79zBFyB0VWVdO3XcazccwaPBNkKGvGYTX1tFjdLqD6hq');

-- --------------------------------------------------------

--
-- Table structure for table `tbposts`
--

CREATE TABLE `tbposts` (
  `postId` int(11) NOT NULL,
  `content` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbposts`
--

INSERT INTO `tbposts` (`postId`, `content`) VALUES
(18, 'Test Upload'),
(19, ''),
(20, 'TEST'),
(21, 'HAHA');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbimages`
--
ALTER TABLE `tbimages`
  ADD PRIMARY KEY (`imageId`),
  ADD KEY `postId` (`postId`);

--
-- Indexes for table `tblogininfo`
--
ALTER TABLE `tblogininfo`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `userEmail` (`userEmail`,`username`);

--
-- Indexes for table `tbposts`
--
ALTER TABLE `tbposts`
  ADD PRIMARY KEY (`postId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbimages`
--
ALTER TABLE `tbimages`
  MODIFY `imageId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tblogininfo`
--
ALTER TABLE `tblogininfo`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tbposts`
--
ALTER TABLE `tbposts`
  MODIFY `postId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbimages`
--
ALTER TABLE `tbimages`
  ADD CONSTRAINT `tbimages_ibfk_1` FOREIGN KEY (`postId`) REFERENCES `tbposts` (`postId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
