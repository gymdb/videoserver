-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 31. Aug 2016 um 20:34
-- Server-Version: 10.1.13-MariaDB
-- PHP-Version: 7.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE DATABASE IF NOT EXISTS `media` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `media`;

CREATE TABLE `media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` longtext NOT NULL,
  `duration` varchar(20) DEFAULT NULL,
  `fileLocation` varchar(255) NOT NULL,
  `fileName` varchar(255) NOT NULL,
  `form` varchar(20) NOT NULL,
  `subject` varchar(40) NOT NULL,
  `title` varchar(255) NOT NULL,
  `uploadedOn` bigint(20) NOT NULL,
  `archived` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
