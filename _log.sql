/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50138
Source Host           : localhost:3306
Source Database       : torrent

Target Server Type    : MYSQL
Target Server Version : 50138
File Encoding         : 65001

Date: 2011-09-14 10:54:39
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `_log`
-- ----------------------------
DROP TABLE IF EXISTS `_log`;
CREATE TABLE `_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) DEFAULT NULL,
  `text` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of _log
-- ----------------------------
