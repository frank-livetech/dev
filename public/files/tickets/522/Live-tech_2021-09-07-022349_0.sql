-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 09, 2021 at 11:16 PM
-- Server version: 5.7.31
-- PHP Version: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `frame`
--

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

DROP TABLE IF EXISTS `tickets`;
CREATE TABLE IF NOT EXISTS `tickets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dept_id` int(11) DEFAULT NULL,
  `priority` int(11) DEFAULT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `ticket_detail` longtext,
  `status` int(20) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `deadline` timestamp NULL DEFAULT NULL,
  `coustom_id` varchar(191) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_deleted` int(1) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `is_flagged` tinyint(1) NOT NULL DEFAULT '0',
  `seq_custom_id` int(11) DEFAULT NULL,
  `trashed` tinyint(11) NOT NULL DEFAULT '0',
  `reply_deadline` varchar(255) DEFAULT NULL,
  `resolution_deadline` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`id`, `dept_id`, `priority`, `assigned_to`, `subject`, `customer_id`, `ticket_detail`, `status`, `type`, `deadline`, `coustom_id`, `created_by`, `updated_by`, `created_at`, `updated_at`, `is_deleted`, `deleted_at`, `is_flagged`, `seq_custom_id`, `trashed`, `reply_deadline`, `resolution_deadline`) VALUES
(1, 12, 9, NULL, 'Default Sla Plan Assignment', 2, '<p>Default Sla Plan Assignment Details</p>', 12, 6, '2021-08-25 19:00:00', 'VYV-179-1190', 8, 8, '2021-08-04 22:33:34', '2021-08-09 12:36:25', 0, NULL, 0, 2, 0, NULL, NULL),
(2, 12, 9, NULL, 'Default SLA Plan Assoc', 2, '<p>Default SLA Plan Assoc Details</p>', 12, 3, '2021-08-30 19:00:00', 'OXJ-696-8977', 8, 8, '2021-08-06 14:59:59', '2021-08-09 17:59:33', 0, NULL, 0, 3, 0, '2021-08-12T03:59', '2021-08-14T03:59'),
(4, 12, 9, NULL, 'Default SLA Plan Assoc 1', 2, '<p>Default SLA Plan Assoc 1 Details</p>', 12, 3, '2021-08-30 19:00:00', 'JYN-627-8059', 8, 8, '2021-08-09 15:06:24', '2021-08-09 15:34:23', 0, NULL, 0, 5, 0, NULL, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
