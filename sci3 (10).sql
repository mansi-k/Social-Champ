-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 20, 2018 at 03:34 PM
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
-- Table structure for table `ngo_social_objects`
--

CREATE TABLE `ngo_social_objects` (
  `ns_id` int(11) NOT NULL,
  `u_id` int(11) DEFAULT NULL,
  `so_id` text NOT NULL,
  `so_name` varchar(100) NOT NULL,
  `so_token` text,
  `so_type_id` int(11) NOT NULL,
  `at_id` int(11) NOT NULL,
  `is_sw` varchar(3) NOT NULL,
  `so_score` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `points`
--

CREATE TABLE `points` (
  `p_id` int(11) NOT NULL,
  `p_name` varchar(30) NOT NULL,
  `points` int(10) NOT NULL,
  `pa_type` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `points`
--

INSERT INTO `points` (`p_id`, `p_name`, `points`, `pa_type`) VALUES
(1, 'n_like', 3, 1),
(2, 'n_like', 3, 2),
(3, 'n_love', 4, 1),
(4, 'n_comment', 7, 1),
(5, 'n_comment', 7, 2),
(6, 'n_reply', 6, 1),
(7, 'n_tagcom', 5, 1),
(8, 'n_share', 12, 1),
(9, 'n_share', 12, 2),
(10, 'n_posttag', 17, 1),
(11, 'nn_like', 2, 1),
(12, 'nn_like', 2, 2),
(13, 'nn_love', 3, 1),
(14, 'nn_comment', 6, 1),
(15, 'nn_comment', 6, 2),
(16, 'nn_reply', 5, 1),
(17, 'nn_tagcom', 4, 1),
(18, 'nn_share', 11, 1),
(19, 'nn_share', 11, 2),
(20, 'nn_posttag', 16, 1),
(21, 'nn_from', 18, 1),
(22, 'o_like', 1, 1),
(23, 'o_like', 1, 2),
(24, 'o_love', 2, 1),
(25, 'o_comment', 5, 1),
(26, 'o_comment', 5, 2),
(27, 'o_reply', 4, 1),
(28, 'o_tagcom', 3, 1),
(29, 'o_share', 10, 1),
(30, 'o_share', 10, 2),
(31, 'o_posttag', 15, 1),
(32, 'o_from', 17, 1),
(33, 'n_attend', 25, 1),
(34, 'n_interest', 20, 1),
(35, 'o_attend', 23, 1),
(36, 'o_interest', 18, 1),
(37, 'n_member', 25, 1),
(38, 'o_member', 23, 1),
(39, 'n_listsub', 20, 2),
(40, 'n_listmem', 20, 2),
(41, 'o_ownlist', 15, 2),
(42, 'o_ownlistmem', 5, 2),
(43, 'o_sublist', 10, 2),
(44, 'o_addedto', 10, 2),
(45, 'v_family', 10, 1),
(46, 'v_about', 10, 1),
(47, 'vn_edu', 25, 1),
(48, 'vo_edu', 20, 1),
(49, 'vn_work', 30, 1),
(50, 'vo_work', 25, 1),
(52, 'vo_acc', 30, 1),
(53, 'vo_grp', 25, 1),
(54, 'vn_event', 35, 1),
(55, 'vo_event', 30, 1),
(56, 'n_conv', 25, 1),
(57, 'o_conv', 20, 1),
(58, 'o_posttag', 15, 2),
(59, 'nn_posttag', 16, 2),
(60, 'n_sublist', 7, 2),
(61, 'n_addedto', 12, 2),
(62, 'o_listfrom', 5, 2),
(63, 'n_listfrom', 10, 2),
(64, 'n_posttag', 17, 2),
(65, 'o_from', 17, 2),
(66, 'o_listsub', 15, 2);

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
-- Table structure for table `social_object_types`
--

CREATE TABLE `social_object_types` (
  `ot_id` int(11) NOT NULL,
  `ot_type` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `social_object_types`
--

INSERT INTO `social_object_types` (`ot_id`, `ot_type`) VALUES
(1, 'page'),
(2, 'group'),
(3, 'event');

-- --------------------------------------------------------

--
-- Table structure for table `social_responses`
--

CREATE TABLE `social_responses` (
  `sr_id` int(11) NOT NULL,
  `so_id` int(11) NOT NULL,
  `response` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Stand-in structure for view `this_ngo_accs_view`
--
CREATE TABLE `this_ngo_accs_view` (
`ns_id` int(11)
,`u_id` int(11)
,`so_id` text
,`so_name` varchar(100)
,`so_token` text
,`so_type_id` int(11)
,`at_id` int(11)
,`is_sw` varchar(3)
,`so_score` int(11)
);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `u_id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `phone_no` bigint(10) DEFAULT NULL,
  `email_id` varchar(50) DEFAULT NULL,
  `address` text,
  `city` varchar(20) DEFAULT NULL,
  `pin` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`u_id`, `name`, `phone_no`, `email_id`, `address`, `city`, `pin`) VALUES
(1, 'Mansi Khamkar', 8692023065, 'khamkarmansi@gmail.com', 'RCF-colony,Chembur', 'Mumbai', 400071),
(2, 'Pinky Rathod', 7303302447, 'rathodpriya371@yahoo.com', 'Sec-11,Vashi', 'Navimumbai', 400703),
(3, 'Ruchita Yeole', 8879608610, 'ruchitayewale@gmail.com', 'Bhatwadi,Ghatkopar', 'Mumbai', 400072),
(4, 'Reshma Khot', 8879434347, 'khotreshmar@gmail.com', 'sec-22,Kharghar', 'Navimumbai', 410206),
(5, 'Nandini Shah', 9757400798, '2015pinky.rathod@ves.ac.in', 'sector 05,Sanpada', 'Navimumbai', 410235),
(6, 'Revati Dhoble', 9076181355, 'manukhamkar697@gmail.com', 'sector 22,Nerul', 'Navimumbai', 400706),
(7, 'Jeevika Jain ', 7045142807, '2015mansi.khamkar@ves.ac.in', 'HOC Society,Panvel', 'Navimumbai', 410206),
(8, 'Tapsee Sahani', 9930637638, '2015ruchita.yeole@ves.ac.in', 'Shaniwarvada', 'Pune', 411011),
(9, 'Sangeeta Rajput', 8850438062, '2015reshma.khot@ves.ac.in', 'Hindmata,Dadar', 'Mumbai', 400456),
(10, 'Priyanka Rathod', 9983456730, 'rathodpinky56@gmail.com', 'PMGP,Mankhurd', 'Navimumbai', 400703),
(11, 'Default User', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_accounts`
--

CREATE TABLE `user_accounts` (
  `a_id` int(11) NOT NULL,
  `u_id` int(11) NOT NULL,
  `at_id` int(11) NOT NULL,
  `account_id` text NOT NULL,
  `account_name` varchar(100) NOT NULL,
  `account_token` text,
  `token_expiry` timestamp NULL DEFAULT NULL,
  `account_secret` text,
  `inactive_from` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_accounts`
--

INSERT INTO `user_accounts` (`a_id`, `u_id`, `at_id`, `account_id`, `account_name`, `account_token`, `token_expiry`, `account_secret`, `inactive_from`) VALUES
(1, 10, 1, '129247864509182', 'Priyanka Rathod', 'EAAH09htiIfABAEmrtcJvABikZATJjQA7Uyf3utsWCHPppPJ4xxpUyoF2htXORsBp34tenFYVR7Ae7RzdoSGbwCwz09T5iRGyolZCDycvKZCZCdPzCYSGzuqtCJCAhqnH7JOY1dMuptLNWOTYR7j1vIiATbIpcRPoqXg1boxm8QZDZD', '2018-01-12 01:22:22', '', NULL),
(2, 2, 1, '1953204708281962', 'PInky RathOd', 'EAAH09htiIfABAAjuDo0mLSd3Q6e2SrdejsbdlD5BZAyZAwkV8YtZAZAZCd9PUzthIDUtl8pPAPanUhZBZAGFBOd8YDl8wjhISgfR4NncPZBkq23rqTRKf2fq3kyC3TdzsJ1PK1Yux4Es0cbV4lI6SbZCKZA2qthSEiZA01sePY9MxVNIwZDZD', '2018-01-12 03:16:29', '', NULL),
(3, 5, 1, '125711448200101', 'Nandini Shah', 'EAAH09htiIfABAIJSrLezjl35yCYFD4KszGYEmPM73XzvXfv8Sww43rEMOZBUQdAABJMW4jGRGnP500DMvkrqkQA6fsC1kHYfblV97WZBVu82EDkx0UL3M4zVnlLuZB8dckqs8A9gTZCwYoFotaWhUwtg4b9e47iBhOmDqJYefojsHSSCb0ab', '2018-01-12 12:08:51', '', NULL),
(4, 1, 1, '717968921731085', 'Mansi Khamkar', 'EAAH09htiIfABABSCRiRLDX8PZBV66BqzgMXU6YCAiZB5peZAceqhZBnIZA4jufVlZBaVdGq4vtbIwlZCVvojUnGKv0BZC2hAdoF6H2DnCfpFeKJx1XXh5x0ZC5YPJJ8ElhVnyHxa9in5n4crni96RDOhZAlfusddSWrt8aeZC7ZCYnnaVgZDZD', '2018-01-12 01:27:13', '', NULL),
(5, 6, 1, '130187267745029', 'Revati Dhoble', 'EAAH09htiIfABAF4z4bjhApSBdmH4LIQMjpQVV9l6gftEpcfZA1jLbT6iE5ZC5wnfmxIZCGn9N7CghoLuTD7Nx2n8tl0ZAyzGb7JoL2uNtBSXj0hct4JZBR5mG7LuhiH8RWp9H9hKhtBbtqwsWZA4RFredgrxxIpky5QG5EeJG25AZDZD', '2018-01-12 01:40:42', '', NULL),
(6, 7, 1, '107052656735498', 'Jeevika Jain', 'EAAH09htiIfABAGiLaQ6rRGADZBQUs2xH5TRar2XnddnCt3aLswNwUebi5fWZAuPYGUJxytIZBX39Fapuw6Td8ZCjnfgqkQ0y1DUwlw2n7nLyQ9dbxgplQgSArN2HFkdEbUajllaBYi9P9MR0cZBlHRTjO6t2s8eU1VWZC7LtdMPues2g4n4P6b', '2018-01-11 23:39:19', '', NULL),
(7, 8, 1, '118346308937246', 'Tapsee Sahani', 'EAAH09htiIfABADSxyZB2JGkCAHwJZCpmXVVly1GZCqyiDiXtYdD8r8cSUeZBq8DZCI86I5buo0rvrnHeWUHfWJWDoybtpr1NU3ZBkiQpUPBJTRo04ZBHtjMJucgIzCPcrQ2iZBjxieNeAICM8yams2tlb8q2SpnXRZAZATOhyVtEqhVVSykVx9gZCdJ', '2018-01-12 12:38:21', '', NULL),
(8, 9, 1, '102020307240359', 'Sangeeta Rajput', 'EAAH09htiIfABACClYHWjzJiksGiaMjGxOrSABVonuyzw2FcRcCSBZA8g3Q4u3xqII2JqDnZBpI7EdqdxD6ZCoBZC0inlZBAZCCMCWbd5ZBwJv2tPs5D45xRNWg5LNoxx5l6HiOTn79276IhoA0SXvJO4cri62A7RXGPA5gEg4c9zZCB8I2XvWMR7', '2018-01-12 05:54:45', '', NULL),
(9, 4, 1, '1957604804497487', 'Reshma Khot', 'EAAH09htiIfABAAPmBHm2T8w7987flIiPXSCnZAOZALMaQXtGSZC96m0UUK0PRzlzWi9Ghm6tZBMZCYBeTQYy0cXXsdwbJ1TJKwwDqNxSzspFOKgAkAEB67iAczZAEb4Y3qnTsPuPqsfRtuASpq9dxsMCyHgI0D12oIxC3PUhbTUQZDZD', '2018-01-12 12:47:21', '', NULL),
(10, 3, 1, '1976170165986074', 'Ruchita Yeole', 'EAAH09htiIfABAPiEpbg4wsqDPfGGYsN0dq2fdEb3UemD2UQ3ytMJ35naQrQC5FAeqZBEAhMx3HmDIILMgSEpo39Ntot4nsX3CO8ES4lZCpNWfFPZADaZADTcZBHZAWO2rJTNZC24YCfEke2vjiz1bc3AGZBrJGKsn5tKpZCLsWn0YCwZDZD', '2018-01-12 09:34:43', '', NULL),
(11, 2, 2, '927938548614885376', 'rathodpinky371', '927938548614885376-FzPkqzSKmArfTODoYF63XLDQ22a29IL', '0000-00-00 00:00:00', 'bRDSoiI8gyAJbFXsKa2xf5HfnxOojLTEfAJXJdC4kBkoD', NULL),
(12, 1, 2, '2744971321', 'khamkarmansi', '2744971321-fg7AJFKP032Dn29EJDdexDFmEhR0bHM0hOe8Chr', '0000-00-00 00:00:00', 'bAQYBZ4P6hM5fr4BItCivJi4gHgMpVyiHy01bD41zWKUN', NULL),
(13, 3, 2, '927933736947130369', 'Ruchita_Yeole', '927933736947130369-0RPedBPIr4qQOWg79goAKdbhhhnTRXe', '0000-00-00 00:00:00', 'vnStVc3xUD8uFxQzIkzqF7oPnyIL6yrq0D4bmB72PbfOV', NULL),
(14, 4, 2, '926448476212174849', 'Reshma_Khot', '926448476212174849-V55q3spHf7EEJCeL14tjc2AO5yavdEt', '0000-00-00 00:00:00', '3bJkAyye4VPbvdk9rucsRtevVqbNDWpYGFGbT9bdVPQbU', NULL),
(15, 5, 2, '920542341609357312', 'Nandinishah123', '920542341609357312-dMqdmuDknCBGhP9ihw3ZYxZpdRG1bLw', '0000-00-00 00:00:00', 'BaY3B5Cw44xwFKnj75Ux9jZp8l3EfhyICUETv7UZLn6jx', NULL),
(16, 6, 2, '926780334045216768', 'DhobleRevati', '926780334045216768-wU3sH1tZWbeQI4xdRbMXRQMSpuMzk9e', '0000-00-00 00:00:00', 'gRDLZRlUeggereyFtTKvHGjgsl2yb3fdo4tARPa2VRs6B', NULL),
(17, 7, 2, '878172559975399424', 'jeevikajain123', '878172559975399424-BdCY5s8VWsDs4GJWHeFBuAMefE6tNjL', '0000-00-00 00:00:00', 'b3reRHduvSTnLdObXmQbbpncm4p5iOED0aaGjYfHDvqqm', NULL),
(18, 8, 2, '927922770981265413', 'Tapsee_Sahani', '927922770981265413-eHAScYbZEhHPyucp4jskWi8IDW6Raal', '0000-00-00 00:00:00', 'w1jBf6ydpC3N4ixkHCG5UmbphFWgZ7su3VL4EXyyA95CQ', NULL),
(19, 9, 2, '927936965311217664', 'Rsangeeta123', '927936965311217664-EnEPnBG6gcSBqpXQSrgnNVeQMt9pbHM', '0000-00-00 00:00:00', 'f872mmIXeQ8EyyerMWPb8Nkw46R3Msf4XEQQpJJrwKv9b', NULL),
(20, 10, 2, '925405979642028032', 'Rpriyanka56', '925405979642028032-uHWBIPpcVN7m5qiCVTyjegzSblcsrXj', '0000-00-00 00:00:00', 'NfyV0b7mour7UfNwRftRa1lIVW78vzOdiIdHz3BV9lYM2\r\n', NULL),
(21, 11, 1, '0', 'Default', 'EAAH09htiIfABABSCRiRLDX8PZBV66BqzgMXU6YCAiZB5peZAceqhZBnIZA4jufVlZBaVdGq4vtbIwlZCVvojUnGKv0BZC2hAdoF6H2DnCfpFeKJx1XXh5x0ZC5YPJJ8ElhVnyHxa9in5n4crni96RDOhZAlfusddSWrt8aeZC7ZCYnnaVgZDZD', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_extended`
--

CREATE TABLE `user_extended` (
  `ue_id` int(11) NOT NULL,
  `u_id` int(11) NOT NULL,
  `ut_id` int(11) NOT NULL,
  `level` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_extended`
--

INSERT INTO `user_extended` (`ue_id`, `u_id`, `ut_id`, `level`) VALUES
(1, 1, 4, 1),
(3, 2, 1, 2),
(4, 2, 2, 2),
(5, 3, 1, 3),
(6, 3, 2, 3),
(7, 4, 1, 4),
(8, 4, 2, 4),
(9, 5, 1, 5),
(10, 6, 1, 4),
(11, 7, 1, 3),
(12, 8, 2, 4),
(13, 9, 2, 5),
(14, 10, 2, 2),
(15, 9, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `user_scores`
--

CREATE TABLE `user_scores` (
  `us_id` int(11) NOT NULL,
  `ue_id` int(11) NOT NULL,
  `st_id` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `percent_score` float DEFAULT NULL,
  `details` text
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
(3, 'prospective'),
(4, 'social_admin');

-- --------------------------------------------------------

--
-- Structure for view `this_ngo_accs_view`
--
DROP TABLE IF EXISTS `this_ngo_accs_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `this_ngo_accs_view`  AS  select `t1`.`ns_id` AS `ns_id`,`t1`.`u_id` AS `u_id`,`t1`.`so_id` AS `so_id`,`t1`.`so_name` AS `so_name`,`t1`.`so_token` AS `so_token`,`t1`.`so_type_id` AS `so_type_id`,`t1`.`at_id` AS `at_id`,`t1`.`is_sw` AS `is_sw`,`t1`.`so_score` AS `so_score` from (`ngo_social_objects` `t1` join `user_extended` `t2`) where ((`t1`.`at_id` = 1) and (`t2`.`ut_id` = 4) and (`t1`.`u_id` = `t2`.`u_id`)) ;

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
-- Indexes for table `ngo_social_objects`
--
ALTER TABLE `ngo_social_objects`
  ADD PRIMARY KEY (`ns_id`),
  ADD KEY `so_type_id` (`so_type_id`),
  ADD KEY `at_id` (`at_id`),
  ADD KEY `u_id` (`u_id`);

--
-- Indexes for table `points`
--
ALTER TABLE `points`
  ADD PRIMARY KEY (`p_id`),
  ADD KEY `pa_type` (`pa_type`);

--
-- Indexes for table `score_types`
--
ALTER TABLE `score_types`
  ADD PRIMARY KEY (`st_id`);

--
-- Indexes for table `social_object_types`
--
ALTER TABLE `social_object_types`
  ADD PRIMARY KEY (`ot_id`);

--
-- Indexes for table `social_responses`
--
ALTER TABLE `social_responses`
  ADD PRIMARY KEY (`sr_id`),
  ADD KEY `so_id` (`so_id`);

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
  ADD KEY `u_id` (`ue_id`),
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
  MODIFY `ft_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `ngo_social_objects`
--
ALTER TABLE `ngo_social_objects`
  MODIFY `ns_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `points`
--
ALTER TABLE `points`
  MODIFY `p_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;
--
-- AUTO_INCREMENT for table `score_types`
--
ALTER TABLE `score_types`
  MODIFY `st_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `social_object_types`
--
ALTER TABLE `social_object_types`
  MODIFY `ot_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `social_responses`
--
ALTER TABLE `social_responses`
  MODIFY `sr_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `u_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `user_accounts`
--
ALTER TABLE `user_accounts`
  MODIFY `a_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `user_extended`
--
ALTER TABLE `user_extended`
  MODIFY `ue_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
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
-- Constraints for table `ngo_social_objects`
--
ALTER TABLE `ngo_social_objects`
  ADD CONSTRAINT `account_type_id` FOREIGN KEY (`at_id`) REFERENCES `account_types` (`at_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `social_type_id` FOREIGN KEY (`so_type_id`) REFERENCES `social_object_types` (`ot_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_sobject` FOREIGN KEY (`u_id`) REFERENCES `user` (`u_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `points`
--
ALTER TABLE `points`
  ADD CONSTRAINT `points_ibfk_1` FOREIGN KEY (`pa_type`) REFERENCES `account_types` (`at_id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
  ADD CONSTRAINT `user_scores_ibfk_1` FOREIGN KEY (`ue_id`) REFERENCES `user_extended` (`ue_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_scores_ibfk_2` FOREIGN KEY (`st_id`) REFERENCES `score_types` (`st_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
