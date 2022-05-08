-- This file: /dbdump/02.sql (UTF-8)
-- Create structure of table tblSite
-- Requires './01.sql'

USE multisite;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

CREATE TABLE `tblSite` (
  `ID` int(11) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Descr` mediumtext,
  `Descr2` mediumtext,
  `Dir` varchar(255) DEFAULT NULL,
  `URL` varchar(255) DEFAULT NULL,
  `HomeID` int(11) DEFAULT NULL,
  `Login` varchar(30) DEFAULT NULL,
  `AllowLoginYN` tinyint(4) NOT NULL DEFAULT '1',
  `LastLoginDt` datetime DEFAULT NULL,
  `LastFailDt` datetime DEFAULT NULL,
  `LastFailCount` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `Passwd` varchar(255) DEFAULT NULL,
  `Template` varchar(255) NOT NULL,
  `Lang` char(2) NOT NULL,
  `Lang2` char(2) DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `FullName` varchar(255) DEFAULT NULL,
  `Header` mediumtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `tblSite`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Login` (`Login`);

ALTER TABLE `tblSite`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
