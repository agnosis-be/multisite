-- This file: /dbdump/03.sql (UTF-8)
-- Create structure of table tblContent
-- @requires './01.sql'
-- @requires './02.sql'

USE multisite;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE TABLE `tblContent` (
  `ID` int(11) NOT NULL,
  `SiteID` int(11) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Title2` varchar(255) DEFAULT NULL,
  `Data` mediumtext,
  `Data2` mediumtext,
  `AlbumYN` tinyint(1) NOT NULL DEFAULT '0',
  `AlbumDir` int(11) DEFAULT NULL,
  `NavBarYN` tinyint(1) NOT NULL DEFAULT '0',
  `NavBarPosAfterID` int(11) DEFAULT NULL,
  `UrlPasswdYN` tinyint(1) NOT NULL DEFAULT '0',
  `UrlPasswd` varchar(10) DEFAULT NULL,
  `BgTxt` varchar(255) DEFAULT NULL,
  `BgTxt2` varchar(255) DEFAULT NULL,
  `BgImg` varchar(255) DEFAULT NULL,
  `ExtraHeader` mediumtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `tblContent`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `SiteID` (`SiteID`);

ALTER TABLE `tblContent`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `tblContent`
  ADD CONSTRAINT `tblContent_ibfk_1` FOREIGN KEY (`SiteID`) REFERENCES `tblSite` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
