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

insert into `snake_node`(`id`,`node_name`,`module_name`,`control_name`,`action_name`,`is_menu`,`typeid`,`style`) values('1','用户管理','#','#','#','2','0','fa fa-users');
insert into `snake_node`(`id`,`node_name`,`module_name`,`control_name`,`action_name`,`is_menu`,`typeid`,`style`) values('2','用户列表','admin','user','index','2','1','');
insert into `snake_node`(`id`,`node_name`,`module_name`,`control_name`,`action_name`,`is_menu`,`typeid`,`style`) values('3','添加用户','admin','user','useradd','1','2','');
insert into `snake_node`(`id`,`node_name`,`module_name`,`control_name`,`action_name`,`is_menu`,`typeid`,`style`) values('4','编辑用户','admin','user','useredit','1','2','');
insert into `snake_node`(`id`,`node_name`,`module_name`,`control_name`,`action_name`,`is_menu`,`typeid`,`style`) values('5','删除用户','admin','user','userdel','1','2','');
insert into `snake_node`(`id`,`node_name`,`module_name`,`control_name`,`action_name`,`is_menu`,`typeid`,`style`) values('6','角色列表','admin','role','index','2','1','');
insert into `snake_node`(`id`,`node_name`,`module_name`,`control_name`,`action_name`,`is_menu`,`typeid`,`style`) values('7','添加角色','admin','role','roleadd','1','6','');
insert into `snake_node`(`id`,`node_name`,`module_name`,`control_name`,`action_name`,`is_menu`,`typeid`,`style`) values('8','编辑角色','admin','role','roleedit','1','6','');
insert into `snake_node`(`id`,`node_name`,`module_name`,`control_name`,`action_name`,`is_menu`,`typeid`,`style`) values('9','删除角色','admin','role','roledel','1','6','');
insert into `snake_node`(`id`,`node_name`,`module_name`,`control_name`,`action_name`,`is_menu`,`typeid`,`style`) values('10','分配权限','admin','role','giveaccess','1','6','');
insert into `snake_node`(`id`,`node_name`,`module_name`,`control_name`,`action_name`,`is_menu`,`typeid`,`style`) values('11','系统管理','#','#','#','2','0','fa fa-desktop');
insert into `snake_node`(`id`,`node_name`,`module_name`,`control_name`,`action_name`,`is_menu`,`typeid`,`style`) values('12','数据备份/还原','admin','data','index','2','11','');
insert into `snake_node`(`id`,`node_name`,`module_name`,`control_name`,`action_name`,`is_menu`,`typeid`,`style`) values('13','备份数据','admin','data','importdata','1','12','');
insert into `snake_node`(`id`,`node_name`,`module_name`,`control_name`,`action_name`,`is_menu`,`typeid`,`style`) values('14','还原数据','admin','data','backdata','1','12','');
