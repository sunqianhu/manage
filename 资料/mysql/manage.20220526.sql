/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50529
Source Host           : 127.0.0.1:3306
Source Database       : manage

Target Server Type    : MYSQL
Target Server Version : 50529
File Encoding         : 65001

Date: 2022-05-27 18:18:03
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COMMENT='部门';

-- ----------------------------
-- Records of department
-- ----------------------------
INSERT INTO `department` VALUES ('1', '0', '1', '顶级部门', '1', '顶级部门');
INSERT INTO `department` VALUES ('2', '1', '1,2', '部门1', '1', '');
INSERT INTO `department` VALUES ('5', '2', '1,2,5', '部门1_1', '0', '');
INSERT INTO `department` VALUES ('6', '2', '1,2,6', '部门1_1', '0', '');
INSERT INTO `department` VALUES ('7', '1', '1,7', '部门2', '1', '');
INSERT INTO `department` VALUES ('8', '7', '1,7,8', '部门2_1', '1', '');
INSERT INTO `department` VALUES ('9', '8', '1,7,8,9', '部门2_1_1', '1', '备注');
INSERT INTO `department` VALUES ('10', '9', '1,7,8,9,10', '部门2_1_1_1', '1', '');
INSERT INTO `department` VALUES ('14', '2', '1,2,14', '部门1_3', '3', '');
INSERT INTO `department` VALUES ('17', '1', '1,17', '部门3', '3', '');

-- ----------------------------
-- Table structure for dictionary
-- ----------------------------
DROP TABLE IF EXISTS `dictionary`;
CREATE TABLE `dictionary` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `type` varchar(64) NOT NULL DEFAULT '' COMMENT '类型',
  `key` varchar(64) NOT NULL DEFAULT '' COMMENT '键',
  `value` varchar(128) NOT NULL DEFAULT '' COMMENT '值',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`),
  KEY `type` (`type`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COMMENT='字典';

-- ----------------------------
-- Records of dictionary
-- ----------------------------
INSERT INTO `dictionary` VALUES ('1', 'system_menu_type', '1', '目录', '1');
INSERT INTO `dictionary` VALUES ('4', 'system_menu_type', '2', '菜单', '2');
INSERT INTO `dictionary` VALUES ('5', 'system_menu_type', '3', '按钮', '3');

-- ----------------------------
-- Table structure for menu
-- ----------------------------
DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级菜单',
  `parent_ids` varchar(255) NOT NULL DEFAULT '' COMMENT '所有父部门id',
  `type` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '类型',
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '名称',
  `url` varchar(1024) NOT NULL DEFAULT '' COMMENT 'url',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COMMENT='菜单';

-- ----------------------------
-- Records of menu
-- ----------------------------
INSERT INTO `menu` VALUES ('1', '0', '', '1', '顶级菜单', '', '1');
INSERT INTO `menu` VALUES ('2', '1', ',2', '1', '系统管理', '', '1');
INSERT INTO `menu` VALUES ('3', '2', ',2,3', '2', '用户管理', 'system/user/user.html', '1');
INSERT INTO `menu` VALUES ('4', '2', ',2,4', '2', '部门管理', 'system/department/department.html', '2');
INSERT INTO `menu` VALUES ('5', '2', ',2,5', '1', '角色管理', 'system/role/role.html', '1');
INSERT INTO `menu` VALUES ('6', '4', ',2,4,6', '3', '添加部门', 'system/department/department-add.html\r\nsystem/department/department-add_select_department.html\r\nsystem/department/department-add_save.json', '1');
INSERT INTO `menu` VALUES ('7', '4', ',2,4,7', '3', '修改部门', 'system/department/department-edit.html\r\nsystem/department/department-edit_select_department.html\r\nsystem/department/department-edit_save.json', '2');
INSERT INTO `menu` VALUES ('8', '4', ',2,4,8', '3', '删除部门', 'system/department/department-delete.html', '3');

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
  `name` varchar(64) NOT NULL DEFAULT '' COMMENT '名称',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `time_add` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `time_edit` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='角色';

-- ----------------------------
-- Records of role
-- ----------------------------
INSERT INTO `role` VALUES ('1', '超级管理员', '全部权限', '1653557735', '1653644537');
INSERT INTO `role` VALUES ('2', '普通管理员', '', '1653557917', '1653644600');

-- ----------------------------
-- Table structure for role_menu
-- ----------------------------
DROP TABLE IF EXISTS `role_menu`;
CREATE TABLE `role_menu` (
  `role_id` int(10) unsigned NOT NULL COMMENT '角色id',
  `menu_id` int(10) unsigned NOT NULL COMMENT '权限id',
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='角色权限关联';

-- ----------------------------
-- Records of role_menu
-- ----------------------------
INSERT INTO `role_menu` VALUES ('1', '2');
INSERT INTO `role_menu` VALUES ('1', '3');
INSERT INTO `role_menu` VALUES ('1', '4');
INSERT INTO `role_menu` VALUES ('1', '6');
INSERT INTO `role_menu` VALUES ('1', '7');
INSERT INTO `role_menu` VALUES ('1', '8');
INSERT INTO `role_menu` VALUES ('1', '5');
INSERT INTO `role_menu` VALUES ('2', '2');
INSERT INTO `role_menu` VALUES ('2', '3');

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
