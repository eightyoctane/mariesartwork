-- MySQL dump 9.11
--
-- Host: localhost    Database: ggross
-- ------------------------------------------------------
-- Server version	4.0.25

--
-- Current Database: ggross
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ ggross;

USE ggross;

--
-- Table structure for table `user`
--

CREATE TABLE user (
  rowID smallint(5) unsigned NOT NULL auto_increment,
  name char(40) NOT NULL default '',
  email char(55) NOT NULL default '',
  username char(15) NOT NULL default '',
  pwd char(32) NOT NULL default '',
  add_street char(80) default NULL,
  add_city char(80) default NULL,
  add_state char(80) default NULL,
  add_country char(80) default NULL,
  add_zip char(20) default NULL,
  u_type tinyint(4) NOT NULL default '0',
  u_priv tinyint(4) NOT NULL default '0',
  add_apt char(80) default NULL,
  phone char(20) default NULL,
  start_date int(11) default NULL,
  PRIMARY KEY  (rowID)
) TYPE=MyISAM;

--
-- Dumping data for table `user`
--

INSERT INTO user VALUES (5,'Gary Gross','eightyoctane@yahoo.com','eightyoctane','768a7b74b3f9fe2194fbc17efd1876e6',NULL,NULL,NULL,NULL,NULL,1,0,NULL,NULL,NULL);
INSERT INTO user VALUES (6,'Marie House','mar4483@yahoo.com','artmarie','ddec3e29dda218fdac64aeb036ee8916',NULL,NULL,NULL,NULL,NULL,2,0,NULL,NULL,NULL);
INSERT INTO user VALUES (10,'Mike@PAO','eightyoctane@yahoo.com','mike','655ae040aec1d7f78d790580c5ac37ab',NULL,NULL,NULL,NULL,NULL,2,0,NULL,NULL,NULL);
INSERT INTO user VALUES (13,'Russ Spencer','rsaircraft@yahoo.com','rsaircraft','3117b60c93c75ebdf36feab18cb83044',NULL,NULL,NULL,NULL,NULL,2,0,NULL,NULL,NULL);
INSERT INTO user VALUES (14,'ggross','eightyoctane@yahoo.com','ggross','768a7b74b3f9fe2194fbc17efd1876e6',NULL,NULL,NULL,NULL,NULL,1,0,NULL,NULL,NULL);
INSERT INTO user VALUES (15,'Ron Rugel','hothopper@hotmail.com','hothopper','2b30c2b9255f9359cf91133043cea563',NULL,NULL,NULL,NULL,NULL,2,0,NULL,NULL,NULL);
INSERT INTO user VALUES (16,'Lilian','lfserejo@yahoo.com.br','lilian','0768532aeff369dec923ffd3f0f6ac71',NULL,NULL,NULL,NULL,NULL,3,0,NULL,NULL,NULL);
INSERT INTO user VALUES (17,'level0','eightyoctane@yahoo.com','level0','768a7b74b3f9fe2194fbc17efd1876e6',NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL);
INSERT INTO user VALUES (18,'AJ Gross','stuff969@yahoo.com','aj','d8b05a141fb0a72033efe55578bba96f',NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL);
INSERT INTO user VALUES (19,'Michael Bor','stuff969@yahoo.com','michael','6d6d0bdaee64b6bf567a91a93aa2c4fa',NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL);
INSERT INTO user VALUES (20,'Mike Kent','mikent@mikent.com','mike','468937192bf5d0ebbf4062ba2b5ce3f5',NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL);
INSERT INTO user VALUES (21,'logbooktest','eightyoctane@yahoo.com','logbooktest','768a7b74b3f9fe2194fbc17efd1876e6',NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL);
INSERT INTO user VALUES (22,'Jim Schindler','jimschin@gmail.com','jimschin','81bc6c1574ce0238d63aeac7f4a3f88e',NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL);
INSERT INTO user VALUES (23,'Larry Reece','mr56f100@yahoo.com','larryr','5fea567d36691c813ed6a63a41effff2',NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL);
INSERT INTO user VALUES (24,'Chris Gross','gremlingross@yahoo.com','gremlingross','b84dddcc47f9032af48c96d5c28d24ae',NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL);
INSERT INTO user VALUES (25,'Cheryl Gross','cherylpg2002@yahoo.com','cherylpg2002','915a597515feb07adecd426acd12a2ea',NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL);
INSERT INTO user VALUES (26,'AJ Gross','stuff969@yahoo.com','ajwqr','f3228dc6053cf144d5eeaf968a973de5',NULL,NULL,NULL,NULL,NULL,4,0,NULL,NULL,NULL);
INSERT INTO user VALUES (27,'eightyoctanel','','','768a7b74b3f9fe2194fbc17efd1876e6',NULL,NULL,NULL,NULL,NULL,3,0,NULL,NULL,NULL);
INSERT INTO user VALUES (28,'eightyoctanel','','','768a7b74b3f9fe2194fbc17efd1876e6',NULL,NULL,NULL,NULL,NULL,3,0,NULL,NULL,NULL);

