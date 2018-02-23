-- MySQL dump 10.14  Distrib 5.5.56-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: daniel.hanson
-- ------------------------------------------------------
-- Server version	5.5.56-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `t_agency`
--

DROP TABLE IF EXISTS `t_agency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_agency` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `foodDefault` enum('0','1','2') NOT NULL DEFAULT '0',
  `toysDefault` enum('0','1','2') NOT NULL DEFAULT '0',
  `hideFood` int(11) DEFAULT '0',
  `hideToys` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=171 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_application`
--

DROP TABLE IF EXISTS `t_application`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_application` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `agency_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `user_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `tstamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tstampUpd` timestamp NULL DEFAULT NULL,
  `season` year(4) NOT NULL DEFAULT '0000',
  `phone` varchar(20) NOT NULL DEFAULT '',
  `street` varchar(128) NOT NULL DEFAULT '',
  `city` varchar(64) NOT NULL DEFAULT '',
  `state` varchar(2) NOT NULL DEFAULT '',
  `zip` varchar(10) NOT NULL DEFAULT '',
  `familySize` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `employer` varchar(64) NOT NULL DEFAULT '',
  `income` varchar(12) NOT NULL DEFAULT '',
  `expenses` varchar(12) NOT NULL DEFAULT '',
  `comments` text NOT NULL,
  `ss` enum('0','1') NOT NULL DEFAULT '0',
  `ssi` enum('0','1') NOT NULL DEFAULT '0',
  `va` enum('0','1') NOT NULL DEFAULT '0',
  `tanf` enum('0','1') NOT NULL DEFAULT '0',
  `fstamps` enum('0','1') NOT NULL DEFAULT '0',
  `other` enum('0','1') NOT NULL DEFAULT '0',
  `toys` enum('0','1','2') NOT NULL DEFAULT '0',
  `food` enum('0','1','2') NOT NULL DEFAULT '0',
  `dupe` enum('0','1') NOT NULL DEFAULT '0',
  `dupe1` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `agency_id` (`agency_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=48966 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_application_test`
--

DROP TABLE IF EXISTS `t_application_test`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_application_test` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agency_id` smallint(6) NOT NULL DEFAULT '0',
  `user_id` smallint(6) NOT NULL DEFAULT '0',
  `tstamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tstampUpd` timestamp NULL DEFAULT NULL,
  `season` year(4) NOT NULL DEFAULT '0000',
  `phone` varchar(20) NOT NULL DEFAULT '',
  `street` varchar(128) NOT NULL DEFAULT '',
  `city` varchar(64) NOT NULL DEFAULT '',
  `state` varchar(2) NOT NULL DEFAULT '',
  `zip` varchar(10) NOT NULL DEFAULT '',
  `familySize` tinyint(4) NOT NULL DEFAULT '0',
  `employer` varchar(64) NOT NULL DEFAULT '',
  `income` varchar(12) NOT NULL DEFAULT '',
  `expenses` varchar(12) NOT NULL DEFAULT '',
  `comments` text NOT NULL,
  `ss` enum('0','1') NOT NULL DEFAULT '0',
  `ssi` enum('0','1') NOT NULL DEFAULT '0',
  `va` enum('0','1') NOT NULL DEFAULT '0',
  `tanf` enum('0','1') NOT NULL DEFAULT '0',
  `fstamps` enum('0','1') NOT NULL DEFAULT '0',
  `other` enum('0','1') NOT NULL DEFAULT '0',
  `toys` enum('0','1','2') NOT NULL DEFAULT '0',
  `food` enum('0','1','2') NOT NULL DEFAULT '0',
  `dupe` enum('0','1') NOT NULL DEFAULT '0',
  `dupe1` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5403 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_config`
--

DROP TABLE IF EXISTS `t_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_config` (
  `season` year(4) NOT NULL DEFAULT '0000',
  `tft` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_family`
--

DROP TABLE IF EXISTS `t_family`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_family` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `application_id` int(10) unsigned NOT NULL DEFAULT '0',
  `type` enum('1','2','3') DEFAULT NULL,
  `firstName` varchar(64) NOT NULL DEFAULT '',
  `lastName` varchar(64) NOT NULL DEFAULT '',
  `dob` date NOT NULL DEFAULT '0000-00-00',
  `lookup_id` int(10) unsigned NOT NULL DEFAULT '0',
  `sex` enum('Male','Female') DEFAULT NULL,
  `wishlist` text,
  PRIMARY KEY (`id`),
  KEY `application_id` (`application_id`),
  KEY `lookup_id` (`lookup_id`)
) ENGINE=MyISAM AUTO_INCREMENT=158212 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_family_test`
--

DROP TABLE IF EXISTS `t_family_test`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_family_test` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `application_id` int(10) unsigned NOT NULL DEFAULT '0',
  `type` enum('1','2','3') DEFAULT NULL,
  `firstName` varchar(64) NOT NULL DEFAULT '',
  `lastName` varchar(64) NOT NULL DEFAULT '',
  `dob` date NOT NULL DEFAULT '0000-00-00',
  `lookup_id` int(10) unsigned NOT NULL DEFAULT '0',
  `sex` enum('Male','Female') DEFAULT NULL,
  `wishlist` text,
  PRIMARY KEY (`id`),
  KEY `application_id` (`application_id`),
  KEY `lookup_id` (`lookup_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13212 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_lookup`
--

DROP TABLE IF EXISTS `t_lookup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_lookup` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` varchar(64) NOT NULL DEFAULT '',
  `season` year(4) NOT NULL DEFAULT '0000',
  `dataExp` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `data` (`data`,`season`)
) ENGINE=MyISAM AUTO_INCREMENT=146423 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_lookup_test`
--

DROP TABLE IF EXISTS `t_lookup_test`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_lookup_test` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` varchar(64) NOT NULL DEFAULT '',
  `season` year(4) NOT NULL DEFAULT '0000',
  `dataExp` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `data` (`data`,`season`)
) ENGINE=MyISAM AUTO_INCREMENT=12624 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_session`
--

DROP TABLE IF EXISTS `t_session`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_session` (
  `login_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `hash` char(40) NOT NULL DEFAULT '',
  `tstamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_user`
--

DROP TABLE IF EXISTS `t_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_user` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL DEFAULT '',
  `password` varchar(64) NOT NULL DEFAULT '',
  `accessLevel` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `firstName` varchar(64) NOT NULL DEFAULT '',
  `lastName` varchar(64) NOT NULL DEFAULT '',
  `phone` varchar(20) NOT NULL DEFAULT '',
  `dlFlag` enum('0','1') NOT NULL DEFAULT '0',
  `email` varchar(64) DEFAULT '',
  `passwordReset` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=526 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `t_user_agency`
--

DROP TABLE IF EXISTS `t_user_agency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `t_user_agency` (
  `agency_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `user_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `type` enum('0','1') NOT NULL DEFAULT '0',
  KEY `agency_id` (`agency_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-01-18 11:42:59
