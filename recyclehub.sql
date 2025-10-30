-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 30, 2025 at 04:03 AM
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
(13, 23, 'uploads/img_68ece410a68312.56239217.jpg', 20),
(14, 24, 'uploads/img_68ee14aeb70016.40697872.jpg', 21),
(15, 24, 'uploads/img_68ee14aeb77d91.46524555.jpg', 21),
(16, 25, 'uploads/img_68ee2919347387.63444999.jpg', 21),
(17, 28, 'uploads/img_68f055bf4555b1.15851251.jpg', 23),
(18, 29, 'uploads/img_68f05692a880e3.09214337.jpg', 23),
(19, 30, 'uploads/img_6902cab9ce4d22.50460770.jpg', 25),
(20, 31, 'uploads/img_6902cc7dbf3127.34653621.jpg', 25),
(21, 32, 'uploads/img_6902d3bc85c851.81180938.jpg', 25);

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
(23, 'POGI', 20, 'Other(s)'),
(24, 'GANDA', 21, 'Other(s)'),
(25, 'WOOD', 21, 'Wood'),
(28, 'Nostalgia', 23, 'Plastic'),
(29, 'Nature', 23, 'Wood'),
(30, 'SKY', 25, 'Wood'),
(31, 'TEST', 25, 'Other(s)'),
(32, 'MAI', 25, 'Other(s)');

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbfollows`
--
ALTER TABLE `tbfollows`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `tbimages`
--
ALTER TABLE `tbimages`
  MODIFY `imageId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tblogininfo`
--
ALTER TABLE `tblogininfo`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `tbposts`
--
ALTER TABLE `tbposts`
  MODIFY `postId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Constraints for dumped tables
--

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
