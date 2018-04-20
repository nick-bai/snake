SET FOREIGN_KEY_CHECKS=0;
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

INSERT INTO `snake_node` VALUES ('1', '用户管理', '#', '#', '2', '0', 'fa fa-users');
INSERT INTO `snake_node` VALUES ('2', '管理员管理', 'user', 'index', '2', '1', '');
INSERT INTO `snake_node` VALUES ('3', '添加管理员', 'user', 'useradd', '1', '2', '');
INSERT INTO `snake_node` VALUES ('4', '编辑管理员', 'user', 'useredit', '1', '2', '');
INSERT INTO `snake_node` VALUES ('5', '删除管理员', 'user', 'userdel', '1', '2', '');
INSERT INTO `snake_node` VALUES ('6', '角色管理', 'role', 'index', '2', '1', '');
INSERT INTO `snake_node` VALUES ('7', '添加角色', 'role', 'roleadd', '1', '6', '');
INSERT INTO `snake_node` VALUES ('8', '编辑角色', 'role', 'roleedit', '1', '6', '');
INSERT INTO `snake_node` VALUES ('9', '删除角色', 'role', 'roledel', '1', '6', '');
INSERT INTO `snake_node` VALUES ('10', '分配权限', 'role', 'giveaccess', '1', '6', '');
INSERT INTO `snake_node` VALUES ('11', '系统管理', '#', '#', '2', '0', 'fa fa-desktop');
INSERT INTO `snake_node` VALUES ('12', '数据备份/还原', 'data', 'index', '2', '11', '');
INSERT INTO `snake_node` VALUES ('13', '备份数据', 'data', 'importdata', '1', '12', '');
INSERT INTO `snake_node` VALUES ('14', '还原数据', 'data', 'backdata', '1', '12', '');
INSERT INTO `snake_node` VALUES ('15', '节点管理', 'node', 'index', '2', '1', '');
INSERT INTO `snake_node` VALUES ('16', '添加节点', 'node', 'nodeadd', '1', '15', '');
INSERT INTO `snake_node` VALUES ('17', '编辑节点', 'node', 'nodeedit', '1', '15', '');
INSERT INTO `snake_node` VALUES ('18', '删除节点', 'node', 'nodedel', '1', '15', '');
INSERT INTO `snake_node` VALUES ('19', '文章管理', 'articles', 'index', '2', '0', 'fa fa-book');
INSERT INTO `snake_node` VALUES ('20', '文章列表', 'articles', 'index', '2', '19', '');
INSERT INTO `snake_node` VALUES ('21', '添加文章', 'articles', 'articleadd', '1', '19', '');
INSERT INTO `snake_node` VALUES ('22', '编辑文章', 'articles', 'articleedit', '1', '19', '');
INSERT INTO `snake_node` VALUES ('23', '删除文章', 'articles', 'articledel', '1', '19', '');
INSERT INTO `snake_node` VALUES ('24', '上传图片', 'articles', 'uploadImg', '1', '19', '');
INSERT INTO `snake_node` VALUES ('25', '个人中心', '#', '#', '1', '0', '');
INSERT INTO `snake_node` VALUES ('26', '编辑信息', 'profile', 'index', '1', '25', '');
INSERT INTO `snake_node` VALUES ('27', '编辑头像', 'profile', 'headedit', '1', '25', '');
INSERT INTO `snake_node` VALUES ('28', '上传头像', 'profile', 'uploadheade', '1', '25', '');
