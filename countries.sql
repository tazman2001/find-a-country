-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 17, 2019 at 12:37 PM
-- Server version: 10.3.16-MariaDB
-- PHP Version: 7.3.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `countries`
--

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `country_code` varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `capital` varchar(50) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `region` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `flag_image` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `dialing_code` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`country_code`, `name`, `capital`, `region`, `flag_image`, `dialing_code`) VALUES
('ALB', 'Albania', 'Tirana', 'Europe', 'https://restcountries.eu/data/alb.svg', 355),
('BFA', 'Burkina Faso', 'Ouagadougou', 'Africa', 'https://restcountries.eu/data/bfa.svg', 226),
('COK', 'Cook Islands', 'Avarua', 'Oceania', 'https://restcountries.eu/data/cok.svg', 682),
('GBR', 'United Kingdom of Great Britain and Northern Ireland', 'London', 'Europe', 'https://restcountries.eu/data/gbr.svg', 44),
('IRL', 'Ireland', 'Dublin', 'Europe', 'https://restcountries.eu/data/irl.svg', 353),
('IRN', 'Iran (Islamic Republic of)', 'Tehran', 'Asia', 'https://restcountries.eu/data/irn.svg', 98),
('KIR', 'Kiribati', 'South Tarawa', 'Oceania', 'https://restcountries.eu/data/kir.svg', 686),
('SHN', 'Saint Helena, Ascension and Tristan da Cunha', 'Jamestown', 'Africa', 'https://restcountries.eu/data/shn.svg', 290),
('USA', 'United States of America', 'Washington, D.C.', 'Americas', 'https://restcountries.eu/data/usa.svg', 1),
('VGB', 'Virgin Islands (British)', 'Road Town', 'Americas', 'https://restcountries.eu/data/vgb.svg', 1284);

-- --------------------------------------------------------

--
-- Table structure for table `countries_currencies`
--

CREATE TABLE `countries_currencies` (
  `country_code` varchar(5) NOT NULL,
  `currency_code` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `countries_currencies`
--

INSERT INTO `countries_currencies` (`country_code`, `currency_code`) VALUES
('ALB', 'ALL'),
('BFA', 'XOF'),
('COK', 'CKD'),
('COK', 'NZD'),
('GBR', 'GBP'),
('IRL', 'EUR'),
('IRN', 'IRR'),
('KIR', '(none'),
('KIR', 'AUD'),
('SHN', 'SHP'),
('USA', 'USD'),
('VGB', 'USD');

-- --------------------------------------------------------

--
-- Table structure for table `countries_languages`
--

CREATE TABLE `countries_languages` (
  `country_code` varchar(5) NOT NULL,
  `language_code` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `countries_languages`
--

INSERT INTO `countries_languages` (`country_code`, `language_code`) VALUES
('ALB', 'sq'),
('BFA', 'ff'),
('BFA', 'fr'),
('IRN', 'fa'),
('USA', 'en');

-- --------------------------------------------------------

--
-- Table structure for table `countries_timezones`
--

CREATE TABLE `countries_timezones` (
  `country_code` varchar(5) NOT NULL,
  `timezone` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `countries_timezones`
--

INSERT INTO `countries_timezones` (`country_code`, `timezone`) VALUES
('ALB', 'UTC+01:00'),
('BFA', 'UTC'),
('COK', 'UTC-10:00'),
('GBR', 'UTC'),
('GBR', 'UTC+01:00'),
('GBR', 'UTC+02:00'),
('GBR', 'UTC+06:00'),
('GBR', 'UTC-02:00'),
('GBR', 'UTC-03:00'),
('GBR', 'UTC-04:00'),
('GBR', 'UTC-05:00'),
('GBR', 'UTC-08:00'),
('IRL', 'UTC'),
('IRN', 'UTC+03:30'),
('KIR', 'UTC+12:00'),
('KIR', 'UTC+13:00'),
('KIR', 'UTC+14:00'),
('SHN', 'UTC+00:00'),
('USA', 'UTC+10:00'),
('USA', 'UTC+12:00'),
('USA', 'UTC-04:00'),
('USA', 'UTC-05:00'),
('USA', 'UTC-06:00'),
('USA', 'UTC-07:00'),
('USA', 'UTC-08:00'),
('USA', 'UTC-09:00'),
('USA', 'UTC-10:00'),
('USA', 'UTC-11:00'),
('USA', 'UTC-12:00'),
('VGB', 'UTC-04:00');

-- --------------------------------------------------------

--
-- Table structure for table `currency_codes`
--

CREATE TABLE `currency_codes` (
  `currency_code` varchar(5) NOT NULL,
  `name` varchar(50) NOT NULL,
  `symbol` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `currency_codes`
--

INSERT INTO `currency_codes` (`currency_code`, `name`, `symbol`) VALUES
('(none', 'Kiribati dollar', '$'),
('ALL', 'Albanian lek', 'L'),
('AUD', 'Australian dollar', '$'),
('CKD', 'Cook Islands dollar', '$'),
('EUR', 'Euro', 'â‚¬'),
('GBP', 'British pound', 'Â£'),
('IRR', 'Iranian rial', 'ï·¼'),
('NZD', 'New Zealand dollar', '$'),
('SHP', 'Saint Helena pound', 'Â£'),
('USD', 'United States dollar', '$'),
('XOF', 'West African CFA franc', 'Fr');

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `language_code` varchar(5) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`language_code`, `name`) VALUES
('sq', 'Albanian'),
('en', 'English'),
('fr', 'French'),
('ff', 'Fula'),
('fa', 'Persian (Farsi)');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`country_code`),
  ADD KEY `name` (`name`),
  ADD KEY `capital` (`capital`);

--
-- Indexes for table `countries_currencies`
--
ALTER TABLE `countries_currencies`
  ADD PRIMARY KEY (`country_code`,`currency_code`) USING BTREE,
  ADD KEY `currency_code` (`currency_code`);

--
-- Indexes for table `countries_languages`
--
ALTER TABLE `countries_languages`
  ADD PRIMARY KEY (`country_code`,`language_code`) USING BTREE,
  ADD UNIQUE KEY `language_code` (`language_code`);

--
-- Indexes for table `countries_timezones`
--
ALTER TABLE `countries_timezones`
  ADD PRIMARY KEY (`country_code`,`timezone`) USING BTREE,
  ADD KEY `country_code` (`timezone`) USING BTREE;

--
-- Indexes for table `currency_codes`
--
ALTER TABLE `currency_codes`
  ADD PRIMARY KEY (`currency_code`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`language_code`),
  ADD KEY `language` (`name`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
