-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 11, 2016 at 07:23 AM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `auditbox`
--

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE IF NOT EXISTS `clients` (
`id` bigint(20) NOT NULL,
  `auth_hash` varchar(64) NOT NULL,
  `app_key` varchar(64) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `auth_hash`, `app_key`) VALUES
(1, '21add8c2dd6a77418b2ca9adef48e081fd2870b528625eecba74e285267ebb5d', '6F1ED002AB5595859014EBF0951522D9');

-- --------------------------------------------------------

--
-- Table structure for table `client_devices`
--

CREATE TABLE IF NOT EXISTS `client_devices` (
`id` bigint(20) NOT NULL,
  `owner_id` bigint(20) NOT NULL,
  `target_id` varchar(36) NOT NULL,
  `device_label` varchar(64) NOT NULL,
  `avail_configs` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `client_devices`
--

INSERT INTO `client_devices` (`id`, `owner_id`, `target_id`, `device_label`, `avail_configs`) VALUES
(1, 1, '8618ee57-27c2-4aaa-95f2-218f503a8398', 'main_office', '{"0":{"id":"daba56c8-73ec-11df-a475-002264764cea","label":"Full and fast"},"1":{"id":"698f691e-7489-11df-9d8c-002264764cea","label":"Full and fast ultimate"},"2":{"id":"708f25c4-7489-11df-8094-002264764cea","label":"Full and very deep"},"3":{"id":"74db13d6-7489-11df-91b9-002264764cea","label":"Full and very deep ultimate"}}'),
(2, 1, '31337e57-27c2-4aaa-95f2-218f503a8398', 'warehouse', '{"0":{"id":"daba56c8-73ec-11df-a475-002264764cea","label":"Full and fast"},"1":{"id":"698f691e-7489-11df-9d8c-002264764cea","label":"Full and fast ultimate"},"2":{"id":"708f25c4-7489-11df-8094-002264764cea","label":"Full and very deep"},"3":{"id":"74db13d6-7489-11df-91b9-002264764cea","label":"Full and very deep ultimate"}}'),
(3, 1, '1', '1', '1'),
(4, 1, '', '1337_test', '    {\\"0\\":{\\"id\\":\\"daba56c8-73ec-11df-a475-002264764cea\\",\\"label\\":\\"Full and fast\\"},\\"1\\":{\\"id\\":\\"698f691e-7489-11df-9d8c-002264764cea\\",\\"label\\":\\"Full and fast ultimate\\"},\\"2\\":{\\"id\\":\\"708f25c4-7489-11df-8094-002264764cea\\",\\"label\\":\\"Full and very deep\\"},\\"3\\":{\\"id\\":\\"74db13d6-7489-11df-91b9-002264764cea\\",\\"label\\":\\"Full and very deep ultimate\\"}}\\r\\n  '),
(5, 1, '00000000000', '000_test', '    {\\"0\\":{\\"id\\":\\"daba56c8-73ec-11df-a475-002264764cea\\",\\"label\\":\\"Full and fast\\"},\\"1\\":{\\"id\\":\\"698f691e-7489-11df-9d8c-002264764cea\\",\\"label\\":\\"Full and fast ultimate\\"},\\"2\\":{\\"id\\":\\"708f25c4-7489-11df-8094-002264764cea\\",\\"label\\":\\"Full and very deep\\"},\\"3\\":{\\"id\\":\\"74db13d6-7489-11df-91b9-002264764cea\\",\\"label\\":\\"Full and very deep ultimate\\"}}\\r\\n  '),
(6, 1, 'adsf', '000_test', '    {\\"0\\":{\\"id\\":\\"daba56c8-73ec-11df-a475-002264764cea\\",\\"label\\":\\"Full and fast\\"},\\"1\\":{\\"id\\":\\"698f691e-7489-11df-9d8c-002264764cea\\",\\"label\\":\\"Full and fast ultimate\\"},\\"2\\":{\\"id\\":\\"708f25c4-7489-11df-8094-002264764cea\\",\\"label\\":\\"Full and very deep\\"},\\"3\\":{\\"id\\":\\"74db13d6-7489-11df-91b9-002264764cea\\",\\"label\\":\\"Full and very deep ultimate\\"}}\\r\\n  '),
(7, 1, 'lasttest', 'lasttest', '    {\\"0\\":{\\"id\\":\\"daba56c8-73ec-11df-a475-002264764cea\\",\\"label\\":\\"Full and fast\\"},\\"1\\":{\\"id\\":\\"698f691e-7489-11df-9d8c-002264764cea\\",\\"label\\":\\"Full and fast ultimate\\"},\\"2\\":{\\"id\\":\\"708f25c4-7489-11df-8094-002264764cea\\",\\"label\\":\\"Full and very deep\\"},\\"3\\":{\\"id\\":\\"74db13d6-7489-11df-91b9-002264764cea\\",\\"label\\":\\"Full and very deep ultimate\\"}}\\r\\n  ');

-- --------------------------------------------------------

--
-- Table structure for table `client_meta`
--

CREATE TABLE IF NOT EXISTS `client_meta` (
`id` bigint(20) NOT NULL,
  `client_id` bigint(20) NOT NULL,
  `first_name` varchar(42) NOT NULL,
  `last_name` varchar(42) NOT NULL,
  `business_name` varchar(64) NOT NULL,
  `email` varchar(255) NOT NULL,
  `avatar` varchar(64) NOT NULL,
  `max_devices` bigint(20) NOT NULL,
  `device_labels` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `client_meta`
--

INSERT INTO `client_meta` (`id`, `client_id`, `first_name`, `last_name`, `business_name`, `email`, `avatar`, `max_devices`, `device_labels`) VALUES
(1, 1, 'Rut', 'Marzavec', 'Marzavec LLC', 'admin@marzavec.com', '', 5, ' {"0":"main_office","1":"warehouse"}');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE IF NOT EXISTS `jobs` (
`id` bigint(20) NOT NULL,
  `label` varchar(64) NOT NULL,
  `task_id` varchar(36) NOT NULL,
  `job_owner` bigint(20) NOT NULL,
  `last_run` datetime NOT NULL,
  `next_run` datetime NOT NULL,
  `run_interval` varchar(12) NOT NULL,
  `run_once` tinyint(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `label`, `task_id`, `job_owner`, `last_run`, `next_run`, `run_interval`, `run_once`) VALUES
(1, 'Intrusion Scan', 'c9d0b718-7003-410e-b94b-f1557425c942', 1, '2016-12-04 21:34:07', '2016-12-05 00:00:00', '1 00:00', 0),
(2, 'Full Scan', 'c9d0b718-7003-410e-b94b-f1557425c942', 1, '2016-12-06 21:34:27', '2016-12-07 21:34:27', '1 00:00', 0),
(4, 'Dashboard test', '698f691e-7489-11df-9d8c-002264764cea', 1, '2016-12-07 00:07:53', '2016-12-07 09:07:53', '3 00:00', 0),
(6, 'qwerqwerqwer', 'daba56c8-73ec-11df-a475-002264764cea', 1, '2016-12-07 00:12:54', '2016-12-07 00:22:54', '1 00:00', 0),
(7, '12 10 2016 new job test', 'daba56c8-73ec-11df-a475-002264764cea', 1, '2016-12-10 21:23:23', '2016-12-10 21:33:23', '1 00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE IF NOT EXISTS `reports` (
`id` bigint(20) NOT NULL,
  `job_owner` bigint(20) NOT NULL,
  `seen` tinyint(1) NOT NULL,
  `task_id` varchar(36) NOT NULL,
  `report_date` datetime NOT NULL,
  `gzipped` tinyint(1) NOT NULL,
  `path` varchar(64) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_devices`
--
ALTER TABLE `client_devices`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_meta`
--
ALTER TABLE `client_meta`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `client_devices`
--
ALTER TABLE `client_devices`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `client_meta`
--
ALTER TABLE `client_meta`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
