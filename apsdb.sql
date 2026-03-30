-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 30, 2026 at 03:29 PM
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
-- Database: `apsdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `log_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `encoded_by` varchar(20) NOT NULL,
  `time_in` datetime NOT NULL,
  `time_out` datetime DEFAULT NULL,
  `total_hours` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`log_id`, `employee_id`, `encoded_by`, `time_in`, `time_out`, `total_hours`) VALUES
(9, 2, 'steve', '2026-03-29 09:00:00', '2026-03-29 17:00:00', 8.00),
(12, 1, 'john', '2026-03-29 09:00:00', '2026-03-29 23:46:28', 14.77);

-- --------------------------------------------------------

--
-- Table structure for table `benefits`
--

CREATE TABLE `benefits` (
  `benefit_id` int(11) NOT NULL,
  `payroll_id` int(3) NOT NULL,
  `benefit_name` varchar(20) NOT NULL,
  `amount` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_data`
--

CREATE TABLE `employee_data` (
  `employee_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `baserate` decimal(10,2) NOT NULL,
  `mandatory_deduction` varchar(20) NOT NULL,
  `marital_status` enum('single','married','widowed') NOT NULL,
  `date_hired` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_data`
--

INSERT INTO `employee_data` (`employee_id`, `user_id`, `name`, `baserate`, `mandatory_deduction`, `marital_status`, `date_hired`) VALUES
(1, 1, 'john', 2.00, 'SSS', 'single', '2026-03-29'),
(2, 2, 'steve', 3.00, 'PhilHealth', 'married', '2017-03-01'),
(3, 14, 'Mark', 60.00, 'SSS,Philhealth,Pag-I', 'married', '2026-03-30');

-- --------------------------------------------------------

--
-- Table structure for table `employee_deductions`
--

CREATE TABLE `employee_deductions` (
  `deduction_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `SSS` decimal(10,2) NOT NULL,
  `PhilHealth` decimal(10,2) NOT NULL,
  `Pag-IBIG` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_deductions`
--

INSERT INTO `employee_deductions` (`deduction_id`, `employee_id`, `SSS`, `PhilHealth`, `Pag-IBIG`) VALUES
(3, 3, 500.00, 240.00, 100.00);

-- --------------------------------------------------------

--
-- Table structure for table `finance`
--

CREATE TABLE `finance` (
  `finance_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `baserate` decimal(10,2) NOT NULL,
  `total_hours` decimal(5,2) NOT NULL,
  `SSS` decimal(10,2) NOT NULL,
  `PhilHealth` decimal(10,2) NOT NULL,
  `Pag-IBIG` decimal(10,2) NOT NULL,
  `benefit_name` varchar(20) NOT NULL,
  `amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `government_contribution`
--

CREATE TABLE `government_contribution` (
  `contribution_id` int(11) NOT NULL,
  `contribution_name` varchar(20) NOT NULL,
  `employee_share` int(8) NOT NULL,
  `employer_share` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payroll`
--

CREATE TABLE `payroll` (
  `payroll_id` int(11) NOT NULL,
  `employee_id` int(3) NOT NULL,
  `total_hours` int(3) NOT NULL,
  `overtime_hours` int(3) NOT NULL,
  `gross_salary` int(8) NOT NULL,
  `total_deductions` int(8) NOT NULL,
  `total_benefits` int(8) NOT NULL,
  `net_salary` int(8) NOT NULL,
  `approved_by` varchar(20) NOT NULL,
  `approved_at` datetime NOT NULL,
  `generated_at` datetime NOT NULL,
  `sent_at` datetime NOT NULL,
  `week_start` date NOT NULL,
  `week_end` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `ticket_id` int(11) NOT NULL,
  `payroll_id` int(3) NOT NULL,
  `requested_by` varchar(20) NOT NULL,
  `request_type` enum('baserate','employeestatus','addemployee','removeemployee') NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  `resolved_by` varchar(20) NOT NULL,
  `created_at` datetime NOT NULL,
  `resolved_at` datetime NOT NULL,
  `sent_to` varchar(20) NOT NULL DEFAULT 'hr'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `password` varchar(15) NOT NULL,
  `datecreated` date NOT NULL,
  `status` tinyint(1) NOT NULL,
  `role` varchar(20) NOT NULL,
  `username` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `password`, `datecreated`, `status`, `role`, `username`) VALUES
(1, 'qwerty1234!', '2026-03-12', 1, 'employee', 'john'),
(2, 'qwerty1234!', '2026-03-12', 1, 'employee', 'steve'),
(3, 'qwerty1234!', '2026-03-12', 1, 'employee', 'alice'),
(4, 'qwerty1234!', '2026-03-12', 1, 'employee', 'geane'),
(6, 'pass123', '2026-03-29', 1, 'hr', 'jane'),
(7, 'pass123', '2026-03-29', 1, 'finance', 'mike'),
(8, 'admin123', '2026-03-29', 1, 'admin', 'admin'),
(9, 'pass123', '2026-03-30', 1, 'Employee', 'lisa'),
(10, 'pass123', '2026-03-30', 1, 'Admin', 'Brenda'),
(11, 'pass123', '2026-03-30', 1, 'Employee', 'Marc'),
(12, 'pass123', '2026-03-30', 1, 'Employee', 'Marc'),
(13, 'pass123', '2026-03-30', 1, 'Employee', 'bert'),
(14, 'pass123', '2026-03-30', 1, 'Employee', 'Mark');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `benefits`
--
ALTER TABLE `benefits`
  ADD PRIMARY KEY (`benefit_id`);

--
-- Indexes for table `employee_data`
--
ALTER TABLE `employee_data`
  ADD PRIMARY KEY (`employee_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `employee_deductions`
--
ALTER TABLE `employee_deductions`
  ADD PRIMARY KEY (`deduction_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `finance`
--
ALTER TABLE `finance`
  ADD PRIMARY KEY (`finance_id`);

--
-- Indexes for table `government_contribution`
--
ALTER TABLE `government_contribution`
  ADD PRIMARY KEY (`contribution_id`);

--
-- Indexes for table `payroll`
--
ALTER TABLE `payroll`
  ADD PRIMARY KEY (`payroll_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`ticket_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `benefits`
--
ALTER TABLE `benefits`
  MODIFY `benefit_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_data`
--
ALTER TABLE `employee_data`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `employee_deductions`
--
ALTER TABLE `employee_deductions`
  MODIFY `deduction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `finance`
--
ALTER TABLE `finance`
  MODIFY `finance_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `government_contribution`
--
ALTER TABLE `government_contribution`
  MODIFY `contribution_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payroll`
--
ALTER TABLE `payroll`
  MODIFY `payroll_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `ticket_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `employee_deductions`
--
ALTER TABLE `employee_deductions`
  ADD CONSTRAINT `employee_deductions_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employee_data` (`employee_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
