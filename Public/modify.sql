ALTER TABLE `made_user` CHANGE admin_id parent_id int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父级ID';


CREATE TABLE `made_cart_module` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `fur_quo_id` tinyint UNSIGNED NOT NULL COMMENT '家具扩展属性Id',
  `attr` VARCHAR(50) NOT NULL DEFAULT '' COMMENT '选择属性',
  `space_fur_name` varchar(100) NOT NULL DEFAULT '' COMMENT '产品位置 + 名称',
  `material` text NOT NULL COMMENT '材料价格',
  `parameter` text NOT NULL COMMENT '参数值',
  `ext` text NOT NULL COMMENT '扩展',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='加入过购物车的模块家具';

drop table if exists `made_integration`;
CREATE TABLE `made_integration` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'Id',
  `integration` VARCHAR(50) NOT NULL DEFAULT 0 COMMENT '积分',
  `sum` VARCHAR(50) NOT NULL DEFAULT 0 COMMENT '总金额',
  `cash` VARCHAR(50) NOT NULL DEFAULT 0 COMMENT '提现中',
  `surplus` VARCHAR(50) NOT NULL DEFAULT 0 COMMENT '可提现',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='积分和推广奖励';

drop table if exists `made_theme`;
CREATE TABLE `made_theme` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `theme_name` VARCHAR(50) NOT NULL DEFAULT '' COMMENT '主题名称',
  `sort_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '100' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='首页主题分类';

drop table if exists `made_carousel`;
CREATE TABLE `made_carousel` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `theme_id` int(10) UNSIGNED NOT NULL COMMENT '主题Id',
  `img_src` varchar(150) NOT NULL COMMENT '轮播图地址',
  `url` varchar(150) NOT NULL DEFAULT '' COMMENT '跳转路径',
  `sort_id` smallint(5) UNSIGNED NOT NULL DEFAULT '100' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='报价模型';

ALTER TABLE `made_goods_number` ADD `deduction` tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '最大抵扣积分';
ALTER TABLE `made_goods_number` ADD `reward` decimal(10,0) NOT NULL DEFAULT 0 COMMENT '奖励';

drop table if exists `made_order_goods`;
CREATE TABLE `made_order_goods` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `order_id` varchar(20) NOT NULL COMMENT '订单编号',
  `goods_id` mediumint(8) UNSIGNED NOT NULL COMMENT '商品Id',
  `goods_attr_id` varchar(50) NOT NULL COMMENT '商品属性ID',
  `price` decimal(10,0) NOT NULL COMMENT '商品价格',
  `cart_number` mediumint(8) UNSIGNED NOT NULL COMMENT '购物数量',
  `child_id` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '子订单号 ',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单商品';

drop table if exists `made_order`;
CREATE TABLE `made_order` (
  `order_id` varchar(20) NOT NULL UNIQUE COMMENT '订单编号',
  `user_id` mediumint(8) UNSIGNED NOT NULL COMMENT '用户Id',
  `message` varchar(200) NOT NULL DEFAULT '' COMMENT '用户留言',
  `address` varchar(200) NOT NULL COMMENT '收件信息',
  `price` decimal(10,2) NOT NULL COMMENT '总价格',
  `add_time` varchar(20) NOT NULL DEFAULT '' COMMENT '添加时间',
  `update_time` varchar(20) NOT NULL DEFAULT '' COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '订单状态 ',
  `prepay_id` varchar(50) NOT NULL DEFAULT '' COMMENT '微信订单号',
  `express` varchar(40) NOT NULL DEFAULT '' COMMENT '快递单号',
  `child_id` tinyint(3) NOT NULL DEFAULT '0' COMMENT '子订单号 ',
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单';

drop table if exists `made_reward`;
CREATE TABLE `made_reward` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `integration` tinyint UNSIGNED NOT NULL COMMENT '积分',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='积分表';

INSERT INTO `made_reward` (`id`, `integration`) VALUES ('1','1'),('2','2'),('3','4'),('4','5');

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
  `is_quote` tinyint NOT NULL DEFAULT 0 COMMENT '是否报价',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `admin_id` (`admin_id`),
  KEY `telephone` (`telephone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='报价单';

drop table if exists `made_integration_record`;
CREATE TABLE `made_integration_record` (
  `user_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户Id',
  `integration` tinyint UNSIGNED NOT NULL COMMENT '积分',
  `add_time` varchar(20) NOT NULL DEFAULT '' COMMENT '添加时间',
  `message` varchar(20) NOT NULL DEFAULT '' COMMENT '备注',
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='积分获取记录';

drop table if exists `made_order`;
CREATE TABLE `made_order` (
  `order_id` varchar(25) NOT NULL COMMENT '订单编号',
  `user_id` mediumint(8) UNSIGNED NOT NULL COMMENT '用户Id',
  `message` varchar(200) NOT NULL DEFAULT '' COMMENT '用户留言',
  `address` varchar(200) NOT NULL COMMENT '收件信息',
  `price` decimal(10,2) NOT NULL COMMENT '总价格',
  `deduction` decimal(10,2) NOT NULL COMMENT '抵扣',
  `modify_price` decimal(10,2) NOT NULL DEFAULT 0 COMMENT '后台修改价格',
  `last_price` decimal(10,2) NOT NULL COMMENT '交易价格',
  `add_time` varchar(20) NOT NULL DEFAULT '' COMMENT '添加时间',
  `update_time` varchar(20) NOT NULL DEFAULT '' COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '订单状态 ',
  `prepay_id` varchar(50) NOT NULL DEFAULT '' COMMENT '微信订单号',
  `express` varchar(40) NOT NULL DEFAULT '' COMMENT '快递单号',
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单';


  CREATE TABLE `made_article_category` (
    `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'id',
    `name` varchar(30) NOT NULL COMMENT '分类名称',
    `parent_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '0' COMMENT '父级ID',
    `order_id` smallint(5) UNSIGNED NOT NULL DEFAULT '100' COMMENT '排序',
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文章分类';

drop table if exists `made_article`;
CREATE TABLE `made_article` (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `article_name` varchar(150) NOT NULL COMMENT '文章标题',
  `cate_id` smallint UNSIGNED NOT NULL COMMENT '分类ID',
  `article_brief` varchar(150) NOT NULL DEFAULT '' COMMENT '文章简介',
  `add_time` varchar(30) NOT NULL COMMENT '添加时间',
  `is_index` tinyint(3) UNSIGNED NOT NULL DEFAULT '1 ' COMMENT '是否显示',
  `sort_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '100' COMMENT '排序',
  `goods` varchar(30) NOT NULL DEFAULT '' COMMENT '包含商品',
  `img_src` varchar(150) NOT NULL DEFAULT '' COMMENT '文章封面图',
  PRIMARY KEY (`id`),
  KEY `cate_id`(`cate_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='设计文章';