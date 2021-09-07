-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 13, 2021 at 05:05 AM
-- Server version: 10.4.6-MariaDB
-- PHP Version: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `accas`
--

-- --------------------------------------------------------

--
-- Table structure for table `cca_team_employee_transaction`
--

CREATE TABLE `cca_team_employee_transaction` (
  `id` int(11) NOT NULL,
  `plan_id` int(11) DEFAULT NULL,
  `emp_id` int(11) DEFAULT NULL,
  `drop_team_id` int(11) DEFAULT NULL,
  `add_team_id` int(11) DEFAULT NULL,
  `drop_date` date DEFAULT NULL,
  `rev_add_date` date DEFAULT NULL,
  `rev_status` varchar(100) DEFAULT NULL,
  `rev_drop_comment` text DEFAULT NULL,
  `rev_add_comment` text DEFAULT NULL,
  `prog_auditor_shq_comment` text DEFAULT NULL,
  `dept_id` int(11) DEFAULT NULL,
  `prog_auditor_submit_status` varchar(100) DEFAULT NULL,
  `hod_comment` text DEFAULT NULL,
  `hod_status` varchar(222) DEFAULT NULL,
  `reason_reject` varchar(500) DEFAULT NULL,
  `hod_appr_dis_date` date DEFAULT NULL,
  `operation_by` int(11) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cca_team_employee_transaction`
--

INSERT INTO `cca_team_employee_transaction` (`id`, `plan_id`, `emp_id`, `drop_team_id`, `add_team_id`, `drop_date`, `rev_add_date`, `rev_status`, `rev_drop_comment`, `rev_add_comment`, `prog_auditor_shq_comment`, `dept_id`, `prog_auditor_submit_status`, `hod_comment`, `hod_status`, `reason_reject`, `hod_appr_dis_date`, `operation_by`, `role_id`) VALUES
(1, 1, 200, 1, 2, '2021-07-13', '2021-07-13', 'Approved', 'hello', NULL, 'hello', 10, '0', 'ok', 'Approved', NULL, '2021-07-13', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cca_team_employee_transaction`
--
ALTER TABLE `cca_team_employee_transaction`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cca_team_employee_transaction`
--
ALTER TABLE `cca_team_employee_transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
