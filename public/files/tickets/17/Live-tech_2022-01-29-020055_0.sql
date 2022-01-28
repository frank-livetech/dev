-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 15, 2021 at 02:53 PM
-- Server version: 10.3.31-MariaDB
-- PHP Version: 7.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mylivete_framework`
--

-- --------------------------------------------------------

--
-- Table structure for table `email_queues`
--

CREATE TABLE `email_queues` (
  `id` int(11) NOT NULL,
  `mail_queue_address` varchar(191) DEFAULT NULL,
  `queue_type` varchar(30) DEFAULT NULL,
  `protocol` varchar(20) DEFAULT NULL,
  `queue_template` varchar(191) DEFAULT NULL,
  `is_enabled` varchar(11) DEFAULT NULL,
  `mailserver_hostname` varchar(191) DEFAULT NULL,
  `mailserver_port` varchar(11) DEFAULT NULL,
  `mailserver_username` text DEFAULT NULL,
  `mailserver_password` text DEFAULT NULL,
  `from_name` varchar(191) DEFAULT NULL,
  `from_mail` varchar(191) DEFAULT NULL,
  `mail_dept_id` int(11) DEFAULT NULL,
  `mail_type_id` int(11) DEFAULT NULL,
  `mail_status_id` int(11) DEFAULT NULL,
  `mail_priority_id` int(11) DEFAULT NULL,
  `registration_required` varchar(11) DEFAULT NULL,
  `autosend` varchar(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `is_deleted` int(11) NOT NULL DEFAULT 0,
  `php_mailer` varchar(11) NOT NULL DEFAULT 'no',
  `outbound` varchar(40) NOT NULL DEFAULT 'yes',
  `is_default` varchar(40) NOT NULL DEFAULT 'no'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `email_queues`
--

INSERT INTO `email_queues` (`id`, `mail_queue_address`, `queue_type`, `protocol`, `queue_template`, `is_enabled`, `mailserver_hostname`, `mailserver_port`, `mailserver_username`, `mailserver_password`, `from_name`, `from_mail`, `mail_dept_id`, `mail_type_id`, `mail_status_id`, `mail_priority_id`, `registration_required`, `autosend`, `created_by`, `created_at`, `updated_at`, `deleted_at`, `updated_by`, `deleted_by`, `is_deleted`, `php_mailer`, `outbound`, `is_default`) VALUES
(2, 'Support', 'pop3', 'ssl', 'pop3tls', 'yes', 'mylive-tech.com', '995', 'support2@mylive-tech.com', 'y7.v9jLy!JLG9!s', 'Live-Tech Support', 'support2@mylive-tech.com', 16, 3, 24, 10, 'yes', 'yes', 8, '2021-05-08 22:25:05', '2021-08-26 15:23:14', '2021-08-25 22:10:22', 8, 8, 1, 'yes', 'yes', 'no'),
(3, 'Web Dev', 'pop3', 'ssl', 'pop3tls', 'no', 'mylive-tech.com', '995', 'web_dev2@mylive-tech.com', '596F{qLHQ,;U', 'Live-Tech WebDev Team', 'web_dev2@mylive-tech.com', 18, 6, 24, 10, 'yes', 'yes', 8, '2021-05-08 22:28:22', '2021-09-02 12:59:46', '2021-07-21 16:37:57', 107, 107, 1, 'no', 'yes', 'no'),
(4, 'BILLING', 'pop3', 'ssl', 'pop3tls', 'yes', 'mylive-tech.com', '995', 'billing_dev@mylive-tech.com', 'fT#Jol557NcA', 'Live-Tech Billing', 'billing_dev@mylive-tech.com', 12, 8, 43, 9, 'yes', 'yes', 8, '2021-05-23 22:51:46', '2021-09-02 12:59:51', NULL, 8, NULL, 0, 'yes', 'yes', 'no'),
(5, 'BUGS', 'pop3', 'ssl', 'pop3tls', 'yes', 'mylive-tech.com', '995', 'bugs_dev@mylive-tech.com', 'nool][(Oz#];', 'LT bug detectors', 'bugs_dev@mylive-tech.com', 18, 7, 43, 10, 'yes', 'yes', 8, '2021-05-23 23:13:24', '2021-09-02 13:07:25', NULL, 8, NULL, 0, 'yes', 'yes', 'no'),
(6, 'LEADS', 'pop3', 'ssl', 'pop3tls', 'no', 'mylive-tech.com', '995', 'leads_dev@mylive-tech.com', '&^7VV#?~NF$C', 'Live-Tech New Business Dept.', 'leads_dev@mylive-tech.com', 21, 11, 43, 12, 'no', 'no', 8, '2021-05-24 11:58:53', '2021-09-02 12:59:55', NULL, 8, NULL, 0, 'yes', 'yes', 'no'),
(7, 'WEB LEAD', 'pop3', 'ssl', 'pop3tls', 'yes', 'mylive-tech.com', '995', 'online-forms_dev@mylive-tech.com', 'tZ=CrNP=X)-2', 'Live-Tech Online Contact', 'online-forms_dev@mylive-tech.com', 21, 3, 43, 9, 'yes', 'yes', 8, '2021-05-24 12:08:16', '2021-09-02 12:59:58', NULL, 8, NULL, 0, 'no', 'yes', 'no'),
(8, 'PAY', 'pop3', 'ssl', 'pop3tls', 'yes', 'mylive-tech.com', '995', 'payment_issues_dev@mylive-tech.com', '1MtkhKq=G;%V', 'Live-Tech Billing Dept', 'payment_issues_dev@mylive-tech.com', 12, 8, 43, 12, 'yes', 'yes', 8, '2021-05-24 12:23:05', '2021-09-02 13:00:01', NULL, 8, NULL, 0, 'no', 'yes', 'no'),
(9, 'SALES', 'pop3', 'ssl', 'pop3tls', 'yes', 'mylive-tech.com', '995', 'sales_dev@mylive-tech.com', '6o~c*J9xditj', 'Live-Tech Sales', 'sales_dev@mylive-tech.com', 17, 10, 43, 10, 'yes', 'yes', 8, '2021-05-24 12:29:51', '2021-09-02 12:56:59', NULL, 8, NULL, 0, 'yes', 'yes', 'no'),
(10, 'LT SHOP', 'pop3', 'ssl', 'pop3tls', 'yes', 'mylive-tech.com', '995', 'shop_order_dev@mylive-tech.com', 'wN5OR+@[u[.q', 'Live-Tech Billing', 'shop_order_dev@mylive-tech.com', 12, 8, 43, 9, 'yes', 'yes', 8, '2021-05-24 12:41:32', '2021-09-02 16:54:18', NULL, 8, NULL, 0, 'yes', 'yes', 'no'),
(11, 'Support', 'pop3', 'ssl', 'pop3tls', 'yes', 'mylive-tech.com', '995', 'support_dev@mylive-tech.com', 'Adm4^MU^?98w', 'Live-Tech Support', 'support_dev@mylive-tech.com', 16, 3, 43, 10, 'yes', 'yes', 8, '2021-08-02 15:07:38', '2021-09-02 16:40:19', NULL, 8, NULL, 0, 'yes', 'yes', 'no');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `email_queues`
--
ALTER TABLE `email_queues`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `email_queues`
--
ALTER TABLE `email_queues`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
