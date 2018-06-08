drop table if exists `made_module`;
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

drop table if exists `made_model`;
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

drop table if exists `made_carousel`;
CREATE TABLE `made_carousel` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `img_src` varchar(150) NOT NULL COMMENT '轮播图地址',
  `url` varchar(150) NOT NULL DEFAULT '' COMMENT '跳转路径',
  `sort_id` smallint(5) UNSIGNED NOT NULL DEFAULT '100' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='报价模型';