-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 22, 2022 at 10:26 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `anspaugh_care`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_notification`
--

CREATE TABLE `admin_notification` (
  `noti_ID` int(11) NOT NULL,
  `doctor` varchar(15) NOT NULL,
  `content` text DEFAULT NULL,
  `created_date_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `advertisement`
--

CREATE TABLE `advertisement` (
  `ad_ID` int(11) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `desription` varchar(255) DEFAULT NULL,
  `remark` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `appointment_ID` int(11) NOT NULL,
  `patient_ID` int(11) DEFAULT NULL,
  `payment_status` varchar(10) DEFAULT NULL,
  `queue_no` int(11) NOT NULL,
  `create_date_time` varchar(20) DEFAULT current_timestamp(),
  `opened_channeling_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`appointment_ID`, `patient_ID`, `payment_status`, `queue_no`, `create_date_time`, `opened_channeling_ID`) VALUES
(289, 29, 'Pending', 2, '2022-12-22 18:13:58', 36),
(290, 29, 'Pending', 2, '2022-12-22 18:16:05', 36),
(294, 29, 'Pending', 1, '2022-12-23 00:54:58', 37),
(296, 31, 'Pending', 1, '2022-12-23 01:24:10', 38),
(297, 30, 'Pending', 2, '2022-12-23 02:45:24', 38),
(298, 30, 'Pending', 2, '2022-12-23 02:49:13', 37);

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_ID` int(11) NOT NULL,
  `created_date` date DEFAULT NULL,
  `patient` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `channeling`
--

CREATE TABLE `channeling` (
  `channeling_ID` int(11) NOT NULL,
  `doctor` varchar(15) NOT NULL,
  `fee` float NOT NULL,
  `total_patients` int(11) DEFAULT NULL,
  `extra_patients` int(11) DEFAULT NULL,
  `max_free_appointments` int(11) DEFAULT NULL,
  `day` varchar(15) DEFAULT NULL,
  `count` int(11) DEFAULT NULL,
  `type` varchar(30) DEFAULT NULL,
  `percentage` int(11) DEFAULT NULL,
  `speciality` varchar(50) NOT NULL,
  `room` varchar(100) NOT NULL,
  `start_date` varchar(20) NOT NULL,
  `time` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `channeling`
--

INSERT INTO `channeling` (`channeling_ID`, `doctor`, `fee`, `total_patients`, `extra_patients`, `max_free_appointments`, `day`, `count`, `type`, `percentage`, `speciality`, `room`, `start_date`, `time`) VALUES
(487, '2000400003045', 1500, 30, 12, 3, 'Monday', 1, 'Week', 20, 'Cardiology', 'curtain-02', '2022-12-24', '11:06'),
(488, '200014203045', 1400, 10, 5, 3, 'Monday', 30, 'Month', 80, 'Gastrology', 'curtain-03', '2022-12-23', '20:07'),
(489, '209914203045', 1500, 50, 20, 5, 'Monday', 3, 'Week', 30, 'Cardiology', 'curtain-02', '2022-12-25', '13:13');

-- --------------------------------------------------------

--
-- Table structure for table `consultation_report`
--

CREATE TABLE `consultation_report` (
  `report_ID` int(11) DEFAULT NULL,
  `examination` text DEFAULT NULL,
  `consultation` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

CREATE TABLE `content` (
  `content_ID` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `reference_ranges` varchar(100) DEFAULT NULL,
  `position` varchar(50) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `course_ID` varchar(10) DEFAULT NULL,
  `cname` varchar(10) DEFAULT NULL,
  `credits` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `delivery`
--

CREATE TABLE `delivery` (
  `delivery_ID` int(11) NOT NULL,
  `contact` varchar(15) DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL,
  `time_of_creation` varchar(50) DEFAULT NULL,
  `delivery_rider` int(11) DEFAULT NULL,
  `PIN` varchar(7) DEFAULT NULL,
  `postal_code` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_allocation`
--

CREATE TABLE `delivery_allocation` (
  `order_ID` int(11) NOT NULL,
  `patient_ID` int(11) NOT NULL,
  `delivery_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `nic` varchar(15) NOT NULL,
  `speciality` varchar(20) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`nic`, `speciality`, `description`) VALUES
('200014203045', 'Cardiologist', 'Just completed residency'),
('2000400003045', 'Gastrologist', 'Just completed residency'),
('209914203045', 'Gastrologist', 'Just completed residency');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_patient`
--

CREATE TABLE `doctor_patient` (
  `doctor` varchar(15) NOT NULL,
  `patient_ID` int(11) NOT NULL,
  `expire_date` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `emp_ID` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `nic` varchar(15) DEFAULT NULL,
  `gender` varchar(7) DEFAULT NULL,
  `contact` varchar(15) NOT NULL,
  `email` varchar(50) NOT NULL,
  `address` varchar(50) DEFAULT NULL,
  `role` varchar(20) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `img` varchar(200) DEFAULT NULL,
  `age` int(11) NOT NULL,
  `create_date_time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`emp_ID`, `name`, `nic`, `gender`, `contact`, `email`, `address`, `role`, `password`, `img`, `age`, `create_date_time`) VALUES
(106, 'Sean King', '200034501200', 'male', '', 'admin@gmail.com', '', 'admin', '$2y$10$GGrnjHXaCqzTIBdlH47N/.tT6wO16J6goWLR3YZHzs5nfTDRZBrUu', 'admin.png', 32, '2022-12-13 09:10:32'),
(138, 'Morgon Stern', '2000400003045', 'male', '0775678093', 'morgon@ans.com', '123/A,Lively,Berthawood', 'doctor', '$2y$10$SEVEKahBCAXS9QzLacJ9D..rCH.fUZmnl3dRS0b8v5E43b3CMS6CC', '63a44bbfc4c03pexels-thirdman-5327656.jpg', 30, '2022-12-22 17:51:19'),
(139, 'John Carter', '200014203045', 'male', '0775678993', 'carter@ans.com', '123/A,Lively,Berthawood', 'doctor', '$2y$10$zPk2lnk4o0HK8H.V4/Y2RuvTB16hvYDgyqPJHi95VbkZt2iNgJ0cy', '63a44dc7a9a97pexels-thirdman-5327656.jpg', 33, '2022-12-22 17:59:59'),
(140, 'nurse one', '2000400003945', 'female', '0775678193', 'nurseone@ans.com', '123/A,Lively,Berthawood', 'nurse', '$2y$10$Jb3.yTaxvK4Usta3tEPeEujvoJMTmmjpoCLkMx7fhtn/8LNxwpTJm', '63a44ee66b18bpexels-los-muertos-crew-8460370.jpg', 20, '2022-12-22 18:04:46'),
(141, 'nurse two', '230004566790', 'female', '075874387434', 'nursetwo@ans.com', '123/A,Lively,Berthawood', 'nurse', '$2y$10$ifnOeX3WgwQmGkKaUDft6ucGykM0F0VPHonNYIgC9fZV.40ArbmdG', '63a44f1c3e4b9pexels-tran-nhu-tuan-14438787.jpg', 34, '2022-12-22 18:05:40'),
(142, 'receptionist one', '2300045678899', 'female', '075874387434', 'receptionist@ans.com', '123/A,Lively,Berthawood', 'receptionist', '$2y$10$a1Dr0OPWZ5MPQhbSz/8hQOGfMjpyxtMH0ALCWN7DCoLFsyQd4o9Hq', '63a4506603e12pexels-los-muertos-crew-8460373.jpg', 30, '2022-12-22 18:11:10'),
(143, 'doctor one', '209914203045', 'female', '0775678093', 'doctor@ans.com', '123/A,Lively,Berthawood', 'doctor', '$2y$10$ry0y.KBm.yONWf9EfcWUc.qwWu/FlRHFyrsxwMJ28MDS2MC.6ZS2y', '63a4b3281260fpexels-antoni-shkraba-5215024.jpg', 40, '2022-12-23 01:12:32'),
(144, 'pharmacist one', '230014203045', 'male', '0775678993', 'pharmacist@ans.com', '123/A,Lively,Berthawood', 'pharmacist', '$2y$10$0SixL7pRfb579.8lvAUaxOYU/MHQ5Gvh/0OZG7seGcZM84RxLk8HS', '63a4b6b9d0bd7', 34, '2022-12-23 01:27:45');

-- --------------------------------------------------------

--
-- Table structure for table `lab_report`
--

CREATE TABLE `lab_report` (
  `report_ID` int(11) NOT NULL,
  `fee` int(11) DEFAULT NULL,
  `upload_date_time` datetime DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `lab_report_allocation`
--

CREATE TABLE `lab_report_allocation` (
  `report_ID` int(11) NOT NULL,
  `patient_ID` int(11) NOT NULL,
  `doctor` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `lab_report_content`
--

CREATE TABLE `lab_report_content` (
  `report_ID` int(11) NOT NULL,
  `content_ID` int(11) NOT NULL,
  `location` varchar(100) DEFAULT NULL,
  `int_value` varchar(20) DEFAULT NULL,
  `text_value` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `lab_tests`
--

CREATE TABLE `lab_tests` (
  `name` varchar(100) NOT NULL,
  `fee` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `lab_tests`
--

INSERT INTO `lab_tests` (`name`, `fee`) VALUES
('CBC', 2500);

-- --------------------------------------------------------

--
-- Table structure for table `medical_history`
--

CREATE TABLE `medical_history` (
  `report_ID` int(11) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `history` text DEFAULT NULL,
  `medication` text DEFAULT NULL,
  `allergies` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `medical_report`
--

CREATE TABLE `medical_report` (
  `report_ID` int(11) NOT NULL,
  `uploaded_date_time` varchar(30) DEFAULT NULL,
  `type` varchar(30) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `medical_report_allocation`
--

CREATE TABLE `medical_report_allocation` (
  `report_ID` int(11) NOT NULL,
  `doctor` varchar(15) NOT NULL,
  `patient_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `medicine`
--

CREATE TABLE `medicine` (
  `med_ID` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `brand` varchar(40) DEFAULT NULL,
  `strength` varchar(20) DEFAULT NULL,
  `availability` varchar(20) DEFAULT NULL,
  `category` varchar(20) DEFAULT NULL,
  `unit` varchar(20) DEFAULT NULL,
  `unit_price` int(11) DEFAULT NULL,
  `amount` int(11) NOT NULL,
  `img` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `medicine_in_order`
--

CREATE TABLE `medicine_in_order` (
  `order_ID` int(11) NOT NULL,
  `med_ID` int(11) NOT NULL,
  `amount` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `nurse_channeling_allocataion`
--

CREATE TABLE `nurse_channeling_allocataion` (
  `emp_ID` int(11) NOT NULL,
  `channeling_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `nurse_channeling_allocataion`
--

INSERT INTO `nurse_channeling_allocataion` (`emp_ID`, `channeling_ID`) VALUES
(140, 487),
(140, 488),
(140, 489),
(141, 487);

-- --------------------------------------------------------

--
-- Table structure for table `opened_channeling`
--

CREATE TABLE `opened_channeling` (
  `opened_channeling_ID` int(11) NOT NULL,
  `remaining_free_appointments` int(11) DEFAULT NULL,
  `remaining_appointments` int(11) DEFAULT NULL,
  `channeling_date` date DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `channeling_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `opened_channeling`
--

INSERT INTO `opened_channeling` (`opened_channeling_ID`, `remaining_free_appointments`, `remaining_appointments`, `channeling_date`, `status`, `channeling_ID`) VALUES
(36, 3, -1, '2022-12-26', 'started', 487),
(37, 3, 2, '2022-12-26', 'started', 488),
(38, 5, 2, '2022-12-26', 'started', 489);

-- --------------------------------------------------------

--
-- Table structure for table `past_channeling`
--

CREATE TABLE `past_channeling` (
  `channeling_ID` int(11) NOT NULL,
  `no_of_patient` int(11) DEFAULT NULL,
  `total_time` varchar(20) DEFAULT NULL,
  `total_income` int(11) DEFAULT NULL,
  `free_appointments` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `patient_ID` int(11) NOT NULL,
  `nic` varchar(15) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `guardian_name` varchar(50) DEFAULT NULL,
  `relation` varchar(30) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `contact` varchar(30) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `verification` varchar(10) DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`patient_ID`, `nic`, `type`, `name`, `guardian_name`, `relation`, `age`, `email`, `contact`, `gender`, `verification`, `address`, `password`) VALUES
(27, '200018602508', '', 'Jimmy Newton123', '', NULL, 35, 'jimmy@ans.com', '0775678093', 'select', '', '123/A,Lively,Berthawood', ''),
(28, '2000400003045', '', 'john seena', '', NULL, 45, 'seena@ans.com', '0775678993', 'select', '', '123/A,Lively,Berthawood', '$2y$10$0rC9N1OFvXiA1ZRH0nEx.usN4o2dWGyoNQpdhw5Od2dM1XI7z18da'),
(29, '200014101220', '', 'patient three12', '', NULL, 12, 'receptionist@ans.com', '0775678093', 'select', '', '123/A,Lively,Berthawood', ''),
(30, '200014101221', '', 'patient four', '', NULL, 30, 'receptidfdt@ans.com', '0775678993', 'male', '', '123/A,Lively,Berthawood', '$2y$10$nFG2/C3VpYncmhLPOB4sO.RAEuv7Y9cene2JMrl5eD88pcHqti6Iy'),
(31, '2000467003045', '', 'patient 1', '', NULL, 40, 'patient@gmail.com', '0775678993', 'select', '', '123/A,Lively,Berthawood', '');

-- --------------------------------------------------------

--
-- Table structure for table `patient_notification`
--

CREATE TABLE `patient_notification` (
  `noti_ID` int(11) NOT NULL,
  `created_date_time` varchar(30) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `text` varchar(255) DEFAULT NULL,
  `patient_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `patient_ID` int(11) DEFAULT NULL,
  `payment_ID` int(11) NOT NULL,
  `amount` float DEFAULT NULL,
  `generated_timestamp` datetime DEFAULT current_timestamp(),
  `type` varchar(10) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `payement_status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `prescription`
--

CREATE TABLE `prescription` (
  `doctor` varchar(15) DEFAULT NULL,
  `patient` int(11) DEFAULT NULL,
  `order_ID` int(11) DEFAULT NULL,
  `prescription_ID` int(11) NOT NULL,
  `uploaded_date` date DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `last_processed_timestamp` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `prescription_medicine`
--

CREATE TABLE `prescription_medicine` (
  `med_ID` int(11) NOT NULL,
  `prescription_ID` int(11) NOT NULL,
  `amount` int(11) DEFAULT NULL,
  `route` varchar(10) DEFAULT NULL,
  `dispense` varchar(10) DEFAULT NULL,
  `frequency` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `pre_channeilng_test_aloc`
--

CREATE TABLE `pre_channeilng_test_aloc` (
  `channeling_ID` int(11) NOT NULL,
  `test_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `pre_channeling_tests`
--

CREATE TABLE `pre_channeling_tests` (
  `test_ID` int(11) NOT NULL,
  `metric` varchar(50) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `pre_channeling_tests_values`
--

CREATE TABLE `pre_channeling_tests_values` (
  `value` float DEFAULT NULL,
  `test_ID` int(11) NOT NULL,
  `appointment_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `referrel`
--

CREATE TABLE `referrel` (
  `doctor` varchar(15) DEFAULT NULL,
  `ref_ID` int(11) NOT NULL,
  `speciality` varchar(20) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `text` text DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `refer_doctor` varchar(15) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `appointment_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `referrel`
--

INSERT INTO `referrel` (`doctor`, `ref_ID`, `speciality`, `location`, `text`, `type`, `refer_doctor`, `name`, `appointment_ID`) VALUES
('2000400003045', 54, 'Gastrologist', NULL, '', 'softcopy', '', '', 289),
('2000400003045', 55, 'Gastrologist', NULL, '', 'soft-copy', 'Morgon Stern', '63a4518f8e199', 290),
('200014203045', 57, 'Cardiologist', NULL, '', 'soft-copy', 'John Carter', '63a4af0d3ce29', 294),
('209914203045', 59, 'Gastrologist', NULL, '', 'soft-copy', 'doctor one', '63a4b5e513b49', 296),
('209914203045', 60, 'Gastrologist', NULL, '', 'softcopy', '', '', 297),
('200014203045', 61, 'Cardiologist', NULL, '', 'softcopy', '', '', 298);

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`name`) VALUES
('curtain-02'),
('curtain-03');

-- --------------------------------------------------------

--
-- Table structure for table `_order`
--

CREATE TABLE `_order` (
  `order_ID` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `patient_ID` int(11) DEFAULT NULL,
  `cart_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_notification`
--
ALTER TABLE `admin_notification`
  ADD PRIMARY KEY (`noti_ID`,`doctor`),
  ADD KEY `doctor` (`doctor`);

--
-- Indexes for table `advertisement`
--
ALTER TABLE `advertisement`
  ADD PRIMARY KEY (`ad_ID`);

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`appointment_ID`),
  ADD KEY `patient_ID` (`patient_ID`),
  ADD KEY `opened_channeling_ID` (`opened_channeling_ID`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_ID`),
  ADD KEY `patient_cart` (`patient`);

--
-- Indexes for table `channeling`
--
ALTER TABLE `channeling`
  ADD PRIMARY KEY (`channeling_ID`),
  ADD KEY `doctor` (`doctor`),
  ADD KEY `room` (`room`);

--
-- Indexes for table `consultation_report`
--
ALTER TABLE `consultation_report`
  ADD KEY `report_ID` (`report_ID`);

--
-- Indexes for table `content`
--
ALTER TABLE `content`
  ADD PRIMARY KEY (`content_ID`);

--
-- Indexes for table `delivery`
--
ALTER TABLE `delivery`
  ADD PRIMARY KEY (`delivery_ID`),
  ADD KEY `delivery_rider` (`delivery_rider`);

--
-- Indexes for table `delivery_allocation`
--
ALTER TABLE `delivery_allocation`
  ADD PRIMARY KEY (`order_ID`,`patient_ID`,`delivery_ID`),
  ADD KEY `patient_ID` (`patient_ID`),
  ADD KEY `delivery_ID` (`delivery_ID`);

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`nic`);

--
-- Indexes for table `doctor_patient`
--
ALTER TABLE `doctor_patient`
  ADD PRIMARY KEY (`doctor`,`patient_ID`),
  ADD KEY `patient_ID` (`patient_ID`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`emp_ID`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `nic` (`nic`);

--
-- Indexes for table `lab_report`
--
ALTER TABLE `lab_report`
  ADD PRIMARY KEY (`report_ID`);

--
-- Indexes for table `lab_report_allocation`
--
ALTER TABLE `lab_report_allocation`
  ADD PRIMARY KEY (`report_ID`,`patient_ID`,`doctor`),
  ADD KEY `patient_ID` (`patient_ID`),
  ADD KEY `doctor_ID` (`doctor`);

--
-- Indexes for table `lab_report_content`
--
ALTER TABLE `lab_report_content`
  ADD PRIMARY KEY (`report_ID`,`content_ID`),
  ADD KEY `content_ID` (`content_ID`);

--
-- Indexes for table `lab_tests`
--
ALTER TABLE `lab_tests`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `medical_history`
--
ALTER TABLE `medical_history`
  ADD KEY `report_ID` (`report_ID`);

--
-- Indexes for table `medical_report`
--
ALTER TABLE `medical_report`
  ADD PRIMARY KEY (`report_ID`);

--
-- Indexes for table `medical_report_allocation`
--
ALTER TABLE `medical_report_allocation`
  ADD PRIMARY KEY (`report_ID`,`doctor`,`patient_ID`),
  ADD KEY `doctor_ID` (`doctor`),
  ADD KEY `patient_ID` (`patient_ID`);

--
-- Indexes for table `medicine`
--
ALTER TABLE `medicine`
  ADD PRIMARY KEY (`med_ID`);

--
-- Indexes for table `medicine_in_order`
--
ALTER TABLE `medicine_in_order`
  ADD PRIMARY KEY (`order_ID`,`med_ID`),
  ADD KEY `med_ID` (`med_ID`);

--
-- Indexes for table `nurse_channeling_allocataion`
--
ALTER TABLE `nurse_channeling_allocataion`
  ADD PRIMARY KEY (`emp_ID`,`channeling_ID`),
  ADD KEY `channeling_ID` (`channeling_ID`);

--
-- Indexes for table `opened_channeling`
--
ALTER TABLE `opened_channeling`
  ADD PRIMARY KEY (`opened_channeling_ID`),
  ADD KEY `channeling_ID` (`channeling_ID`);

--
-- Indexes for table `past_channeling`
--
ALTER TABLE `past_channeling`
  ADD PRIMARY KEY (`channeling_ID`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`patient_ID`);

--
-- Indexes for table `patient_notification`
--
ALTER TABLE `patient_notification`
  ADD PRIMARY KEY (`noti_ID`),
  ADD KEY `patient_ID` (`patient_ID`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_ID`),
  ADD KEY `patient_ID` (`patient_ID`);

--
-- Indexes for table `prescription`
--
ALTER TABLE `prescription`
  ADD PRIMARY KEY (`prescription_ID`),
  ADD KEY `doctor` (`doctor`),
  ADD KEY `_order` (`order_ID`),
  ADD KEY `patient` (`patient`);

--
-- Indexes for table `prescription_medicine`
--
ALTER TABLE `prescription_medicine`
  ADD PRIMARY KEY (`med_ID`,`prescription_ID`),
  ADD KEY `prescription_ID` (`prescription_ID`);

--
-- Indexes for table `pre_channeilng_test_aloc`
--
ALTER TABLE `pre_channeilng_test_aloc`
  ADD PRIMARY KEY (`test_ID`,`channeling_ID`),
  ADD KEY `channeling_ID` (`channeling_ID`);

--
-- Indexes for table `pre_channeling_tests`
--
ALTER TABLE `pre_channeling_tests`
  ADD PRIMARY KEY (`test_ID`);

--
-- Indexes for table `pre_channeling_tests_values`
--
ALTER TABLE `pre_channeling_tests_values`
  ADD PRIMARY KEY (`test_ID`,`appointment_ID`),
  ADD KEY `appointment_ID` (`appointment_ID`),
  ADD KEY `test_ID` (`test_ID`),
  ADD KEY `test_ID_2` (`test_ID`);

--
-- Indexes for table `referrel`
--
ALTER TABLE `referrel`
  ADD PRIMARY KEY (`ref_ID`),
  ADD KEY `doctor` (`doctor`),
  ADD KEY `appointment_ID` (`appointment_ID`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `_order`
--
ALTER TABLE `_order`
  ADD PRIMARY KEY (`order_ID`),
  ADD KEY `patient_ID` (`patient_ID`),
  ADD KEY `cart_ID` (`cart_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `advertisement`
--
ALTER TABLE `advertisement`
  MODIFY `ad_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `appointment_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=299;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `channeling`
--
ALTER TABLE `channeling`
  MODIFY `channeling_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=490;

--
-- AUTO_INCREMENT for table `content`
--
ALTER TABLE `content`
  MODIFY `content_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `delivery`
--
ALTER TABLE `delivery`
  MODIFY `delivery_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=500;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `emp_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=145;

--
-- AUTO_INCREMENT for table `lab_report`
--
ALTER TABLE `lab_report`
  MODIFY `report_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=300;

--
-- AUTO_INCREMENT for table `medical_report`
--
ALTER TABLE `medical_report`
  MODIFY `report_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medicine`
--
ALTER TABLE `medicine`
  MODIFY `med_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `opened_channeling`
--
ALTER TABLE `opened_channeling`
  MODIFY `opened_channeling_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `patient`
--
ALTER TABLE `patient`
  MODIFY `patient_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `patient_notification`
--
ALTER TABLE `patient_notification`
  MODIFY `noti_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prescription`
--
ALTER TABLE `prescription`
  MODIFY `prescription_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pre_channeling_tests`
--
ALTER TABLE `pre_channeling_tests`
  MODIFY `test_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `referrel`
--
ALTER TABLE `referrel`
  MODIFY `ref_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_notification`
--
ALTER TABLE `admin_notification`
  ADD CONSTRAINT `admin_notification_ibfk_1` FOREIGN KEY (`doctor`) REFERENCES `doctor` (`nic`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `appointment_ibfk_2` FOREIGN KEY (`patient_ID`) REFERENCES `patient` (`patient_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `appointment_ibfk_3` FOREIGN KEY (`opened_channeling_ID`) REFERENCES `opened_channeling` (`opened_channeling_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `patient_cart` FOREIGN KEY (`patient`) REFERENCES `patient` (`patient_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `channeling`
--
ALTER TABLE `channeling`
  ADD CONSTRAINT `channeling_ibfk_1` FOREIGN KEY (`doctor`) REFERENCES `doctor` (`nic`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `channeling_ibfk_2` FOREIGN KEY (`room`) REFERENCES `room` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `consultation_report`
--
ALTER TABLE `consultation_report`
  ADD CONSTRAINT `consultation_report_ibfk_1` FOREIGN KEY (`report_ID`) REFERENCES `medical_report` (`report_ID`);

--
-- Constraints for table `delivery`
--
ALTER TABLE `delivery`
  ADD CONSTRAINT `delivery_ibfk_1` FOREIGN KEY (`delivery_rider`) REFERENCES `employee` (`emp_ID`) ON UPDATE CASCADE;

--
-- Constraints for table `delivery_allocation`
--
ALTER TABLE `delivery_allocation`
  ADD CONSTRAINT `delivery_allocation_ibfk_1` FOREIGN KEY (`order_ID`) REFERENCES `_order` (`order_ID`),
  ADD CONSTRAINT `delivery_allocation_ibfk_2` FOREIGN KEY (`patient_ID`) REFERENCES `patient` (`patient_ID`),
  ADD CONSTRAINT `delivery_allocation_ibfk_3` FOREIGN KEY (`delivery_ID`) REFERENCES `delivery` (`delivery_ID`);

--
-- Constraints for table `doctor`
--
ALTER TABLE `doctor`
  ADD CONSTRAINT `doctor_ibfk_1` FOREIGN KEY (`nic`) REFERENCES `employee` (`nic`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `doctor_patient`
--
ALTER TABLE `doctor_patient`
  ADD CONSTRAINT `doctor_patient_ibfk_1` FOREIGN KEY (`patient_ID`) REFERENCES `patient` (`patient_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `doctor_patient_ibfk_2` FOREIGN KEY (`doctor`) REFERENCES `doctor` (`nic`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `lab_report_allocation`
--
ALTER TABLE `lab_report_allocation`
  ADD CONSTRAINT `lab_report_allocation_ibfk_1` FOREIGN KEY (`report_ID`) REFERENCES `lab_report` (`report_ID`),
  ADD CONSTRAINT `lab_report_allocation_ibfk_2` FOREIGN KEY (`patient_ID`) REFERENCES `patient` (`patient_ID`),
  ADD CONSTRAINT `lab_report_allocation_ibfk_3` FOREIGN KEY (`doctor`) REFERENCES `doctor` (`nic`) ON UPDATE CASCADE;

--
-- Constraints for table `lab_report_content`
--
ALTER TABLE `lab_report_content`
  ADD CONSTRAINT `lab_report_content_ibfk_1` FOREIGN KEY (`report_ID`) REFERENCES `lab_report` (`report_ID`),
  ADD CONSTRAINT `lab_report_content_ibfk_2` FOREIGN KEY (`content_ID`) REFERENCES `content` (`content_ID`);

--
-- Constraints for table `medical_history`
--
ALTER TABLE `medical_history`
  ADD CONSTRAINT `Medical_history_ibfk_1` FOREIGN KEY (`report_ID`) REFERENCES `medical_report` (`report_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `medical_report_allocation`
--
ALTER TABLE `medical_report_allocation`
  ADD CONSTRAINT `medical_report_allocation_ibfk_1` FOREIGN KEY (`report_ID`) REFERENCES `medical_report` (`report_ID`),
  ADD CONSTRAINT `medical_report_allocation_ibfk_3` FOREIGN KEY (`patient_ID`) REFERENCES `patient` (`patient_ID`),
  ADD CONSTRAINT `medical_report_allocation_ibfk_4` FOREIGN KEY (`doctor`) REFERENCES `doctor` (`nic`) ON UPDATE CASCADE;

--
-- Constraints for table `medicine_in_order`
--
ALTER TABLE `medicine_in_order`
  ADD CONSTRAINT `medicine_in_order_ibfk_1` FOREIGN KEY (`order_ID`) REFERENCES `_order` (`order_ID`),
  ADD CONSTRAINT `medicine_in_order_ibfk_2` FOREIGN KEY (`med_ID`) REFERENCES `medicine` (`med_ID`);

--
-- Constraints for table `nurse_channeling_allocataion`
--
ALTER TABLE `nurse_channeling_allocataion`
  ADD CONSTRAINT `nurse_channeling_allocataion_ibfk_1` FOREIGN KEY (`emp_ID`) REFERENCES `employee` (`emp_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nurse_channeling_allocataion_ibfk_2` FOREIGN KEY (`channeling_ID`) REFERENCES `channeling` (`channeling_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `opened_channeling`
--
ALTER TABLE `opened_channeling`
  ADD CONSTRAINT `opened_channeling_ibfk_1` FOREIGN KEY (`channeling_ID`) REFERENCES `channeling` (`channeling_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `past_channeling`
--
ALTER TABLE `past_channeling`
  ADD CONSTRAINT `past_channeling_ibfk_1` FOREIGN KEY (`channeling_ID`) REFERENCES `channeling` (`channeling_ID`) ON UPDATE CASCADE;

--
-- Constraints for table `patient_notification`
--
ALTER TABLE `patient_notification`
  ADD CONSTRAINT `patient_notification_ibfk_1` FOREIGN KEY (`patient_ID`) REFERENCES `patient` (`patient_ID`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`patient_ID`) REFERENCES `patient` (`patient_ID`);

--
-- Constraints for table `prescription`
--
ALTER TABLE `prescription`
  ADD CONSTRAINT `prescription_ibfk_2` FOREIGN KEY (`order_ID`) REFERENCES `_order` (`order_ID`),
  ADD CONSTRAINT `prescription_ibfk_3` FOREIGN KEY (`patient`) REFERENCES `patient` (`patient_ID`),
  ADD CONSTRAINT `prescription_ibfk_4` FOREIGN KEY (`doctor`) REFERENCES `doctor` (`nic`) ON UPDATE CASCADE;

--
-- Constraints for table `prescription_medicine`
--
ALTER TABLE `prescription_medicine`
  ADD CONSTRAINT `prescription_medicine_ibfk_2` FOREIGN KEY (`prescription_ID`) REFERENCES `prescription` (`prescription_ID`),
  ADD CONSTRAINT `prescription_medicine_ibfk_3` FOREIGN KEY (`med_ID`) REFERENCES `medicine` (`med_ID`);

--
-- Constraints for table `pre_channeilng_test_aloc`
--
ALTER TABLE `pre_channeilng_test_aloc`
  ADD CONSTRAINT `pre_channeilng_test_aloc_ibfk_1` FOREIGN KEY (`test_ID`) REFERENCES `pre_channeling_tests` (`test_ID`),
  ADD CONSTRAINT `pre_channeilng_test_aloc_ibfk_2` FOREIGN KEY (`channeling_ID`) REFERENCES `channeling` (`channeling_ID`);

--
-- Constraints for table `pre_channeling_tests_values`
--
ALTER TABLE `pre_channeling_tests_values`
  ADD CONSTRAINT `pre_channeling_tests_values_ibfk_1` FOREIGN KEY (`test_ID`) REFERENCES `pre_channeling_tests` (`test_ID`),
  ADD CONSTRAINT `pre_channeling_tests_values_ibfk_2` FOREIGN KEY (`appointment_ID`) REFERENCES `appointment` (`appointment_ID`),
  ADD CONSTRAINT `pre_channeling_tests_values_ibfk_3` FOREIGN KEY (`test_ID`) REFERENCES `pre_channeling_tests` (`test_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `pre_channeling_tests_values_ibfk_4` FOREIGN KEY (`test_ID`) REFERENCES `pre_channeling_tests` (`test_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `pre_channeling_tests_values_ibfk_5` FOREIGN KEY (`test_ID`) REFERENCES `pre_channeling_tests` (`test_ID`) ON UPDATE CASCADE;

--
-- Constraints for table `referrel`
--
ALTER TABLE `referrel`
  ADD CONSTRAINT `referrel_ibfk_2` FOREIGN KEY (`doctor`) REFERENCES `doctor` (`nic`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `referrel_ibfk_3` FOREIGN KEY (`appointment_ID`) REFERENCES `appointment` (`appointment_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `_order`
--
ALTER TABLE `_order`
  ADD CONSTRAINT `_order_ibfk_1` FOREIGN KEY (`patient_ID`) REFERENCES `patient` (`patient_ID`),
  ADD CONSTRAINT `_order_ibfk_2` FOREIGN KEY (`cart_ID`) REFERENCES `cart` (`cart_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
