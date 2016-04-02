-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Apr 02, 2016 at 05:18 PM
-- Server version: 5.5.42
-- PHP Version: 7.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `lmsTesting`
--

-- --------------------------------------------------------

--
-- Table structure for table `testPost`
--

CREATE TABLE `testPost` (
  `postID` int(7) NOT NULL,
  `title` varchar(30) NOT NULL,
  `content` text NOT NULL,
  `commentsAllowed` tinyint(1) NOT NULL DEFAULT '0',
  `dateTimePosted` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `testPost`
--

-- --------------------------------------------------------

--
-- Table structure for table `testPostFile`
--

CREATE TABLE `testPostFile` (
  `postID` int(7) NOT NULL,
  `fileID` int(7) NOT NULL,
  `fileName` varchar(30) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `testPostFile`
--

--
-- Indexes for dumped tables
--

--
-- Indexes for table `testPost`
--
ALTER TABLE `testPost`
  ADD PRIMARY KEY (`postID`);

--
-- Indexes for table `testPostFile`
--
ALTER TABLE `testPostFile`
  ADD PRIMARY KEY (`fileID`),
  ADD KEY `postID` (`postID`),
  ADD KEY `postID_2` (`postID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `testPost`
--
ALTER TABLE `testPost`
  MODIFY `postID` int(7) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `testPostFile`
--
ALTER TABLE `testPostFile`
  MODIFY `fileID` int(7) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `testPostFile`
--
ALTER TABLE `testPostFile`
  ADD CONSTRAINT `testPostFile` FOREIGN KEY (`postID`) REFERENCES `testPost` (`postID`);
