drop table if exists `made_model`;
CREATE TABLE `made_model` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `model_name` varchar(30) NOT NULL COMMENT '模型名称',
  `material` text NOT NULL COMMENT '涉及材料',
  `parameter` varchar(300) NOT NULL COMMENT '所需参数',
  `formula` text NOT NULL COMMENT '计价公式',
  `ext` text NOT NULL COMMENT '扩展数据',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='报价模型';
