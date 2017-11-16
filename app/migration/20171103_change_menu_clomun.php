<?php
$sql[] ='ALTER TABLE `zgcdb0001`.`menu`   
  CHANGE `model` `model` VARCHAR(20) CHARSET utf8 COLLATE utf8_general_ci NULL  COMMENT '模型',
  CHANGE `controller` `controller` VARCHAR(20) CHARSET utf8 COLLATE utf8_general_ci NULL  COMMENT '控制器',
  CHANGE `action` `action` VARCHAR(20) CHARSET utf8 COLLATE utf8_general_ci NULL  COMMENT '操作方法',
  ADD COLUMN `level` TINYINT(1) DEFAULT 3  NULL  COMMENT '层级' AFTER `action`;';