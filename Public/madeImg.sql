CREATE TABLE `made_goods_img` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `img_src` varchar(150) NOT NULL COMMENT '图片路径',
  `goods_id` mediumint(8) UNSIGNED NOT NULL COMMENT '商品Id',
  `sort_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '100' COMMENT '排序',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品描述轮播图';

INSERT INTO `made_admin` (`id`, `username`, `password`) VALUES
(1, 'root', '48601017c9c217061bc9c231f246ca7f');