drop table if exists `made_privilege`;
CREATE TABLE `made_privilege` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `pri_name` varchar(30) NOT NULL COMMENT '权限名称',
  `module_name` varchar(30) NOT NULL COMMENT '模型名称',
  `controller_name` varchar(30) NOT NULL COMMENT '控制器名称',
  `action_name` varchar(30) NOT NULL COMMENT '方法名称',
  `parent_id` int(10) UNSIGNED NOT NULL COMMENT '上级分类ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='权限表';

drop table if exists `made_role`;
CREATE TABLE `made_role` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `role_name` varchar(30) NOT NULL COMMENT '角色名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='角色表';

drop table if exists `made_role_pri`;
CREATE TABLE `made_role_pri` (
  `role_id` int(10) UNSIGNED NOT NULL COMMENT '角色ID',
  `pri_id` int(10) UNSIGNED NOT NULL COMMENT '权限ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='权限对应表';

drop table if exists `made_admin_role`;
CREATE TABLE `made_admin_role` (
  `role_id` int(10) UNSIGNED NOT NULL COMMENT '角色ID',
  `admin_id` int(10) UNSIGNED NOT NULL COMMENT '管理员ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员角色对应表';

drop table if exists `made_quote`;
CREATE TABLE `made_quote` (
  `id` varchar(30) NOT NULL  COMMENT 'Id',
  `user_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户Id',
  `admin_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '0' COMMENT '管理员Id',
  `address` varchar(100) NOT NULL DEFAULT '' COMMENT '用户地址',
  `user_name` varchar(100) NOT NULL DEFAULT '' COMMENT '用户姓名',
  `telephone` varchar(20) NOT NULL DEFAULT '' COMMENT '手机号',
  `add_time` varchar(20) NOT NULL DEFAULT '' COMMENT '添加时间',
  `update_time` varchar(20) NOT NULL DEFAULT '' COMMENT '最后修改时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `admin_id` (`admin_id`),
  KEY `telephone` (`telephone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='报价单';


INSERT INTO `made_privilege` (`id`, `pri_name`, `module_name`, `controller_name`, `action_name`, `parent_id`) VALUES
(1, '商品管理', 'Admin', 'Goods', '-', 0),
(2, '商品列表', 'Admin', 'Goods', 'lst', 1),
(3, '商品分类', 'Admin', 'Category', 'lst', 1),
(4, '商品类型', 'Admin', 'Type', 'lst', 1),
(5, '轮播图管理', 'Admin', 'Carousel', '-', 0),
(6, '轮播图列表', 'Admin', 'Carousel', 'lst', 5),
(7, '颜色库管理', 'Admin', 'Color', '-', 0),
(8, '颜色列表', 'Admin', 'Color', 'lst', 7),
(9, '文章管理', 'Admin', 'Article', '-', 0),
(10, '文章列表', 'Admin', 'Article', 'lst', 9),
(11, '订单管理', 'Admin', 'Order', '-', 0),
(12, '订单列表', 'Admin', 'Order', 'lst', 11),
(13, '发货单列表', 'Admin', 'Order', 'deli', 11),
(14, '报价管理', 'Admin', '-', '-', 0),
(15, '模型列表', 'Admin', 'Model', 'lst', 14),
(16, '家具类型列表', 'Admin', 'Furniture', 'lst', 14),
(17, '管理员', 'Admin', '-', '-', 0),
(18, '权限列表', 'Admin', 'Privilege', 'lst', 17),
(19, '角色列表', 'Admin', 'Role', 'lst', 17),
(20, '商品添加', 'Admin', 'Goods', 'add', 2),
(21, '商品修改', 'Admin', 'Goods', 'edit', 2),
(22, '商品删除', 'Admin', 'Goods', 'delete', 2),
(24, '管理员列表', 'Admin', 'Admin', 'lst', 17),
(25, '我的报价单', 'Admin', 'Quote', 'lst', 14),
(26, '管理员报价单', 'Admin', 'Quote', 'adminlst', 14),
(27, '用户报价单', 'Admin', 'Quote', 'userlst', 14),
(28, '分类添加', 'Admin', 'Category', 'add', 3),
(29, '分类修改', 'Admin', 'Category', 'edit', 3),
(30, '分类删除', 'Admin', 'Category', 'delete', 3),
(31, '类型添加', 'Admin', 'Type', 'add', 4),
(32, '类型修改', 'Admin', 'Type', 'edit', 4),
(33, '类型删除', 'Admin', 'Type', 'delete', 4),
(34, '属性列表', 'Admin', 'Attribute', 'lst', 4),
(35, '属性添加', 'Admin', 'Attribute', 'add', 4),
(36, '属性修改', 'Admin', 'Attribute', 'edit', 4),
(37, '属性删除', 'Admin', 'Attribute', 'delete', 4),
(38, '轮播图添加', 'Admin', 'Carousel', 'add', 6),
(39, '轮播图修改', 'Admin', 'Carousel', 'edit', 6),
(40, '轮播图删除', 'Admin', 'Carousel', 'delete', 6),
(41, '颜色添加', 'Admin', 'Color', 'add', 8),
(42, '颜色修改', 'Admin', 'Color', 'edit', 8),
(43, '颜色删除', 'Admin', 'Color', 'delete', 8),
(44, '文章添加', 'Admin', 'Article', 'add', 10),
(45, '文章修改', 'Admin', 'Article', 'edit', 10),
(46, '文章删除', 'Admin', 'Article', 'delete', 10),
(47, '模型添加', 'Admin', 'Model', 'add', 15),
(48, '模型修改', 'Admin', 'Model', 'edit', 15),
(49, '模型删除', 'Admin', 'Model', 'delete', 15),
(50, '家具添加', 'Admin', 'Furniture', 'add', 16),
(51, '家具修改', 'Admin', 'Furniture', 'edit', 16),
(52, '家具删除', 'Admin', 'Furniture', 'delete', 16),
(53, '家具扩展对应', 'Admin', 'Furniture', 'furniture_quote', 16),
(54, '报价单添加', 'Admin', 'Quote', 'add', 25),
(55, '报价单修改', 'Admin', 'Quote', 'edit', 25),
(56, '报价单修改', 'Admin', 'Quote', 'delete', 25),
(57, '密码修改', 'Admin', 'Admin', 'editpass', 17);