/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50529
Source Host           : 127.0.0.1:3306
Source Database       : manage

Target Server Type    : MYSQL
Target Server Version : 50529
File Encoding         : 65001

Date: 2022-06-10 17:46:44
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
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COMMENT='部门';

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COMMENT='字典';

-- ----------------------------
-- Records of dictionary
-- ----------------------------
INSERT INTO `dictionary` VALUES ('1', 'system_menu_type', '1', '目录', '1');
INSERT INTO `dictionary` VALUES ('4', 'system_menu_type', '2', '菜单', '2');
INSERT INTO `dictionary` VALUES ('5', 'system_menu_type', '3', '权限', '3');
INSERT INTO `dictionary` VALUES ('6', 'system_user_status', '1', '启用', '1');
INSERT INTO `dictionary` VALUES ('7', 'system_user_status', '2', '停用', '2');

-- ----------------------------
-- Table structure for login_log
-- ----------------------------
DROP TABLE IF EXISTS `login_log`;
CREATE TABLE `login_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `department_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '部门id',
  `time_login` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录时间',
  `ip` varchar(32) NOT NULL DEFAULT '' COMMENT '登录ip',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COMMENT='登录日志';

-- ----------------------------
-- Records of login_log
-- ----------------------------
INSERT INTO `login_log` VALUES ('5', '7', '1', '1654851609', '127.0.0.1');

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
  `tag` varchar(64) NOT NULL DEFAULT '' COMMENT '菜单标识',
  `icon_class` varchar(64) NOT NULL DEFAULT '' COMMENT '图标class',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '导航URL',
  `permission` varchar(64) NOT NULL DEFAULT '' COMMENT '权限标识',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COMMENT='菜单';

-- ----------------------------
-- Records of menu
-- ----------------------------
INSERT INTO `menu` VALUES ('1', '0', '', '1', '顶级菜单', '', '', '', '', '1');
INSERT INTO `menu` VALUES ('2', '1', ',2', '1', '系统管理', 'system', 'iconfont icon-user', '', 'system', '1');
INSERT INTO `menu` VALUES ('3', '2', ',2,3', '2', '用户管理', 'system_user', '', 'system/user/index.php', 'system_user', '1');
INSERT INTO `menu` VALUES ('4', '2', ',2,4', '2', '部门管理', 'system_department', '', 'system/department/index.php', 'system_department', '2');
INSERT INTO `menu` VALUES ('5', '2', ',2,5', '2', '角色管理', 'system_role', '', 'system/role/index.php', 'system_role', '1');
INSERT INTO `menu` VALUES ('9', '2', ',2,9', '2', '菜单管理', 'system_menu', '', 'system/menu/index.php', 'system_menu', '1');
INSERT INTO `menu` VALUES ('11', '2', ',2,11', '2', '字典管理', 'system_dictionary', '', 'system/dictionary/index.php', 'system_dictionary', '4');
INSERT INTO `menu` VALUES ('12', '2', ',2,12', '2', '登录日志', 'system_login_log', '', 'system/login_log/index.php', 'system_login_log', '5');
INSERT INTO `menu` VALUES ('14', '2', ',2,14', '2', '操作日志', 'system_operation_log', '', 'system/operation_log/index.php', 'system_operation_log', '6');

-- ----------------------------
-- Table structure for operation_log
-- ----------------------------
DROP TABLE IF EXISTS `operation_log`;
CREATE TABLE `operation_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `department_id` int(64) NOT NULL DEFAULT '0' COMMENT '部门id',
  `url` text NOT NULL COMMENT 'url',
  `ip` varchar(32) NOT NULL DEFAULT '' COMMENT '登录ip',
  `request` text NOT NULL COMMENT '请求内容',
  `response` text NOT NULL COMMENT '响应内容',
  `time_add` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '操作时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COMMENT='操作日志';

-- ----------------------------
-- Records of operation_log
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='角色';

-- ----------------------------
-- Records of role
-- ----------------------------
INSERT INTO `role` VALUES ('1', '超级管理员', '全部权限', '1653557735', '1654850831');
INSERT INTO `role` VALUES ('2', '普通管理员', '', '1653557917', '1654592252');

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
INSERT INTO `role_menu` VALUES ('2', '2');
INSERT INTO `role_menu` VALUES ('2', '3');
INSERT INTO `role_menu` VALUES ('1', '2');
INSERT INTO `role_menu` VALUES ('1', '3');
INSERT INTO `role_menu` VALUES ('1', '4');
INSERT INTO `role_menu` VALUES ('1', '5');
INSERT INTO `role_menu` VALUES ('1', '9');
INSERT INTO `role_menu` VALUES ('1', '11');
INSERT INTO `role_menu` VALUES ('1', '12');
INSERT INTO `role_menu` VALUES ('1', '14');

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `department_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '部门id',
  `role_id_string` varchar(255) NOT NULL DEFAULT '' COMMENT '角色id串',
  `username` varchar(64) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(64) NOT NULL DEFAULT '' COMMENT '密码',
  `name` varchar(64) NOT NULL DEFAULT '' COMMENT '姓名',
  `phone` varchar(64) NOT NULL DEFAULT '' COMMENT '手机号码',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `time_add` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `time_edit` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后修改时间',
  `time_login` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `ip` varchar(32) NOT NULL DEFAULT '' COMMENT '登录ip',
  PRIMARY KEY (`id`),
  KEY `department_id` (`department_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='用户';

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('6', '1', '1,2', 'admin', 'a06f5cdb68839c63228059340f609f5e', '超级管理员', '12345678911', '1', '1653709625', '1654483741', '0', '');
INSERT INTO `user` VALUES ('7', '1', '1', '15108273576', 'a06f5cdb68839c63228059340f609f5e', '孙乾户', '15108273576', '1', '1653783552', '1654595539', '1654851609', '127.0.0.1');
INSERT INTO `user` VALUES ('8', '1', '2', '18781933732', 'bfb0e640376eb36ae75a8bf1e2106e34', '唐琴梅', '18781933732', '2', '1653783812', '1654595762', '0', '');

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
