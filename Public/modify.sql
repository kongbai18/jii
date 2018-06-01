ALTER TABLE `made_user` ADD  `session_key` varchar(150) NOT NULL DEFAULT '' COMMENT '密钥',

CREATE TABLE `made_furniture` (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `fur_name` varchar(30) NOT NULL  COMMENT '家具名称',
  `img_src` varchar(150) NOT NULL DEFAULT '' COMMENT '封面图',
  `cate_id` tinyint(4) UNSIGNED NOT NULL COMMENT '分类ID；1柜体，2门，3饰面',
  `attribute` text NOT NULL COMMENT '扩展属性',
  `sort_id` smallint(5) UNSIGNED NOT NULL DEFAULT '100' COMMENT '排序',
  `is_index` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否显示',
   PRIMARY KEY (`id`),
   KEY `cate_id` `cate_id`
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

DROP TABLE `made_model`;
CREATE TABLE `made_model` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `model_name` varchar(30) NOT NULL COMMENT '模型名称',
  `material` text NOT NULL COMMENT '涉及材料',
  `parameter` varchar(300) NOT NULL COMMENT '所需参数',
  `formula` text NOT NULL COMMENT '计价公式',
  `extend` text NOT NULL COMMENT '扩展数据',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='报价模型';

DROP TABLE `made_module`;
CREATE TABLE `made_module` (
  `quote_id` varchar(30) NOT NULL  COMMENT '报价单Id',
  `sort_id` tinyint UNSIGNED NOT NULL COMMENT '家具编号',
  `model_id` tinyint UNSIGNED NOT NULL COMMENT '模型Id',
  `cate_id` tinyint UNSIGNED NOT NULL COMMENT '家具大类Id，1柜体，2门，3饰面',
  `agio` decimal(3,2) UNSIGNED NOT NULL DEFAULT '1'COMMENT '折扣',
  `space` varchar(100) NOT NULL DEFAULT '' COMMENT '产品位置',
  `fur_name` varchar(50) NOT NULL DEFAULT '' COMMENT '家具名称',
  `material` text NOT NULL COMMENT '材料价格',
  `parameter` text NOT NULL COMMENT '参数值',
  `ext` text NOT NULL COMMENT '扩展',
   KEY `quote_id` (`quote_id`,`cate_id`,`sort_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='报价单中模块家具';

ALTER TABLE `made_module` drop column `open`;

