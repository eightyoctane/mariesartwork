-- phpMyAdmin SQL Dump
-- version 2.10.1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Feb 28, 2008 at 06:35 PM
-- Server version: 5.0.45
-- PHP Version: 5.2.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Database: `db_todolist`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `tdl_items`
-- 

CREATE TABLE `tdl_items` (
  `id` int(11) NOT NULL auto_increment,
  `item_desc` text NOT NULL,
  `item_tags` text NOT NULL,
  `item_priority` int(11) NOT NULL,
  `item_added` varchar(45) NOT NULL,
  `item_status` int(10) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

-- 
-- Dumping data for table `tdl_items`
-- 

INSERT INTO `tdl_items` (`id`, `item_desc`, `item_tags`, `item_priority`, `item_added`, `item_status`) VALUES 
(7, 'my new item', '', 2, 'February 28, 2008, 2:23 pm', 0),
(8, 'my new item 2', '', 2, 'February 28, 2008, 2:24 pm', 0),
(9, 'call bob about presentation', '', 2, 'February 28, 2008, 2:41 pm', 0),
(10, 'call jason about house', '', 1, 'February 28, 2008, 2:43 pm', 0),
(13, 'call and meet daddy', '', 0, 'February 28, 2008, 4:07 pm', 0),
(15, 'My New Item', '', 0, 'February 28, 2008, 6:09 pm', 2),
(16, 'Another item', '', 0, 'February 28, 2008, 6:10 pm', 2),
(17, 'call bob about presentation', '', 0, 'February 28, 2008, 6:26 pm', 1),
(18, 'go see grandma', '', 0, 'February 28, 2008, 6:26 pm', 1),
(19, 'prepare for meeting', '', 0, 'February 28, 2008, 6:27 pm', 1),
(20, 'order tickets for movie', '', 0, 'February 28, 2008, 6:27 pm', 1);

-- --------------------------------------------------------

-- 
-- Table structure for table `tdl_tags`
-- 

CREATE TABLE `tdl_tags` (
  `tags_id` int(11) NOT NULL auto_increment,
  `tags_tags` text NOT NULL,
  `id_item` int(11) NOT NULL,
  `item_status` int(10) NOT NULL,
  PRIMARY KEY  (`tags_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=40 ;

-- 
-- Dumping data for table `tdl_tags`
-- 

INSERT INTO `tdl_tags` (`tags_id`, `tags_tags`, `id_item`, `item_status`) VALUES 
(3, 'tag3', 2, 0),
(4, 'tag1', 8, 0),
(5, 'tag2', 8, 0),
(6, 'tag3', 8, 0),
(7, 'bob', 9, 0),
(8, 'work', 9, 0),
(9, 'calls', 9, 0),
(10, 'calls', 10, 0),
(11, 'jason', 10, 0),
(12, 'house', 10, 0),
(19, 'new', 7, 0),
(20, 'items', 7, 0),
(21, 'my', 7, 0),
(22, 'calls', 13, 0),
(23, 'daddy', 13, 0),
(27, 'items', 15, 2),
(28, 'test', 15, 2),
(29, 'items', 16, 2),
(30, 'another', 16, 2),
(31, 'bob', 17, 1),
(32, 'work', 17, 1),
(33, 'calls', 17, 1),
(34, 'go', 18, 1),
(35, 'grandma', 18, 1),
(36, 'work', 19, 1),
(37, 'computer', 19, 1),
(38, 'computer', 20, 1),
(39, 'family', 20, 1);
