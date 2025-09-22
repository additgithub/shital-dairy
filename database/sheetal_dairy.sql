-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 22, 2025 at 05:02 PM
-- Server version: 5.7.23-23
-- PHP Version: 8.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sheetal_dairy`
--

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `ip_address` varchar(16) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `user_agent` varchar(150) COLLATE utf8_bin NOT NULL,
  `last_activity` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `user_data` text COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(40) COLLATE utf8_bin NOT NULL,
  `login` varchar(50) COLLATE utf8_bin NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `login_attempts`
--

INSERT INTO `login_attempts` (`id`, `ip_address`, `login`, `time`) VALUES
(27, '45.126.169.29', 'admin@sheetal_daily.com', '2025-09-22 11:16:00'),
(28, '45.126.169.29', 'admin@sheetal_daily.com', '2025-09-22 11:16:12');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_customer`
--

CREATE TABLE `tbl_customer` (
  `customer_id` bigint(20) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_mobile` varchar(255) NOT NULL,
  `customer_whatsapp_number` varchar(55) NOT NULL,
  `address` text NOT NULL,
  `created_on` datetime NOT NULL,
  `created_by` bigint(20) NOT NULL,
  `modified_on` datetime NOT NULL,
  `modified_by` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_customer`
--

INSERT INTO `tbl_customer` (`customer_id`, `customer_name`, `customer_email`, `customer_mobile`, `customer_whatsapp_number`, `address`, `created_on`, `created_by`, `modified_on`, `modified_by`) VALUES
(1, 'ARYAN CATRERS', '', '9773111456', '9773111456', '', '2025-09-20 10:06:11', 1, '2025-09-20 10:06:44', 1),
(2, 'ARIHANT CATRERS', '', '9820634954', '9820634954', '', '2025-09-20 10:07:42', 1, '0000-00-00 00:00:00', 0),
(3, 'AKSHAY CATRERS', '', '9819022667', '9819022667', '', '2025-09-21 09:26:28', 1, '2025-09-21 09:45:39', 1),
(4, 'AMEY CATRERS', '', '7977336106', '7977336106', '', '2025-09-21 09:26:59', 1, '2025-09-21 09:45:47', 1),
(5, 'ASHISH (NAGRIK STORES)', '', '9322847214', '9322847214', '', '2025-09-21 09:27:28', 1, '0000-00-00 00:00:00', 0),
(6, 'BHALARA CATRERS', '', '9819753161', '9819753161', '', '2025-09-21 09:28:12', 1, '2025-09-21 09:45:57', 1),
(7, 'BHAVESH HAVMOR ICECREAM', '', '9320592244', '9320592244', '', '2025-09-21 09:28:35', 1, '0000-00-00 00:00:00', 0),
(8, 'DILIP CATRERS', '', '8850627004', '8850627004', '', '2025-09-21 09:28:50', 1, '2025-09-21 09:46:06', 1),
(9, 'DINESH MAHARAJ CHEMBUR', '', '9867137563', '9867137563', '', '2025-09-21 09:29:13', 1, '0000-00-00 00:00:00', 0),
(10, 'DIPTI CATRERS', '', '9820038072', '9820038072', '', '2025-09-21 09:29:29', 1, '2025-09-21 09:46:19', 1),
(11, 'JAGRUTI CATRERS', '', '9870982162', '9870982162', '', '2025-09-21 09:29:49', 1, '2025-09-21 09:46:28', 1),
(12, 'LAXMI CATRERS', '', '9322402878', '9322402878', '', '2025-09-21 09:30:07', 1, '2025-09-21 09:46:37', 1),
(13, 'LAXMI VILAS HOTEL', '', '9820257872', '9820257872', '', '2025-09-21 09:30:25', 1, '0000-00-00 00:00:00', 0),
(14, 'MAHESHBHAI SAKSHI FOOD', '', '920655632', '920655632', '', '2025-09-21 09:30:46', 1, '0000-00-00 00:00:00', 0),
(15, 'MAITHLI CATRERS', '', '9870328768', '9870328768', '', '2025-09-21 09:31:07', 1, '2025-09-21 09:46:45', 1),
(16, 'MAYUR CATRERS', '', '7977180671', '7977180671', '', '2025-09-21 09:31:26', 1, '2025-09-21 09:46:54', 1),
(17, 'NARESH MAHARAJ SION', '', '9372440603', '9372440603', '', '2025-09-21 09:31:55', 1, '0000-00-00 00:00:00', 0),
(18, 'NAVKAR CATRERS', '', '8461921816', '8461921816', '', '2025-09-21 09:32:13', 1, '2025-09-21 09:47:07', 1),
(19, 'OM CATRERS PAVANBHAI', '', '8424900531', '8424900531', '', '2025-09-21 09:32:33', 1, '2025-09-21 09:47:18', 1),
(20, 'PANCHAMRUT CATRERS', '', '9869032728', '9869032728', '', '2025-09-21 09:32:54', 1, '2025-09-21 09:47:29', 1),
(21, 'JIVAN JYOT PARULBEN', '', '9833021505', '9833021505', '', '2025-09-21 09:33:29', 1, '0000-00-00 00:00:00', 0),
(22, 'PRAKASH CATRERS BIDNU', '', '9820151318', '9820151318', '', '2025-09-21 09:33:46', 1, '2025-09-21 09:47:43', 1),
(23, 'RAGHU MAHARAJ RJVADI', '', '8779009541', '8779009541', '', '2025-09-21 09:34:07', 1, '0000-00-00 00:00:00', 0),
(24, 'RAJESH CATRERS SANTACRUZ', '', '9820960692', '9820960692', '', '2025-09-21 09:34:41', 1, '2025-09-21 09:48:11', 1),
(25, 'RAJSJEKHAR CATRERS', '', '9619021264', '9619021264', '', '2025-09-21 09:35:01', 1, '2025-09-21 09:48:28', 1),
(26, 'RAJU MAHARAJ MANAV SEVA', '', '9137882135', '9137882135', '', '2025-09-21 09:35:21', 1, '0000-00-00 00:00:00', 0),
(27, 'RANAVAT CATRERS', '', '9821182827', '9821182827', '', '2025-09-21 09:35:36', 1, '2025-09-21 09:48:36', 1),
(28, 'REKHA CATRERS GULAB MAHARAJ', '', '9619951223', '9619951223', '', '2025-09-21 09:35:53', 1, '2025-09-21 09:48:57', 1),
(29, 'RISHI CHANDAN', '', '9820729789', '9820729789', '', '2025-09-21 09:36:11', 1, '0000-00-00 00:00:00', 0),
(30, 'RAMESHBHAI', '', '9653158103', '9653158103', '', '2025-09-21 09:36:39', 1, '0000-00-00 00:00:00', 0),
(31, 'RANE CATRERS', '', '9821028214', '9821028214', '', '2025-09-21 09:36:54', 1, '2025-09-21 09:49:27', 1),
(32, 'SAI CATRERS VIVEK', '', '9820454771', '9820454771', '', '2025-09-21 09:37:10', 1, '2025-09-21 09:49:38', 1),
(33, 'SAMIR BHAI', '', '9820281274', '9820281274', '', '2025-09-21 09:37:27', 1, '0000-00-00 00:00:00', 0),
(34, 'SATISH CATRERS', '', '965329637', '965329637', '', '2025-09-21 09:40:02', 1, '2025-09-21 09:49:47', 1),
(35, 'SHEETAL LIMEY GAURAV PANVEL', '', '9920746252', '9920746252', '', '2025-09-21 09:40:28', 1, '0000-00-00 00:00:00', 0),
(36, 'SHANTI MAHARAJ', '', '9892887273', '9892887273', '', '2025-09-21 09:40:50', 1, '0000-00-00 00:00:00', 0),
(37, 'SANJAY MAMAL', '', '7021743516', '7021743516', '', '2025-09-21 09:41:15', 1, '0000-00-00 00:00:00', 0),
(38, 'SHUKLAJI LUHARWADI', '', '9869304780', '9869304780', '', '2025-09-21 09:41:50', 1, '0000-00-00 00:00:00', 0),
(39, 'SILVER CATRERS', '', '9870294831', '9870294831', '', '2025-09-21 09:42:07', 1, '2025-09-21 09:49:57', 1),
(40, 'BAHGWAN MAHRAJ', '', '9892027370', '9892027370', '', '2025-09-21 09:42:28', 1, '0000-00-00 00:00:00', 0),
(41, 'SUVARNA CATERING DALVI BHAI', '', '9819252598', '9819252598', '', '2025-09-21 09:42:55', 1, '0000-00-00 00:00:00', 0),
(42, 'S.K. SAMTOSH BHAI', '', '9819756782', '9819756782', '', '2025-09-21 09:43:15', 1, '0000-00-00 00:00:00', 0),
(43, 'TEJAL CATRERS', '', '9773106976', '9773106976', '', '2025-09-21 09:43:34', 1, '2025-09-21 09:50:06', 1),
(44, 'TULJA BHAVANI CATRERS', '', '9819302214', '9819302214', '', '2025-09-21 09:43:55', 1, '2025-09-21 09:50:17', 1),
(45, 'ULKA CATRERS', '', '9820832808', '9820832808', '', '2025-09-21 09:44:10', 1, '2025-09-21 09:50:25', 1),
(46, 'VIJAY SHAH', '', '9224413897', '9224413897', '', '2025-09-21 09:44:27', 1, '0000-00-00 00:00:00', 0),
(47, 'WELINGKAR COLLEGE CATERING', '', '9869353548', '9869353548', '', '2025-09-21 09:44:49', 1, '0000-00-00 00:00:00', 0),
(48, 'YUVRAJ CATRERS', '', '9082786669', '9082786669', '', '2025-09-21 09:45:12', 1, '2025-09-21 09:50:35', 1),
(49, 'RAJWADI CATRERS', '', '9867208824', '9867208824', '', '2025-09-21 09:50:59', 1, '0000-00-00 00:00:00', 0),
(50, 'RAMNATH CATRERS', '', '9820101208', '9820101208', '', '2025-09-21 09:51:40', 1, '0000-00-00 00:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_damage_item`
--

CREATE TABLE `tbl_damage_item` (
  `damange_item_id` bigint(20) NOT NULL,
  `item_id` bigint(20) NOT NULL,
  `qty` decimal(10,2) NOT NULL,
  `remark` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_expense`
--

CREATE TABLE `tbl_expense` (
  `expense_id` bigint(20) NOT NULL,
  `expense_date` date NOT NULL,
  `total_expense` decimal(10,2) NOT NULL,
  `remark` text NOT NULL,
  `created_on` datetime NOT NULL,
  `modified_on` datetime NOT NULL,
  `created_by` bigint(20) NOT NULL,
  `modified_by` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_expense`
--

INSERT INTO `tbl_expense` (`expense_id`, `expense_date`, `total_expense`, `remark`, `created_on`, `modified_on`, `created_by`, `modified_by`) VALUES
(1, '2025-07-10', 15000.00, 'asd', '2025-07-15 12:51:58', '0000-00-00 00:00:00', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_item`
--

CREATE TABLE `tbl_item` (
  `item_id` bigint(20) NOT NULL,
  `item_code` varchar(50) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `size` varchar(55) NOT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `factor` decimal(5,3) NOT NULL,
  `reorder` int(1) DEFAULT NULL,
  `created_on` datetime NOT NULL,
  `created_by` varchar(55) NOT NULL,
  `modified_on` datetime NOT NULL,
  `modified_by` varchar(55) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_item`
--

INSERT INTO `tbl_item` (`item_id`, `item_code`, `item_name`, `size`, `selling_price`, `factor`, `reorder`, `created_on`, `created_by`, `modified_on`, `modified_by`) VALUES
(1, 'WS', 'WHITE SHRIKHAND (સફેદ શ્રીખંડ)', '250 GM.', 290.00, 0.250, 1, '2025-07-05 15:54:47', '1', '2025-09-20 08:58:47', '1'),
(2, 'KS', 'KEASR SHRIKHAND (કેસર શ્રીખંડ)', '250 GM', 330.00, 0.250, 0, '2025-07-05 15:59:26', '1', '2025-09-20 09:00:32', '1'),
(3, 'MS', 'MANGO SHRIKHAND  (મેંગો શ્રીખંડ)', '500 GM', 330.00, 0.500, 0, '2025-07-05 11:31:07', '1', '2025-09-20 09:01:45', '1'),
(4, 'WDS', 'WHITE DRYFRUIT SHRIKHAND (સફેદ ડ્રાયફ્રુટ શ્રીખંડ )', '500 ગ્રામ', 460.00, 0.500, 0, '2025-07-05 11:31:07', '1', '2025-09-20 09:01:56', '1'),
(5, 'KDS', 'KEASR DRYFRUIT SHRIKHAND (કેસર ડ્રાયફ્રુટ શ્રીખંડ )', '500 GM', 520.00, 0.500, 0, '2025-07-05 11:31:07', '1', '2025-09-20 09:02:05', '1'),
(6, 'RG', 'RASGULLA  (રસગુલ્લા)', '250 GM', 18.00, 0.250, 0, '2025-07-05 11:31:07', '1', '2025-09-20 09:02:14', '1'),
(7, 'GJ', 'GULAB JAMUN (ગુલાબજાંબુ)', '100 GM', 18.00, 0.100, 0, '2025-07-05 11:31:07', '1', '2025-09-20 09:02:21', '1'),
(8, 'RM', 'RASMALAI (રસમલાઈ)', '100 GM', 30.00, 0.100, 0, '2025-07-05 11:31:07', '1', '2025-09-20 09:02:30', '1'),
(9, 'RMV', 'RASMALAI VATI (રસમલાઈ વાટી)', '100 GM', 24.00, 0.100, 0, '2025-07-05 11:31:07', '1', '2025-09-20 09:02:39', '1'),
(10, 'MSD', 'MALAI SANDWICH (મલાઈ સેંડવિચ)', '100 GM', 30.00, 0.100, 1, '2025-07-05 11:31:07', '1', '2025-09-20 09:04:07', '1'),
(11, 'RAJ', 'RAJBHOG (રાજભોગ)', '1 KG', 26.00, 1.000, 0, '2025-07-05 11:31:07', '1', '2025-09-20 08:59:09', '1'),
(12, 'BS', 'BENGALI SWEETS (બંગાળી મીઠાઈ)', '900 GM', 30.00, 0.900, 0, '2025-07-05 11:31:07', '1', '2025-09-20 08:59:17', '1'),
(13, 'DS', 'DRYFRUIT SWEETS (ડ્રાયફ્રુટ મીઠાઈ)', '900 GM', 1050.00, 0.900, 0, '2025-07-05 11:31:07', '1', '2025-09-20 08:59:35', '1'),
(14, 'SP', 'SITAFAL PULP (સીતાફળ પલ્પ)', '500 GM', 330.00, 0.500, 0, '2025-07-05 11:31:07', '1', '2025-09-20 08:59:26', '1'),
(15, 'MM', 'MANGO MILKSHAKE 100% HAFUS(કેરી રસ (હાફુસ)', '100 GM', 330.00, 0.100, 0, '2025-07-05 11:31:07', '1', '2025-09-20 08:59:44', '1'),
(16, 'MB', 'MALAI BASUNDI (મલાઈ બાસુંદી)', '100 GM', 290.00, 0.100, 0, '2025-07-05 11:31:07', '1', '2025-09-20 08:59:54', '1'),
(17, 'CB', 'CREAM BASUNDI (ક્રીમ બાસુંદી )', '100 GM', 320.00, 0.100, 0, '2025-07-05 11:31:07', '1', '2025-09-20 09:00:01', '1'),
(18, 'KB', 'KEASR BASUNDI (કેસર બાસુંદી )', '1 KG', 330.00, 1.000, 0, '2025-07-05 11:31:07', '1', '2025-09-20 09:00:09', '1'),
(19, 'MSB', 'MALAI SITAFAL BASUNDI (મલાઈ સીતાફળ  બાસુંદી  )', '1 GM', 300.00, 0.001, 0, '2025-07-05 11:31:07', '1', '2025-09-20 09:00:23', '1'),
(20, 'CSB', 'CREAM SITAFAL BASUNDI (ક્રીમ સીતાફળ બાસુંદી)', '5 KG', 330.00, 5.000, 0, '2025-07-12 14:01:46', '1', '2025-09-20 09:00:41', '1'),
(21, 'CSAB', 'CREAM SITAFAL ANGUR BASUNDI (ક્રીમ સીતાફળ અંગૂર બાસુંદી)', '1 KG', 420.00, 1.000, 0, '2025-07-12 21:23:07', '1', '2025-09-20 09:00:50', '1'),
(24, 'MAB', 'MALAI ANGOOR BASUNDI(મલાઈ અંગૂર બાસુંદી)', '', 380.00, 0.000, NULL, '2025-09-20 08:48:43', '1', '2025-09-20 08:49:07', '1'),
(25, 'KAB', 'KESAR ANGOOR BASUNDI(કેસર અંગૂર બાસુંદી)', '', 420.00, 0.000, NULL, '2025-09-20 08:50:53', '1', '0000-00-00 00:00:00', ''),
(26, 'CAB', 'CREAM ANGOOR BASUNDI (ક્રીમ અંગૂર બાસુંદી', '', 420.00, 0.000, NULL, '2025-09-20 08:51:54', '1', '0000-00-00 00:00:00', ''),
(27, 'CSTB', 'CREAM STRAWBEERY BASUNDI (ક્રીમ સ્ટ્રોબેરી બાસુંદી)', '', 360.00, 0.000, NULL, '2025-09-20 08:52:56', '1', '2025-09-20 09:03:18', '1'),
(28, 'MDB', 'MALAI DRYFRUIT BASUNDI (મલાઈ ડ્રાયફ્રુટ બાસુંદી)', '', 460.00, 0.000, NULL, '2025-09-20 08:53:59', '1', '0000-00-00 00:00:00', ''),
(29, 'KDB', 'KEAST DRYFRUIT BASUNDI(કેસર ડ્રાયફ્રુટ બાસુંદી)', '', 520.00, 0.000, NULL, '2025-09-20 08:54:50', '1', '0000-00-00 00:00:00', ''),
(30, 'MAN', 'MINI ANGOOR( મીની અંગૂર)', '', 420.00, 0.000, NULL, '2025-09-20 08:55:48', '1', '0000-00-00 00:00:00', ''),
(31, 'CDR', 'CREAM DANA RABDI (ક્રીમ દાણા રબડી)', '', 500.00, 0.000, NULL, '2025-09-20 08:56:52', '1', '0000-00-00 00:00:00', ''),
(32, 'PP', 'PLAIN PANEER (પનીર)', '', 260.00, 0.000, NULL, '2025-09-20 08:57:55', '1', '0000-00-00 00:00:00', ''),
(33, 'MP', 'MALAI PANEER (મલાઈ પનીર)', '', 360.00, 0.000, NULL, '2025-09-20 08:58:35', '1', '0000-00-00 00:00:00', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_item_stock`
--

CREATE TABLE `tbl_item_stock` (
  `stock_id` bigint(20) NOT NULL,
  `item_id` bigint(20) NOT NULL,
  `total_purchase_qty_kg` decimal(10,2) NOT NULL,
  `total_sells_qty_kg` decimal(10,2) NOT NULL,
  `total_purchase_qty_pkt` decimal(10,2) NOT NULL,
  `total_sells_qty_pkt` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_item_stock`
--

INSERT INTO `tbl_item_stock` (`stock_id`, `item_id`, `total_purchase_qty_kg`, `total_sells_qty_kg`, `total_purchase_qty_pkt`, `total_sells_qty_pkt`) VALUES
(1, 1, 114.00, 78.00, 456.00, 312.00),
(2, 2, 100.00, 40.25, 400.00, 161.00),
(3, 3, 150.00, 75.50, 300.00, 151.00),
(4, 4, 2.00, 59.00, 4.00, 118.00),
(5, 5, 0.00, 43.50, 0.00, 87.00),
(6, 6, 0.00, 24.50, 0.00, 98.00),
(7, 7, 15.00, 14.40, 150.00, 144.00),
(8, 8, 0.00, 9.20, 0.00, 92.00),
(9, 9, 0.00, 14.40, 0.00, 144.00),
(10, 10, 25.00, 25.20, 250.00, 252.00),
(11, 11, 0.00, 159.00, 0.00, 159.00),
(12, 12, 0.00, 101.70, 0.00, 113.00),
(13, 13, 0.00, 45.90, 0.00, 51.00),
(14, 14, 0.00, 38.50, 0.00, 77.00),
(15, 15, 0.00, 0.50, 0.00, 5.00),
(16, 16, 0.00, 1.20, 0.00, 12.00),
(17, 17, 0.00, 11.20, 0.00, 112.00),
(18, 18, 0.00, 455.00, 0.00, 455.00),
(19, 19, 0.00, 0.04, 0.00, 42.00),
(20, 20, 0.00, 50.00, 0.00, 10.00),
(21, 21, 0.00, 19.00, 0.00, 19.00);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_m_role`
--

CREATE TABLE `tbl_m_role` (
  `RoleID` int(11) NOT NULL,
  `RoleName` varchar(255) NOT NULL,
  `isActive` tinyint(1) NOT NULL,
  `CreatedOn` datetime NOT NULL,
  `CreatedBy` varchar(55) NOT NULL,
  `ModifiedOn` datetime NOT NULL,
  `ModifiedBy` varchar(55) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_m_role`
--

INSERT INTO `tbl_m_role` (`RoleID`, `RoleName`, `isActive`, `CreatedOn`, `CreatedBy`, `ModifiedOn`, `ModifiedBy`) VALUES
(1, 'Admin', 1, '2021-07-01 16:15:32', 'admin', '0000-00-00 00:00:00', ''),
(2, 'User', 1, '2021-07-01 16:15:39', 'admin', '0000-00-00 00:00:00', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order_dtl`
--

CREATE TABLE `tbl_order_dtl` (
  `order_dtl_id` bigint(20) NOT NULL,
  `order_hdr_id` bigint(20) NOT NULL,
  `item_id` bigint(20) NOT NULL,
  `qty` decimal(10,2) NOT NULL,
  `price_per_item` decimal(10,2) NOT NULL DEFAULT '0.00',
  `amount` decimal(10,2) NOT NULL,
  `created_on` datetime NOT NULL,
  `modified_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_order_dtl`
--

INSERT INTO `tbl_order_dtl` (`order_dtl_id`, `order_hdr_id`, `item_id`, `qty`, `price_per_item`, `amount`, `created_on`, `modified_on`) VALUES
(1, 1, 1, 2.00, 290.00, 290.00, '2025-09-20 11:18:37', '2025-09-20 13:17:46'),
(2, 1, 2, 2.00, 330.00, 660.00, '2025-09-20 11:18:37', '2025-09-20 13:17:46');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order_hdr`
--

CREATE TABLE `tbl_order_hdr` (
  `order_hdr_id` bigint(20) NOT NULL,
  `order_no` varchar(255) NOT NULL,
  `order_date` date NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `contact_no` varchar(55) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `delivery_charges` int(11) NOT NULL,
  `dry_ice_box_charges` int(11) NOT NULL,
  `other_charges` int(11) NOT NULL,
  `delivery_time` varchar(55) NOT NULL,
  `is_paid` int(1) NOT NULL,
  `paid_date` date NOT NULL,
  `id_delivered` int(1) NOT NULL,
  `delivery_date` date NOT NULL,
  `remarks` text NOT NULL,
  `created_on` datetime NOT NULL,
  `created_by` varchar(55) NOT NULL,
  `modified_on` datetime NOT NULL,
  `modified_by` varchar(55) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_order_hdr`
--

INSERT INTO `tbl_order_hdr` (`order_hdr_id`, `order_no`, `order_date`, `customer_name`, `contact_no`, `amount`, `delivery_charges`, `dry_ice_box_charges`, `other_charges`, `delivery_time`, `is_paid`, `paid_date`, `id_delivered`, `delivery_date`, `remarks`, `created_on`, `created_by`, `modified_on`, `modified_by`) VALUES
(1, 'ORD202509200001', '2025-09-20', '1', '8866721053', 990.00, 40, 0, 0, 'Morning', 0, '0000-00-00', 0, '0000-00-00', 'this is test remark', '2025-09-20 11:18:37', '1', '2025-09-20 13:17:46', '1');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_purchase_dtl`
--

CREATE TABLE `tbl_purchase_dtl` (
  `purchase_dtl_id` bigint(20) NOT NULL,
  `purchase_hdr_id` bigint(20) NOT NULL,
  `item_id` bigint(20) NOT NULL,
  `qty` decimal(10,2) NOT NULL,
  `pkt_price` decimal(10,2) NOT NULL,
  `qty_kg` decimal(10,2) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `created_on` datetime NOT NULL,
  `modified_on` datetime NOT NULL,
  `purchase_price_per_kg` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_purchase_dtl`
--

INSERT INTO `tbl_purchase_dtl` (`purchase_dtl_id`, `purchase_hdr_id`, `item_id`, `qty`, `pkt_price`, `qty_kg`, `total_amount`, `created_on`, `modified_on`, `purchase_price_per_kg`) VALUES
(1, 1, 1, 16.00, 0.00, 4.00, 200.00, '2025-07-19 19:39:00', '2025-07-19 19:39:00', 50.00);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_purchase_hdr`
--

CREATE TABLE `tbl_purchase_hdr` (
  `purchase_hdr_id` bigint(20) NOT NULL,
  `purchase_date` date NOT NULL,
  `suppiler_id` bigint(20) NOT NULL,
  `total_purchase_amount` decimal(10,2) NOT NULL,
  `remarks` text NOT NULL,
  `created_on` datetime NOT NULL,
  `created_by` bigint(20) NOT NULL,
  `modified_on` datetime NOT NULL,
  `modified_by` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_purchase_hdr`
--

INSERT INTO `tbl_purchase_hdr` (`purchase_hdr_id`, `purchase_date`, `suppiler_id`, `total_purchase_amount`, `remarks`, `created_on`, `created_by`, `modified_on`, `modified_by`) VALUES
(1, '2025-07-19', 1, 200.00, '', '2025-07-19 19:39:00', 1, '2025-07-19 19:39:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_return_qty`
--

CREATE TABLE `tbl_return_qty` (
  `return_id` bigint(20) NOT NULL,
  `order_hdr_id` bigint(20) NOT NULL,
  `order_dtl_id` bigint(20) NOT NULL,
  `item_id` bigint(20) NOT NULL,
  `return_qty` decimal(10,2) NOT NULL,
  `created_on` datetime NOT NULL,
  `created_by` varchar(55) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_return_qty`
--

INSERT INTO `tbl_return_qty` (`return_id`, `order_hdr_id`, `order_dtl_id`, `item_id`, `return_qty`, `created_on`, `created_by`) VALUES
(1, 1, 1, 1, 1.00, '2025-09-20 13:17:46', '1'),
(2, 1, 2, 2, 0.00, '2025-09-20 13:17:46', '1');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `Code` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `username` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `title` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `confirm_password` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `mobile_no` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `isApproved` tinyint(1) DEFAULT NULL,
  `subscription` tinyint(1) DEFAULT NULL,
  `activated` tinyint(1) DEFAULT '1',
  `banned` tinyint(1) DEFAULT '0',
  `ban_reason` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `new_password_key` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `new_password_requested` datetime DEFAULT NULL,
  `new_email` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `new_email_key` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `last_ip` varchar(40) COLLATE utf8_bin NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `Code`, `username`, `title`, `first_name`, `last_name`, `password`, `confirm_password`, `email`, `mobile_no`, `isApproved`, `subscription`, `activated`, `banned`, `ban_reason`, `new_password_key`, `new_password_requested`, `new_email`, `new_email_key`, `last_ip`, `last_login`, `created`, `modified`) VALUES
(1, 1, '', 'admin@sheetal_dairy.com', NULL, 'Sheetal', 'Dairy', '$2a$08$D4hbWrnZ/jHQQVYkP4O/9uLyX5T9DawC5D2AT0Qmtjo7hwz55Po.S', '$2a$08$D4hbWrnZ/jHQQVYkP4O/9uLyX5T9DawC5D2AT0Qmtjo7hwz55Po.S', 'admin@sheetal_dairy.com', '', NULL, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, '45.126.169.29', '2025-09-22 16:46:36', NULL, '2025-09-22 11:16:36'),
(26, 2, NULL, 'ramizg.aipl@gmail.com', NULL, 'Ramiz', 'Girach', '$2a$08$OcTNO0/iMIuY9BbGXCtmoOODS9Y.D9D18yeE0gI7frvDF.RqVXfXW', NULL, 'ramizg.aipl@gmail.com', '9624049054', NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, '::1', '2025-07-05 20:12:16', NULL, '2025-07-05 14:42:16');

-- --------------------------------------------------------

--
-- Table structure for table `user_autologin`
--

CREATE TABLE `user_autologin` (
  `key_id` char(32) COLLATE utf8_bin NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `user_agent` varchar(150) COLLATE utf8_bin NOT NULL,
  `last_ip` varchar(40) COLLATE utf8_bin NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `user_autologin`
--

INSERT INTO `user_autologin` (`key_id`, `user_id`, `user_agent`, `last_ip`, `last_login`) VALUES
('0ce3fa3ed7053489938f46cf72919f59', 1, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.135 Safari/537.36 Edg/84.0.522.63', '49.36.6.10', '2020-08-27 11:32:52'),
('3aa59ef79715a7b9170e40a7a0928c65', 1, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '49.36.10.15', '2025-09-21 03:55:36');

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE `user_profiles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `country` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `website` varchar(255) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `user_profiles`
--

INSERT INTO `user_profiles` (`id`, `user_id`, `country`, `website`) VALUES
(1, 1, NULL, NULL),
(2, 4, NULL, NULL),
(3, 5, NULL, NULL),
(4, 6, NULL, NULL),
(5, 7, NULL, NULL),
(6, 8, NULL, NULL),
(7, 9, NULL, NULL),
(8, 10, NULL, NULL),
(9, 11, NULL, NULL),
(10, 12, NULL, NULL),
(11, 13, NULL, NULL),
(12, 14, NULL, NULL),
(13, 15, NULL, NULL),
(14, 16, NULL, NULL),
(15, 17, NULL, NULL),
(16, 18, NULL, NULL),
(17, 19, NULL, NULL),
(18, 20, NULL, NULL),
(19, 21, NULL, NULL),
(20, 22, NULL, NULL),
(21, 23, NULL, NULL),
(22, 24, NULL, NULL),
(23, 25, NULL, NULL),
(24, 26, NULL, NULL),
(25, 27, NULL, NULL),
(26, 28, NULL, NULL),
(27, 29, NULL, NULL),
(28, 30, NULL, NULL),
(29, 31, NULL, NULL),
(30, 33, NULL, NULL),
(31, 41, NULL, NULL),
(32, 43, NULL, NULL),
(33, 45, NULL, NULL),
(34, 46, NULL, NULL),
(35, 47, NULL, NULL),
(36, 49, NULL, NULL),
(37, 57, NULL, NULL),
(38, 58, NULL, NULL),
(39, 59, NULL, NULL),
(40, 61, NULL, NULL),
(41, 2, NULL, NULL),
(42, 3, NULL, NULL),
(43, 4, NULL, NULL),
(44, 5, NULL, NULL),
(45, 6, NULL, NULL),
(46, 2, NULL, NULL),
(47, 3, NULL, NULL),
(48, 4, NULL, NULL),
(49, 5, NULL, NULL),
(50, 6, NULL, NULL),
(51, 7, NULL, NULL),
(52, 8, NULL, NULL),
(53, 9, NULL, NULL),
(54, 10, NULL, NULL),
(55, 11, NULL, NULL),
(56, 12, NULL, NULL),
(57, 13, NULL, NULL),
(58, 14, NULL, NULL),
(59, 15, NULL, NULL),
(60, 16, NULL, NULL),
(61, 17, NULL, NULL),
(62, 18, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ci_sessions`
--
ALTER TABLE `ci_sessions`
  ADD PRIMARY KEY (`session_id`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_customer`
--
ALTER TABLE `tbl_customer`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `tbl_damage_item`
--
ALTER TABLE `tbl_damage_item`
  ADD PRIMARY KEY (`damange_item_id`);

--
-- Indexes for table `tbl_expense`
--
ALTER TABLE `tbl_expense`
  ADD PRIMARY KEY (`expense_id`);

--
-- Indexes for table `tbl_item`
--
ALTER TABLE `tbl_item`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `tbl_item_stock`
--
ALTER TABLE `tbl_item_stock`
  ADD PRIMARY KEY (`stock_id`);

--
-- Indexes for table `tbl_m_role`
--
ALTER TABLE `tbl_m_role`
  ADD PRIMARY KEY (`RoleID`);

--
-- Indexes for table `tbl_order_dtl`
--
ALTER TABLE `tbl_order_dtl`
  ADD PRIMARY KEY (`order_dtl_id`);

--
-- Indexes for table `tbl_order_hdr`
--
ALTER TABLE `tbl_order_hdr`
  ADD PRIMARY KEY (`order_hdr_id`);

--
-- Indexes for table `tbl_purchase_dtl`
--
ALTER TABLE `tbl_purchase_dtl`
  ADD PRIMARY KEY (`purchase_dtl_id`);

--
-- Indexes for table `tbl_purchase_hdr`
--
ALTER TABLE `tbl_purchase_hdr`
  ADD PRIMARY KEY (`purchase_hdr_id`);

--
-- Indexes for table `tbl_return_qty`
--
ALTER TABLE `tbl_return_qty`
  ADD PRIMARY KEY (`return_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_autologin`
--
ALTER TABLE `user_autologin`
  ADD PRIMARY KEY (`key_id`,`user_id`);

--
-- Indexes for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `tbl_customer`
--
ALTER TABLE `tbl_customer`
  MODIFY `customer_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `tbl_damage_item`
--
ALTER TABLE `tbl_damage_item`
  MODIFY `damange_item_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_expense`
--
ALTER TABLE `tbl_expense`
  MODIFY `expense_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_item`
--
ALTER TABLE `tbl_item`
  MODIFY `item_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `tbl_item_stock`
--
ALTER TABLE `tbl_item_stock`
  MODIFY `stock_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `tbl_m_role`
--
ALTER TABLE `tbl_m_role`
  MODIFY `RoleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_order_dtl`
--
ALTER TABLE `tbl_order_dtl`
  MODIFY `order_dtl_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_order_hdr`
--
ALTER TABLE `tbl_order_hdr`
  MODIFY `order_hdr_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_purchase_dtl`
--
ALTER TABLE `tbl_purchase_dtl`
  MODIFY `purchase_dtl_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_purchase_hdr`
--
ALTER TABLE `tbl_purchase_hdr`
  MODIFY `purchase_hdr_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_return_qty`
--
ALTER TABLE `tbl_return_qty`
  MODIFY `return_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `user_profiles`
--
ALTER TABLE `user_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
