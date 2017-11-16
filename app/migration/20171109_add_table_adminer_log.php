<?php
$sql[] ="CREATE TABLE `adminer_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) DEFAULT '0',
  `user_id` int(11) DEFAULT NULL,
  `username` varchar(200) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `query` text,
  `remark` varchar(200) DEFAULT NULL,
  `creater` int(11) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `updater` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `create_ip` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='utf8_general_ci'";