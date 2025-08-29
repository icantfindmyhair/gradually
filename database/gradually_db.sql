-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 29, 2025 at 05:08 PM
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
-- Database: `gradually_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL,
  `create_date_time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `create_date_time`) VALUES
(4, 'admin', '21232f297a57a5a743894a0e4a801fc3', '2025-08-28 22:24:07'),
(5, 'test', '098f6bcd4621d373cade4e832627b4f6', '2025-08-28 16:27:17'),
(6, 'newadmin', '80396443f055ea0c4fd9719ecefcc25a', '2025-08-28 16:36:57');

-- --------------------------------------------------------

--
-- Table structure for table `diary_entry`
--

CREATE TABLE `diary_entry` (
  `diary_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `mood_id` int(11) DEFAULT NULL,
  `weather_id` int(11) DEFAULT NULL,
  `diary_date` date NOT NULL,
  `diary_time` time NOT NULL,
  `diary_content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `diary_entry`
--

INSERT INTO `diary_entry` (`diary_id`, `user_id`, `mood_id`, `weather_id`, `diary_date`, `diary_time`, `diary_content`) VALUES
(1, 6, 7, 10, '2025-08-03', '00:00:00', 'Today is a good day. I feel joyful!'),
(2, 4, 10, 1, '2025-08-05', '00:00:00', 'Today is a cool and refreshing day. I plan to travel through Singapore!'),
(3, 5, 2, NULL, '2025-08-11', '00:00:00', 'Ughhh, what a terrible day! I fell down the stairs and I am still in so much pain!'),
(4, 2, NULL, 7, '2025-08-17', '00:00:00', 'U La La La La La'),
(6, 6, 6, 12, '2025-08-28', '00:00:00', 'Tomorrow is the day I will be traveling through Singapore, I am so exciting! Wish me the best of luck. '),
(7, 1, 12, 8, '2025-08-28', '00:00:00', 'I am feeling unwell today. It is raining outside, and I think the weather might make my condition worse.'),
(13, 6, NULL, 8, '2025-08-29', '00:00:00', 'asdasdasd'),
(14, 6, NULL, 8, '2025-08-29', '00:00:00', 'asdasdasd'),
(15, 6, NULL, NULL, '2025-08-14', '00:00:00', 'asdasdasd'),
(16, 6, NULL, NULL, '2025-08-14', '00:00:00', 'asdasdasd');

-- --------------------------------------------------------

--
-- Table structure for table `exercises`
--

CREATE TABLE `exercises` (
  `exercise_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `exercise_type` varchar(100) NOT NULL,
  `duration_minutes` int(11) NOT NULL,
  `calories_burnt` decimal(6,2) NOT NULL,
  `exercise_date` date NOT NULL,
  `notes` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `exercises`
--

INSERT INTO `exercises` (`exercise_id`, `user_id`, `exercise_type`, `duration_minutes`, `calories_burnt`, `exercise_date`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 'Running', 30, 250.50, '2025-08-25', 'Morning jog at the park', '2025-08-29 03:24:07', '2025-08-29 03:24:07'),
(2, 1, 'Cycling', 45, 400.75, '2025-08-27', 'Evening cycling session', '2025-08-29 03:24:07', '2025-08-29 03:24:07'),
(3, 1, 'Yoga', 60, 180.00, '2025-08-28', 'Relaxing yoga for flexibility', '2025-08-29 03:24:07', '2025-08-29 03:24:07'),
(4, 1, 'Swimming', 50, 420.30, '2025-08-29', 'Lap swimming at community pool', '2025-08-29 03:24:07', '2025-08-29 03:24:07'),
(5, 1, 'Strength Training', 40, 350.00, '2025-08-30', 'Full body workout at the gym', '2025-08-29 03:24:07', '2025-08-29 03:24:07'),
(6, 1, 'Running', 30, 250.50, '2025-08-25', 'Morning jog at the park', '2025-08-29 09:59:05', '2025-08-29 09:59:05'),
(7, 7, 'Cycling', 45, 400.75, '2025-08-27', 'Evening cycling session', '2025-08-29 09:59:05', '2025-08-29 09:59:05'),
(8, 7, 'Yoga', 60, 180.00, '2025-08-28', 'Relaxing yoga for flexibility', '2025-08-29 09:59:05', '2025-08-29 09:59:05'),
(9, 1, 'Swimming', 50, 420.30, '2025-08-29', 'Lap swimming at community pool', '2025-08-29 09:59:05', '2025-08-29 09:59:05'),
(10, 7, 'Strength Training', 40, 350.00, '2025-08-30', 'Full body workout at the gym', '2025-08-29 09:59:05', '2025-08-29 09:59:05'),
(11, 1, 'Running', 25, 210.25, '2025-08-25', 'Quick morning run', '2025-08-29 09:59:05', '2025-08-29 09:59:05'),
(12, 2, 'Cycling', 60, 520.40, '2025-08-26', 'Long-distance cycling', '2025-08-29 09:59:05', '2025-08-29 09:59:05'),
(13, 2, 'Yoga', 45, 150.00, '2025-08-27', 'Evening yoga session', '2025-08-29 09:59:05', '2025-08-29 09:59:05'),
(14, 7, 'Swimming', 30, 250.00, '2025-08-28', 'Short swim at pool', '2025-08-29 09:59:05', '2025-08-29 09:59:05'),
(15, 2, 'Strength Training', 50, 410.00, '2025-08-29', 'Upper body focus', '2025-08-29 09:59:05', '2025-08-29 09:59:05'),
(16, 5, 'Running', 20, 180.00, '2025-08-25', 'Treadmill jog', '2025-08-29 09:59:05', '2025-08-29 09:59:05'),
(17, 4, 'Cycling', 30, 260.00, '2025-08-26', 'Stationary bike workout', '2025-08-29 09:59:05', '2025-08-29 09:59:05'),
(18, 4, 'Yoga', 40, 140.00, '2025-08-27', 'Morning stretch yoga', '2025-08-29 09:59:05', '2025-08-29 09:59:05'),
(19, 4, 'Swimming', 55, 460.00, '2025-08-28', 'Swimming endurance training', '2025-08-29 09:59:05', '2025-08-29 09:59:05'),
(20, 4, 'Strength Training', 35, 300.00, '2025-08-29', 'Leg day workout', '2025-08-29 09:59:05', '2025-08-29 09:59:05'),
(21, 5, 'Running', 28, 230.00, '2025-08-25', 'Track running', '2025-08-29 09:59:05', '2025-08-29 09:59:05'),
(22, 5, 'Cycling', 50, 420.00, '2025-08-26', 'Evening cycling outdoors', '2025-08-29 09:59:05', '2025-08-29 09:59:05'),
(23, 6, 'Yoga', 30, 120.00, '2025-08-27', 'Quick yoga', '2025-08-29 09:59:05', '2025-08-29 09:59:05'),
(24, 5, 'Swimming', 40, 340.00, '2025-08-28', 'Swimming practice', '2025-08-29 09:59:05', '2025-08-29 09:59:05'),
(25, 5, 'Strength Training', 60, 500.00, '2025-08-29', 'Gym strength workout', '2025-08-29 09:59:05', '2025-08-29 09:59:05'),
(26, 6, 'Running', 35, 280.00, '2025-08-25', 'Morning run', '2025-08-29 09:59:05', '2025-08-29 09:59:05'),
(27, 5, 'Cycling', 40, 350.00, '2025-08-26', 'Cycling with friends', '2025-08-29 09:59:05', '2025-08-29 09:59:05'),
(28, 6, 'Yoga', 50, 170.00, '2025-08-27', 'Evening relaxation yoga', '2025-08-29 09:59:05', '2025-08-29 09:59:05'),
(29, 6, 'Swimming', 45, 380.00, '2025-08-28', 'Swim training', '2025-08-29 09:59:05', '2025-08-29 09:59:05'),
(30, 6, 'Strength Training', 55, 460.00, '2025-08-29', 'Strength training full session', '2025-08-29 09:59:05', '2025-08-29 09:59:05'),
(31, 7, 'Running', 32, 260.00, '2025-08-25', 'Jogging at the park', '2025-08-29 09:59:05', '2025-08-29 09:59:05'),
(32, 7, 'Cycling', 48, 390.00, '2025-08-26', 'Cycling near the lake', '2025-08-29 09:59:05', '2025-08-29 09:59:05'),
(33, 6, 'Yoga', 55, 200.00, '2025-08-27', 'Yoga flow', '2025-08-29 09:59:05', '2025-08-29 09:59:05'),
(34, 7, 'Swimming', 42, 360.00, '2025-08-28', 'Pool laps', '2025-08-29 09:59:05', '2025-08-29 09:59:05'),
(35, 6, 'Strength Training', 38, 310.00, '2025-08-29', 'Core and arms workout', '2025-08-29 09:59:05', '2025-08-29 09:59:05');

-- --------------------------------------------------------

--
-- Table structure for table `habit_log`
--

CREATE TABLE `habit_log` (
  `log_id` int(11) NOT NULL,
  `habit_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `habit_log`
--

INSERT INTO `habit_log` (`log_id`, `habit_id`, `date`, `status`) VALUES
(1, 50, '2025-07-01', 1),
(2, 50, '2025-07-02', 1),
(3, 50, '2025-07-03', 0),
(4, 50, '2025-07-04', 1),
(5, 50, '2025-07-05', 0),
(6, 50, '2025-07-06', 1),
(7, 50, '2025-07-07', 0),
(8, 50, '2025-07-08', 1),
(9, 50, '2025-07-09', 1),
(10, 50, '2025-07-10', 1),
(11, 50, '2025-07-11', 1),
(12, 50, '2025-07-12', 0),
(13, 50, '2025-07-13', 1),
(14, 50, '2025-07-14', 0),
(15, 50, '2025-07-15', 1),
(16, 50, '2025-07-16', 0),
(17, 50, '2025-07-17', 0),
(18, 50, '2025-07-18', 1),
(19, 50, '2025-07-19', 1),
(20, 50, '2025-07-20', 1),
(21, 50, '2025-07-21', 0),
(22, 50, '2025-07-22', 0),
(23, 50, '2025-07-23', 0),
(24, 50, '2025-07-24', 1),
(25, 50, '2025-07-25', 1),
(26, 50, '2025-07-26', 0),
(27, 50, '2025-07-27', 0),
(28, 50, '2025-07-28', 1),
(29, 50, '2025-07-29', 1),
(30, 50, '2025-07-30', 1),
(31, 50, '2025-07-31', 1),
(32, 50, '2025-08-01', 1),
(33, 50, '2025-08-02', 1),
(34, 50, '2025-08-03', 0),
(35, 50, '2025-08-04', 0),
(36, 50, '2025-08-05', 0),
(37, 50, '2025-08-06', 0),
(38, 50, '2025-08-07', 0),
(39, 50, '2025-08-08', 0),
(40, 50, '2025-08-09', 1),
(41, 50, '2025-08-10', 0),
(42, 50, '2025-08-11', 0),
(43, 50, '2025-08-12', 0),
(44, 50, '2025-08-13', 0),
(45, 50, '2025-08-14', 0),
(46, 50, '2025-08-15', 0),
(47, 50, '2025-08-16', 1),
(48, 50, '2025-08-17', 0),
(49, 50, '2025-08-18', 1),
(50, 50, '2025-08-19', 1),
(51, 50, '2025-08-20', 0),
(52, 50, '2025-08-21', 0),
(53, 50, '2025-08-22', 1),
(54, 50, '2025-08-23', 0),
(55, 50, '2025-08-24', 0),
(56, 50, '2025-08-25', 0),
(57, 50, '2025-08-26', 1),
(58, 50, '2025-08-27', 0),
(59, 51, '2025-07-01', 1),
(60, 51, '2025-07-02', 1),
(61, 51, '2025-07-03', 1),
(62, 51, '2025-07-04', 1),
(63, 51, '2025-07-05', 1),
(64, 51, '2025-07-06', 1),
(65, 51, '2025-07-07', 1),
(66, 51, '2025-07-08', 0),
(67, 51, '2025-07-09', 0),
(68, 51, '2025-07-10', 1),
(69, 51, '2025-07-11', 1),
(70, 51, '2025-07-12', 0),
(71, 51, '2025-07-13', 1),
(72, 51, '2025-07-14', 1),
(73, 51, '2025-07-15', 1),
(74, 51, '2025-07-16', 0),
(75, 51, '2025-07-17', 0),
(76, 51, '2025-07-18', 1),
(77, 51, '2025-07-19', 1),
(78, 51, '2025-07-20', 0),
(79, 51, '2025-07-21', 0),
(80, 51, '2025-07-22', 1),
(81, 51, '2025-07-23', 1),
(82, 51, '2025-07-24', 0),
(83, 51, '2025-07-25', 0),
(84, 51, '2025-07-26', 0),
(85, 51, '2025-07-27', 0),
(86, 51, '2025-07-28', 1),
(87, 51, '2025-07-29', 0),
(88, 51, '2025-07-30', 1),
(89, 51, '2025-07-31', 0),
(90, 51, '2025-08-01', 1),
(91, 51, '2025-08-02', 0),
(92, 51, '2025-08-03', 1),
(93, 51, '2025-08-04', 0),
(94, 51, '2025-08-05', 0),
(95, 51, '2025-08-06', 1),
(96, 51, '2025-08-07', 1),
(97, 51, '2025-08-08', 0),
(98, 51, '2025-08-09', 1),
(99, 51, '2025-08-10', 0),
(100, 51, '2025-08-11', 1),
(101, 51, '2025-08-12', 1),
(102, 51, '2025-08-13', 1),
(103, 51, '2025-08-14', 0),
(104, 51, '2025-08-15', 1),
(105, 51, '2025-08-16', 1),
(106, 51, '2025-08-17', 0),
(107, 51, '2025-08-18', 0),
(108, 51, '2025-08-19', 1),
(109, 51, '2025-08-20', 1),
(110, 51, '2025-08-21', 0),
(111, 51, '2025-08-22', 1),
(112, 51, '2025-08-23', 1),
(113, 51, '2025-08-24', 1),
(114, 51, '2025-08-25', 0),
(115, 51, '2025-08-26', 0),
(116, 51, '2025-08-27', 1),
(117, 52, '2025-07-01', 0),
(118, 52, '2025-07-02', 0),
(119, 52, '2025-07-03', 1),
(120, 52, '2025-07-04', 1),
(121, 52, '2025-07-05', 0),
(122, 52, '2025-07-06', 1),
(123, 52, '2025-07-07', 1),
(124, 52, '2025-07-08', 0),
(125, 52, '2025-07-09', 0),
(126, 52, '2025-07-10', 0),
(127, 52, '2025-07-11', 0),
(128, 52, '2025-07-12', 1),
(129, 52, '2025-07-13', 1),
(130, 52, '2025-07-14', 0),
(131, 52, '2025-07-15', 1),
(132, 52, '2025-07-16', 0),
(133, 52, '2025-07-17', 1),
(134, 52, '2025-07-18', 0),
(135, 52, '2025-07-19', 1),
(136, 52, '2025-07-20', 1),
(137, 52, '2025-07-21', 0),
(138, 52, '2025-07-22', 1),
(139, 52, '2025-07-23', 0),
(140, 52, '2025-07-24', 0),
(141, 52, '2025-07-25', 0),
(142, 52, '2025-07-26', 0),
(143, 52, '2025-07-27', 1),
(144, 52, '2025-07-28', 1),
(145, 52, '2025-07-29', 0),
(146, 52, '2025-07-30', 0),
(147, 52, '2025-07-31', 0),
(148, 52, '2025-08-01', 0),
(149, 52, '2025-08-02', 0),
(150, 52, '2025-08-03', 1),
(151, 52, '2025-08-04', 1),
(152, 52, '2025-08-05', 0),
(153, 52, '2025-08-06', 0),
(154, 52, '2025-08-07', 0),
(155, 52, '2025-08-08', 0),
(156, 52, '2025-08-09', 1),
(157, 52, '2025-08-10', 0),
(158, 52, '2025-08-11', 1),
(159, 52, '2025-08-12', 1),
(160, 52, '2025-08-13', 0),
(161, 52, '2025-08-14', 0),
(162, 52, '2025-08-15', 0),
(163, 52, '2025-08-16', 1),
(164, 52, '2025-08-17', 1),
(165, 52, '2025-08-18', 1),
(166, 52, '2025-08-19', 0),
(167, 52, '2025-08-20', 0),
(168, 52, '2025-08-21', 0),
(169, 52, '2025-08-22', 1),
(170, 52, '2025-08-23', 0),
(171, 52, '2025-08-24', 1),
(172, 52, '2025-08-25', 0),
(173, 52, '2025-08-26', 1),
(174, 52, '2025-08-27', 0),
(175, 53, '2025-07-01', 0),
(176, 53, '2025-07-02', 0),
(177, 53, '2025-07-03', 0),
(178, 53, '2025-07-04', 1),
(179, 53, '2025-07-05', 0),
(180, 53, '2025-07-06', 0),
(181, 53, '2025-07-07', 1),
(182, 53, '2025-07-08', 1),
(183, 53, '2025-07-09', 1),
(184, 53, '2025-07-10', 1),
(185, 53, '2025-07-11', 1),
(186, 53, '2025-07-12', 0),
(187, 53, '2025-07-13', 0),
(188, 53, '2025-07-14', 0),
(189, 53, '2025-07-15', 0),
(190, 53, '2025-07-16', 0),
(191, 53, '2025-07-17', 1),
(192, 53, '2025-07-18', 0),
(193, 53, '2025-07-19', 1),
(194, 53, '2025-07-20', 1),
(195, 53, '2025-07-21', 0),
(196, 53, '2025-07-22', 1),
(197, 53, '2025-07-23', 1),
(198, 53, '2025-07-24', 1),
(199, 53, '2025-07-25', 1),
(200, 53, '2025-07-26', 0),
(201, 53, '2025-07-27', 1),
(202, 53, '2025-07-28', 1),
(203, 53, '2025-07-29', 0),
(204, 53, '2025-07-30', 1),
(205, 53, '2025-07-31', 1),
(206, 53, '2025-08-01', 1),
(207, 53, '2025-08-02', 1),
(208, 53, '2025-08-03', 1),
(209, 53, '2025-08-04', 0),
(210, 53, '2025-08-05', 1),
(211, 53, '2025-08-06', 0),
(212, 53, '2025-08-07', 1),
(213, 53, '2025-08-08', 1),
(214, 53, '2025-08-09', 0),
(215, 53, '2025-08-10', 0),
(216, 53, '2025-08-11', 1),
(217, 53, '2025-08-12', 1),
(218, 53, '2025-08-13', 1),
(219, 53, '2025-08-14', 0),
(220, 53, '2025-08-15', 1),
(221, 53, '2025-08-16', 1),
(222, 53, '2025-08-17', 1),
(223, 53, '2025-08-18', 1),
(224, 53, '2025-08-19', 1),
(225, 53, '2025-08-20', 0),
(226, 53, '2025-08-21', 0),
(227, 53, '2025-08-22', 1),
(228, 53, '2025-08-23', 1),
(229, 53, '2025-08-24', 0),
(230, 53, '2025-08-25', 0),
(231, 53, '2025-08-26', 0),
(232, 53, '2025-08-27', 0),
(233, 54, '2025-07-01', 0),
(234, 54, '2025-07-02', 1),
(235, 54, '2025-07-03', 1),
(236, 54, '2025-07-04', 1),
(237, 54, '2025-07-05', 1),
(238, 54, '2025-07-06', 0),
(239, 54, '2025-07-07', 0),
(240, 54, '2025-07-08', 1),
(241, 54, '2025-07-09', 1),
(242, 54, '2025-07-10', 1),
(243, 54, '2025-07-11', 1),
(244, 54, '2025-07-12', 0),
(245, 54, '2025-07-13', 0),
(246, 54, '2025-07-14', 0),
(247, 54, '2025-07-15', 0),
(248, 54, '2025-07-16', 1),
(249, 54, '2025-07-17', 0),
(250, 54, '2025-07-18', 1),
(251, 54, '2025-07-19', 1),
(252, 54, '2025-07-20', 0),
(253, 54, '2025-07-21', 0),
(254, 54, '2025-07-22', 1),
(255, 54, '2025-07-23', 0),
(256, 54, '2025-07-24', 1),
(257, 54, '2025-07-25', 1),
(258, 54, '2025-07-26', 0),
(259, 54, '2025-07-27', 1),
(260, 54, '2025-07-28', 1),
(261, 54, '2025-07-29', 1),
(262, 54, '2025-07-30', 1),
(263, 54, '2025-07-31', 1),
(264, 54, '2025-08-01', 1),
(265, 54, '2025-08-02', 1),
(266, 54, '2025-08-03', 1),
(267, 54, '2025-08-04', 0),
(268, 54, '2025-08-05', 1),
(269, 54, '2025-08-06', 0),
(270, 54, '2025-08-07', 0),
(271, 54, '2025-08-08', 0),
(272, 54, '2025-08-09', 1),
(273, 54, '2025-08-10', 1),
(274, 54, '2025-08-11', 0),
(275, 54, '2025-08-12', 1),
(276, 54, '2025-08-13', 1),
(277, 54, '2025-08-14', 0),
(278, 54, '2025-08-15', 0),
(279, 54, '2025-08-16', 1),
(280, 54, '2025-08-17', 1),
(281, 54, '2025-08-18', 1),
(282, 54, '2025-08-19', 1),
(283, 54, '2025-08-20', 0),
(284, 54, '2025-08-21', 1),
(285, 54, '2025-08-22', 0),
(286, 54, '2025-08-23', 0),
(287, 54, '2025-08-24', 1),
(288, 54, '2025-08-25', 0),
(289, 54, '2025-08-26', 1),
(290, 54, '2025-08-27', 1),
(293, 50, '2025-08-29', 0),
(295, 51, '2025-08-29', 0),
(303, 52, '2025-08-29', 0),
(318, 55, '2025-07-01', 1),
(319, 55, '2025-07-02', 1),
(320, 55, '2025-07-03', 1),
(321, 55, '2025-07-04', 1),
(322, 55, '2025-07-05', 1),
(323, 55, '2025-07-06', 1),
(324, 55, '2025-07-07', 1),
(325, 55, '2025-07-08', 1),
(326, 55, '2025-07-09', 1),
(327, 55, '2025-07-10', 1),
(328, 55, '2025-07-11', 1),
(329, 55, '2025-07-12', 1),
(330, 55, '2025-07-13', 1),
(331, 55, '2025-07-14', 1),
(332, 55, '2025-07-15', 1),
(333, 55, '2025-07-16', 1),
(334, 55, '2025-07-17', 1),
(335, 55, '2025-07-18', 1),
(336, 55, '2025-07-19', 1),
(337, 55, '2025-07-20', 1),
(338, 55, '2025-07-21', 1),
(339, 55, '2025-07-22', 1),
(340, 55, '2025-07-23', 1),
(341, 55, '2025-07-24', 1),
(342, 55, '2025-07-25', 1),
(343, 55, '2025-07-26', 1),
(344, 55, '2025-07-27', 1),
(345, 55, '2025-07-28', 1),
(346, 55, '2025-07-29', 1),
(347, 55, '2025-07-30', 1),
(348, 55, '2025-07-31', 1),
(349, 55, '2025-08-01', 1),
(350, 55, '2025-08-02', 1),
(351, 55, '2025-08-03', 1),
(352, 55, '2025-08-04', 1),
(353, 55, '2025-08-05', 1),
(354, 55, '2025-08-06', 1),
(355, 55, '2025-08-07', 1),
(356, 55, '2025-08-08', 1),
(357, 55, '2025-08-09', 1),
(358, 55, '2025-08-10', 1),
(359, 55, '2025-08-11', 1),
(360, 55, '2025-08-12', 1),
(361, 55, '2025-08-13', 1),
(362, 55, '2025-08-14', 1),
(363, 55, '2025-08-15', 1),
(364, 55, '2025-08-16', 1),
(365, 55, '2025-08-17', 1),
(366, 55, '2025-08-18', 1),
(367, 55, '2025-08-19', 1),
(368, 55, '2025-08-20', 1),
(369, 55, '2025-08-21', 1),
(370, 55, '2025-08-22', 1),
(371, 55, '2025-08-23', 1),
(372, 55, '2025-08-24', 1),
(373, 55, '2025-08-25', 1),
(374, 55, '2025-08-26', 1),
(375, 55, '2025-08-27', 1),
(376, 55, '2025-08-28', 1),
(377, 55, '2025-08-29', 1);

-- --------------------------------------------------------

--
-- Table structure for table `habit_repeat`
--

CREATE TABLE `habit_repeat` (
  `repeat_id` int(11) NOT NULL,
  `habit_id` int(11) NOT NULL,
  `day_of_week` enum('monday','tuesday','wednesday','thursday','friday','saturday','sunday') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `habit_type`
--

CREATE TABLE `habit_type` (
  `habit_id` int(11) NOT NULL,
  `habit_name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `create_date` date NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `habit_type`
--

INSERT INTO `habit_type` (`habit_id`, `habit_name`, `description`, `create_date`, `user_id`) VALUES
(50, 'Drink Water', 'Drink at least 8 glasses of water', '2025-08-27', 6),
(51, 'Exercise', 'Do at least 30 minutes of exercise', '2025-08-27', 6),
(52, 'Read Book', 'Read at least 20 pages', '2025-08-27', 6),
(53, 'Meditate', 'Meditate for 10 minutes', '2025-08-27', 6),
(54, 'Sleep Early', 'Go to bed before 11 PM', '2025-08-27', 6),
(55, 'Call mom', 'tell her you love her', '2025-08-28', 6),
(101, 'Morning Jog', 'Jogging every morning for better stamina', '2025-08-01', 1),
(102, 'Read Books', 'Read at least 20 pages daily', '2025-08-02', 1),
(103, 'Drink More Water', 'Finish at least 2 liters of water a day', '2025-08-03', 1),
(104, 'Meditation', '15 minutes of mindfulness meditation', '2025-08-04', 2),
(105, 'No Junk Food', 'Avoid fast food and sugary drinks', '2025-08-05', 2),
(106, 'Gym Workout', 'Strength training sessions 3x a week', '2025-08-06', 4),
(107, 'Sleep Early', 'Go to bed before 11pm', '2025-08-07', 4),
(108, 'Practice Coding', 'Spend 1 hour on coding practice', '2025-08-08', 4),
(109, 'Stretching', '10 minutes of stretching after waking up', '2025-08-09', 4),
(110, 'Write Journal', 'Daily reflection in personal journal', '2025-08-10', 5),
(111, 'Morning Run', 'Run 3km before breakfast', '2025-08-11', 7),
(112, 'Healthy Breakfast', 'Eat fruits or oats instead of fried food', '2025-08-12', 7),
(113, 'Learn Guitar', 'Practice guitar for 20 minutes daily', '2025-08-13', 7),
(114, 'Plan Day', 'Spend 5 minutes planning tomorrow before bed', '2025-08-14', 7),
(115, 'Call Family', 'Check in with family at least once a week', '2025-08-15', 7);

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `media_id` int(11) NOT NULL,
  `diary_id` int(11) NOT NULL,
  `media_type` varchar(50) NOT NULL,
  `media_path` varchar(255) NOT NULL,
  `media_position_marker` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mood`
--

CREATE TABLE `mood` (
  `mood_id` int(11) NOT NULL,
  `mood_emoji` varchar(255) NOT NULL,
  `mood_category` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `mood`
--

INSERT INTO `mood` (`mood_id`, `mood_emoji`, `mood_category`) VALUES
(1, '../images/moodEmoji/Afraid/orangeRedScareRoundEye1.png', 'Afraid'),
(2, '../images/moodEmoji/Angry/redAnger1.png', 'Angry'),
(3, '../images/moodEmoji/Happy/lightBrownJoyBlush1.png', 'Happy'),
(4, '../images/moodEmoji/Happy/lightBrownJoyWinkBlush1.png', 'Happy'),
(5, '../images/moodEmoji/Happy/lightBrownKittenSmileBlush1.png', 'Happy'),
(6, '../images/moodEmoji/Happy/orangeRedHappyJoy1.png', 'Happy'),
(7, '../images/moodEmoji/Happy/orangeRedSmile1.png', 'Happy'),
(8, '../images/moodEmoji/Happy/yellowJoyBlush1.png', 'Happy'),
(9, '../images/moodEmoji/Happy/yellowRelaxDashEyeRoundMouth1.png', 'Happy'),
(10, '../images/moodEmoji/Happy/yellowSmileBlush1.png', 'Happy'),
(11, '../images/moodEmoji/Sad/greyLongTearBigMouth1.png', 'Sad'),
(12, '../images/moodEmoji/Sad/greyLongTearSmallMouth1.png', 'Sad'),
(13, '../images/moodEmoji/Sad/lightRedSadness1.png', 'Sad'),
(14, '../images/moodEmoji/Sad/lightRedStandardSad1.png', 'Sad'),
(15, '../images/moodEmoji/Unwell/greyWearMask1.png', 'Unwell'),
(16, '../images/moodEmoji/Unwell/lightBrownSleepy1.png', 'Unwell'),
(17, '../images/moodEmoji/Unwell/lightRedCold1.png', 'Unwell'),
(18, '../images/moodEmoji/Unwell/lightRedUnaffortableCrossEye1.png', 'Unwell'),
(19, '../images/moodEmoji/Unwell/lightYellowNotHappy1.png', 'Unwell'),
(20, '../images/moodEmoji/Unwell/yellowishWhiteSyncope1.png', 'Unwell');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `token`, `created_at`) VALUES
(9, '456@mail.com', 'e36ac83718495d14e739e770df61c4abd31f9250a6f262d5fc07981df8d0bd57c645de31cae0515a008e69fd900f519846d6', '2025-08-28 16:29:18');

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `trans_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `type` varchar(30) NOT NULL,
  `category` varchar(255) NOT NULL,
  `account_type` varchar(255) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `description` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`trans_id`, `amount`, `type`, `category`, `account_type`, `date`, `description`, `user_id`) VALUES
(1, 100.00, 'expense', 'food', 'E-wallet', '2025-08-26', '', 6),
(11, 123.00, 'expense', 'Food', 'Cash', '2025-08-20', 'Mcdonald', 1),
(12, 1000.00, 'income', 'Online Sales', 'E-wallet', '2025-08-20', 'Shopee', 1),
(13, 12.00, 'expense', 'Food', 'Cash', '2025-08-20', 'zapfan', 2),
(14, 11.90, 'expense', 'Food', 'Cash', '2025-08-19', 'UTAR The Olive', 2),
(15, 1700.00, 'income', 'commission', 'Cash', '2025-08-20', 'Income', 6),
(16, 500.00, 'expense', 'Rental', 'Cash', '2025-08-20', '', 6),
(17, 65.70, 'expense', 'Fees', 'Cash', '2025-08-20', 'Water and Electricity', 4),
(18, 99.10, 'expense', 'Pet', 'Cash', '2025-08-20', 'Vet', 4),
(19, 19.90, 'expense', 'Entertainment', 'Cash', '2025-08-20', 'Star Rail', 5),
(20, 13.90, 'expense', 'Drinks', 'E-wallet', '2025-08-20', 'Chagee', 5),
(32, 123.00, 'expense', '123', 'Cash', '2025-08-24', 'asd', 6),
(38, 11.00, 'expense', 'Food', 'E-wallet', '2025-08-25', 'Lamabao', 6),
(39, 48.30, 'expense', 'Lazada-Face Mask', 'Cash', '2025-08-25', 'Abib', 1),
(40, 5.00, 'expense', 'Grab', 'Cash', '2025-08-24', 'Grab car', 1),
(43, 2.00, 'expense', 'Food', 'Cash', '2025-06-25', '', 2),
(44, 1700.00, 'income', 'Survival money', 'Cash', '2025-08-25', '', 2),
(45, 1700.00, 'income', 'Survival money', 'Cash', '2025-07-25', '', 6),
(48, 10.90, 'expense', 'Food', 'Cash', '2025-08-26', 'Econsave Medan Selera', 6),
(49, 9.90, 'expense', 'Food', 'E-wallet', '2025-08-26', 'Ama TauFuFa', 4),
(50, 31.80, 'expense', 'Barang Keperluan', 'Card', '2025-08-26', 'Watson', 4),
(51, 17.90, 'expense', 'Food', 'Cash', '2025-08-09', 'Oden', 5),
(52, 500.00, 'expense', 'Rental', 'BankAccount', '2025-08-09', 'Rental for Aug\'25', 5),
(53, 500.00, 'expense', 'Rental', 'BankAccount', '2025-07-01', 'Rental for Jul\'25', 6),
(55, 9.60, 'expense', 'Food', 'E-wallet', '2025-08-28', 'KFC', 6),
(57, 5.50, 'expense', 'Food', 'Cash', '2025-08-27', 'Student Pavilion 1', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `create_date_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `create_date_time`) VALUES
(1, 'hangtuah', 'hangtuah@gmail.com', '25d55ad283aa400af464c76d713c07ad', '2024-01-21 11:38:39'),
(2, 'mahsuri', 'mahsuri@yahoo.com', '25d55ad283aa400af464c76d713c07ad', '2024-01-21 11:41:34'),
(4, 'hangjebat', 'hangjebat@yahoo.com', '25d55ad283aa400af464c76d713c07ad', '2025-02-20 09:47:25'),
(5, 'char', 'char@gmail.com', '202cb962ac59075b964b07152d234b70', '2025-07-22 08:18:45'),
(6, '123', '123@123.com', '202cb962ac59075b964b07152d234b70', '2025-08-13 18:07:24'),
(7, '456', '456@mail.com', 'b51e8dbebd4ba8a8f342190a4b9f08d7', '2025-08-26 12:27:37');

-- --------------------------------------------------------

--
-- Table structure for table `weather`
--

CREATE TABLE `weather` (
  `weather_id` int(11) NOT NULL,
  `weather_name` varchar(100) NOT NULL,
  `weather_icon` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `weather`
--

INSERT INTO `weather` (`weather_id`, `weather_name`, `weather_icon`) VALUES
(1, 'Cloudy', '../images/weather/Cloudy.png'),
(2, 'Hail', '../images/weather/Hail.png'),
(3, 'Intermediate Sunlight', '../images/weather/IntermediateSunlight.png'),
(4, 'Lightning', '../images/weather/Lightning.png'),
(5, 'Overcase', '../images/weather/Overcase.png'),
(6, 'Peaceful Night', '../images/weather/PeacefulNight.png'),
(7, 'Rainbow', '../images/weather/Rainbow.png'),
(8, 'Rainy', '../images/weather/Rainy.png'),
(9, 'Snowy', '../images/weather/Snowy.png'),
(10, 'Sunny', '../images/weather/Sunny.png'),
(11, 'Tornado', '../images/weather/Tornado.png'),
(12, 'Windy', '../images/weather/Windy.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `diary_entry`
--
ALTER TABLE `diary_entry`
  ADD PRIMARY KEY (`diary_id`),
  ADD KEY ` FK with user 3` (`user_id`),
  ADD KEY `FK with mood` (`mood_id`),
  ADD KEY `FK with weather` (`weather_id`);

--
-- Indexes for table `exercises`
--
ALTER TABLE `exercises`
  ADD PRIMARY KEY (`exercise_id`),
  ADD KEY `FK with user 4` (`user_id`);

--
-- Indexes for table `habit_log`
--
ALTER TABLE `habit_log`
  ADD PRIMARY KEY (`log_id`),
  ADD UNIQUE KEY `unique_habit_date` (`habit_id`,`date`);

--
-- Indexes for table `habit_repeat`
--
ALTER TABLE `habit_repeat`
  ADD PRIMARY KEY (`repeat_id`),
  ADD KEY `FK with habit_type 2` (`habit_id`);

--
-- Indexes for table `habit_type`
--
ALTER TABLE `habit_type`
  ADD PRIMARY KEY (`habit_id`),
  ADD KEY `FK with user` (`user_id`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`media_id`),
  ADD KEY `FK with diary` (`diary_id`);

--
-- Indexes for table `mood`
--
ALTER TABLE `mood`
  ADD PRIMARY KEY (`mood_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`trans_id`),
  ADD KEY `FK with user 2` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `weather`
--
ALTER TABLE `weather`
  ADD PRIMARY KEY (`weather_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `diary_entry`
--
ALTER TABLE `diary_entry`
  MODIFY `diary_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `exercises`
--
ALTER TABLE `exercises`
  MODIFY `exercise_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `habit_log`
--
ALTER TABLE `habit_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=378;

--
-- AUTO_INCREMENT for table `habit_repeat`
--
ALTER TABLE `habit_repeat`
  MODIFY `repeat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `habit_type`
--
ALTER TABLE `habit_type`
  MODIFY `habit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `media_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mood`
--
ALTER TABLE `mood`
  MODIFY `mood_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `trans_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `weather`
--
ALTER TABLE `weather`
  MODIFY `weather_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `diary_entry`
--
ALTER TABLE `diary_entry`
  ADD CONSTRAINT ` FK with user 3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK with mood` FOREIGN KEY (`mood_id`) REFERENCES `mood` (`mood_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK with weather` FOREIGN KEY (`weather_id`) REFERENCES `weather` (`weather_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `exercises`
--
ALTER TABLE `exercises`
  ADD CONSTRAINT `FK with user 4` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `habit_log`
--
ALTER TABLE `habit_log`
  ADD CONSTRAINT `FK with habit_type` FOREIGN KEY (`habit_id`) REFERENCES `habit_type` (`habit_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `habit_repeat`
--
ALTER TABLE `habit_repeat`
  ADD CONSTRAINT `FK with habit_type 2` FOREIGN KEY (`habit_id`) REFERENCES `habit_type` (`habit_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `habit_type`
--
ALTER TABLE `habit_type`
  ADD CONSTRAINT `FK with user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `media`
--
ALTER TABLE `media`
  ADD CONSTRAINT `FK with diary` FOREIGN KEY (`diary_id`) REFERENCES `diary_entry` (`diary_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `FK with user 2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
