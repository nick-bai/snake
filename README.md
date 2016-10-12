# snake
thinkphp5做的通用系统改后台

目前完成的功能有:

后台管理员的增删改查

角色的增删改查

权限的分配

数据库的备份与还原

# 使用方法
配置好项目路径，将代码放入

运行根目录下的snake.sql文件进行数据还原

在浏览器上输入 你的地址/admin

管理员是: admin
密码是: admin

可以自己进行修改  
# 注意事项：  
2016.10.12 19:34分,已经讲系统升级到了thinkphp5.0.1,若有发现问题，请积极反馈。谢谢  

# 数据库脚本
/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50547
Source Host           : localhost:3306
Source Database       : snake

Target Server Type    : MYSQL
Target Server Version : 50547
File Encoding         : 65001

Date: 2016-09-07 22:44:16
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for snake_node
-- ----------------------------
DROP TABLE IF EXISTS `snake_node`;  
CREATE TABLE `snake_node` (  
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `node_name` varchar(155) NOT NULL DEFAULT '' COMMENT '节点名称',  
  `module_name` varchar(155) NOT NULL DEFAULT '' COMMENT '模块名',  
  `control_name` varchar(155) NOT NULL DEFAULT '' COMMENT '控制器名',  
  `action_name` varchar(155) NOT NULL COMMENT '方法名',  
  `is_menu` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否是菜单项 1不是 2是',  
  `typeid` int(11) NOT NULL COMMENT '父级节点id',  
  `style` varchar(155) DEFAULT '' COMMENT '菜单样式',  
  PRIMARY KEY (`id`)  
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;  

-- ----------------------------
-- Records of snake_node
-- ----------------------------
INSERT INTO `snake_node` VALUES ('1', '用户管理', '#', '#', '#', '2', '0', 'fa fa-users');  
INSERT INTO `snake_node` VALUES ('2', '用户列表', 'admin', 'user', 'index', '2', '1', '');  
INSERT INTO `snake_node` VALUES ('3', '添加用户', 'admin', 'user', 'useradd', '1', '2', '');  
INSERT INTO `snake_node` VALUES ('4', '编辑用户', 'admin', 'user', 'useredit', '1', '2', '');  
INSERT INTO `snake_node` VALUES ('5', '删除用户', 'admin', 'user', 'userdel', '1', '2', '');  
INSERT INTO `snake_node` VALUES ('6', '角色列表', 'admin', 'role', 'index', '2', '1', '');  
INSERT INTO `snake_node` VALUES ('7', '添加角色', 'admin', 'role', 'roleadd', '1', '6', '');  
INSERT INTO `snake_node` VALUES ('8', '编辑角色', 'admin', 'role', 'roleedit', '1', '6', '');  
INSERT INTO `snake_node` VALUES ('9', '删除角色', 'admin', 'role', 'roledel', '1', '6', '');  
INSERT INTO `snake_node` VALUES ('10', '分配权限', 'admin', 'role', 'giveaccess', '1', '6', '');  
INSERT INTO `snake_node` VALUES ('11', '系统管理', '#', '#', '#', '2', '0', 'fa fa-desktop');  
INSERT INTO `snake_node` VALUES ('12', '数据备份/还原', 'admin', 'data', 'index', '2', '11', '');  
INSERT INTO `snake_node` VALUES ('13', '备份数据', 'admin', 'data', 'importdata', '1', '12', '');  
INSERT INTO `snake_node` VALUES ('14', '还原数据', 'admin', 'data', 'backdata', '1', '12', '');  

-- ----------------------------
-- Table structure for snake_role
-- ----------------------------
DROP TABLE IF EXISTS `snake_role`;  
CREATE TABLE `snake_role` (  
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',  
  `rolename` varchar(155) NOT NULL COMMENT '角色名称',  
  `rule` varchar(255) DEFAULT '' COMMENT '权限节点数据',  
  PRIMARY KEY (`id`)  
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;  

-- ----------------------------
-- Records of snake_role
-- ----------------------------
INSERT INTO `snake_role` VALUES ('1', '超级管理员', '');  
INSERT INTO `snake_role` VALUES ('2', '系统维护员', '1,2,3,4,5,6,7,8,9,10');  
INSERT INTO `snake_role` VALUES ('3', '新闻发布员', '1,2,3,4,5');  

-- ----------------------------
-- Table structure for snake_user
-- ----------------------------
DROP TABLE IF EXISTS `snake_user`;  
CREATE TABLE `snake_user` (  
  `id` int(11) NOT NULL AUTO_INCREMENT,  
  `username` varchar(255) COLLATE utf8_bin DEFAULT '' COMMENT '用户名',  
  `password` varchar(255) COLLATE utf8_bin DEFAULT '' COMMENT '密码',  
  `loginnum` int(11) DEFAULT '0' COMMENT '登陆次数',  
  `last_login_ip` varchar(255) COLLATE utf8_bin DEFAULT '' COMMENT '最后登录IP',  
  `last_login_time` int(11) DEFAULT '0' COMMENT '最后登录时间',  
  `real_name` varchar(255) COLLATE utf8_bin DEFAULT '' COMMENT '真实姓名',  
  `status` int(1) DEFAULT '0' COMMENT '状态',  
  `typeid` int(11) DEFAULT '1' COMMENT '用户角色id',  
  PRIMARY KEY (`id`)  
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;  

-- ----------------------------
-- Records of snake_user
-- ----------------------------
INSERT INTO `snake_user` VALUES ('1', 'admin', '21232f297a57a5a743894a0e4a801fc3', '64', '127.0.0.1', '1473228905', 'admin', '1', '1');  
INSERT INTO `snake_user` VALUES ('2', 'xiaobai', '4297f44b13955235245b2497399d7a93', '6', '127.0.0.1', '1470368260', '小白', '1', '2');  


