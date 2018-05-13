CREATE TABLE `made_model` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `model_name` varchar(30) NOT NULL COMMENT '模型名称',
  `model_cate` tinyint UNSIGNED NOT NULL COMMENT '模型类Id',
  `img_src` varchar(150) NOT NULL COMMENT '图片路径',
  `material` varchar(300) NOT NULL COMMENT '涉及材料',
  `parameter` varchar(300) NOT NULL COMMENT '所需参数',
  `formula` varchar(500) NOT NULL COMMENT '计价公式',
  `is_index` tinyint(4) UNSIGNED NOT NULL DEFAULT '1' COMMENT '是否显示',
  `sort_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '100' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='报价模型';

CREATE TABLE `made_quote` (
  `id` varchar(30) NOT NULL  COMMENT 'Id',
  `user_id` mediumint(8) UNSIGNED NOT NULL COMMENT '用户Id',
  `address` varchar(100) NOT NULL COMMENT '用户地址',
  `telephone` varchar(20) NOT NULL DEFAULT '' COMMENT '手机号',
  `add_time` varchar(20) NOT NULL DEFAULT '' COMMENT '添加时间',
  `update_time` varchar(20) NOT NULL DEFAULT '' COMMENT '最后修改时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `telephone` (`telephone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='报价单';

CREATE TABLE `made_module` (
  `quote_id` varchar(30) NOT NULL  COMMENT '报价单Id',
  `sort_id` tinyint UNSIGNED NOT NULL COMMENT '模块商品编号',
  `model_id` tinyint UNSIGNED NOT NULL COMMENT '模型Id',
  `model_cate` tinyint UNSIGNED NOT NULL COMMENT '模型类Id',
  `agio` decimal(3,2) UNSIGNED NOT NULL DEFAULT '1'COMMENT '折扣',
  `space` varchar(100) NOT NULL COMMENT '产品位置',
  `type` varchar(50) NOT NULL DEFAULT '' COMMENT '产品类型',
  `material` varchar(300) NOT NULL COMMENT '材料价格',
  `parameter` varchar(300) NOT NULL COMMENT '参数值',
  KEY `quote_id` (`quote_id`,`model_cate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='报价单中模块商品';

ALTER TABLE `made_goods` ADD `is_quote` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否报价系统中商品';