-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 10, 2017 at 09:49 AM
-- Server version: 10.1.19-MariaDB
-- PHP Version: 7.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sci3`
--

-- --------------------------------------------------------

--
-- Table structure for table `account_types`
--

CREATE TABLE `account_types` (
  `at_id` int(11) NOT NULL,
  `acc_type` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `account_types`
--

INSERT INTO `account_types` (`at_id`, `acc_type`) VALUES
(1, 'facebook'),
(2, 'twitter'),
(3, 'linkedin');

-- --------------------------------------------------------

--
-- Table structure for table `donation`
--

CREATE TABLE `donation` (
  `d_id` int(11) NOT NULL,
  `u_id` int(11) NOT NULL,
  `what_donated` varchar(30) NOT NULL,
  `amount` int(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `e_id` int(11) NOT NULL,
  `u_id` int(11) NOT NULL,
  `event_name` varchar(30) NOT NULL,
  `about` text NOT NULL,
  `profits` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `follow_up`
--

CREATE TABLE `follow_up` (
  `f_id` int(11) NOT NULL,
  `u_id` int(11) NOT NULL,
  `ft_id` int(11) NOT NULL,
  `purpose` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `follow_up_types`
--

CREATE TABLE `follow_up_types` (
  `ft_id` int(11) NOT NULL,
  `followup_type` varchar(30) NOT NULL,
  `weightage` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `follow_up_types`
--

INSERT INTO `follow_up_types` (`ft_id`, `followup_type`, `weightage`) VALUES
(1, 'calls_to_ngo', 0),
(2, 'emails_to_ngo', 0),
(3, 'visits_to_ngo', 0),
(4, 'subscriptions', 0),
(5, 'website_visits', 0);

-- --------------------------------------------------------

--
-- Table structure for table `score_types`
--

CREATE TABLE `score_types` (
  `st_id` int(11) NOT NULL,
  `score_type` varchar(30) NOT NULL,
  `highest_score` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `score_types`
--

INSERT INTO `score_types` (`st_id`, `score_type`, `highest_score`) VALUES
(1, 'facebook', 0),
(2, 'twitter', 0),
(3, 'linkedin', 0),
(4, 'social_media', 0),
(5, 'donation', 0),
(6, 'promotion', 0),
(7, 'prospective', 0),
(8, 'events', 0),
(9, 'follow_up', 0),
(10, 'overall', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `u_id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `phone_no` bigint(10) NOT NULL,
  `email_id` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(20) NOT NULL,
  `pin` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_accounts`
--

CREATE TABLE `user_accounts` (
  `a_id` int(11) NOT NULL,
  `u_id` int(11) NOT NULL,
  `at_id` int(11) NOT NULL,
  `account_id` text NOT NULL,
  `account_token` text NOT NULL,
  `token_expiry` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_extended`
--

CREATE TABLE `user_extended` (
  `ue_id` int(11) NOT NULL,
  `u_id` int(11) NOT NULL,
  `ut_id` int(11) NOT NULL,
  `level` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_scores`
--

CREATE TABLE `user_scores` (
  `us_id` int(11) NOT NULL,
  `u_id` int(11) NOT NULL,
  `st_id` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `percent_score` float NOT NULL,
  `details` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_types`
--

CREATE TABLE `user_types` (
  `ut_id` int(11) NOT NULL,
  `user_type` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_types`
--

INSERT INTO `user_types` (`ut_id`, `user_type`) VALUES
(1, 'donor'),
(2, 'promoter'),
(3, 'prospective');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_types`
--
ALTER TABLE `account_types`
  ADD PRIMARY KEY (`at_id`);

--
-- Indexes for table `donation`
--
ALTER TABLE `donation`
  ADD PRIMARY KEY (`d_id`),
  ADD KEY `u_id` (`u_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`e_id`),
  ADD KEY `u_id` (`u_id`);

--
-- Indexes for table `follow_up`
--
ALTER TABLE `follow_up`
  ADD PRIMARY KEY (`f_id`),
  ADD KEY `u_id` (`u_id`),
  ADD KEY `ft_id` (`ft_id`);

--
-- Indexes for table `follow_up_types`
--
ALTER TABLE `follow_up_types`
  ADD PRIMARY KEY (`ft_id`);

--
-- Indexes for table `score_types`
--
ALTER TABLE `score_types`
  ADD PRIMARY KEY (`st_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`u_id`);

--
-- Indexes for table `user_accounts`
--
ALTER TABLE `user_accounts`
  ADD PRIMARY KEY (`a_id`),
  ADD KEY `user_accounts_ibfk_1` (`u_id`),
  ADD KEY `at_id` (`at_id`);

--
-- Indexes for table `user_extended`
--
ALTER TABLE `user_extended`
  ADD PRIMARY KEY (`ue_id`),
  ADD KEY `u_id` (`u_id`),
  ADD KEY `ut_id` (`ut_id`);

--
-- Indexes for table `user_scores`
--
ALTER TABLE `user_scores`
  ADD PRIMARY KEY (`us_id`),
  ADD KEY `u_id` (`u_id`),
  ADD KEY `st_id` (`st_id`);

--
-- Indexes for table `user_types`
--
ALTER TABLE `user_types`
  ADD PRIMARY KEY (`ut_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account_types`
--
ALTER TABLE `account_types`
  MODIFY `at_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `donation`
--
ALTER TABLE `donation`
  MODIFY `d_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `e_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `follow_up`
--
ALTER TABLE `follow_up`
  MODIFY `f_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `follow_up_types`
--
ALTER TABLE `follow_up_types`
  MODIFY `ft_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `score_types`
--
ALTER TABLE `score_types`
  MODIFY `st_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `u_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_accounts`
--
ALTER TABLE `user_accounts`
  MODIFY `a_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_extended`
--
ALTER TABLE `user_extended`
  MODIFY `ue_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_scores`
--
ALTER TABLE `user_scores`
  MODIFY `us_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_types`
--
ALTER TABLE `user_types`
  MODIFY `ut_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `donation`
--
ALTER TABLE `donation`
  ADD CONSTRAINT `donation_ibfk_1` FOREIGN KEY (`u_id`) REFERENCES `user` (`u_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`u_id`) REFERENCES `user` (`u_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `follow_up`
--
ALTER TABLE `follow_up`
  ADD CONSTRAINT `follow_up_ibfk_1` FOREIGN KEY (`u_id`) REFERENCES `user` (`u_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `follow_up_ibfk_2` FOREIGN KEY (`ft_id`) REFERENCES `follow_up_types` (`ft_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_accounts`
--
ALTER TABLE `user_accounts`
  ADD CONSTRAINT `user_accounts_ibfk_1` FOREIGN KEY (`u_id`) REFERENCES `user` (`u_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_accounts_ibfk_2` FOREIGN KEY (`at_id`) REFERENCES `account_types` (`at_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_extended`
--
ALTER TABLE `user_extended`
  ADD CONSTRAINT `user_extended_ibfk_1` FOREIGN KEY (`u_id`) REFERENCES `user` (`u_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_extended_ibfk_2` FOREIGN KEY (`ut_id`) REFERENCES `user_types` (`ut_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_scores`
--
ALTER TABLE `user_scores`
  ADD CONSTRAINT `user_scores_ibfk_1` FOREIGN KEY (`u_id`) REFERENCES `user` (`u_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_scores_ibfk_2` FOREIGN KEY (`st_id`) REFERENCES `score_types` (`st_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
