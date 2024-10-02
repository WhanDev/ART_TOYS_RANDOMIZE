-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307:3307
-- Generation Time: Oct 02, 2024 at 11:06 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_art_toy`
--

-- --------------------------------------------------------

--
-- Table structure for table `art_user`
--

CREATE TABLE `art_user` (
  `user_id` char(5) NOT NULL COMMENT 'รหัสผู้ใช้',
  `f_name` varchar(100) NOT NULL COMMENT 'ชื่อ',
  `l_name` varchar(100) NOT NULL COMMENT 'นามสกุล',
  `email` varchar(100) NOT NULL COMMENT 'อีเมล์',
  `tel` char(10) NOT NULL COMMENT 'เบอร์โทร',
  `address` text NOT NULL COMMENT 'ที่อยู่',
  `user_password` varchar(100) NOT NULL COMMENT 'รหัสผ่าน',
  `user_role` enum('admin','customer') NOT NULL COMMENT 'สิทธิ์ผู้ใช้'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `pamt_id` char(5) NOT NULL COMMENT 'รหัสการชำระเงิน',
  `pamt_amount` float(6,2) DEFAULT NULL COMMENT 'ยอดชำระก่อนหักส่วนลด',
  `pamt_img` text DEFAULT NULL COMMENT 'รูปพี่น่า น้องหนาว ซ้อหนิง พี่เจล',
  `pamt_discount` float(6,2) DEFAULT NULL COMMENT 'ส่วนลด',
  `pamt_net` float(6,2) DEFAULT NULL COMMENT 'ยอดจ่ายสุทธิ',
  `or_id` char(5) DEFAULT NULL COMMENT 'รหัสสั่งซื้อ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `prod_id` char(5) NOT NULL COMMENT 'รหัสสินค้า',
  `prod_name` varchar(100) NOT NULL COMMENT 'ชื่อสินค้า',
  `prod_size` varchar(100) NOT NULL COMMENT 'ขนาด',
  `prod_amount` int(5) NOT NULL COMMENT 'จำนวน',
  `prod_price` float(6,2) NOT NULL COMMENT 'ราคา',
  `prod_img` text NOT NULL COMMENT 'รูปภาพ',
  `type_id` char(5) NOT NULL COMMENT 'รหัสประเภทสินค้า'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `product_type`
--

CREATE TABLE `product_type` (
  `type_id` char(5) NOT NULL COMMENT 'รหัสประเภทสินค้า',
  `type_name` varchar(100) DEFAULT NULL COMMENT 'ชื่อปรเภท'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `toy_order`
--

CREATE TABLE `toy_order` (
  `or_id` char(5) NOT NULL COMMENT 'รหัสสั่งซื้อ',
  `or_date` date DEFAULT NULL COMMENT 'วันที่สั่งซื้อ',
  `user_id` char(5) DEFAULT NULL COMMENT 'รหัสผู้ใช้'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `toy_order_details`
--

CREATE TABLE `toy_order_details` (
  `ordt_id` char(5) NOT NULL COMMENT 'รหัสรายการสั่งซื้อ',
  `ordt_amount` int(3) DEFAULT NULL COMMENT 'จำนวน',
  `prod_id` char(5) DEFAULT NULL COMMENT 'รหัสสินค้า',
  `or_id` char(5) DEFAULT NULL COMMENT 'รหัสสั่งซื้อ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `art_user`
--
ALTER TABLE `art_user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`pamt_id`),
  ADD KEY `or_id` (`or_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`prod_id`),
  ADD KEY `type_id` (`type_id`);

--
-- Indexes for table `product_type`
--
ALTER TABLE `product_type`
  ADD PRIMARY KEY (`type_id`);

--
-- Indexes for table `toy_order`
--
ALTER TABLE `toy_order`
  ADD PRIMARY KEY (`or_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `toy_order_details`
--
ALTER TABLE `toy_order_details`
  ADD PRIMARY KEY (`ordt_id`),
  ADD KEY `prod_id` (`prod_id`),
  ADD KEY `or_id` (`or_id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`or_id`) REFERENCES `toy_order` (`or_id`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `product_type` (`type_id`);

--
-- Constraints for table `toy_order`
--
ALTER TABLE `toy_order`
  ADD CONSTRAINT `toy_order_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `art_user` (`user_id`);

--
-- Constraints for table `toy_order_details`
--
ALTER TABLE `toy_order_details`
  ADD CONSTRAINT `toy_order_details_ibfk_1` FOREIGN KEY (`prod_id`) REFERENCES `product` (`prod_id`),
  ADD CONSTRAINT `toy_order_details_ibfk_2` FOREIGN KEY (`or_id`) REFERENCES `toy_order` (`or_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
