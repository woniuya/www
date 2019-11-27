/*
Date: 2017-12-18 11:21:00
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `lmq_stock_broker`
-- ----------------------------
DROP TABLE IF EXISTS `lmq_stock_broker`;
CREATE TABLE `lmq_stock_broker` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `key` int(11) NOT NULL COMMENT '券商ID',
  `value` varchar(50) NOT NULL COMMENT '券商名称',
  `clienver` varchar(128) NOT NULL COMMENT '客户端版本号',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态 默认1：启用 2：禁用',
  `crate_time` int(11) NOT NULL COMMENT '创建时间',
  `info` varchar(255) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='券商类型表';

-- ----------------------------
-- Records of lmq_stock_broker
-- ----------------------------
INSERT INTO `lmq_stock_broker` VALUES ('1', '1', '长江证券', '11.10','1','0', null);
INSERT INTO `lmq_stock_broker` VALUES ('2', '2', '第一创业', '	6.95','1','0', null);
INSERT INTO `lmq_stock_broker` VALUES ('3', '3', '东莞证券', '	6.64','1','0', null);
INSERT INTO `lmq_stock_broker` VALUES ('4', '4', '国信证券', '6.58','1','0', null);
INSERT INTO `lmq_stock_broker` VALUES ('5', '6', '华泰证券','6.41','1','0', null);
INSERT INTO `lmq_stock_broker` VALUES ('6', '7', '平安证券', '2.08','1','0', null);
INSERT INTO `lmq_stock_broker` VALUES ('7', '12', '广发证券', '8.17','1','0', null);
INSERT INTO `lmq_stock_broker` VALUES ('8', '13', '大通证券', '1.03','1','0', null);
INSERT INTO `lmq_stock_broker` VALUES ('9', '14', '华西证券', '7.24','1','0', null);
INSERT INTO `lmq_stock_broker` VALUES ('10', '15', '兴业证券', '6.65','1','0', null);
INSERT INTO `lmq_stock_broker` VALUES ('11', '16', '招商证券', '2.95','1','0', null);
INSERT INTO `lmq_stock_broker` VALUES ('12', '17', '金元证券', '6.37','1','0', null);
INSERT INTO `lmq_stock_broker` VALUES ('13', '18', '中信建投', '7.09','1','0', null);
INSERT INTO `lmq_stock_broker` VALUES ('14', '19', '红塔证券', '6.26','1','0', null);
INSERT INTO `lmq_stock_broker` VALUES ('15', '20', '长城证券', '6.26','1','0', null);
INSERT INTO `lmq_stock_broker` VALUES ('16', '22', '国泰君安', '9.40','1','0', null);
INSERT INTO `lmq_stock_broker` VALUES ('17', '23', '世纪证券', '6.40','1','0', null);
INSERT INTO `lmq_stock_broker` VALUES ('18', '24', '安信证券', '7.07','1','0', null);
INSERT INTO `lmq_stock_broker` VALUES ('19', '25', '财富证券', '6.47','1','0', null);
INSERT INTO `lmq_stock_broker` VALUES ('20', '26', '东兴证券', '8.15','1','0', null);
INSERT INTO `lmq_stock_broker` VALUES ('21', '27', '银河证券', '2.59','1','0', null);
INSERT INTO `lmq_stock_broker` VALUES ('22', '28', '光大证券', '6.43','1','0', null);
INSERT INTO `lmq_stock_broker` VALUES ('23', '29', '英大证券', '6.43','1','0', null);
INSERT INTO `lmq_stock_broker` VALUES ('24', '30', '德邦证券', '6.37','1','0', null);
INSERT INTO `lmq_stock_broker` VALUES ('25', '31', '南京证券', '6.68','1','0', null);
INSERT INTO `lmq_stock_broker` VALUES ('26', '32', '中信证券', '8.30','1','0', null);
INSERT INTO `lmq_stock_broker` VALUES ('27', '33', '上海证券', '10.55','1','0', null);
INSERT INTO `lmq_stock_broker` VALUES ('28', '34', '华宝证券', '7.68','1','0', null);
INSERT INTO `lmq_stock_broker` VALUES ('29', '35', '爱建证券', '6.50','1','0', null);
INSERT INTO `lmq_stock_broker` VALUES ('30', '36', '中泰证券', '1.41','1','0', null);
INSERT INTO `lmq_stock_broker` VALUES ('31', '37', '中银国际', '7.21','1','0', null);