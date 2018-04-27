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
  `order_id` varchar(20) NOT NULL COMMENT '订单编号',
  `user_id` mediumint(8) UNSIGNED NOT NULL COMMENT '用户Id',
  `message` varchar(200) NOT NULL DEFAULT '' COMMENT '用户留言',
  `address` varchar(200) NOT NULL COMMENT '收件信息',
  `price` decimal(10,2) NOT NULL COMMENT '总价格',
  `add_time` varchar(20) NOT NULL DEFAULT '' COMMENT '添加时间',
  `update_time` varchar(20) NOT NULL DEFAULT '' COMMENT '更新时间',
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '订单状态 ',
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