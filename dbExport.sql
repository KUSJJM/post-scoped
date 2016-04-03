-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Apr 03, 2016 at 04:27 PM
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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `testPost`
--

-- --------------------------------------------------------

--
-- Table structure for table `testPostComment`
--

CREATE TABLE `testPostComment` (
  `commentID` int(7) NOT NULL,
  `postID` int(7) NOT NULL,
  `commentText` text NOT NULL,
  `dateTimeCommented` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `testPostComment`
--

-- --------------------------------------------------------

--
-- Table structure for table `testPostFile`
--

CREATE TABLE `testPostFile` (
  `postID` int(7) NOT NULL,
  `fileID` int(7) NOT NULL,
  `fileName` varchar(30) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `testPostFile`
--

-- --------------------------------------------------------

--
-- Table structure for table `testPostLink`
--

CREATE TABLE `testPostLink` (
  `linkID` int(7) NOT NULL,
  `postID` int(7) NOT NULL,
  `linkName` varchar(30) NOT NULL,
  `linkURL` varchar(256) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `testPostLink`
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
-- Indexes for table `testPostComment`
--
ALTER TABLE `testPostComment`
  ADD PRIMARY KEY (`commentID`),
  ADD KEY `postID` (`postID`);

--
-- Indexes for table `testPostFile`
--
ALTER TABLE `testPostFile`
  ADD PRIMARY KEY (`fileID`),
  ADD KEY `postID` (`postID`),
  ADD KEY `postID_2` (`postID`);

--
-- Indexes for table `testPostLink`
--
ALTER TABLE `testPostLink`
  ADD PRIMARY KEY (`linkID`),
  ADD KEY `postID` (`postID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `testPost`
--
ALTER TABLE `testPost`
  MODIFY `postID` int(7) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `testPostComment`
--
ALTER TABLE `testPostComment`
  MODIFY `commentID` int(7) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `testPostFile`
--
ALTER TABLE `testPostFile`
  MODIFY `fileID` int(7) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `testPostLink`
--
ALTER TABLE `testPostLink`
  MODIFY `linkID` int(7) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `testPostFile`
--
ALTER TABLE `testPostFile`
  ADD CONSTRAINT `testPostFile` FOREIGN KEY (`postID`) REFERENCES `testPost` (`postID`);
