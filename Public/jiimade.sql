  CREATE TABLE `made_category` (
    `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'id',
    `name` varchar(30) NOT NULL COMMENT '分类名称',
    `name_en` varchar(30) NOT NULL COMMENT '英文名称',
    `parent_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '0' COMMENT '父级ID',
    `is_index` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否显示',
    `index_block` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '首页所在区块',
    `order_id` smallint(5) UNSIGNED NOT NULL DEFAULT '100' COMMENT '排序',
    `img_src` varchar(150) NOT NULL DEFAULT '' COMMENT '分类图',
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='分类';

CREATE TABLE `made_goods` (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `goods_name` varchar(150) NOT NULL COMMENT '商品名称',
  `tag` varchar(30) NOT NULL COMMENT '标签',
  `is_on_sale` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否上架',
  `add_time` varchar(30) NOT NULL COMMENT '添加时间',
  `cat_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '0' COMMENT '分类Id',
  `type_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '0' COMMENT '类型Id',
  `is_new` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否新品',
  `is_hot` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否热卖',
  `is_quote` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否报价系统中商品';
  `sort_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '100' COMMENT '排序',
  PRIMARY KEY (`id`),
  KEY `cat_id` (`cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品';

CREATE TABLE `made_type` (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `type_name` varchar(30) NOT NULL COMMENT '类型名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='类型';

CREATE TABLE `made_attribute` (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `attr_name` varchar(30) NOT NULL COMMENT '属性名称',
  `attr_type` tinyint(3) UNSIGNED NOT NULL DEFAULT '1' COMMENT '属性类型',
  `attr_option_values` varchar(300) NOT NULL DEFAULT '' COMMENT '属性值',
  `type_id` mediumint(8) UNSIGNED NOT NULL COMMENT '类型ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='属性';

CREATE TABLE `made_color` (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `color_name` varchar(30) NOT NULL COMMENT '颜色名称',
  `img_src` varchar(150) NOT NULL DEFAULT '' COMMENT '颜色图片路径',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='颜色库';

CREATE TABLE `made_goods_desc` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `img_src` varchar(150) NOT NULL COMMENT '图片路径',
  `goods_id` mediumint(8) UNSIGNED NOT NULL COMMENT '商品Id',
  `sort_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '100' COMMENT '排序',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品描述图片';

CREATE TABLE `made_goods_img` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `img_src` varchar(150) NOT NULL COMMENT '图片路径',
  `goods_id` mediumint(8) UNSIGNED NOT NULL COMMENT '商品Id',
  `sort_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '100' COMMENT '排序',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品描述轮播图';

CREATE TABLE `made_goods_cat` (
  `cat_id` mediumint(8) UNSIGNED NOT NULL COMMENT '分类id',
  `goods_id` mediumint(8) UNSIGNED NOT NULL COMMENT '商品Id',
   KEY `cat_id` (`cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='扩展分类';

CREATE TABLE `made_goods_attr` (
  `id` int(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `attr_value` varchar(150) NOT NULL DEFAULT '' COMMENT '属性值',
  `attr_id` mediumint(8) UNSIGNED NOT NULL COMMENT '属性Id',
  `goods_id` mediumint(8) UNSIGNED NOT NULL COMMENT '商品Id',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品属性';

CREATE TABLE `made_article` (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `article_name` varchar(150) NOT NULL COMMENT '文章标题',
  `article_brief` varchar(150) NOT NULL DEFAULT '' COMMENT '文章简介',
  `add_time` varchar(30) NOT NULL COMMENT '添加时间',
  `is_index` tinyint(3) UNSIGNED NOT NULL DEFAULT '1 ' COMMENT '是否显示',
  `sort_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '100' COMMENT '排序',
  `goods` varchar(30) NOT NULL DEFAULT '' COMMENT '包含商品',
  `img_src` varchar(150) NOT NULL DEFAULT '' COMMENT '文章封面图',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='设计文章';

CREATE TABLE `made_article_desc` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `img_src` varchar(150) NOT NULL COMMENT '图片路径',
  `article_id` mediumint(8) UNSIGNED NOT NULL COMMENT '文章Id',
  `sort_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '100' COMMENT '排序',
  PRIMARY KEY (`id`),
  KEY `article_id` (`article_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文章描述图片';

CREATE TABLE `made_goods_number` (
    `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
    `goods_id` mediumint(8) UNSIGNED NOT NULL COMMENT '商品Id',
    `goods_attr_id` varchar(50) NOT NULL COMMENT '商品属性ID',
    `goods_price` decimal(10,0) NOT NULL DEFAULT '0' COMMENT '商品价格',
    `discount_price` decimal(10,0) NOT NULL DEFAULT '0' COMMENT '商品价格',
    `goods_number` mediumint(8) UNSIGNED NOT NULL DEFAULT '0' COMMENT '库存数量',
    `img_src` varchar(150) NOT NULL DEFAULT '' COMMENT '对应图片',
    PRIMARY KEY (`id`),
    KEY `goods_id` (`goods_id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品库存及图片';

CREATE TABLE `made_user` (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `openid` varchar(150) NOT NULL DEFAULT '' COMMENT '微信ID',
  `telephone` varchar(20) NOT NULL DEFAULT '' COMMENT '手机号',
  `thr_session` varchar(150) NOT NULL DEFAULT '' COMMENT '登陆标识',
  `session_key` varchar(150) NOT NULL DEFAULT '' COMMENT '密钥',
  `add_time` varchar(30) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `thr_session` (`thr_session`),
  KEY `openid` (`openid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户';

CREATE TABLE `made_cart` (
  `user_id` mediumint(8) UNSIGNED NOT NULL COMMENT '用户Id',
  `goods_id` mediumint(8) UNSIGNED NOT NULL COMMENT '商品Id',
  `goods_attr_id` varchar(50) NOT NULL COMMENT '商品属性ID',
  `cart_number` mediumint(8) UNSIGNED NOT NULL DEFAULT '1' COMMENT '购物数量'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='购物车';

CREATE TABLE `made_address` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `user_id` mediumint(8) UNSIGNED NOT NULL COMMENT '用户Id',
  `name` varchar(50) NOT NULL COMMENT '收件人',
  `mobile` varchar(20) NOT NULL COMMENT '手机号',
  `city` varchar(50) NOT NULL COMMENT '城市',
  `address` varchar(100) NOT NULL COMMENT '详细地址',
  `status` tinyint(3) UNSIGNED NOT NULL COMMENT '是否默认 ',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='收货地址';

CREATE TABLE `made_order` (
  `order_id` varchar(25) NOT NULL COMMENT '订单编号',
  `user_id` mediumint(8) UNSIGNED NOT NULL COMMENT '用户Id',
  `message` varchar(200) NOT NULL DEFAULT '' COMMENT '用户留言',
  `address` varchar(200) NOT NULL COMMENT '收件信息',
  `price` decimal(10,2) NOT NULL COMMENT '总价格',
  `add_time` varchar(20) NOT NULL DEFAULT '' COMMENT '添加时间',
  `update_time` varchar(20) NOT NULL DEFAULT '' COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '订单状态 ',
  `prepay_id` varchar(50) NOT NULL DEFAULT '' COMMENT '微信订单号',
  `express` varchar(40) NOT NULL DEFAULT '' COMMENT '快递单号',
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单';

CREATE TABLE `made_order_goods` (
  `order_id` varchar(20) NOT NULL COMMENT '订单编号',
  `goods_id` mediumint(8) UNSIGNED NOT NULL COMMENT '商品Id',
  `goods_attr_id` varchar(50) NOT NULL COMMENT '商品属性ID',
  `price` decimal(10,0) NOT NULL COMMENT '商品价格',
  `cart_number` mediumint(8) UNSIGNED NOT NULL COMMENT '购物数量'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单商品';

CREATE TABLE `made_admin` (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `username` varchar(30) NOT NULL COMMENT '用户名',
  `password` char(32) NOT NULL COMMENT '密码',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员';

INSERT INTO `made_admin` (`id`, `username`, `password`) VALUES
(1, 'root', '48601017c9c217061bc9c231f246ca7f');

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

CREATE TABLE `made_furniture` (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `fur_name` varchar(30) NOT NULL  COMMENT '家具名称',
  `img_src` varchar(150) NOT NULL DEFAULT '' COMMENT '封面图',
  `cate_id` tinyint(4) UNSIGNED NOT NULL COMMENT '分类ID；1柜体，2门，3饰面',
  `attribute` text NOT NULL COMMENT '扩展属性',
  `sort_id` smallint(5) UNSIGNED NOT NULL DEFAULT '100' COMMENT '排序',
  `is_index` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否显示',
   PRIMARY KEY (`id`),
   KEY `cate_id` (`cate_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='家具类表';

CREATE TABLE `made_furniture_quote` (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `fur_id` mediumint(8) UNSIGNED NOT NULL COMMENT 'Id',
  `fur_attr_id` VARCHAR(30) NOT NULL COMMENT '属性联合ID',
  `model_id` mediumint(8) UNSIGNED NOT NULL COMMENT '计算模型Id',
  `img_src` varchar(150) NOT NULL DEFAULT '' COMMENT '封面图',
   PRIMARY KEY (`id`),
   KEY `furId` (`fur_id`,`fur_attr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='家具类表';

CREATE TABLE `made_model` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `model_name` varchar(30) NOT NULL COMMENT '模型名称',
  `material` text NOT NULL COMMENT '涉及材料',
  `parameter` varchar(300) NOT NULL COMMENT '所需参数',
  `project_area` text NOT NULL COMMENT '投影面积计算公式',
  `formula` text NOT NULL COMMENT '计价公式',
  `ext` text NOT NULL COMMENT '扩展数据',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='报价模型';

CREATE TABLE `made_module` (
  `quote_id` varchar(30) NOT NULL  COMMENT '报价单Id',
  `sort_id` tinyint UNSIGNED NOT NULL COMMENT '家具编号',
  `fur_quo_id` tinyint UNSIGNED NOT NULL COMMENT '家具扩展属性Id',
  `attr` VARCHAR(50) NOT NULL DEFAULT '' COMMENT '选择属性',
  `cate_id` tinyint UNSIGNED NOT NULL COMMENT '家具大类Id，1柜体，2门，3饰面',
  `agio` decimal(3,2) UNSIGNED NOT NULL DEFAULT '1'COMMENT '折扣',
  `space` varchar(100) NOT NULL DEFAULT '' COMMENT '产品位置',
  `fur_name` varchar(50) NOT NULL DEFAULT '' COMMENT '家具名称',
  `material` text NOT NULL COMMENT '材料价格',
  `parameter` text NOT NULL COMMENT '参数值',
  `ext` text NOT NULL COMMENT '扩展',
   KEY `quote_id` (`quote_id`,`cate_id`,`sort_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='报价单中模块家具';

CREATE TABLE `made_carousel` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `img_src` varchar(150) NOT NULL COMMENT '轮播图地址',
  `url` varchar(150) NOT NULL DEFAULT '' COMMENT '跳转路径',
  `sort_id` smallint(5) UNSIGNED NOT NULL DEFAULT '100' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='报价模型';

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