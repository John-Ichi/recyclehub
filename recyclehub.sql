-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 07, 2025 at 02:31 AM
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
-- Table structure for table `tbadmininfo`
--

CREATE TABLE `tbadmininfo` (
  `adminId` int(11) NOT NULL,
  `adminName` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `datetimeCreated` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbadmininfo`
--

INSERT INTO `tbadmininfo` (`adminId`, `adminName`, `password`, `datetimeCreated`) VALUES
(2, 'admin1', '$2y$10$0UPLRvGhffVRo7G4LjOEyeq84Ta/L8NtMLkVcoSWGvt04miyBMJnW', '2025-11-04 10:45:08');

-- --------------------------------------------------------

--
-- Table structure for table `tbbanlogs`
--

CREATE TABLE `tbbanlogs` (
  `logId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `reason` varchar(250) NOT NULL,
  `datetimeCreated` datetime NOT NULL DEFAULT current_timestamp(),
  `unban` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbbanlogs`
--

INSERT INTO `tbbanlogs` (`logId`, `userId`, `reason`, `datetimeCreated`, `unban`) VALUES
(2, 25, 'You are banned!', '2025-11-06 22:01:05', 1),
(3, 25, 'u are banned', '2025-11-07 09:12:51', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbcomments`
--

CREATE TABLE `tbcomments` (
  `commentId` int(11) NOT NULL,
  `postId` int(11) NOT NULL,
  `commentContent` varchar(250) NOT NULL,
  `userId` int(11) NOT NULL,
  `datetimeCreated` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbcomments`
--

INSERT INTO `tbcomments` (`commentId`, `postId`, `commentContent`, `userId`, `datetimeCreated`) VALUES
(44, 48, 'Test', 25, '2025-11-06 13:00:05'),
(45, 48, 'Test 2', 25, '2025-11-06 13:00:07'),
(46, 48, 'Profile comment test', 25, '2025-11-06 13:00:15');

-- --------------------------------------------------------

--
-- Table structure for table `tbfollows`
--

CREATE TABLE `tbfollows` (
  `id` int(11) NOT NULL,
  `followee` int(11) NOT NULL,
  `follower` int(11) NOT NULL,
  `timeCreated` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbimages`
--

CREATE TABLE `tbimages` (
  `imageId` int(11) NOT NULL,
  `postId` int(11) NOT NULL,
  `image` varchar(250) NOT NULL,
  `userId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbimages`
--

INSERT INTO `tbimages` (`imageId`, `postId`, `image`, `userId`) VALUES
(35, 48, 'uploads/img_690c2b511b4765.36387512.jpg', 25);

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
(20, 'dummyemail2@mail.com', 'dummyuser2', '$2y$10$4nUq1cIFBfV.4Zzi4B0F8u11vm90bQVHEJknJG2Q00L6rE8V79hkK'),
(21, 'dummyemail1@mail.com', 'dummyuser1', '$2y$10$Z6WDo.S.NOcfgQ02mNgBmehGc9z/1oWCHr3vJON8Dwtj1GRXQE5r6'),
(23, 'dummyemail@mail.com', 'dummyuser', '$2y$10$Ld7xuncK4.aCoOVSOGldv.B/Cj6i1z7nAKNtnfXCJHBI184pYz.TO'),
(25, 'dummy@mail.com', 'dummy', '$2y$10$rqAsP5KfAYbA0bVu/RyldeESgCOPBjflLQlA4FYLHKzFHAA/GtwHq');

-- --------------------------------------------------------

--
-- Table structure for table `tbposts`
--

CREATE TABLE `tbposts` (
  `postId` int(11) NOT NULL,
  `content` varchar(250) DEFAULT NULL,
  `userId` int(11) NOT NULL,
  `category` enum('Plastic','Paper','Glass','Wood','Scrap Metal','Other(s)') NOT NULL DEFAULT 'Other(s)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbposts`
--

INSERT INTO `tbposts` (`postId`, `content`, `userId`, `category`) VALUES
(48, 'POGI', 25, 'Plastic');

-- --------------------------------------------------------

--
-- Table structure for table `tbpostsdeletionlog`
--

CREATE TABLE `tbpostsdeletionlog` (
  `logId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `postId` int(11) NOT NULL,
  `content` varchar(250) NOT NULL,
  `purposeOfDeletion` enum('Inappropriate_image(s)','Inappropriate_caption','Inappropriate_comment(s)') NOT NULL,
  `datetimeCreated` datetime NOT NULL DEFAULT current_timestamp(),
  `confirmed` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbpostsdeletionlog`
--

INSERT INTO `tbpostsdeletionlog` (`logId`, `userId`, `postId`, `content`, `purposeOfDeletion`, `datetimeCreated`, `confirmed`) VALUES
(6, 25, 42, 'For deletion', 'Inappropriate_image(s)', '2025-11-06 05:35:28', 1),
(7, 25, 43, 'Test deletion 2', 'Inappropriate_image(s)', '2025-11-06 05:42:27', 1),
(8, 25, 44, 'TEST', 'Inappropriate_caption', '2025-11-06 06:34:33', 1),
(9, 25, 45, 'Test again', 'Inappropriate_image(s)', '2025-11-06 06:35:33', 1),
(10, 25, 46, 'Comment', 'Inappropriate_comment(s)', '2025-11-06 06:36:06', 1),
(11, 25, 47, 'Test refresh', 'Inappropriate_caption', '2025-11-06 12:55:24', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbwarninglogs`
--

CREATE TABLE `tbwarninglogs` (
  `logId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `warningMessage` varchar(250) NOT NULL,
  `datetimeCreated` datetime NOT NULL DEFAULT current_timestamp(),
  `confirmed` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbwarninglogs`
--

INSERT INTO `tbwarninglogs` (`logId`, `userId`, `warningMessage`, `datetimeCreated`, `confirmed`) VALUES
(1, 25, 'You have too many inappropriate posts, your account is in risk of termination\r\n', '2025-11-06 12:47:01', 1),
(2, 20, 'Test multiple warning', '2025-11-06 13:15:01', 0),
(3, 21, 'tes t2', '2025-11-06 13:16:48', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbadmininfo`
--
ALTER TABLE `tbadmininfo`
  ADD PRIMARY KEY (`adminId`),
  ADD UNIQUE KEY `adminName` (`adminName`);

--
-- Indexes for table `tbbanlogs`
--
ALTER TABLE `tbbanlogs`
  ADD PRIMARY KEY (`logId`);

--
-- Indexes for table `tbcomments`
--
ALTER TABLE `tbcomments`
  ADD PRIMARY KEY (`commentId`),
  ADD KEY `tbcomments_ibfk_1` (`postId`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `tbfollows`
--
ALTER TABLE `tbfollows`
  ADD PRIMARY KEY (`id`),
  ADD KEY `followee` (`followee`),
  ADD KEY `follower` (`follower`);

--
-- Indexes for table `tbimages`
--
ALTER TABLE `tbimages`
  ADD PRIMARY KEY (`imageId`),
  ADD KEY `postId` (`postId`),
  ADD KEY `userId` (`userId`);

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
  ADD PRIMARY KEY (`postId`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `tbpostsdeletionlog`
--
ALTER TABLE `tbpostsdeletionlog`
  ADD PRIMARY KEY (`logId`),
  ADD KEY `userId` (`userId`),
  ADD KEY `postId` (`postId`);

--
-- Indexes for table `tbwarninglogs`
--
ALTER TABLE `tbwarninglogs`
  ADD PRIMARY KEY (`logId`),
  ADD KEY `userId` (`userId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbadmininfo`
--
ALTER TABLE `tbadmininfo`
  MODIFY `adminId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbbanlogs`
--
ALTER TABLE `tbbanlogs`
  MODIFY `logId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbcomments`
--
ALTER TABLE `tbcomments`
  MODIFY `commentId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `tbfollows`
--
ALTER TABLE `tbfollows`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `tbimages`
--
ALTER TABLE `tbimages`
  MODIFY `imageId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `tblogininfo`
--
ALTER TABLE `tblogininfo`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `tbposts`
--
ALTER TABLE `tbposts`
  MODIFY `postId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `tbpostsdeletionlog`
--
ALTER TABLE `tbpostsdeletionlog`
  MODIFY `logId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbwarninglogs`
--
ALTER TABLE `tbwarninglogs`
  MODIFY `logId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbcomments`
--
ALTER TABLE `tbcomments`
  ADD CONSTRAINT `tbcomments_ibfk_1` FOREIGN KEY (`postId`) REFERENCES `tbposts` (`postId`),
  ADD CONSTRAINT `tbcomments_ibfk_2` FOREIGN KEY (`userId`) REFERENCES `tblogininfo` (`userId`);

--
-- Constraints for table `tbfollows`
--
ALTER TABLE `tbfollows`
  ADD CONSTRAINT `tbfollows_ibfk_1` FOREIGN KEY (`followee`) REFERENCES `tblogininfo` (`userId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `tbfollows_ibfk_2` FOREIGN KEY (`follower`) REFERENCES `tblogininfo` (`userId`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tbimages`
--
ALTER TABLE `tbimages`
  ADD CONSTRAINT `tbimages_ibfk_1` FOREIGN KEY (`postId`) REFERENCES `tbposts` (`postId`),
  ADD CONSTRAINT `tbimages_ibfk_2` FOREIGN KEY (`userId`) REFERENCES `tblogininfo` (`userId`);

--
-- Constraints for table `tbposts`
--
ALTER TABLE `tbposts`
  ADD CONSTRAINT `tbposts_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `tblogininfo` (`userId`);

--
-- Constraints for table `tbpostsdeletionlog`
--
ALTER TABLE `tbpostsdeletionlog`
  ADD CONSTRAINT `tbpostsdeletionlog_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `tblogininfo` (`userId`);

--
-- Constraints for table `tbwarninglogs`
--
ALTER TABLE `tbwarninglogs`
  ADD CONSTRAINT `tbwarninglogs_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `tblogininfo` (`userId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
