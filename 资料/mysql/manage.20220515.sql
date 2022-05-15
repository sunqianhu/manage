/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50529
Source Host           : 127.0.0.1:3306
Source Database       : manage

Target Server Type    : MYSQL
Target Server Version : 50529
File Encoding         : 65001

Date: 2022-05-15 16:50:49
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for department
-- ----------------------------
DROP TABLE IF EXISTS `department`;
CREATE TABLE `department` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级id',
  `parent_ids` varchar(255) NOT NULL DEFAULT '' COMMENT '所有父部门id',
  `name` varchar(64) NOT NULL DEFAULT '' COMMENT '部门名称',
  `sort` int(255) NOT NULL DEFAULT '0' COMMENT '排序',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `level` int(255) unsigned NOT NULL DEFAULT '0' COMMENT '级别',
  `time_add` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `time_update` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COMMENT='部门';

-- ----------------------------
-- Records of department
-- ----------------------------
INSERT INTO `department` VALUES ('1', '0', '1', '顶级部门', '1', '顶级部门', '1', '1652350351', '1652350351');
INSERT INTO `department` VALUES ('2', '1', '1,2', '部门1', '1', '', '2', '1652350440', '1652350440');
INSERT INTO `department` VALUES ('5', '2', '1,2,5', '部门1_1', '0', '', '3', '1652436414', '1652436414');
INSERT INTO `department` VALUES ('6', '2', '1,2,6', '部门1_1', '0', '', '3', '1652436424', '1652436424');
INSERT INTO `department` VALUES ('7', '1', '1,7', '部门2', '1', '', '2', '1652498942', '1652498942');
INSERT INTO `department` VALUES ('8', '7', '1,7,8', '部门2_1', '1', '', '3', '1652498962', '1652498962');
INSERT INTO `department` VALUES ('9', '8', '1,7,8,9', '部门2_1_1', '1', '备注', '4', '1652499142', '1652499142');

-- ----------------------------
-- Table structure for menu
-- ----------------------------
DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级菜单',
  `type` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '类型',
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '名称',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `level` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '级别',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='菜单';

-- ----------------------------
-- Records of menu
-- ----------------------------
INSERT INTO `menu` VALUES ('1', '0', '1', '顶级菜单', '1', '1');

-- ----------------------------
-- Table structure for menu_controller
-- ----------------------------
DROP TABLE IF EXISTS `menu_controller`;
CREATE TABLE `menu_controller` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `menu_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '菜单id',
  `name` varchar(128) NOT NULL DEFAULT '' COMMENT '控制器名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='菜单控制器';

-- ----------------------------
-- Records of menu_controller
-- ----------------------------

-- ----------------------------
-- Table structure for permission
-- ----------------------------
DROP TABLE IF EXISTS `permission`;
CREATE TABLE `permission` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '权限id',
  `name` varchar(64) NOT NULL DEFAULT '' COMMENT '名称',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `edit_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '编辑时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='权限';

-- ----------------------------
-- Records of permission
-- ----------------------------

-- ----------------------------
-- Table structure for role
-- ----------------------------
DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '角色id',
  `name` varchar(64) NOT NULL COMMENT '名称',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `edit_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '编辑时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='角色';

-- ----------------------------
-- Records of role
-- ----------------------------

-- ----------------------------
-- Table structure for role_permission
-- ----------------------------
DROP TABLE IF EXISTS `role_permission`;
CREATE TABLE `role_permission` (
  `role_id` int(10) unsigned NOT NULL COMMENT '角色id',
  `permission_id` int(10) unsigned NOT NULL COMMENT '权限id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='角色权限关联';

-- ----------------------------
-- Records of role_permission
-- ----------------------------

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `username` varchar(64) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(64) NOT NULL DEFAULT '' COMMENT '密码',
  `name` varchar(64) NOT NULL COMMENT '姓名',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `super` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '超级管理员',
  `time_add` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `time_update` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后修改时间',
  `time_login` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='用户';

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', 'admin', 'e10adc3949ba59abbe56e057f20f883e', '孙乾户', '1', '1', '111111', '1111111', '0');

-- ----------------------------
-- Table structure for user_role
-- ----------------------------
DROP TABLE IF EXISTS `user_role`;
CREATE TABLE `user_role` (
  `user_id` int(10) unsigned NOT NULL COMMENT '用户id',
  `role_id` int(10) unsigned NOT NULL COMMENT '角色id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户角色关联';

-- ----------------------------
-- Records of user_role
-- ----------------------------
