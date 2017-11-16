<?php
$sql[] ="USE `zgcdb0001`;";
$sql[] ="DROP TABLE IF EXISTS `admin`";
$sql[] ="CREATE TABLE `admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` varchar(50) NOT NULL DEFAULT '1' COMMENT '用户名',
  `password` varchar(64) NOT NULL COMMENT '密码',
  `realname` varchar(50) NOT NULL COMMENT '用户真实姓名',
  `phone_number` varchar(20) NOT NULL COMMENT '联系方式 ',
  `intro` varchar(255) NOT NULL DEFAULT '' COMMENT '用户简介',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态 1：激活 0：冻结',
  `creater` int(10) unsigned NOT NULL COMMENT '创建者',
  `create_ip` varchar(24) NOT NULL,
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updater` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '修改者',
  `update_time` int(11) DEFAULT '0' COMMENT '修改时间',
  `role_id` int(10) unsigned DEFAULT NULL COMMENT '角色id',
  `grade` tinyint(4) unsigned DEFAULT '1' COMMENT '管理员级别',
  `email` varchar(50) DEFAULT NULL COMMENT '邮箱',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UQ_USERNAME` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='管理员表';";

$sql[] ="insert  into `admin`(`id`,`username`,`password`,`realname`,`phone_number`,`intro`,`status`,`creater`,`create_ip`,`create_time`,`updater`,`update_time`,`role_id`,`grade`,`email`) values (1,'admin','$2y$08$Y1N4NnRvTGNZWkRoM3Jxe.4aYU1zW3vfaEaPY0csHVzwidI4Qxncq','柳云龙','18101120979','超级管理员',1,1,'127.0.0.1',2147483647,1,1509439563,1,0,'535319719@qq.com'),(3,'adwind','$2y$08$YU5rMHVRQ3VQRmNid09JUetOO49CmywoTqFkTPZM9yoEiDC2ZJcQy','柳云龙测试','18141923026','第二个描述',1,1,'127.0.0.1',2147483647,1,1509437144,2,1,'535319719@qq.com'),(5,'adwind3','$2y$08$b052NHBoVnM3a1FOWWV5cuM/UIykC10YJA4XXf0GZ/1gxIIlf.PNG','柳云龙','18141923026','第三个描述',1,1,'127.0.0.1',2147483647,1,2147483647,4,1,'535319719@qq.com'),(9,'adwind5','$2y$08$UlBQaUNNNEFSYUJDa05BYOXFBauTnjG0DkqWyatvUqod.xLjyNuPe','柳云龙','18112345678','第四个描述',1,1,'127.0.0.1',2147483647,1,2147483647,1,1,'535319719@qq.com'),(17,'adwind7','$2y$08$L2R6NWF2dXBXSnh1UmxTcuznLnsJEnzMgYPM7Fi67RCOZQe52AVvO','柳云龙7','18141923026','descript',1,1,'127.0.0.1',2147483647,1,1509429113,6,1,'535319719@qq.com'),(19,'test','$2y$08$WXFYOGc5Z1kzNVhaczBiT.Jq9M1QG9Tdf52OqLOujMwxhWUiDMkkm','测试','18624578963','dsadsxxx',1,1,'127.0.0.1',1509428918,1,1509438073,4,1,'dsadsa@qq.com'),(20,'test2','$2y$08$ZG9wb01COG1uSW9JT1I4Nu.BYB4BJtxi6Kme7wLr0mJEuUG6LdfTS','liuyunlong','18101220979','详细描述',1,1,'127.0.0.1',1509438199,1,1509438199,1,1,'535319719@qq.com');";
$sql[] = "DROP TABLE IF EXISTS `menu`;";
$sql[] = "CREATE TABLE `menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '菜单ID',
  `name` varchar(50) NOT NULL COMMENT '菜单名',
  `url` varchar(50) NOT NULL COMMENT '菜单URL',
  `sort` mediumint(9) NOT NULL DEFAULT '999' COMMENT '菜单排序',
  `parent_id` int(10) unsigned NOT NULL COMMENT '父菜单ID',
  `path` varchar(255) NOT NULL COMMENT '菜单路径',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态 0：删除',
  `creater` int(10) unsigned NOT NULL COMMENT '创建者',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updater` int(10) unsigned NOT NULL COMMENT '修改者',
  `create_ip` varchar(24) DEFAULT NULL,
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `model` char(20) DEFAULT NULL COMMENT '模型',
  `controller` char(20) DEFAULT NULL COMMENT '控制器',
  `action` char(20) DEFAULT NULL COMMENT '操作方法',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8 COMMENT='菜单表';";
$sql[] = "insert  into `menu`(`id`,`name`,`url`,`sort`,`parent_id`,`path`,`status`,`creater`,`create_time`,`updater`,`create_ip`,`update_time`,`model`,`controller`,`action`) values (6,'区块链','/cat',999,0,'/0/',0,1,2147483647,1,NULL,2147483647,'admin','','cat'),(7,'订单管理','/orders/index',998,0,'/0/',1,1,2147483647,1,NULL,2147483647,'admin','orders','index'),(8,'后台管理','#',999,0,'/0/',0,1,2147483647,1,NULL,2147483647,'admin','',''),(9,'二级菜单','/admin/index/index',12,0,'/0/',0,1,2147483647,1,NULL,2147483647,'admin','index','index'),(10,'区块链次级菜单','/index/index3',12,0,'/0/',0,1,2147483647,1,NULL,2147483647,'admin','index','index3'),(11,'管理组','/index/index',1,0,'/0/',1,1,2147483647,1,NULL,1509439171,'admin','index','index'),(12,'角色管理','role/index',3,11,'/0/11/',1,1,2147483647,1,NULL,1509420067,'admin','role','index'),(13,'次次级管理','index/index',3,12,'/0/11/12/',0,1,2147483647,1,NULL,2147483647,'admin','index','index'),(14,'权限管理','rights/index',3,11,'/0/11/',0,1,2147483647,1,NULL,2147483647,'admin','rights','index'),(15,'权限管理','rights/index',123,11,'/0/11/',0,1,2147483647,1,NULL,2147483647,'admin','rights','index'),(16,'用户管理','/index/index',213,0,'/0/',0,1,2147483647,1,NULL,2147483647,'admin','index','index'),(17,'12','/index/index',12,12,'/0/11/12/',0,1,2147483647,1,NULL,2147483647,'admin','index','index'),(18,'二级菜单的菜单','index/index1',5,0,'/0/',0,1,2147483647,1,NULL,2147483647,'admin','index','index1'),(19,'二级菜单第二个菜单','index/index2',7,0,'/0/',0,1,2147483647,1,NULL,2147483647,'admin','index','index2'),(20,'管理员管理','admin/index',99,11,'/0/11/',1,1,2147483647,1,NULL,1509420474,'admin','admin','index'),(21,'订单管理','/index/index',9,0,'/0/',0,1,2147483647,1,NULL,2147483647,'admin','index','index'),(22,'添加用户','users/add',10,20,'/0/11/20/',0,1,2147483647,1,NULL,2147483647,'admin','users','add'),(23,'订车管理','car/index',999,7,'/0/7/',1,1,2147483647,1,NULL,2147483647,'admin','car','index'),(24,'添加菜单','/cat/add',456,23,'/0/7/23/',0,1,2147483647,1,NULL,2147483647,'admin','cat','add'),(25,'小程序头登陆管理','/image/index',999,0,'/0/',0,3,2147483647,1,NULL,2147483647,'admin','image','index'),(26,'修改订单','/orders/update',999,23,'/0/7/23/',1,1,2147483647,1,NULL,2147483647,'admin','orders','update'),(27,'删除订单','/orders/delete',999,23,'/0/7/23/',1,1,2147483647,1,NULL,2147483647,'admin','orders','delete'),(28,'权限添加','/role/add',999,12,'/0/11/12/',1,1,2147483647,1,NULL,1509437597,'admin','role','add'),(29,'权限保存','/role/save',137,12,'/0/11/12/',1,1,2147483647,1,NULL,1509436651,'admin','role','save'),(30,'测试菜单','/test/test',123,0,'/0/',0,1,2147483647,1,NULL,2147483647,'admin','test','test'),(31,'测试二级菜单','test/index',123,0,'/0/',0,1,2147483647,1,NULL,2147483647,'admin','test','index'),(32,'角色添加','role/addRole',123,12,'/0/11/12/',1,1,2147483647,1,NULL,2147483647,'admin','role','addRole'),(33,'角色保存','role/saveRole',123,12,'/0/11/12/',1,1,2147483647,1,NULL,1509430186,'admin','role','saveRole'),(34,'管理员添加','admin/add',123,20,'/0/11/20/',1,1,2147483647,1,NULL,1509420497,'admin','admin','add'),(35,'管理员编辑','admin/edit',122,20,'/0/11/20/',1,1,2147483647,1,NULL,1509430179,'admin','admin','edit');";
$sql[] = "DROP TABLE IF EXISTS `migration`;";
$sql[] = "CREATE TABLE `migration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(64) NOT NULL,
  `version` varchar(64) NOT NULL,
  `run_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;";
$sql[] = "DROP TABLE IF EXISTS `role`;";
$sql[] = "CREATE TABLE `role` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL COMMENT '分组名称',
  `purviewids` text COMMENT '显示权限id',
  `description` varchar(255) NOT NULL COMMENT '描述',
  `isupdate` tinyint(1) unsigned DEFAULT '1',
  `listorder` tinyint(4) unsigned DEFAULT '99' COMMENT '排序',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  `create_time` int(11) NOT NULL,
  `creater` int(11) NOT NULL,
  `create_ip` varchar(24) NOT NULL,
  `updater` int(11) DEFAULT '0',
  `update_time` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;";
$sql[] = "insert  into `role`(`id`,`title`,`purviewids`,`description`,`isupdate`,`listorder`,`status`,`create_time`,`creater`,`create_ip`,`updater`,`update_time`) values (1,'系统管理员','11,32','描述信息',1,99,0,0,0,'',0,1509437081),(2,'销售管理员','11,12,28,32,20,27','智能管理销售',1,99,1,0,0,'',0,1509437174),(4,'辅助管理员','12,28,32,33,20,31','辅助管理员管理',1,99,1,1509348059,1,'192.168.1.81',0,0),(6,'新角色','7,23','新角色管理',1,99,1,1509355333,1,'192.168.1.81',0,0),(9,'测试发信11','12,29,28,20,34','23',1,99,1,1509428039,1,'127.0.0.1',1,1509428039),(10,'是的撒的',NULL,'1111333',1,99,1,1509428514,1,'127.0.0.1',1,1509428514);";



$sql[] = "DROP TABLE IF EXISTS `options`;";
$sql[] = "CREATE TABLE `options` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `op_key` varchar(50) NOT NULL COMMENT '配置key',
  `op_value` varchar(50) NOT NULL DEFAULT '' COMMENT '配置value',
  `create_by` int(10) unsigned NOT NULL COMMENT '创建者',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `modify_by` int(10) unsigned NOT NULL COMMENT '修改者',
  `modify_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UQ_OP_KEY` (`op_key`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='配置表';";
$sql[] = "insert  into `options`(`id`,`op_key`,`op_value`,`create_by`,`create_time`,`modify_by`,`modify_time`) values (1,'site_name','小程序',1,'2016-11-28 10:48:58',1,'2017-10-26 14:24:54'),(2,'site_url','http://www.adwind.cn',1,'2016-11-28 10:49:20',1,'2017-10-30 15:39:15'),(3,'site_description','描述',1,'2016-11-28 10:49:33',1,'2016-11-28 10:53:10'),(4,'site_keywords','关键字',1,'2016-11-28 10:49:45',1,'2016-11-28 10:53:10'),(5,'page_article_number','10',1,'2016-11-28 11:05:10',1,'2016-12-29 16:11:46'),(6,'recommend_article_number','10',1,'2016-11-28 11:05:19',1,'2016-12-29 16:11:43'),(7,'site_title','标题',1,'2016-12-01 11:54:17',1,'2016-12-01 12:01:33'),(8,'relate_article_number','8',1,'2016-12-21 10:00:38',1,'2016-12-21 10:00:38'),(9,'cdn_url','',1,'2016-12-22 12:16:41',1,'2016-12-24 15:51:59');";