-- MySQL dump 10.13  Distrib 5.1.47, for unknown-linux-gnu (x86_64)
--
-- Host: localhost    Database: bluemov2_visitors
-- ------------------------------------------------------
-- Server version	5.1.47-community-log

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
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `rowID` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL DEFAULT '',
  `email` varchar(55) NOT NULL DEFAULT '',
  `pwd` varchar(32) NOT NULL DEFAULT '',
  `add_street` varchar(80) DEFAULT NULL,
  `add_city` varchar(80) DEFAULT NULL,
  `add_state` varchar(80) DEFAULT NULL,
  `add_country` varchar(80) DEFAULT NULL,
  `add_zip` varchar(20) DEFAULT NULL,
  `u_type` tinyint(4) NOT NULL DEFAULT '0',
  `u_priv` tinyint(4) NOT NULL DEFAULT '0',
  `add_apt` varchar(80) DEFAULT NULL,
  `username` varchar(20) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `start_date` int(11) DEFAULT NULL,
  `login_type` varchar(20) DEFAULT NULL,
  `sstartid` bigint(20) DEFAULT NULL,
  `last_access` int(11) DEFAULT NULL,
  PRIMARY KEY (`rowID`)
) ENGINE=MyISAM AUTO_INCREMENT=125 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (117,'Gary Gross','eightyoctane@yahoo.com','768a7b74b3f9fe2194fbc17efd1876e6','','','','','',2,6,'','eightyoctane','',1291524716,'bluemountain',NULL,NULL),(118,'Marie House','mariesrtwork@yahoo.com','b1a4041c3de5df34d3bd3d469928ccaf','','Trion','ga','USA','30753',2,4,'391 Blue Mountain Lane','mariesartwork','423-605-4483',1291562929,'bluemountain',NULL,NULL),(124,'Phil Dater','pkdater@mindspring.com','8cad04b6c598dfe96fade4a53217be2f','471  Blue Mountain Lane','Trion','GA','USA','30753',2,4,'','planeview','706 639 9334',1291673431,'bluemountain',NULL,NULL),(99,'Gary Gross','eightyoctane@yahoo.com','f968e55139ff54d85f1420cbdf6525d3','','','','','',2,6,'','bmaadmin10','',1291229707,'bluemountain',NULL,NULL),(119,'Gary Gross','eightyoctane@yahoo.com','6a29cf18161f652ab0e0b2943249c52e','','','','','',1,1,'','gen1','',1291563519,'bluemountain',NULL,NULL),(120,'Gary Gross','eightyoctane@yahoo.com','7f903d844b517574584c8669a963227f','','','','','',1,0,'','gen2','',1291570317,'bluemountain',NULL,NULL),(121,'Gary Gross','eightyoctane@yahoo.com','017a220790418f0a62dcd1e46b7db9ed','','','','','',1,1,'','gen3','',1291572365,'bluemountain',NULL,NULL),(123,'alisa bigham','sambigham@aol.com','2ff2d114f3fffaa76bd8d1babc7e0c09','389 blue mountain lane','trion','ga','usa','30753',2,2,'','rivendell','706 638 3041',1291656916,'bluemountain',NULL,NULL);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2010-12-06 18:38:02
