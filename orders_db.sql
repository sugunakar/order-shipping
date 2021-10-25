-- phpMyAdmin SQL Dump
-- version 4.4.13.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 25, 2021 at 02:10 PM
-- Server version: 5.5.8-log
-- PHP Version: 5.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `orders_db`
--

DELIMITER $$
--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `next_sequenceval`(`seq_name` varchar(100)) RETURNS bigint(20)
    DETERMINISTIC
BEGIN
    DECLARE cur_val bigint(20);
 
    SELECT
        sequence_cur_value INTO cur_val
    FROM
        orders_db.od_sequencegenerator
    WHERE
        sequence_name = seq_name
    FOR UPDATE;
 
    IF cur_val IS NOT NULL THEN
        UPDATE
           orders_db.od_sequencegenerator
        SET
            sequence_cur_value = IF (
                (sequence_cur_value + sequence_increment) > sequence_max_value,
                IF (
                    sequence_cycle = TRUE,
                    sequence_min_value,
                    NULL
                ),
                sequence_cur_value + sequence_increment
            )
        WHERE
            sequence_name = seq_name
        ;
    END IF;
 
    RETURN cur_val;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `od_orders`
--

CREATE TABLE IF NOT EXISTS `od_orders` (
  `orderId` int(11) NOT NULL,
  `orderNumber` varchar(20) NOT NULL,
  `shippingNumber` varchar(20) NOT NULL,
  `createdDate` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `od_orders`
--

INSERT INTO `od_orders` (`orderId`, `orderNumber`, `shippingNumber`, `createdDate`) VALUES
(1, '1000025', '2025361004', '2021-10-25 08:54:38'),
(2, '1000030', '2025361014', '2021-10-25 08:54:42'),
(3, '1000035', '2025361024', '2021-10-25 08:56:06'),
(4, '1000040', '2025361034', '2021-10-25 08:56:18'),
(5, '1000045', '2025361044', '2021-10-25 08:56:21'),
(6, '1000050', '2025361054', '2021-10-25 08:56:23'),
(7, '1000055', '2025361064', '2021-10-25 08:56:25'),
(8, '1000060', '2025361074', '2021-10-25 08:56:36'),
(9, '1000065', '2025361084', '2021-10-25 09:05:33'),
(10, '1000070', '2025361094', '2021-10-25 09:12:13'),
(11, '1000075', '2025361104', '2021-10-25 09:17:40'),
(12, '1000080', '2025361114', '2021-10-25 13:59:14'),
(13, '1000085', '2025361124', '2021-10-25 14:02:17'),
(14, '1000090', '2025361134', '2021-10-25 14:05:52');

-- --------------------------------------------------------

--
-- Table structure for table `od_sequencegenerator`
--

CREATE TABLE IF NOT EXISTS `od_sequencegenerator` (
  `sequence_name` varchar(100) NOT NULL,
  `sequence_increment` int(11) unsigned NOT NULL DEFAULT '1',
  `sequence_min_value` int(11) unsigned NOT NULL DEFAULT '1',
  `sequence_max_value` bigint(20) unsigned NOT NULL DEFAULT '18446744073709551615',
  `sequence_cur_value` bigint(20) DEFAULT '1',
  `sequence_cycle` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `od_sequencegenerator`
--

INSERT INTO `od_sequencegenerator` (`sequence_name`, `sequence_increment`, `sequence_min_value`, `sequence_max_value`, `sequence_cur_value`, `sequence_cycle`) VALUES
('orderSequence', 5, 1, 18446744073709551615, 1000095, 0),
('shippingSequence', 10, 1, 18446744073709551615, 2025361144, 0);

-- --------------------------------------------------------

--
-- Table structure for table `od_shipping_tracking`
--

CREATE TABLE IF NOT EXISTS `od_shipping_tracking` (
  `trackingId` int(11) NOT NULL,
  `shippingNumber` varchar(20) NOT NULL,
  `sourceId` int(11) NOT NULL,
  `destinationId` int(11) NOT NULL,
  `startDate` datetime NOT NULL,
  `endDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `statusId` int(1) NOT NULL DEFAULT '1' COMMENT '1=Intransit,2=Destination,3=Final Detination'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `od_shipping_tracking`
--

INSERT INTO `od_shipping_tracking` (`trackingId`, `shippingNumber`, `sourceId`, `destinationId`, `startDate`, `endDate`, `statusId`) VALUES
(1, '2025361134', 1, 2, '2021-10-24 13:15:00', '2021-10-24 14:40:00', 2),
(2, '2025361134', 2, 3, '2021-10-24 16:15:00', '2021-10-24 20:50:00', 2),
(3, '2025361134', 3, 4, '2021-10-24 21:00:00', '0000-00-00 00:00:00', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `od_orders`
--
ALTER TABLE `od_orders`
  ADD PRIMARY KEY (`orderId`);

--
-- Indexes for table `od_sequencegenerator`
--
ALTER TABLE `od_sequencegenerator`
  ADD PRIMARY KEY (`sequence_name`);

--
-- Indexes for table `od_shipping_tracking`
--
ALTER TABLE `od_shipping_tracking`
  ADD PRIMARY KEY (`trackingId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `od_orders`
--
ALTER TABLE `od_orders`
  MODIFY `orderId` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `od_shipping_tracking`
--
ALTER TABLE `od_shipping_tracking`
  MODIFY `trackingId` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
