-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 16, 2018 at 06:30 PM
-- Server version: 10.1.28-MariaDB
-- PHP Version: 7.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
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
  `so_id` text,
  `so_name` varchar(100) NOT NULL,
  `owner_nm` varchar(30) DEFAULT NULL,
  `so_token` text,
  `so_type_id` int(11) NOT NULL,
  `at_id` int(11) NOT NULL,
  `is_sw` varchar(3) NOT NULL,
  `so_score` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ngo_social_objects`
--

INSERT INTO `ngo_social_objects` (`ns_id`, `u_id`, `so_id`, `so_name`, `owner_nm`, `so_token`, `so_type_id`, `at_id`, `is_sw`, `so_score`) VALUES
(1, 12, '147881722563636', 'Udgam NGO', NULL, 'EAAH09htiIfABANZBZBXG4pbq7L2vkniFKWezmDZCsv6IxZBCbq8ZCUZBZBazqEkkpGdjaQigM9tLZAr1SxrH1n2EvzVZAXZCUHAAZBNeAIsZCB207BsBI2zxJyS5lyY7OCp3FGENrZAET7ZAbDX1IyWYhv5Ss7ZATtlWJ2ZBmEkoLjex8bKjQwkHO6CozQJ6', 1, 1, 'yes', 34),
(2, 12, '1211801652285946', 'Udgam Volunteers', NULL, 'EAAH09htiIfABANZBZBXG4pbq7L2vkniFKWezmDZCsv6IxZBCbq8ZCUZBZBazqEkkpGdjaQigM9tLZAr1SxrH1n2EvzVZAXZCUHAAZBNeAIsZCB207BsBI2zxJyS5lyY7OCp3FGENrZAET7ZAbDX1IyWYhv5Ss7ZATtlWJ2ZBmEkoLjex8bKjQwkHO6CozQJ6', 2, 1, 'yes', 22),
(3, 12, '957891977697469', 'Handicrafts Exhibition By NGO Children', NULL, 'EAAH09htiIfABANZBZBXG4pbq7L2vkniFKWezmDZCsv6IxZBCbq8ZCUZBZBazqEkkpGdjaQigM9tLZAr1SxrH1n2EvzVZAXZCUHAAZBNeAIsZCB207BsBI2zxJyS5lyY7OCp3FGENrZAET7ZAbDX1IyWYhv5Ss7ZATtlWJ2ZBmEkoLjex8bKjQwkHO6CozQJ6', 3, 1, 'yes', 10),
(4, 12, '1831714127128319', 'Dance Workshop at Ngos', NULL, 'EAAH09htiIfABANZBZBXG4pbq7L2vkniFKWezmDZCsv6IxZBCbq8ZCUZBZBazqEkkpGdjaQigM9tLZAr1SxrH1n2EvzVZAXZCUHAAZBNeAIsZCB207BsBI2zxJyS5lyY7OCp3FGENrZAET7ZAbDX1IyWYhv5Ss7ZATtlWJ2ZBmEkoLjex8bKjQwkHO6CozQJ6', 3, 1, 'yes', 4),
(5, 11, '91316581274', 'NGO\'s in India', NULL, '', 1, 1, 'yes', 15),
(6, 1, '1774494506184449', 'NPO Publicity', NULL, '', 1, 1, 'yes', 47),
(7, 11, '156042401405028', 'NAAM Foundation', NULL, '', 1, 1, 'yes', 7),
(8, 11, '268055756675250', 'NGO Kartavya,  Dadra & Nagar Haveli,  Mumbai & Vapi', NULL, '', 1, 1, 'yes', 2),
(9, 11, '1800299146901672', 'VESPAA Annual Garba Nite 30th Dec 2016', NULL, '', 3, 1, 'yes', 4),
(10, 11, '1566933253612821', 'SoRT VESIT', NULL, '', 1, 1, 'yes', 2),
(11, 11, '1967993383217178', 'NGO Market 2018', NULL, '', 3, 1, 'yes', 3),
(12, 11, '1304915469641054', 'Robotics Workshop For TLC Children', NULL, '', 3, 1, 'yes', 7),
(13, 11, '518876441810870', 'Talent Hunt by Making Ourselves Better (NGO)', NULL, '', 3, 1, 'yes', 6),
(14, 11, '547508901989666', 'Heart Foundation - NGO', NULL, '', 1, 1, 'yes', 16),
(15, 11, '113392962658445', 'Reachout Foundation Mumbai', NULL, '', 1, 1, 'yes', 2),
(16, 11, '131004083286', 'NGOs India', NULL, '', 1, 1, 'yes', 9),
(17, 1, '1672564486140269', 'Seminar one', NULL, '', 3, 1, 'yes', 12),
(18, 11, '137617906918145', 'Helen Keller Institute for Deaf & Deafblind, Mumbai', NULL, '', 3, 1, 'yes', 2),
(19, 11, '2033044600305883', 'Childline Se Dosti', NULL, '', 3, 1, 'yes', 2),
(20, 1, '1984103015138762', 'NPO Meet', NULL, '', 3, 1, 'yes', 9),
(21, 11, '1656827677935330', 'Sahas Foundation', NULL, '', 1, 1, 'yes', 1),
(22, 7, '261102497389631', 'NPO Helpers', NULL, '', 2, 1, 'yes', 14),
(23, 11, '146073332747121', 'Udgam NGO Helpers', NULL, '', 2, 1, 'yes', 3),
(24, 11, '1454751591436315', 'Lions Clubs India', NULL, '', 1, 1, 'yes', 1),
(25, 11, '1479424555671794', 'Public Complaint Centre-NGO', NULL, '', 1, 1, 'yes', 2),
(26, 11, '6231686654', 'Compassion International', NULL, '', 1, 1, 'yes', 1),
(27, 11, '303587393363260', 'Mumbai Smiles Foundation', NULL, '', 1, 1, 'yes', 1),
(28, 11, '250668978287823', 'BICeBÃ© | Bienal del Cartel Bolivia :: Biennial of Poster Bolivia', NULL, '', 1, 1, 'yes', 1),
(29, 11, '211657000392', '1 Million Women', NULL, '', 1, 1, 'yes', 1),
(30, 11, '232283883548123', 'Say NO to Reservation System in India', NULL, '', 1, 1, 'yes', 1);

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
(61, 'n_addedto', 12, 2),
(64, 'n_posttag', 17, 2),
(65, 'o_from', 17, 2),
(67, 'o_ownlist', 12, 2),
(68, 'o_listmem', 5, 2),
(69, 'o_listsub', 10, 2),
(70, 'n_ownlist', 20, 2),
(71, 'n_listmem', 12, 2),
(72, 'n_listsub', 14, 2),
(73, 'nn_ownlist', 15, 2),
(74, 'nn_listmem', 10, 2),
(75, 'nn_listsub', 12, 2),
(76, 'o_listfrom', 10, 2);

-- --------------------------------------------------------

--
-- Table structure for table `scan_mx_id`
--

CREATE TABLE `scan_mx_id` (
  `sr_id` int(50) NOT NULL,
  `u_id` int(50) NOT NULL,
  `response` bigint(100) NOT NULL,
  `response_type` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
(3, 'event'),
(4, 'post'),
(5, 'list');

-- --------------------------------------------------------

--
-- Table structure for table `social_responses`
--

CREATE TABLE `social_responses` (
  `sr_id` int(11) NOT NULL,
  `so_id` int(11) NOT NULL,
  `response` text NOT NULL,
  `r_obj_id` text NOT NULL,
  `at_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `social_responses`
--

INSERT INTO `social_responses` (`sr_id`, `so_id`, `response`, `r_obj_id`, `at_id`) VALUES
(18, 4, '{\"created_time\":\"2018-02-16T10:35:54+0000\",\"message\":\"\\u201cTo teach a man how he may learn to grow independently, and for himself, is perhaps the greatest service that one man can do another.Human service is the highest form of self-interest for the person who serves.\\u201d Udgam NGO Helpers  Udgam NGO Heart Foundation - NGO\",\"from\":{\"name\":\"PInky RathOd\",\"id\":\"1953204708281962\"},\"message_tags\":[{\"id\":\"147881722563636\",\"name\":\"Udgam NGO\",\"type\":\"page\",\"offset\":231,\"length\":9},{\"id\":\"547508901989666\",\"name\":\"Heart Foundation - NGO\",\"type\":\"page\",\"offset\":241,\"length\":22}],\"type\":\"status\",\"privacy\":{\"value\":\"\",\"description\":\"\",\"friends\":\"\",\"allow\":\"\",\"deny\":\"\"},\"id\":\"1211801652285946_1215774735221971\",\"to\":[{\"id\":\"1211801652285946\",\"name\":\"Udgam Volunteers\",\"profile_type\":\"group\"},{\"id\":\"147881722563636\",\"name\":\"Udgam NGO\",\"profile_type\":\"page\"},{\"id\":\"547508901989666\",\"name\":\"Heart Foundation - NGO\",\"profile_type\":\"page\"}],\"reactions\":[{\"id\":\"118346308937246\",\"name\":\"Tapsee Sahani\",\"profile_type\":\"user\",\"type\":\"LIKE\"},{\"id\":\"1976170165986074\",\"name\":\"Ruchita Yeole\",\"profile_type\":\"user\",\"type\":\"LOVE\"}]}', '1211801652285946_1215774735221971', 1),
(19, 4, '{\"created_time\":\"2018-02-16T09:47:24+0000\",\"message\":\"\\u201cIf you want good service, serve yourself.\\u201cThe sole purpose of business is service. The sole purpose of advertising is explaining the service which business renders.\\u201d\\nReshma Khot Mansi Khamkar Ruchita Yeole PInky RathOd Heart Foundation - NGO Udgam NGO Tapsee Sahani Jeevika Jain\",\"from\":{\"name\":\"Udgam NGO\",\"id\":\"147881722563636\"},\"message_tags\":[{\"id\":\"1957604804497487\",\"name\":\"Reshma Khot\",\"type\":\"user\",\"offset\":167,\"length\":11},{\"id\":\"717968921731085\",\"name\":\"Mansi Khamkar\",\"type\":\"user\",\"offset\":179,\"length\":13},{\"id\":\"1976170165986074\",\"name\":\"Ruchita Yeole\",\"type\":\"user\",\"offset\":193,\"length\":13},{\"id\":\"1953204708281962\",\"name\":\"PInky RathOd\",\"type\":\"user\",\"offset\":207,\"length\":12},{\"id\":\"547508901989666\",\"name\":\"Heart Foundation - NGO\",\"type\":\"page\",\"offset\":220,\"length\":22},{\"id\":\"147881722563636\",\"name\":\"Udgam NGO\",\"type\":\"page\",\"offset\":243,\"length\":9},{\"id\":\"118346308937246\",\"name\":\"Tapsee Sahani\",\"type\":\"user\",\"offset\":253,\"length\":13},{\"id\":\"107052656735498\",\"name\":\"Jeevika Jain\",\"type\":\"user\",\"offset\":267,\"length\":12}],\"type\":\"status\",\"privacy\":{\"value\":\"EVERYONE\",\"description\":\"\",\"friends\":\"\",\"allow\":\"\",\"deny\":\"\"},\"shares\":{\"count\":2},\"id\":\"1211801652285946_1215754768557301\",\"to\":[{\"id\":\"1211801652285946\",\"name\":\"Udgam Volunteers\",\"profile_type\":\"group\"},{\"id\":\"1957604804497487\",\"name\":\"Reshma Khot\",\"profile_type\":\"user\"},{\"id\":\"717968921731085\",\"name\":\"Mansi Khamkar\",\"profile_type\":\"user\"},{\"id\":\"1976170165986074\",\"name\":\"Ruchita Yeole\",\"profile_type\":\"user\"},{\"id\":\"1953204708281962\",\"name\":\"PInky RathOd\",\"profile_type\":\"user\"},{\"id\":\"547508901989666\",\"name\":\"Heart Foundation - NGO\",\"profile_type\":\"page\"},{\"id\":\"147881722563636\",\"name\":\"Udgam NGO\",\"profile_type\":\"page\"},{\"id\":\"118346308937246\",\"name\":\"Tapsee Sahani\",\"profile_type\":\"user\"},{\"id\":\"107052656735498\",\"name\":\"Jeevika Jain\",\"profile_type\":\"user\"}],\"reactions\":[{\"id\":\"1976170165986074\",\"name\":\"Ruchita Yeole\",\"profile_type\":\"user\",\"type\":\"LIKE\"},{\"id\":\"118346308937246\",\"name\":\"Tapsee Sahani\",\"profile_type\":\"user\",\"type\":\"LIKE\"}],\"sharedposts\":[{\"from\":{\"name\":\"PInky RathOd\",\"id\":\"1953204708281962\"},\"id\":\"1953204708281962_2008486856087080\"},{\"from\":{\"name\":\"Tapsee Sahani\",\"id\":\"118346308937246\"},\"id\":\"118346308937246_190922321679644\"}],\"comments\":[{\"from\":{\"name\":\"Tapsee Sahani\",\"id\":\"118346308937246\"},\"message\":\"great social work :) The NGO Debate NGO\'s in India\",\"message_tags\":[{\"id\":\"356402378166429\",\"length\":14,\"name\":\"The NGO Debate\",\"offset\":21,\"type\":\"event\"},{\"id\":\"91316581274\",\"length\":14,\"name\":\"NGO\'s in India\",\"offset\":36,\"type\":\"page\"}],\"id\":\"1215757771890334\"},{\"from\":{\"name\":\"Ruchita Yeole\",\"id\":\"1976170165986074\"},\"message\":\"Amazing work\",\"id\":\"1215774515221993\"}]}', '1211801652285946_1215754768557301', 1),
(20, 4, '{\"created_time\":\"2018-02-16T08:55:54+0000\",\"message\":\"\\u201cIf you want good service, serve yourself.\\u201cThese are times that try men\'s souls. The summer soldier and the sunshine patriot will, in this crisis, shrink from the service of their country.\\u201d Udgam NGO Udgam NGO Helpers Udgam Volunteers Ruchita Yeole Mansi Khamkar PInky RathOd Priyanka Rathod Handicrafts Exhibition By NGO Children\",\"from\":{\"name\":\"Reshma Khot\",\"id\":\"1957604804497487\"},\"message_tags\":[{\"id\":\"147881722563636\",\"name\":\"Udgam NGO\",\"type\":\"page\",\"offset\":190,\"length\":9},{\"id\":\"1211801652285946\",\"name\":\"Udgam Volunteers\",\"type\":\"group\",\"offset\":218,\"length\":16},{\"id\":\"1976170165986074\",\"name\":\"Ruchita Yeole\",\"type\":\"user\",\"offset\":235,\"length\":13},{\"id\":\"717968921731085\",\"name\":\"Mansi Khamkar\",\"type\":\"user\",\"offset\":249,\"length\":13},{\"id\":\"1953204708281962\",\"name\":\"PInky RathOd\",\"type\":\"user\",\"offset\":263,\"length\":12},{\"id\":\"129247864509182\",\"name\":\"Priyanka Rathod\",\"type\":\"user\",\"offset\":276,\"length\":15},{\"id\":\"957891977697469\",\"name\":\"Handicrafts Exhibition By NGO Children\",\"type\":\"event\",\"offset\":292,\"length\":38}],\"type\":\"status\",\"privacy\":{\"value\":\"\",\"description\":\"\",\"friends\":\"\",\"allow\":\"\",\"deny\":\"\"},\"shares\":{\"count\":4},\"id\":\"1211801652285946_1215735178559260\",\"to\":[{\"id\":\"1211801652285946\",\"name\":\"Udgam Volunteers\",\"profile_type\":\"group\"},{\"id\":\"147881722563636\",\"name\":\"Udgam NGO\",\"profile_type\":\"page\"},{\"id\":\"1211801652285946\",\"name\":\"Udgam Volunteers\",\"profile_type\":\"group\"},{\"id\":\"1976170165986074\",\"name\":\"Ruchita Yeole\",\"profile_type\":\"user\"},{\"id\":\"717968921731085\",\"name\":\"Mansi Khamkar\",\"profile_type\":\"user\"},{\"id\":\"1953204708281962\",\"name\":\"PInky RathOd\",\"profile_type\":\"user\"},{\"id\":\"129247864509182\",\"name\":\"Priyanka Rathod\",\"profile_type\":\"user\"},{\"id\":\"957891977697469\",\"name\":\"Handicrafts Exhibition By NGO Children\",\"profile_type\":\"event\"}],\"reactions\":[{\"id\":\"304668860016209\",\"name\":\"Bhavika Mahadik\",\"profile_type\":\"user\",\"type\":\"LIKE\"},{\"id\":\"1976170165986074\",\"name\":\"Ruchita Yeole\",\"profile_type\":\"user\",\"type\":\"LOVE\"},{\"id\":\"1953204708281962\",\"name\":\"PInky RathOd\",\"profile_type\":\"user\",\"type\":\"LOVE\"},{\"id\":\"129247864509182\",\"name\":\"Priyanka Rathod\",\"profile_type\":\"user\",\"type\":\"WOW\"},{\"id\":\"107052656735498\",\"name\":\"Jeevika Jain\",\"profile_type\":\"user\",\"type\":\"LIKE\"},{\"id\":\"130187267745029\",\"name\":\"Revati Dhoble\",\"profile_type\":\"user\",\"type\":\"LIKE\"},{\"id\":\"118346308937246\",\"name\":\"Tapsee Sahani\",\"profile_type\":\"user\",\"type\":\"WOW\"}],\"sharedposts\":[{\"from\":{\"name\":\"Jeevika Jain\",\"id\":\"107052656735498\"},\"id\":\"107052656735498_186361398804623\"},{\"from\":{\"name\":\"Tapsee Sahani\",\"id\":\"118346308937246\"},\"id\":\"118346308937246_190923835012826\"},{\"from\":{\"name\":\"Tapsee Sahani\",\"id\":\"118346308937246\"},\"id\":\"118346308937246_190922735012936\"}],\"comments\":[{\"from\":{\"name\":\"Tapsee Sahani\",\"id\":\"118346308937246\"},\"message\":\"great work Udgam Volunteers NGO Publicity Group Udgam Volunteers\",\"message_tags\":[{\"id\":\"1211801652285946\",\"length\":16,\"name\":\"Udgam Volunteers\",\"offset\":11,\"type\":\"group\"},{\"id\":\"146516985981318\",\"length\":19,\"name\":\"NGO Publicity Group\",\"offset\":28,\"type\":\"group\"},{\"id\":\"1211801652285946\",\"length\":16,\"name\":\"Udgam Volunteers\",\"offset\":48,\"type\":\"group\"}],\"id\":\"1215759728556805\"},{\"from\":{\"name\":\"Revati Dhoble\",\"id\":\"130187267745029\"},\"message\":\"Heart Foundation - NGO NGOs India\",\"message_tags\":[{\"id\":\"547508901989666\",\"length\":22,\"name\":\"Heart Foundation - NGO\",\"offset\":0,\"type\":\"page\"},{\"id\":\"131004083286\",\"length\":10,\"name\":\"NGOs India\",\"offset\":23,\"type\":\"page\"}],\"id\":\"1215762398556538\"},{\"from\":{\"name\":\"Jeevika Jain\",\"id\":\"107052656735498\"},\"message\":\"WOW Udgam NGO NGOs India\",\"message_tags\":[{\"id\":\"147881722563636\",\"length\":9,\"name\":\"Udgam NGO\",\"offset\":4,\"type\":\"page\"},{\"id\":\"131004083286\",\"length\":10,\"name\":\"NGOs India\",\"offset\":14,\"type\":\"page\"}],\"id\":\"1215763181889793\"},{\"from\":{\"name\":\"Priyanka Rathod\",\"id\":\"129247864509182\"},\"message\":\"nice NGO\'s in India NAAM Foundation\",\"message_tags\":[{\"id\":\"91316581274\",\"length\":14,\"name\":\"NGO\'s in India\",\"offset\":5,\"type\":\"page\"},{\"id\":\"156042401405028\",\"length\":15,\"name\":\"NAAM Foundation\",\"offset\":20,\"type\":\"page\"}],\"id\":\"1215769631889148\"},{\"from\":{\"name\":\"PInky RathOd\",\"id\":\"1953204708281962\"},\"message\":\"nice Reshma Khot Udgam Volunteers\",\"message_tags\":[{\"id\":\"1957604804497487\",\"length\":11,\"name\":\"Reshma Khot\",\"offset\":5,\"type\":\"user\"},{\"id\":\"1211801652285946\",\"length\":16,\"name\":\"Udgam Volunteers\",\"offset\":17,\"type\":\"group\"}],\"id\":\"1215771331888978\"}]}', '1211801652285946_1215735178559260', 1),
(21, 4, '{\"created_time\":\"2018-02-16T09:19:28+0000\",\"message\":\"\\u201cI slept and dreamt that life was joy. I awoke and saw that life was service. I acted and behold, service was joy.\\u201d\\nNGO Market 2018  Udgam NGO Helpers Dignity Foundation Mansi Khamkar Ruchita Yeole PInky RathOd Reshma Khot\",\"from\":{\"name\":\"Nandini Shah\",\"id\":\"125711448200101\"},\"message_tags\":[{\"id\":\"1967993383217178\",\"name\":\"NGO Market 2018\",\"type\":\"event\",\"offset\":116,\"length\":15},{\"id\":\"717968921731085\",\"name\":\"Mansi Khamkar\",\"type\":\"user\",\"offset\":170,\"length\":13},{\"id\":\"1976170165986074\",\"name\":\"Ruchita Yeole\",\"type\":\"user\",\"offset\":184,\"length\":13},{\"id\":\"1953204708281962\",\"name\":\"PInky RathOd\",\"type\":\"user\",\"offset\":198,\"length\":12},{\"id\":\"1957604804497487\",\"name\":\"Reshma Khot\",\"type\":\"user\",\"offset\":211,\"length\":11}],\"type\":\"status\",\"privacy\":{\"value\":\"\",\"description\":\"\",\"friends\":\"\",\"allow\":\"\",\"deny\":\"\"},\"shares\":{\"count\":2},\"id\":\"1211801652285946_1215743198558458\",\"to\":[{\"id\":\"1211801652285946\",\"name\":\"Udgam Volunteers\",\"profile_type\":\"group\"},{\"id\":\"1967993383217178\",\"name\":\"NGO Market 2018\",\"profile_type\":\"event\"},{\"id\":\"717968921731085\",\"name\":\"Mansi Khamkar\",\"profile_type\":\"user\"},{\"id\":\"1976170165986074\",\"name\":\"Ruchita Yeole\",\"profile_type\":\"user\"},{\"id\":\"1953204708281962\",\"name\":\"PInky RathOd\",\"profile_type\":\"user\"},{\"id\":\"1957604804497487\",\"name\":\"Reshma Khot\",\"profile_type\":\"user\"}],\"reactions\":[{\"id\":\"1953204708281962\",\"name\":\"PInky RathOd\",\"profile_type\":\"user\",\"type\":\"LOVE\"},{\"id\":\"129247864509182\",\"name\":\"Priyanka Rathod\",\"profile_type\":\"user\",\"type\":\"LOVE\"},{\"id\":\"118346308937246\",\"name\":\"Tapsee Sahani\",\"profile_type\":\"user\",\"type\":\"LIKE\"}],\"sharedposts\":[{\"from\":{\"name\":\"Tapsee Sahani\",\"id\":\"118346308937246\"},\"id\":\"118346308937246_190922448346298\"}],\"comments\":[{\"from\":{\"name\":\"Tapsee Sahani\",\"id\":\"118346308937246\"},\"message\":\"nice Udgam Volunteers Udgam NGO\",\"message_tags\":[{\"id\":\"1211801652285946\",\"length\":16,\"name\":\"Udgam Volunteers\",\"offset\":5,\"type\":\"group\"},{\"id\":\"147881722563636\",\"length\":9,\"name\":\"Udgam NGO\",\"offset\":22,\"type\":\"page\"}],\"reactions\":[{\"id\":\"1953204708281962\",\"name\":\"PInky RathOd\",\"type\":\"LIKE\"}],\"id\":\"1215758381890273\"},{\"from\":{\"name\":\"Priyanka Rathod\",\"id\":\"129247864509182\"},\"message\":\"wow\",\"id\":\"1215769785222466\"},{\"from\":{\"name\":\"Priyanka Rathod\",\"id\":\"129247864509182\"},\"message\":\"great social work\",\"reactions\":[{\"id\":\"1953204708281962\",\"name\":\"PInky RathOd\",\"type\":\"LIKE\"}],\"id\":\"1215769858555792\"}]}', '1211801652285946_1215743198558458', 1),
(22, 4, '{\"created_time\":\"2018-02-16T09:00:48+0000\",\"message\":\"\\u201cHuman service is the highest form of self-interest for the person who serves.If only for a half hour a day, a child should do something serviceable to the community\\u201d  The NGO Debate NPO Helpers Udgam Volunteers PInky RathOd Sukesh Rathod Nandini Shah NPO Helpers\",\"from\":{\"name\":\"Tapsee Sahani\",\"id\":\"118346308937246\"},\"message_tags\":[{\"id\":\"356402378166429\",\"name\":\"The NGO Debate\",\"type\":\"event\",\"offset\":168,\"length\":14},{\"id\":\"261102497389631\",\"name\":\"NPO Helpers\",\"type\":\"group\",\"offset\":183,\"length\":11},{\"id\":\"1211801652285946\",\"name\":\"Udgam Volunteers\",\"type\":\"group\",\"offset\":195,\"length\":16},{\"id\":\"1953204708281962\",\"name\":\"PInky RathOd\",\"type\":\"user\",\"offset\":212,\"length\":12},{\"id\":\"112364232916430\",\"name\":\"Sukesh Rathod\",\"type\":\"user\",\"offset\":225,\"length\":13},{\"id\":\"125711448200101\",\"name\":\"Nandini Shah\",\"type\":\"user\",\"offset\":239,\"length\":12},{\"id\":\"261102497389631\",\"name\":\"NPO Helpers\",\"type\":\"group\",\"offset\":252,\"length\":11}],\"type\":\"status\",\"privacy\":{\"value\":\"\",\"description\":\"\",\"friends\":\"\",\"allow\":\"\",\"deny\":\"\"},\"shares\":{\"count\":1},\"id\":\"1211801652285946_1215736858559092\",\"to\":[{\"id\":\"1211801652285946\",\"name\":\"Udgam Volunteers\",\"profile_type\":\"group\"},{\"id\":\"356402378166429\",\"name\":\"The NGO Debate\",\"profile_type\":\"event\"},{\"id\":\"261102497389631\",\"name\":\"NPO Helpers\",\"profile_type\":\"group\"},{\"id\":\"1211801652285946\",\"name\":\"Udgam Volunteers\",\"profile_type\":\"group\"},{\"id\":\"1953204708281962\",\"name\":\"PInky RathOd\",\"profile_type\":\"user\"},{\"id\":\"112364232916430\",\"name\":\"Sukesh Rathod\",\"profile_type\":\"user\"},{\"id\":\"125711448200101\",\"name\":\"Nandini Shah\",\"profile_type\":\"user\"},{\"id\":\"261102497389631\",\"name\":\"NPO Helpers\",\"profile_type\":\"group\"}],\"reactions\":[{\"id\":\"118346308937246\",\"name\":\"Tapsee Sahani\",\"profile_type\":\"user\",\"type\":\"WOW\"}],\"sharedposts\":[{\"from\":{\"name\":\"Tapsee Sahani\",\"id\":\"118346308937246\"},\"id\":\"118346308937246_190922625012947\"}]}', '1211801652285946_1215736858559092', 1),
(23, 4, '{\"created_time\":\"2018-02-16T08:27:39+0000\",\"message\":\"\\u201cThe greatest good you can do for another is not just to share your riches but to reveal to him his own.The sole meaning of life is to serve humanity.\\u201d Udgam NGO NGO\'s in India Sangeeta Rajput Mansi Khamkar Revati Dhoble Charity\",\"from\":{\"name\":\"Udgam NGO\",\"id\":\"147881722563636\"},\"message_tags\":[{\"id\":\"147881722563636\",\"name\":\"Udgam NGO\",\"type\":\"page\",\"offset\":152,\"length\":9},{\"id\":\"91316581274\",\"name\":\"NGO\'s in India\",\"type\":\"page\",\"offset\":162,\"length\":14},{\"id\":\"102020307240359\",\"name\":\"Sangeeta Rajput\",\"type\":\"user\",\"offset\":177,\"length\":15},{\"id\":\"717968921731085\",\"name\":\"Mansi Khamkar\",\"type\":\"user\",\"offset\":193,\"length\":13},{\"id\":\"130187267745029\",\"name\":\"Revati Dhoble\",\"type\":\"user\",\"offset\":207,\"length\":13},{\"id\":\"299994596062\",\"name\":\"Charity\",\"type\":\"page\",\"offset\":221,\"length\":7}],\"type\":\"status\",\"privacy\":{\"value\":\"EVERYONE\",\"description\":\"\",\"friends\":\"\",\"allow\":\"\",\"deny\":\"\"},\"id\":\"1211801652285946_1215723581893753\",\"to\":[{\"id\":\"1211801652285946\",\"name\":\"Udgam Volunteers\",\"profile_type\":\"group\"},{\"id\":\"147881722563636\",\"name\":\"Udgam NGO\",\"profile_type\":\"page\"},{\"id\":\"91316581274\",\"name\":\"NGO\'s in India\",\"profile_type\":\"page\"},{\"id\":\"102020307240359\",\"name\":\"Sangeeta Rajput\",\"profile_type\":\"user\"},{\"id\":\"717968921731085\",\"name\":\"Mansi Khamkar\",\"profile_type\":\"user\"},{\"id\":\"130187267745029\",\"name\":\"Revati Dhoble\",\"profile_type\":\"user\"},{\"id\":\"299994596062\",\"name\":\"Charity\",\"profile_type\":\"page\"}],\"reactions\":[{\"id\":\"118346308937246\",\"name\":\"Tapsee Sahani\",\"profile_type\":\"user\",\"type\":\"LOVE\"},{\"id\":\"1976170165986074\",\"name\":\"Ruchita Yeole\",\"profile_type\":\"user\",\"type\":\"LIKE\"},{\"id\":\"130187267745029\",\"name\":\"Revati Dhoble\",\"profile_type\":\"user\",\"type\":\"LIKE\"}]}', '1211801652285946_1215723581893753', 1),
(24, 4, '{\"created_time\":\"2018-02-16T08:20:53+0000\",\"message\":\"\\u201cConsciously or unconsciously, everyone of us does render some service or another. If we cultivate the habit of doing this service deliberately, our desire for service will steadily grow stronger, and it will make not only for our own happiness, but that of the world at large.\\u201d Heart Foundation - NGO Ruchita Yeole Mumbai Smiles Foundation Reshma Khot Priyanka Rathod Tapsee Sahani Nandini Shah\",\"from\":{\"name\":\"Udgam NGO\",\"id\":\"147881722563636\"},\"message_tags\":[{\"id\":\"547508901989666\",\"name\":\"Heart Foundation - NGO\",\"type\":\"page\",\"offset\":279,\"length\":22},{\"id\":\"1976170165986074\",\"name\":\"Ruchita Yeole\",\"type\":\"user\",\"offset\":302,\"length\":13},{\"id\":\"303587393363260\",\"name\":\"Mumbai Smiles Foundation\",\"type\":\"page\",\"offset\":316,\"length\":24},{\"id\":\"1957604804497487\",\"name\":\"Reshma Khot\",\"type\":\"user\",\"offset\":341,\"length\":11},{\"id\":\"129247864509182\",\"name\":\"Priyanka Rathod\",\"type\":\"user\",\"offset\":353,\"length\":15},{\"id\":\"118346308937246\",\"name\":\"Tapsee Sahani\",\"type\":\"user\",\"offset\":369,\"length\":13},{\"id\":\"125711448200101\",\"name\":\"Nandini Shah\",\"type\":\"user\",\"offset\":383,\"length\":12}],\"type\":\"status\",\"privacy\":{\"value\":\"EVERYONE\",\"description\":\"\",\"friends\":\"\",\"allow\":\"\",\"deny\":\"\"},\"id\":\"1211801652285946_1215720105227434\",\"to\":[{\"id\":\"1211801652285946\",\"name\":\"Udgam Volunteers\",\"profile_type\":\"group\"},{\"id\":\"547508901989666\",\"name\":\"Heart Foundation - NGO\",\"profile_type\":\"page\"},{\"id\":\"1976170165986074\",\"name\":\"Ruchita Yeole\",\"profile_type\":\"user\"},{\"id\":\"303587393363260\",\"name\":\"Mumbai Smiles Foundation\",\"profile_type\":\"page\"},{\"id\":\"1957604804497487\",\"name\":\"Reshma Khot\",\"profile_type\":\"user\"},{\"id\":\"129247864509182\",\"name\":\"Priyanka Rathod\",\"profile_type\":\"user\"},{\"id\":\"118346308937246\",\"name\":\"Tapsee Sahani\",\"profile_type\":\"user\"},{\"id\":\"125711448200101\",\"name\":\"Nandini Shah\",\"profile_type\":\"user\"}],\"reactions\":[{\"id\":\"118346308937246\",\"name\":\"Tapsee Sahani\",\"profile_type\":\"user\",\"type\":\"LIKE\"},{\"id\":\"1976170165986074\",\"name\":\"Ruchita Yeole\",\"profile_type\":\"user\",\"type\":\"LOVE\"}]}', '1211801652285946_1215720105227434', 1),
(25, 4, '{\"created_time\":\"2018-02-09T18:58:17+0000\",\"from\":{\"name\":\"Udgam NGO\",\"id\":\"147881722563636\"},\"story\":\"Udgam NGO changed the type of group: Udgam Volunteers\\u00a0to \\\"Support\\\".\",\"story_tags\":[{\"id\":\"147881722563636\",\"name\":\"Udgam NGO\",\"type\":\"page\",\"offset\":0,\"length\":9},{\"id\":\"1211801652285946\",\"name\":\"Udgam Volunteers\",\"type\":\"group\",\"offset\":37,\"length\":16}],\"type\":\"status\",\"privacy\":{\"value\":\"EVERYONE\",\"description\":\"\",\"friends\":\"\",\"allow\":\"\",\"deny\":\"\"},\"id\":\"1211801652285946_1211822335617211\",\"to\":[{\"id\":\"1211801652285946\",\"name\":\"Udgam Volunteers\",\"profile_type\":\"group\"}]}', '1211801652285946_1211822335617211', 1),
(26, 4, '{\"created_time\":\"2018-02-09T18:58:17+0000\",\"message\":\"We make a living by what we get, But we make a life by what we give.The life of a man consists not in seeing visions and in dreaming dreams, but in active charity and in willing social service. For a nation to be truly transformed, there must be movements, civil societies, NGOs that are spread all across the land to educate people on the issues of Personal Responsibility.\",\"from\":{\"name\":\"Udgam NGO\",\"id\":\"147881722563636\"},\"story\":\"Udgam NGO updated the description of the group Udgam Volunteers.\",\"story_tags\":[{\"id\":\"147881722563636\",\"name\":\"Udgam NGO\",\"type\":\"page\",\"offset\":0,\"length\":9},{\"id\":\"1211801652285946\",\"name\":\"Udgam Volunteers\",\"type\":\"group\",\"offset\":47,\"length\":16}],\"type\":\"status\",\"privacy\":{\"value\":\"EVERYONE\",\"description\":\"\",\"friends\":\"\",\"allow\":\"\",\"deny\":\"\"},\"id\":\"1211801652285946_1211822332283878\",\"to\":[{\"id\":\"1211801652285946\",\"name\":\"Udgam Volunteers\",\"profile_type\":\"group\"}]}', '1211801652285946_1211822332283878', 1),
(27, 4, '{\"created_time\":\"2018-02-09T18:14:28+0000\",\"message\":\"\\u201cFor a nation to be truly transformed, there must be movements, civil societies, NGOs that are spread all across the land to educate people on the issues of Personal Responsibility. If a nation or rather active citizens of a nation could successfully launch such campaigns and a good percentage of the populace begin to live by the principles of Personal Responsibility, which is \\u201cdon\\u2019t blame others\\u201d, think of what you can do to fix it. Such a nation would cross the huddle of civilization in a record time.\\u201d  NPO Helpers NPO Publicity Revati Dhoble Reshma Khot\",\"from\":{\"name\":\"Udgam NGO\",\"id\":\"147881722563636\"},\"message_tags\":[{\"id\":\"261102497389631\",\"name\":\"NPO Helpers\",\"type\":\"group\",\"offset\":511,\"length\":11},{\"id\":\"1774494506184449\",\"name\":\"NPO Publicity\",\"type\":\"page\",\"offset\":523,\"length\":13},{\"id\":\"130187267745029\",\"name\":\"Revati Dhoble\",\"type\":\"user\",\"offset\":537,\"length\":13},{\"id\":\"1957604804497487\",\"name\":\"Reshma Khot\",\"type\":\"user\",\"offset\":551,\"length\":11}],\"type\":\"status\",\"privacy\":{\"value\":\"EVERYONE\",\"description\":\"\",\"friends\":\"\",\"allow\":\"\",\"deny\":\"\"},\"id\":\"1211801652285946_1211804888952289\",\"to\":[{\"id\":\"1211801652285946\",\"name\":\"Udgam Volunteers\",\"profile_type\":\"group\"},{\"id\":\"261102497389631\",\"name\":\"NPO Helpers\",\"profile_type\":\"group\"},{\"id\":\"1774494506184449\",\"name\":\"NPO Publicity\",\"profile_type\":\"page\"},{\"id\":\"130187267745029\",\"name\":\"Revati Dhoble\",\"profile_type\":\"user\"},{\"id\":\"1957604804497487\",\"name\":\"Reshma Khot\",\"profile_type\":\"user\"}],\"reactions\":[{\"id\":\"1957604804497487\",\"name\":\"Reshma Khot\",\"profile_type\":\"user\",\"type\":\"LIKE\"},{\"id\":\"717968921731085\",\"name\":\"Mansi Khamkar\",\"profile_type\":\"user\",\"type\":\"LIKE\"}]}', '1211801652285946_1211804888952289', 1),
(28, 4, '{\"created_time\":\"2018-02-09T18:09:10+0000\",\"from\":{\"name\":\"Udgam NGO\",\"id\":\"147881722563636\"},\"story\":\"Udgam NGO changed the name of the group \\\"Udgamians\\\" to \\\"Udgam Volunteers\\\".\",\"story_tags\":[{\"id\":\"147881722563636\",\"name\":\"Udgam NGO\",\"type\":\"page\",\"offset\":0,\"length\":9}],\"type\":\"status\",\"privacy\":{\"value\":\"EVERYONE\",\"description\":\"\",\"friends\":\"\",\"allow\":\"\",\"deny\":\"\"},\"id\":\"1211801652285946_1211803242285787\",\"to\":[{\"id\":\"1211801652285946\",\"name\":\"Udgam Volunteers\",\"profile_type\":\"group\"}],\"reactions\":[{\"id\":\"1957604804497487\",\"name\":\"Reshma Khot\",\"profile_type\":\"user\",\"type\":\"LIKE\"},{\"id\":\"717968921731085\",\"name\":\"Mansi Khamkar\",\"profile_type\":\"user\",\"type\":\"LIKE\"}]}', '1211801652285946_1211803242285787', 1),
(29, 4, '{\"created_time\":\"2018-02-09T18:08:02+0000\",\"message\":\"\\u201cWe make a living by what we get, \\nBut we make a life by what we give.The life of a man consists not in seeing visions and in dreaming dreams, but in active charity and in willing service.\\\" NPO Helpers NPO Publicity Jeevika Jain\",\"from\":{\"name\":\"Udgam NGO\",\"id\":\"147881722563636\"},\"message_tags\":[{\"id\":\"261102497389631\",\"name\":\"NPO Helpers\",\"type\":\"group\",\"offset\":190,\"length\":11},{\"id\":\"1774494506184449\",\"name\":\"NPO Publicity\",\"type\":\"page\",\"offset\":202,\"length\":13},{\"id\":\"107052656735498\",\"name\":\"Jeevika Jain\",\"type\":\"user\",\"offset\":216,\"length\":12}],\"type\":\"status\",\"privacy\":{\"value\":\"EVERYONE\",\"description\":\"\",\"friends\":\"\",\"allow\":\"\",\"deny\":\"\"},\"id\":\"1211801652285946_1211802758952502\",\"to\":[{\"id\":\"1211801652285946\",\"name\":\"Udgam Volunteers\",\"profile_type\":\"group\"},{\"id\":\"261102497389631\",\"name\":\"NPO Helpers\",\"profile_type\":\"group\"},{\"id\":\"1774494506184449\",\"name\":\"NPO Publicity\",\"profile_type\":\"page\"},{\"id\":\"107052656735498\",\"name\":\"Jeevika Jain\",\"profile_type\":\"user\"}],\"reactions\":[{\"id\":\"1957604804497487\",\"name\":\"Reshma Khot\",\"profile_type\":\"user\",\"type\":\"LIKE\"},{\"id\":\"717968921731085\",\"name\":\"Mansi Khamkar\",\"profile_type\":\"user\",\"type\":\"LIKE\"}]}', '1211801652285946_1211802758952502', 1),
(30, 4, '{\"created_time\":\"2018-02-09T18:04:35+0000\",\"from\":{\"name\":\"Udgam NGO\",\"id\":\"147881722563636\"},\"story\":\"Udgam NGO created the group Udgam Volunteers.\",\"story_tags\":[{\"id\":\"147881722563636\",\"name\":\"Udgam NGO\",\"type\":\"page\",\"offset\":0,\"length\":9},{\"id\":\"1211801652285946\",\"name\":\"Udgam Volunteers\",\"type\":\"group\",\"offset\":28,\"length\":16}],\"type\":\"status\",\"privacy\":{\"value\":\"EVERYONE\",\"description\":\"\",\"friends\":\"\",\"allow\":\"\",\"deny\":\"\"},\"id\":\"1211801652285946_1211801658952612\",\"to\":[{\"id\":\"1211801652285946\",\"name\":\"Udgam Volunteers\",\"profile_type\":\"group\"}]}', '1211801652285946_1211801658952612', 1),
(31, 2, '{\"admins\":[{\"id\":\"112364232916430\",\"name\":\"Sukesh Rathod\"}],\"members\":[{\"name\":\"PInky RathOd\",\"id\":\"1953204708281962\",\"administrator\":false},{\"name\":\"Priyanka Rathod\",\"id\":\"129247864509182\",\"administrator\":false},{\"name\":\"Jeevika Jain\",\"id\":\"107052656735498\",\"administrator\":false},{\"name\":\"Revati Dhoble\",\"id\":\"130187267745029\",\"administrator\":false},{\"name\":\"Nandini Shah\",\"id\":\"125711448200101\",\"administrator\":false},{\"name\":\"Tapsee Sahani\",\"id\":\"118346308937246\",\"administrator\":false},{\"name\":\"Reshma Khot\",\"id\":\"1957604804497487\",\"administrator\":false},{\"name\":\"Bhavika Mahadik\",\"id\":\"304668860016209\",\"administrator\":false},{\"name\":\"Vidhi Barve\",\"id\":\"1645992188786410\",\"administrator\":false},{\"name\":\"Omkar Sangar\",\"id\":\"1507817132643238\",\"administrator\":false},{\"name\":\"Ruchita Yeole\",\"id\":\"1976170165986074\",\"administrator\":false},{\"name\":\"Ashwini D Sangar\",\"id\":\"1955373411450698\",\"administrator\":false},{\"name\":\"Mansi Khamkar\",\"id\":\"717968921731085\",\"administrator\":false},{\"name\":\"Sukesh Rathod\",\"id\":\"112364232916430\",\"administrator\":true},{\"name\":\"Dipali Mohite\",\"id\":\"540457056289712\",\"administrator\":false},{\"name\":\"Piyush Nandanwar\",\"id\":\"2158084850883830\",\"administrator\":false}]}', '1211801652285946', 1),
(32, 4, '{\"created_time\":\"2018-02-16T10:14:35+0000\",\"message\":\"\\u201cIf only for a half hour a day, a child should do something serviceable to the community.\\u201cQuality, service, cleanliness, and value.Quality, service, cleanliness, and value.\\u201d Udgam NGO\",\"from\":{\"name\":\"Jeevika Jain\",\"id\":\"107052656735498\"},\"message_tags\":[{\"id\":\"147881722563636\",\"name\":\"Udgam NGO\",\"type\":\"page\",\"offset\":174,\"length\":9}],\"type\":\"status\",\"privacy\":{\"value\":\"\",\"description\":\"\",\"friends\":\"\",\"allow\":\"\",\"deny\":\"\"},\"id\":\"957891977697469_963768007109866\",\"to\":[{\"id\":\"957891977697469\",\"name\":\"Handicrafts Exhibition By NGO Children\",\"profile_type\":\"event\"},{\"id\":\"147881722563636\",\"name\":\"Udgam NGO\",\"profile_type\":\"page\"}],\"reactions\":[{\"id\":\"1976170165986074\",\"name\":\"Ruchita Yeole\",\"profile_type\":\"user\",\"type\":\"LOVE\"},{\"id\":\"130187267745029\",\"name\":\"Revati Dhoble\",\"profile_type\":\"user\",\"type\":\"LIKE\"},{\"id\":\"1953204708281962\",\"name\":\"PInky RathOd\",\"profile_type\":\"user\",\"type\":\"WOW\"},{\"id\":\"107052656735498\",\"name\":\"Jeevika Jain\",\"profile_type\":\"user\",\"type\":\"LOVE\"}],\"comments\":[{\"from\":{\"name\":\"Jeevika Jain\",\"id\":\"107052656735498\"},\"message\":\"WOW NPO Publicity\",\"message_tags\":[{\"id\":\"1774494506184449\",\"length\":13,\"name\":\"NPO Publicity\",\"offset\":4,\"type\":\"page\"}],\"id\":\"963768080443192\"},{\"from\":{\"name\":\"PInky RathOd\",\"id\":\"1953204708281962\"},\"message\":\"great social work Udgam NGO Udgam Volunteers\",\"message_tags\":[{\"id\":\"147881722563636\",\"length\":9,\"name\":\"Udgam NGO\",\"offset\":18,\"type\":\"page\"},{\"id\":\"1211801652285946\",\"length\":16,\"name\":\"Udgam Volunteers\",\"offset\":28,\"type\":\"group\"}],\"id\":\"963771740442826\"}]}', '957891977697469_963768007109866', 1),
(33, 4, '{\"created_time\":\"2018-02-09T16:41:01+0000\",\"message\":\"Coming soon! :)\",\"from\":{\"name\":\"Udgam NGO\",\"id\":\"147881722563636\"},\"status_type\":\"added_photos\",\"type\":\"photo\",\"privacy\":{\"value\":\"EVERYONE\",\"description\":\"\",\"friends\":\"\",\"allow\":\"\",\"deny\":\"\"},\"id\":\"957891977697469_959975460822454\",\"to\":[{\"id\":\"957891977697469\",\"name\":\"Handicrafts Exhibition By NGO Children\",\"profile_type\":\"event\"}],\"reactions\":[{\"id\":\"1976170165986074\",\"name\":\"Ruchita Yeole\",\"profile_type\":\"user\",\"type\":\"LIKE\"},{\"id\":\"717968921731085\",\"name\":\"Mansi Khamkar\",\"profile_type\":\"user\",\"type\":\"LOVE\"}]}', '957891977697469_959975460822454', 1),
(34, 3, '{\"admins\":[{\"id\":\"147881722563636\",\"name\":\"Udgam NGO\",\"profile_type\":\"page\"},{\"id\":\"1953204708281962\",\"name\":\"PInky RathOd\",\"profile_type\":\"user\"}],\"attending\":[{\"name\":\"Sukesh Rathod\",\"id\":\"112364232916430\",\"rsvp_status\":\"attending\"},{\"name\":\"Revati Dhoble\",\"id\":\"130187267745029\",\"rsvp_status\":\"attending\"},{\"name\":\"Ruchita Yeole\",\"id\":\"1976170165986074\",\"rsvp_status\":\"attending\"},{\"name\":\"Mansi Khamkar\",\"id\":\"717968921731085\",\"rsvp_status\":\"attending\"}],\"interested\":[{\"name\":\"Sangeeta Rajput\",\"id\":\"102020307240359\",\"rsvp_status\":\"unsure\"},{\"name\":\"Jeevika Jain\",\"id\":\"107052656735498\",\"rsvp_status\":\"unsure\"},{\"name\":\"PInky RathOd\",\"id\":\"1953204708281962\",\"rsvp_status\":\"unsure\"},{\"name\":\"Reshma Khot\",\"id\":\"1957604804497487\",\"rsvp_status\":\"unsure\"},{\"name\":\"Chetan Pawar\",\"id\":\"840951236065397\",\"rsvp_status\":\"unsure\"},{\"name\":\"Neelima Sawant\",\"id\":\"919736868181353\",\"rsvp_status\":\"unsure\"},{\"name\":\"Samidha Parab\",\"id\":\"1657960217576243\",\"rsvp_status\":\"unsure\"},{\"name\":\"Niriksha Nik\",\"id\":\"1690444824326911\",\"rsvp_status\":\"unsure\"},{\"name\":\"Kasturi Marathe\",\"id\":\"1696896733666235\",\"rsvp_status\":\"unsure\"},{\"name\":\"Hemala Khadpekar\",\"id\":\"2227262743956966\",\"rsvp_status\":\"unsure\"},{\"name\":\"Riitu Sood\",\"id\":\"10155867740020937\",\"rsvp_status\":\"unsure\"}]}', '957891977697469', 1),
(35, 4, '{\"created_time\":\"2018-02-09T17:12:45+0000\",\"message\":\"What we have planned | A short, energetic dance routine to a Bollywood song {super simple steps, we promise} by our in-house choreographer Richa bhatia. You don\\u2019t need any dance training or choreography skills! We just need you to come in with lots of enthusiasm and boost the confidence of the kids.\\n\\nshow the interest !and rock the floor\",\"from\":{\"name\":\"Udgam NGO\",\"id\":\"147881722563636\"},\"type\":\"status\",\"privacy\":{\"value\":\"EVERYONE\",\"description\":\"\",\"friends\":\"\",\"allow\":\"\",\"deny\":\"\"},\"id\":\"1831714127128319_1833385733627825\",\"to\":[{\"id\":\"1831714127128319\",\"name\":\"Dance Workshop at Ngos\",\"profile_type\":\"event\"}],\"reactions\":[{\"id\":\"717968921731085\",\"name\":\"Mansi Khamkar\",\"profile_type\":\"user\",\"type\":\"LOVE\"}]}', '1831714127128319_1833385733627825', 1),
(36, 4, '{\"created_time\":\"2018-02-09T17:09:19+0000\",\"message\":\"coming soon\",\"from\":{\"name\":\"Udgam NGO\",\"id\":\"147881722563636\"},\"status_type\":\"added_photos\",\"type\":\"photo\",\"privacy\":{\"value\":\"EVERYONE\",\"description\":\"\",\"friends\":\"\",\"allow\":\"\",\"deny\":\"\"},\"id\":\"1831714127128319_1833383643628034\",\"to\":[{\"id\":\"1831714127128319\",\"name\":\"Dance Workshop at Ngos\",\"profile_type\":\"event\"}],\"reactions\":[{\"id\":\"717968921731085\",\"name\":\"Mansi Khamkar\",\"profile_type\":\"user\",\"type\":\"LIKE\"}]}', '1831714127128319_1833383643628034', 1),
(37, 3, '{\"admins\":[{\"id\":\"147881722563636\",\"name\":\"Udgam NGO\",\"profile_type\":\"page\"}],\"attending\":[{\"name\":\"PInky RathOd\",\"id\":\"1953204708281962\",\"rsvp_status\":\"attending\"}],\"interested\":[{\"name\":\"Sukesh Rathod\",\"id\":\"112364232916430\",\"rsvp_status\":\"unsure\"},{\"name\":\"Sangeeta Rajput\",\"id\":\"102020307240359\",\"rsvp_status\":\"unsure\"},{\"name\":\"Jeevika Jain\",\"id\":\"107052656735498\",\"rsvp_status\":\"unsure\"},{\"name\":\"Tapsee Sahani\",\"id\":\"118346308937246\",\"rsvp_status\":\"unsure\"},{\"name\":\"Revati Dhoble\",\"id\":\"130187267745029\",\"rsvp_status\":\"unsure\"},{\"name\":\"Ruchita Yeole\",\"id\":\"1976170165986074\",\"rsvp_status\":\"unsure\"},{\"name\":\"Mansi Khamkar\",\"id\":\"717968921731085\",\"rsvp_status\":\"unsure\"}]}', '1831714127128319', 1);

-- --------------------------------------------------------

--
-- Stand-in structure for view `this_ngo_accs_view`
-- (See below for the actual view)
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
(11, 'Default User', NULL, NULL, NULL, NULL, NULL),
(12, 'Sukesh Rathod', 8888888888, 'sukeshrathod3@gmail.com', 'basant colony, mulund', 'mumbai', 4000055),
(13, 'Udgam NGO', 7303302447, 'rpinky1397@gmail.com', 'shivleela society, kamothe', 'mumbai', 444444),
(14, 'Veena Bhabal', NULL, NULL, NULL, NULL, NULL),
(15, 'Santosh Rathore', NULL, NULL, NULL, NULL, NULL),
(16, 'Nithya Mani', NULL, NULL, NULL, NULL, NULL),
(17, 'Deepa Narayanan', NULL, NULL, NULL, NULL, NULL),
(18, 'Sanket Paratkar', NULL, NULL, NULL, NULL, NULL),
(19, 'Shriram Rajaraman', NULL, NULL, NULL, NULL, NULL),
(20, 'Pratyush Kambli', NULL, NULL, NULL, NULL, NULL),
(21, 'Dipali Mohite', NULL, NULL, NULL, NULL, NULL),
(22, 'Bhavna Keswani', NULL, NULL, NULL, NULL, NULL),
(23, 'Harsha Punjabi', NULL, NULL, NULL, NULL, NULL),
(24, 'Tushar Jumani', NULL, NULL, NULL, NULL, NULL),
(25, 'Kajol Rohra', NULL, NULL, NULL, NULL, NULL),
(26, 'Bhuvanesh Kachave', NULL, NULL, NULL, NULL, NULL),
(27, 'Vijay Gore', NULL, NULL, NULL, NULL, NULL),
(28, 'Sukanya Jadhav', NULL, NULL, NULL, NULL, NULL),
(29, 'Manish Manjrekar', NULL, NULL, NULL, NULL, NULL),
(30, 'Bhavika Mahadik', NULL, NULL, NULL, NULL, NULL),
(31, 'Vidhi Barve', NULL, NULL, NULL, NULL, NULL),
(32, 'Omkar Sangar', NULL, NULL, NULL, NULL, NULL),
(33, 'Ashwini D Sangar', NULL, NULL, NULL, NULL, NULL),
(34, 'Piyush Nandanwar', NULL, NULL, NULL, NULL, NULL),
(35, 'Chetan Pawar', NULL, NULL, NULL, NULL, NULL),
(36, 'Neelima Sawant', NULL, NULL, NULL, NULL, NULL),
(37, 'Samidha Parab', NULL, NULL, NULL, NULL, NULL),
(38, 'Niriksha Nik', NULL, NULL, NULL, NULL, NULL),
(39, 'Kasturi Marathe', NULL, NULL, NULL, NULL, NULL),
(40, 'Hemala Khadpekar', NULL, NULL, NULL, NULL, NULL),
(41, 'Riitu Sood', NULL, NULL, NULL, NULL, NULL),
(42, 'Ashwini Sangar', NULL, NULL, NULL, NULL, NULL),
(43, 'Shraddha Dhoble', NULL, NULL, NULL, NULL, NULL),
(44, 'Vedant Dhoble', NULL, NULL, NULL, NULL, NULL),
(45, 'Shital Dhoble', NULL, NULL, NULL, NULL, NULL),
(46, 'Rajeev Dhoble', NULL, NULL, NULL, NULL, NULL),
(47, 'Swapnil Trigune', NULL, NULL, NULL, NULL, NULL),
(48, 'Vidya Dhoble', NULL, NULL, NULL, NULL, NULL),
(49, 'Sheetal Shendage', NULL, NULL, NULL, NULL, NULL),
(50, 'Janhavi Gupta', NULL, NULL, NULL, NULL, NULL),
(51, 'Sanjay Udasi', NULL, NULL, NULL, NULL, NULL),
(52, 'Pooja Suvarna', NULL, NULL, NULL, NULL, NULL),
(53, 'Mithil Ghorpade', NULL, NULL, NULL, NULL, NULL),
(54, 'Shaikh Danish', NULL, NULL, NULL, NULL, NULL),
(55, 'Isha Deo', NULL, NULL, NULL, NULL, NULL),
(56, 'Iptisaam Chougle', NULL, NULL, NULL, NULL, NULL),
(57, 'Asmita Parab', NULL, NULL, NULL, NULL, NULL),
(58, 'Seema Revenkar', NULL, NULL, NULL, NULL, NULL),
(59, 'Priyanka Patil', NULL, NULL, NULL, NULL, NULL),
(60, 'Bhavesh Motiramani', NULL, NULL, NULL, NULL, NULL),
(61, 'Rutuja Bauskar', NULL, NULL, NULL, NULL, NULL),
(62, 'Gaurav Hiran', NULL, NULL, NULL, NULL, NULL),
(63, 'Aditi Chavan', NULL, NULL, NULL, NULL, NULL),
(64, 'Hemangi Warhade', NULL, NULL, NULL, NULL, NULL);

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
(21, 11, 1, '0', 'Default', 'EAAH09htiIfABABSCRiRLDX8PZBV66BqzgMXU6YCAiZB5peZAceqhZBnIZA4jufVlZBaVdGq4vtbIwlZCVvojUnGKv0BZC2hAdoF6H2DnCfpFeKJx1XXh5x0ZC5YPJJ8ElhVnyHxa9in5n4crni96RDOhZAlfusddSWrt8aeZC7ZCYnnaVgZDZD', NULL, NULL, NULL),
(22, 12, 1, '112364232916430', 'Sukesh Rathod', 'EAAH09htiIfABAACaSlWuS25CjRJnNXBRAgmzNPpPLd0hPHWITK980DCbwN9vOI9y9RS05TTJlbeTDYTiYZCrocPC7YYrHr4rFBZC2p5lQededsIPVPBcvVERolOThLijh2dVCQSLsx1dPTdZALqRNSQ1iYx8Ym9lixnZBZByqUQZDZD', '0000-00-00 00:00:00', '', NULL),
(23, 13, 2, '942609048599379968', 'dogoodforNGO', '942609048599379968-x7fOIX3c0bkZ1KdIAUHOp2HGiCpvsUE', NULL, 'sRsKwuTKA4RA57IKkn9Ck3dL6Wapw6CAUxJo1rqOA2k0W', NULL),
(24, 14, 1, '319682078511130', 'Veena Bhabal', NULL, NULL, NULL, NULL),
(25, 15, 1, '1495430607244582', 'Santosh Rathore', NULL, NULL, NULL, NULL),
(26, 16, 1, '1820431771303571', 'Nithya Mani', NULL, NULL, NULL, NULL),
(27, 17, 1, '740145999514686', 'Deepa Narayanan', NULL, NULL, NULL, NULL),
(28, 18, 1, '847265915444303', 'Sanket Paratkar', NULL, NULL, NULL, NULL),
(29, 19, 1, '1980144125581537', 'Shriram Rajaraman', NULL, NULL, NULL, NULL),
(30, 20, 1, '1759593100742270', 'Pratyush Kambli', NULL, NULL, NULL, NULL),
(31, 21, 1, '540457056289712', 'Dipali Mohite', NULL, NULL, NULL, NULL),
(32, 22, 1, '117137879080272', 'Bhavna Keswani', NULL, NULL, NULL, NULL),
(33, 23, 1, '363331164126911', 'Harsha Punjabi', NULL, NULL, NULL, NULL),
(34, 24, 1, '1482896981826873', 'Tushar Jumani', NULL, NULL, NULL, NULL),
(35, 25, 1, '697119190495075', 'Kajol Rohra', NULL, NULL, NULL, NULL),
(36, 26, 1, '1502976159799214', 'Bhuvanesh Kachave', NULL, NULL, NULL, NULL),
(37, 27, 1, '169191877199439', 'Vijay Gore', NULL, NULL, NULL, NULL),
(38, 28, 1, '1974019076146964', 'Sukanya Jadhav', NULL, NULL, NULL, NULL),
(39, 29, 1, '1935844456442042', 'Manish Manjrekar', NULL, NULL, NULL, NULL),
(40, 30, 1, '304668860016209', 'Bhavika Mahadik', NULL, NULL, NULL, NULL),
(41, 31, 1, '1645992188786410', 'Vidhi Barve', NULL, NULL, NULL, NULL),
(42, 32, 1, '1507817132643238', 'Omkar Sangar', NULL, NULL, NULL, NULL),
(43, 33, 1, '1955373411450698', 'Ashwini D Sangar', NULL, NULL, NULL, NULL),
(44, 34, 1, '2158084850883830', 'Piyush Nandanwar', NULL, NULL, NULL, NULL),
(45, 35, 1, '840951236065397', 'Chetan Pawar', NULL, NULL, NULL, NULL),
(46, 36, 1, '919736868181353', 'Neelima Sawant', NULL, NULL, NULL, NULL),
(47, 37, 1, '1657960217576243', 'Samidha Parab', NULL, NULL, NULL, NULL),
(48, 38, 1, '1690444824326911', 'Niriksha Nik', NULL, NULL, NULL, NULL),
(49, 39, 1, '1696896733666235', 'Kasturi Marathe', NULL, NULL, NULL, NULL),
(50, 40, 1, '2227262743956966', 'Hemala Khadpekar', NULL, NULL, NULL, NULL),
(51, 41, 1, '10155867740020937', 'Riitu Sood', NULL, NULL, NULL, NULL),
(52, 42, 1, '1980770635581090', 'Ashwini Sangar', NULL, NULL, NULL, NULL),
(53, 43, 1, '1409623505832748', 'Shraddha Dhoble', NULL, NULL, NULL, NULL),
(54, 44, 1, '1346500098811729', 'Vedant Dhoble', NULL, NULL, NULL, NULL),
(55, 45, 1, '1646712128752066', 'Shital Dhoble', NULL, NULL, NULL, NULL),
(56, 46, 1, '1004952922977718', 'Rajeev Dhoble', NULL, NULL, NULL, NULL),
(57, 47, 1, '1193243957443537', 'Swapnil Trigune', NULL, NULL, NULL, NULL),
(58, 48, 1, '2060515757517248', 'Vidya Dhoble', NULL, NULL, NULL, NULL),
(59, 49, 1, '852760414891493', 'Sheetal Shendage', NULL, NULL, NULL, NULL),
(60, 50, 1, '1657722800958738', 'Janhavi Gupta', NULL, NULL, NULL, NULL),
(61, 51, 1, '1971486419734942', 'Sanjay Udasi', NULL, NULL, NULL, NULL),
(62, 52, 1, '1898606477124089', 'Pooja Suvarna', NULL, NULL, NULL, NULL),
(63, 53, 1, '1613671212032496', 'Mithil Ghorpade', NULL, NULL, NULL, NULL),
(64, 54, 1, '1223894297754944', 'Shaikh Danish', NULL, NULL, NULL, NULL),
(65, 55, 1, '2047889395439014', 'Isha Deo', NULL, NULL, NULL, NULL),
(66, 56, 1, '1982846305266712', 'Iptisaam Chougle', NULL, NULL, NULL, NULL),
(67, 57, 1, '905529982930493', 'Asmita Parab', NULL, NULL, NULL, NULL),
(68, 58, 1, '1538500049600234', 'Seema Revenkar', NULL, NULL, NULL, NULL),
(69, 59, 1, '1413287618769099', 'Priyanka Patil', NULL, NULL, NULL, NULL),
(70, 60, 1, '1979578312367914', 'Bhavesh Motiramani', NULL, NULL, NULL, NULL),
(71, 61, 1, '1997338480504506', 'Rutuja Bauskar', NULL, NULL, NULL, NULL),
(72, 62, 1, '1531868760242826', 'Gaurav Hiran', NULL, NULL, NULL, NULL),
(73, 63, 1, '970730769744992', 'Aditi Chavan', NULL, NULL, NULL, NULL),
(74, 64, 1, '1590472661015375', 'Hemangi Warhade', NULL, NULL, NULL, NULL);

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
(1, 1, 1, 1),
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
(15, 9, 1, 2),
(16, 12, 4, NULL),
(17, 13, 4, NULL),
(18, 14, 3, 5),
(19, 15, 3, 5),
(20, 16, 3, 5),
(21, 17, 3, 5),
(22, 18, 3, 5),
(23, 19, 3, 5),
(24, 20, 3, 5),
(25, 21, 3, 5),
(26, 22, 3, 5),
(27, 23, 3, 5),
(28, 24, 3, 5),
(29, 25, 3, 5),
(30, 26, 3, 5),
(31, 27, 3, 5),
(32, 28, 3, 5),
(33, 29, 3, 5),
(34, 30, 3, 5),
(35, 31, 3, 5),
(36, 32, 3, 5),
(37, 33, 3, 5),
(38, 34, 3, 5),
(39, 35, 3, 5),
(40, 36, 3, 5),
(41, 37, 3, 5),
(42, 38, 3, 5),
(43, 39, 3, 5),
(44, 40, 3, 5),
(45, 41, 3, 5),
(46, 42, 3, 5),
(47, 43, 3, 5),
(48, 44, 3, 5),
(49, 45, 3, 5),
(50, 46, 3, 5),
(51, 47, 3, 5),
(52, 48, 3, 5),
(53, 49, 3, 5),
(54, 50, 3, 5),
(55, 51, 3, 5),
(56, 52, 3, 5),
(57, 53, 3, 5),
(58, 54, 3, 5),
(59, 55, 3, 5),
(60, 56, 3, 5),
(61, 57, 3, 5),
(62, 58, 3, 5),
(63, 59, 3, 5),
(64, 60, 3, 5),
(65, 61, 3, 5),
(66, 62, 3, 5),
(67, 63, 3, 5),
(68, 64, 3, 5);

-- --------------------------------------------------------

--
-- Table structure for table `user_scan_response`
--

CREATE TABLE `user_scan_response` (
  `id` int(11) NOT NULL,
  `user_scan_id` int(30) NOT NULL,
  `us_response` longtext NOT NULL,
  `us_type` int(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_scan_response_type`
--

CREATE TABLE `user_scan_response_type` (
  `res_id` int(11) NOT NULL,
  `res_type` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_scan_response_type`
--

INSERT INTO `user_scan_response_type` (`res_id`, `res_type`) VALUES
(1, 'tweet'),
(2, 'comments'),
(3, 'likes'),
(4, 'list'),
(5, 'rgscan_list'),
(6, 'rglist_res');

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

--
-- Dumping data for table `user_scores`
--

INSERT INTO `user_scores` (`us_id`, `ue_id`, `st_id`, `score`, `percent_score`, `details`) VALUES
(1, 7, 1, 419, 419, NULL),
(2, 8, 1, 459, 459, NULL),
(3, 5, 1, 445, 445, NULL),
(4, 6, 1, 465, 465, NULL),
(5, 16, 1, 291, 291, NULL),
(6, 9, 1, 332, 332, NULL),
(7, 18, 1, 21, 21, NULL),
(8, 12, 1, 604, 604, NULL),
(9, 3, 1, 497, 497, NULL),
(10, 4, 1, 533, 533, NULL),
(11, 10, 1, 663, 663, NULL),
(12, 1, 1, 566, 566, NULL),
(13, 11, 1, 600, 600, NULL),
(14, 14, 1, 259, 259, NULL),
(15, 19, 1, 10, 10, NULL),
(16, 13, 1, 500, 500, NULL),
(17, 15, 1, 484, 484, NULL),
(18, 20, 1, 3, 3, NULL),
(19, 21, 1, 3, 3, NULL),
(20, 22, 1, 3, 3, NULL),
(21, 23, 1, 3, 3, NULL),
(22, 24, 1, 6, 6, NULL),
(23, 25, 1, 28, 28, NULL),
(24, 26, 1, 3, 3, NULL),
(25, 27, 1, 12, 12, NULL),
(26, 28, 1, 3, 3, NULL),
(27, 29, 1, 5, 5, NULL),
(28, 30, 1, 5, 5, NULL),
(29, 31, 1, 3, 3, NULL),
(30, 32, 1, 2, 2, NULL),
(31, 33, 1, 2, 2, NULL),
(32, 1, 10, 566, 566, NULL),
(33, 3, 10, 497, 497, NULL),
(34, 4, 10, 533, 533, NULL),
(35, 5, 10, 445, 445, NULL),
(36, 6, 10, 465, 465, NULL),
(37, 7, 10, 419, 419, NULL),
(38, 8, 10, 459, 459, NULL),
(39, 9, 10, 332, 332, NULL),
(40, 10, 10, 663, 663, NULL),
(41, 11, 10, 600, 600, NULL),
(42, 12, 10, 604, 604, NULL),
(43, 13, 10, 500, 500, NULL),
(44, 14, 10, 259, 259, NULL),
(45, 15, 10, 484, 484, NULL),
(46, 16, 10, 291, 291, NULL),
(47, 34, 1, 33, 33, NULL),
(48, 35, 1, 25, 25, NULL),
(49, 36, 1, 35, 35, NULL),
(50, 37, 1, 35, 35, NULL),
(51, 38, 1, 25, 25, NULL),
(52, 39, 1, 20, 20, NULL),
(53, 40, 1, 20, 20, NULL),
(54, 41, 1, 20, 20, NULL),
(55, 42, 1, 20, 20, NULL),
(56, 43, 1, 20, 20, NULL),
(57, 44, 1, 20, 20, NULL),
(58, 45, 1, 20, 20, NULL),
(59, 46, 1, 10, 10, NULL),
(60, 47, 1, 10, 10, NULL),
(61, 48, 1, 10, 10, NULL),
(62, 49, 1, 10, 10, NULL),
(63, 50, 1, 10, 10, NULL),
(64, 51, 1, 10, 10, NULL),
(65, 52, 1, 10, 10, NULL),
(66, 53, 1, 2, 2, NULL),
(67, 54, 1, 2, 2, NULL),
(68, 55, 1, 2, 2, NULL),
(69, 56, 1, 4, 4, NULL),
(70, 57, 1, 2, 2, NULL),
(71, 58, 1, 2, 2, NULL),
(72, 59, 1, 6, 6, NULL),
(73, 60, 1, 4, 4, NULL),
(74, 61, 1, 2, 2, NULL),
(75, 62, 1, 2, 2, NULL),
(76, 63, 1, 2, 2, NULL),
(77, 64, 1, 2, 2, NULL),
(78, 65, 1, 2, 2, NULL),
(79, 66, 1, 3, 3, NULL),
(80, 67, 1, 2, 2, NULL),
(81, 68, 1, 2, 2, NULL);

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
-- Indexes for table `scan_mx_id`
--
ALTER TABLE `scan_mx_id`
  ADD PRIMARY KEY (`sr_id`),
  ADD KEY `u_id` (`u_id`),
  ADD KEY `response_type` (`response_type`);

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
  ADD KEY `so_id` (`so_id`),
  ADD KEY `at_id` (`at_id`);

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
-- Indexes for table `user_scan_response`
--
ALTER TABLE `user_scan_response`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_scan_id` (`user_scan_id`),
  ADD KEY `us_type` (`us_type`);

--
-- Indexes for table `user_scan_response_type`
--
ALTER TABLE `user_scan_response_type`
  ADD PRIMARY KEY (`res_id`);

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
  MODIFY `ns_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `points`
--
ALTER TABLE `points`
  MODIFY `p_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `scan_mx_id`
--
ALTER TABLE `scan_mx_id`
  MODIFY `sr_id` int(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `score_types`
--
ALTER TABLE `score_types`
  MODIFY `st_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `social_object_types`
--
ALTER TABLE `social_object_types`
  MODIFY `ot_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `social_responses`
--
ALTER TABLE `social_responses`
  MODIFY `sr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `u_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `user_accounts`
--
ALTER TABLE `user_accounts`
  MODIFY `a_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `user_extended`
--
ALTER TABLE `user_extended`
  MODIFY `ue_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `user_scan_response`
--
ALTER TABLE `user_scan_response`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_scan_response_type`
--
ALTER TABLE `user_scan_response_type`
  MODIFY `res_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_scores`
--
ALTER TABLE `user_scores`
  MODIFY `us_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

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
-- Constraints for table `scan_mx_id`
--
ALTER TABLE `scan_mx_id`
  ADD CONSTRAINT `scan_mx_id_ibfk_1` FOREIGN KEY (`u_id`) REFERENCES `user_accounts` (`u_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `scan_mx_id_ibfk_2` FOREIGN KEY (`response_type`) REFERENCES `user_scan_response_type` (`res_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `social_responses`
--
ALTER TABLE `social_responses`
  ADD CONSTRAINT `account_type` FOREIGN KEY (`at_id`) REFERENCES `account_types` (`at_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `object_type` FOREIGN KEY (`so_id`) REFERENCES `social_object_types` (`ot_id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
-- Constraints for table `user_scan_response`
--
ALTER TABLE `user_scan_response`
  ADD CONSTRAINT `user_scan_response_ibfk_1` FOREIGN KEY (`user_scan_id`) REFERENCES `user_accounts` (`u_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_scan_response_ibfk_2` FOREIGN KEY (`us_type`) REFERENCES `user_scan_response_type` (`res_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_scores`
--
ALTER TABLE `user_scores`
  ADD CONSTRAINT `user_scores_ibfk_1` FOREIGN KEY (`ue_id`) REFERENCES `user_extended` (`ue_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_scores_ibfk_2` FOREIGN KEY (`st_id`) REFERENCES `score_types` (`st_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
