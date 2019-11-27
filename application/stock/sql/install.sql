-- ----------------------------
-- Records of lmq_stock_account
-- ----------------------------
INSERT INTO `lmq_stock_account` VALUES ('1', '中信建投', '北京海淀区中关村西区营业部', '29666537', 'ceshi', '123456', '', '0.00', '500.00', '18', '0', '2', '7.09', '0', null, '1514358149', '1514358149', '1');
INSERT INTO `lmq_stock_account` VALUES ('2', '模拟账户', '北京西城营业部', 'stock1', 'ceshi', '123456', '', '0.00', '500.00', '-1', '0', '2', '1.00', '0', null, '1514358295', '1514358295', '1');
INSERT INTO `lmq_stock_account` VALUES ('3', '国信证券', '上海虹口区营业部', '87652891', 'ceshi2', '123456', '', '0.00', '500.00', '4', '0', '2', '6.58', '1', null, '1514358827', '1514368775', '1');
INSERT INTO `lmq_stock_account` VALUES ('4', '模拟账户', '平安证券营业部', 'stock2', 'ceshi', '123456', '', '0.00', '500.00', '-1', '0', '2', '1.00', '1', null, '1514359310', '1514359310', '1');
INSERT INTO `lmq_stock_account` VALUES ('5', '东莞证券', '广东证券营业部', '1111111', '测试1', '123456', '', '5.00', '500.00', '3', '0', '2', '	6.64', '1', null, '1514359640', '1514359873', '1');
INSERT INTO `lmq_stock_account` VALUES ('6', '光大证券', '济南槐荫区营业部', '7856578788', 'guangda', '123456', '', '0.00', '500.00', '28', '0', '2', '6.43', '1', null, '1514361366', '1514361366', '1');
INSERT INTO `lmq_stock_account` VALUES ('7', '华泰证券', '北京西城营业部', '66767789', 'huatai', '123456', '', '8.00', '500.00', '6', '0', '2', '6.41', '1', null, '1514361582', '1514361582', '1');

-- ----------------------------
-- Table structure for `lmq_stock_account_info`
-- ----------------------------
DROP TABLE IF EXISTS `lmq_stock_account_info`;
CREATE TABLE `lmq_stock_account_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `lid` varchar(255) NOT NULL COMMENT '交易账户名',
  `stock_account_id` int(11) NOT NULL COMMENT '外键ID stock_account表的ID',
  `soruce` varchar(100) DEFAULT '' COMMENT '证券来源',
  `user` varchar(255) NOT NULL COMMENT '交易通账户',
  `currency` tinyint(2) NOT NULL DEFAULT '0' COMMENT '币种',
  `balance` decimal(15,2) NOT NULL COMMENT '资金余额',
  `account_money` decimal(15,2) DEFAULT NULL COMMENT '可用资金',
  `freeze_money` decimal(15,2) DEFAULT NULL COMMENT '冻结资金',
  `desirable_money` decimal(15,2) DEFAULT NULL COMMENT '可取资金',
  `market_value` decimal(15,2) DEFAULT NULL COMMENT '最新市值',
  `total_money` decimal(15,2) DEFAULT NULL COMMENT '总资产',
  `ck_profit` decimal(15,2) DEFAULT NULL COMMENT '参考浮动盈亏',
  `info` varchar(255) DEFAULT NULL COMMENT '保留信息',
  `sh_code` varchar(100) DEFAULT NULL COMMENT '沪A股东代码',
  `sz_code` varchar(100) DEFAULT NULL COMMENT '深A股东代码',
  `gudong_name` varchar(100) DEFAULT NULL COMMENT '股东名称',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='交易账户信息查询存储表';

-- ----------------------------
-- Records of lmq_stock_account_info
-- ----------------------------
INSERT INTO `lmq_stock_account_info` VALUES ('1', '29666537', '1', '', 'ceshi', '0', '1000.00', '1000.00', null, null, null, null, null, null, null, null, null);
INSERT INTO `lmq_stock_account_info` VALUES ('2', 'stock1', '2', '', 'ceshi', '0', '50000.00', null, null, null, null, null, null, null, null, null, null);
INSERT INTO `lmq_stock_account_info` VALUES ('3', '87652891', '3', '模拟账户', 'ceshi2', '0', '5000.00', null, null, null, null, null, null, null, null, null, null);
INSERT INTO `lmq_stock_account_info` VALUES ('4', 'stock2', '4', '模拟账户', 'ceshi', '0', '0.00', null, null, null, null, null, null, null, null, null, null);
INSERT INTO `lmq_stock_account_info` VALUES ('5', '1111111', '5', '模拟账户', '测试1', '0', '0.00', null, null, null, null, null, null, null, null, null, null);
INSERT INTO `lmq_stock_account_info` VALUES ('6', '7856578788', '6', '模拟账户', 'guangda', '0', '0.00', null, null, null, null, null, null, null, null, null, null);
INSERT INTO `lmq_stock_account_info` VALUES ('7', '66767789', '7', '模拟账户', 'huatai', '0', '0.00', null, null, null, null, null, null, null, null, null, null);

-- ----------------------------
-- Table structure for `lmq_stock_addfinancing`
-- ----------------------------
DROP TABLE IF EXISTS `lmq_stock_addfinancing`;
CREATE TABLE `lmq_stock_addfinancing` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `uid` int(11) NOT NULL COMMENT '会员ID',
  `borrow_id` int(11) NOT NULL COMMENT '配资ID',
  `money` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT '扩大配资金额',
  `rate` decimal(15,2) NOT NULL COMMENT '配资费率',
  `borrow_interest` decimal(15,2) NOT NULL COMMENT '配资管理费',
  `last_deposit_money` decimal(15,2) NOT NULL COMMENT '原始保证金总额',
  `last_borrow_money` decimal(15,2) NOT NULL COMMENT '原始配资总金额',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态 0：待审核 1：审核通过 2：审核未通过',
  `add_time` int(11) NOT NULL COMMENT '申请时间',
  `verify_time` int(11) NOT NULL COMMENT '审核时间',
  `target_uid` int(11) DEFAULT NULL COMMENT '操作者ID',
  `target_name` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '操作者姓名',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COMMENT='扩大配资记录表';

-- ----------------------------
-- Records of lmq_stock_addfinancing
-- ----------------------------
INSERT INTO `lmq_stock_addfinancing` VALUES ('1', '1', '4', '500.00', '0.00', '75.52', '1500.00', '10500.00', '1', '1505803144', '1505803177', null, null);

-- ----------------------------
-- Table structure for `lmq_stock_addmoney`
-- ----------------------------
DROP TABLE IF EXISTS `lmq_stock_addmoney`;
CREATE TABLE `lmq_stock_addmoney` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `borrow_id` int(11) NOT NULL COMMENT '配资ID',
  `uid` int(11) NOT NULL COMMENT '会员ID',
  `money` decimal(15,2) NOT NULL COMMENT '保证金',
  `status` tinyint(2) NOT NULL COMMENT '状态 0：待审核 ，1：审核通过，2：审核失败',
  `add_time` int(11) NOT NULL COMMENT '添加时间',
  `verify_time` int(11) NOT NULL COMMENT '审核时间',
  `target_uid` int(11) DEFAULT NULL COMMENT '操作者ID',
  `target_name` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '操作者姓名',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='追加保证金记录表';

-- ----------------------------
-- Records of lmq_stock_addmoney
-- ----------------------------

-- ----------------------------
-- Table structure for `lmq_stock_agent`
-- ----------------------------
DROP TABLE IF EXISTS `lmq_stock_agent`;
CREATE TABLE `lmq_stock_agent` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `pid` int(11) DEFAULT NULL COMMENT '所属上级的代理ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '代理名称',
  `user_name` varchar(255) NOT NULL COMMENT '用户名',
  `pwd` varchar(255) NOT NULL COMMENT '密码',
  `phone` int(11) NOT NULL COMMENT '手机号',
  `commission_scale` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT '佣金比例（单位：%）',
  `rate_scale` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT '管理费分成比例（单位：%）',
  `profit_share_scale` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT '盈利分成比例（单位：%）',
  `status` tinyint(2) DEFAULT '1' COMMENT '账户状态  1：正常，2：禁用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='代理商表';

-- ----------------------------
-- Records of lmq_stock_agent
-- ----------------------------

-- ----------------------------
-- Table structure for `lmq_stock_borrow`
-- ----------------------------
DROP TABLE IF EXISTS `lmq_stock_borrow`;
CREATE TABLE `lmq_stock_borrow` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `stock_subaccount_id` int(11) NOT NULL COMMENT '外键 交易子账号的唯一主键ID',
  `order_id` bigint(15) NOT NULL COMMENT '配资单号',
  `member_id` int(11) NOT NULL COMMENT '外键 会员ID',
  `type` tinyint(2) NOT NULL COMMENT '配资类型 1：免费配资 2：按天配资 3：按周配资 4：按月配资 5：免费体验  6、免息配资',
  `status` tinyint(3) NOT NULL COMMENT '配资状态 0：待审核  2：操盘中  3：已结束   -1：未通过',
  `deposit_money` decimal(15,2) NOT NULL COMMENT '保证金',
  `multiple` tinyint(2) NOT NULL COMMENT '配资杠杆倍数',
  `init_money` decimal(15,2) DEFAULT NULL COMMENT '股票初始资金',
  `borrow_money` decimal(15,2) NOT NULL COMMENT '配资金额',
  `borrow_interest` decimal(15,2) NOT NULL COMMENT '配资管理费',
  `repayment_type` tinyint(2) NOT NULL COMMENT '还款类型',
  `borrow_duration` int(11) NOT NULL COMMENT '配资时长',
  `loss_warn` decimal(15,2) NOT NULL COMMENT '预警线',
  `loss_close` decimal(15,2) NOT NULL COMMENT '止损线',
  `position` int(11) NOT NULL COMMENT '仓位限制',
  `rate` decimal(5,2) NOT NULL COMMENT '配资费率',
  `sort_order` tinyint(3) NOT NULL COMMENT '配资收费批次',
  `total` tinyint(3) NOT NULL COMMENT '配资收费总批次',
  `create_time` int(11) NOT NULL COMMENT '配资申请时间',
  `end_time` int(11) NOT NULL COMMENT '配资结束时间',
  `verify_time` int(11) NOT NULL COMMENT '审核时间',
  `stock_money` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT '股票卖出价',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='配资信息表';

-- ----------------------------
-- Records of lmq_stock_borrow
-- ----------------------------
INSERT INTO `lmq_stock_borrow` VALUES ('1', '0', '11111', '1', '1', '0', '1000.00', '4', '5000.00', '4000.00', '0.00', '0', '3', '0.00', '0.00', '0', '0.00', '0', '0', '1514351868', '0', '0', '0.00');

-- ----------------------------
-- Table structure for `lmq_stock_broker`
-- ----------------------------
DROP TABLE IF EXISTS `lmq_stock_broker`;
CREATE TABLE `lmq_stock_broker` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `broker_id` int(11) NOT NULL COMMENT '券商ID',
  `broker_value` varchar(50) NOT NULL COMMENT '券商名称',
  `clienver` varchar(128) NOT NULL COMMENT '客户端版本号',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态 默认1：启用 2：禁用',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `info` varchar(255) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=utf8 COMMENT='券商类型表';

-- ----------------------------
-- Records of lmq_stock_broker
-- ----------------------------
INSERT INTO `lmq_stock_broker` VALUES ('1', '1', '长江证券', '11.10', '1', '1513580665', '1513580665', null);
INSERT INTO `lmq_stock_broker` VALUES ('2', '2', '第一创业', '6.95', '1', '1513580665', '1513580665', null);
INSERT INTO `lmq_stock_broker` VALUES ('3', '3', '东莞证券', '	6.64', '1', '1513580665', '1513580665', null);
INSERT INTO `lmq_stock_broker` VALUES ('4', '4', '国信证券', '6.58', '1', '1513580665', '1513580665', null);
INSERT INTO `lmq_stock_broker` VALUES ('5', '6', '华泰证券', '6.41', '1', '1513580665', '1513580665', null);
INSERT INTO `lmq_stock_broker` VALUES ('6', '7', '平安证券', '2.08', '1', '1513580665', '1513580665', null);
INSERT INTO `lmq_stock_broker` VALUES ('7', '12', '广发证券', '8.17', '1', '1513580665', '1513580665', null);
INSERT INTO `lmq_stock_broker` VALUES ('8', '13', '大通证券', '1.03', '1', '1513580665', '1513580665', null);
INSERT INTO `lmq_stock_broker` VALUES ('9', '14', '华西证券', '7.24', '1', '1513580665', '1513580665', null);
INSERT INTO `lmq_stock_broker` VALUES ('10', '15', '兴业证券', '6.65', '1', '1513580665', '1513580665', null);
INSERT INTO `lmq_stock_broker` VALUES ('11', '16', '招商证券', '2.95', '1', '1513580665', '1513580665', null);
INSERT INTO `lmq_stock_broker` VALUES ('12', '17', '金元证券', '6.37', '1', '1513580665', '1513580665', null);
INSERT INTO `lmq_stock_broker` VALUES ('13', '18', '中信建投', '7.09', '1', '1513580665', '1513761920', '测试');
INSERT INTO `lmq_stock_broker` VALUES ('14', '19', '红塔证券', '6.26', '1', '1513580665', '1513580665', null);
INSERT INTO `lmq_stock_broker` VALUES ('15', '20', '长城证券', '6.26', '1', '1513580665', '1513580665', null);
INSERT INTO `lmq_stock_broker` VALUES ('16', '22', '国泰君安', '9.40', '1', '1513580665', '1513580665', null);
INSERT INTO `lmq_stock_broker` VALUES ('17', '23', '世纪证券', '6.40', '1', '1513580665', '1513580665', null);
INSERT INTO `lmq_stock_broker` VALUES ('18', '24', '安信证券', '7.07', '1', '1513580665', '1513580665', null);
INSERT INTO `lmq_stock_broker` VALUES ('19', '25', '财富证券', '6.47', '1', '1513580665', '1513580665', null);
INSERT INTO `lmq_stock_broker` VALUES ('20', '26', '东兴证券', '8.15', '1', '1513580665', '1513580665', null);
INSERT INTO `lmq_stock_broker` VALUES ('21', '27', '银河证券', '2.59', '1', '1513580665', '1513580665', null);
INSERT INTO `lmq_stock_broker` VALUES ('22', '28', '光大证券', '6.43', '1', '1513580665', '1513580665', null);
INSERT INTO `lmq_stock_broker` VALUES ('23', '29', '英大证券', '6.43', '1', '1513580665', '1513580665', null);
INSERT INTO `lmq_stock_broker` VALUES ('24', '30', '德邦证券', '6.37', '1', '1513580665', '1513580665', null);
INSERT INTO `lmq_stock_broker` VALUES ('25', '31', '南京证券', '6.68', '1', '1513580665', '1513580665', null);
INSERT INTO `lmq_stock_broker` VALUES ('26', '32', '中信证券', '8.30', '1', '1513580665', '1513580665', null);
INSERT INTO `lmq_stock_broker` VALUES ('27', '33', '上海证券', '10.55', '1', '1513580665', '1513580665', null);
INSERT INTO `lmq_stock_broker` VALUES ('28', '34', '华宝证券', '7.68', '1', '1513580665', '1513580665', null);
INSERT INTO `lmq_stock_broker` VALUES ('29', '35', '爱建证券', '6.50', '1', '1513580665', '1513580665', null);
INSERT INTO `lmq_stock_broker` VALUES ('30', '36', '中泰证券', '1.41', '1', '1513580665', '1513580665', null);
INSERT INTO `lmq_stock_broker` VALUES ('31', '37', '中银国际', '7.21', '1', '1513580665', '1513580665', null);
INSERT INTO `lmq_stock_broker` VALUES ('40', '39', '湘财证券', '10.28', '1', '1513580665', '1513762108', '');
INSERT INTO `lmq_stock_broker` VALUES ('38', '38', '民族证券', '6.72', '1', '1513580665', '1513580665', '');
INSERT INTO `lmq_stock_broker` VALUES ('42', '-1', '模拟账户', '1.00', '1', '1513823592', '1513823592', '模拟账户');
INSERT INTO `lmq_stock_broker` VALUES ('41', '40', '国金证券', '7.31', '1', '1513762097', '1513762189', '');

-- ----------------------------
-- Table structure for `lmq_stock_deal_stock`
-- ----------------------------
DROP TABLE IF EXISTS `lmq_stock_deal_stock`;
CREATE TABLE `lmq_stock_deal_stock` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `sub_id` int(11) NOT NULL COMMENT '外键子账户ID',
  `lid` varchar(255) DEFAULT '' COMMENT '交易账户',
  `soruce` varchar(100) DEFAULT '' COMMENT '证券来源',
  `login_name` varchar(255) DEFAULT '' COMMENT '证券账户',
  `gupiao_code` varchar(50) NOT NULL COMMENT '证券代码',
  `gupiao_name` varchar(50) NOT NULL COMMENT '证券名称',
  `deal_date` int(11) NOT NULL COMMENT '成交日期',
  `deal_time` varchar(50) NOT NULL COMMENT '成交时间',
  `trust_no` varchar(150) NOT NULL COMMENT '委托编号',
  `trust_price` decimal(15,2) DEFAULT NULL COMMENT '委托价格',
  `trust_count` decimal(15,2) DEFAULT NULL COMMENT '委托数量',
  `deal_no` varchar(150) NOT NULL COMMENT '成交编号',
  `deal_price` decimal(15,2) DEFAULT NULL COMMENT '成交价格',
  `volume` int(15) DEFAULT NULL COMMENT '成交数量',
  `amount` int(15) DEFAULT NULL COMMENT '成交金额',
  `flag1` tinyint(2) DEFAULT NULL COMMENT '买卖标志1 0:买入  1：卖出',
  `flag2` varchar(50) DEFAULT NULL COMMENT '买卖标志2',
  `gudong_code` varchar(100) DEFAULT NULL COMMENT '股东代码',
  `cancel_order_flag` varchar(50) DEFAULT NULL COMMENT '撤单标志',
  `type` tinyint(2) DEFAULT NULL COMMENT '账号类别',
  `status` varchar(255) DEFAULT NULL COMMENT '状态说明',
  `beizhu` varchar(255) DEFAULT NULL COMMENT '备注',
  `info` varchar(255) DEFAULT NULL COMMENT '保留信息',
  `type_today` tinyint(2) DEFAULT NULL COMMENT '当日成交标识',
  `type_history` tinyint(2) DEFAULT NULL COMMENT '历史成交标识',
  PRIMARY KEY (`id`),
  UNIQUE KEY `deal_no` (`deal_no`) USING BTREE,
  UNIQUE KEY `trust_no` (`trust_no`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='股票成交查询存储表';

-- ----------------------------
-- Records of lmq_stock_deal_stock
-- ----------------------------
INSERT INTO `lmq_stock_deal_stock` VALUES ('1', '1', '29666537', '中信建投', 'ceshi', '601288', '农业银行', '1510588800', '13:00:02', '188776', '3.95', '100.00', '0', '3.95', '100', '0', '1', '证券卖出', 'A778979623', '1', '1', '撤单成交', null, '', '1', null);

-- ----------------------------
-- Table structure for `lmq_stock_delivery_order`
-- ----------------------------
DROP TABLE IF EXISTS `lmq_stock_delivery_order`;
CREATE TABLE `lmq_stock_delivery_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `sub_id` int(11) NOT NULL COMMENT '外键子账户ID',
  `lid` varchar(255) DEFAULT NULL COMMENT '交易账户',
  `soruce` varchar(100) DEFAULT NULL COMMENT '证券来源',
  `login_name` varchar(50) DEFAULT NULL COMMENT '证券账户',
  `gupiao_code` varchar(50) DEFAULT NULL COMMENT '证券代码',
  `gupiao_name` varchar(50) DEFAULT NULL COMMENT '证券名称',
  `deal_date` int(11) DEFAULT NULL COMMENT '成交日期',
  `business_name` varchar(50) DEFAULT NULL COMMENT '业务名称',
  `deal_price` decimal(15,2) DEFAULT NULL COMMENT '成交价格',
  `volume` int(15) DEFAULT NULL COMMENT '成交数量',
  `amount` decimal(15,2) DEFAULT NULL COMMENT '成交金额',
  `residual_quantity` int(15) DEFAULT NULL COMMENT '剩余数量',
  `liquidation_amount` decimal(15,2) DEFAULT NULL COMMENT '清算金额',
  `residual_amount` decimal(15,2) DEFAULT NULL COMMENT '剩余金额',
  `stamp_duty` decimal(15,2) DEFAULT NULL COMMENT '印花税',
  `transfer_fee` decimal(15,2) DEFAULT NULL COMMENT '过户费',
  `commission` decimal(15,2) DEFAULT NULL COMMENT '净佣金',
  `transaction_fee` decimal(15,2) DEFAULT NULL COMMENT '交易费',
  `front_desk_fee` decimal(15,2) DEFAULT NULL COMMENT '前台费用',
  `trust_no` varchar(150) DEFAULT NULL COMMENT '委托编号',
  `deal_no` varchar(150) DEFAULT NULL COMMENT '成交编号',
  `gudong_code` varchar(100) DEFAULT NULL COMMENT '股东代码',
  `type` tinyint(2) DEFAULT NULL COMMENT '账号类别',
  PRIMARY KEY (`id`),
  UNIQUE KEY `deal_no` (`deal_no`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='股票交割单';

-- ----------------------------
-- Records of lmq_stock_delivery_order
-- ----------------------------
INSERT INTO `lmq_stock_delivery_order` VALUES ('1', '1', '29666537', '中信建投', 'ceshi', '002610', '爱康科技', '1509638400', '证券买入', '2.45', '100', '245.00', '100', '-250.00', '4752.65', '0.00', '0.00', '4.99', '0.01', '0.00', '0', '2147483647', '0164169954', '0');

-- ----------------------------
-- Table structure for `lmq_stock_drawprofit`
-- ----------------------------
DROP TABLE IF EXISTS `lmq_stock_drawprofit`;
CREATE TABLE `lmq_stock_drawprofit` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `borrow_id` int(11) NOT NULL COMMENT '配资ID',
  `uid` int(11) NOT NULL COMMENT '会员ID',
  `borrow_money` decimal(15,2) NOT NULL COMMENT '配资金额',
  `money` decimal(15,2) NOT NULL COMMENT '提盈金额',
  `status` tinyint(4) NOT NULL COMMENT '状态 0：待审核 1：审核通过 2：审核未通过',
  `add_time` int(11) NOT NULL COMMENT '申请时间',
  `target_uid` int(11) NOT NULL COMMENT '审核人ID',
  `target_name` varchar(20) NOT NULL COMMENT '审核人姓名',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='股票提盈表';


-- ----------------------------
-- Table structure for `lmq_stock_list`
-- ----------------------------
DROP TABLE IF EXISTS `lmq_stock_list`;
CREATE TABLE `lmq_stock_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `title` varchar(100) NOT NULL COMMENT '股票名称',
  `code` varchar(100) NOT NULL COMMENT '股票代码',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '股票状态  0：黑名单  1：白名单',
  `pinyin` varchar(100) NOT NULL COMMENT '股票简拼',
  `add_time` int(11) NOT NULL COMMENT '添加时间',
  `edit_time` int(11) NOT NULL COMMENT '修改时间',
  `info` varchar(500) NOT NULL COMMENT '备注信息',
  `target_uid` int(11) NOT NULL COMMENT '操作人ID',
  `target_name` varchar(20) NOT NULL COMMENT '操作人姓名',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='股票黑/白名单设置表';

-- ----------------------------
-- Records of lmq_stock_list
-- ----------------------------

-- ----------------------------
-- Table structure for `lmq_stock_position`
-- ----------------------------
DROP TABLE IF EXISTS `lmq_stock_position`;
CREATE TABLE `lmq_stock_position` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `sub_id` int(11) NOT NULL COMMENT '外键 子账户ID',
  `lid` varchar(255) DEFAULT '' COMMENT '交易账户名',
  `soruce` varchar(100) DEFAULT '' COMMENT '证券来源',
  `login_name` varchar(255) DEFAULT '' COMMENT '证券账户',
  `gupiao_code` varchar(50) NOT NULL COMMENT '证券代码',
  `gupiao_name` varchar(50) DEFAULT NULL COMMENT '证券名称',
  `count` int(11) DEFAULT NULL COMMENT '证券数量',
  `stock_count` int(11) DEFAULT NULL COMMENT '实际持仓数量',
  `canbuy_count` int(11) DEFAULT NULL COMMENT '可卖数量',
  `ck_price` decimal(15,3) DEFAULT NULL COMMENT '参考成本价',
  `buy_average_price` decimal(15,3) DEFAULT NULL COMMENT '买入均价',
  `ck_profit_price` decimal(15,3) DEFAULT NULL COMMENT '参考盈亏成本价',
  `now_price` decimal(15,2) DEFAULT NULL COMMENT '当前价',
  `market_value` decimal(15,2) DEFAULT NULL COMMENT '最新市值',
  `ck_profit` decimal(15,2) DEFAULT NULL COMMENT '参考浮动盈亏',
  `profit_rate` decimal(15,2) DEFAULT NULL COMMENT '盈亏比例',
  `buying` varchar(50) DEFAULT NULL COMMENT '在途买入',
  `selling` varchar(50) DEFAULT NULL COMMENT '在途卖出',
  `gudong_code` varchar(100) DEFAULT NULL COMMENT '股东代码',
  `type` tinyint(2) DEFAULT NULL COMMENT '账号类别',
  `jigou_type` tinyint(2) DEFAULT NULL COMMENT '交易机构类别',
  `jiyisuo` varchar(50) DEFAULT NULL COMMENT '交易所',
  `info` varchar(255) DEFAULT NULL COMMENT '保留信息',
  `beizhu` varchar(255) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`id`),
  UNIQUE KEY `gupiao_code` (`gupiao_code`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='股票持仓存储表';

-- ----------------------------
-- Records of lmq_stock_position
-- ----------------------------
INSERT INTO `lmq_stock_position` VALUES ('1', '1', '29666537', '中信建投', 'ceshi', '002610', '爱康科技', '100', '100', '100', '2.500', '2.500', '2.500', '2.39', '239.00', '-11.00', '-4.40', '0', '0', '0164169954', '0', '1', '深交所', '', null);

-- ----------------------------
-- Table structure for `lmq_stock_renewal`
-- ----------------------------
DROP TABLE IF EXISTS `lmq_stock_renewal`;
CREATE TABLE `lmq_stock_renewal` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `uid` int(11) NOT NULL COMMENT '会员ID',
  `borrow_id` int(11) NOT NULL COMMENT '配资ID',
  `borrow_fee` decimal(15,2) NOT NULL COMMENT '续期管理费',
  `borrow_duration` tinyint(10) NOT NULL COMMENT '续期时长',
  `status` tinyint(2) NOT NULL COMMENT '审核状态',
  `add_time` int(11) NOT NULL COMMENT '申请时间',
  `verify_time` int(11) NOT NULL COMMENT '审核时间',
  `type` int(2) DEFAULT NULL COMMENT '业务类型 1：续期申请  2：终止配资',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='配资续期/终止表';

-- ----------------------------
-- Records of lmq_stock_renewal
-- ----------------------------

-- ----------------------------
-- Table structure for `lmq_stock_subaccount`
-- ----------------------------
DROP TABLE IF EXISTS `lmq_stock_subaccount`;
CREATE TABLE `lmq_stock_subaccount` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `uid` int(11) NOT NULL COMMENT '会员ID',
  `sub_account` varchar(255) NOT NULL COMMENT '交易子账户',
  `sub_pwd` varchar(255) NOT NULL COMMENT '交易子账户密码',
  `status` tinyint(2) NOT NULL COMMENT '使用状态 0：未使用    1：已使用',
  `user_type` int(2) NOT NULL DEFAULT '0' COMMENT '子账号类型，0：为免费体验账户，1：为按天/周/月分配账户  2：试用配资',
  `account_id` int(10) NOT NULL DEFAULT '0' COMMENT '具体证券ID',
  `relation_type` int(2) NOT NULL DEFAULT '0' COMMENT '账户模式，0:模拟账户，1:证券账户',
  `agent_id` int(11) NOT NULL DEFAULT '0' COMMENT '代理商id  默认为0代表总后台,非0代表具体代理商ID',
  `info` varchar(255) DEFAULT NULL COMMENT '备注信息',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='子账户信息表';

-- ----------------------------
-- Records of lmq_stock_subaccount
-- ----------------------------
INSERT INTO `lmq_stock_subaccount` VALUES ('1', '0', 'lvmaque1', '1234567', '0', '0', '1', '1', '1', '', '1514351868', '1514351928');
INSERT INTO `lmq_stock_subaccount` VALUES ('2', '0', 'lvmaque2', '123456', '0', '1', '2', '0', '2', '', '1514351963', '1514351963');
INSERT INTO `lmq_stock_subaccount` VALUES ('3', '0', 'lvmaque3', '123456', '0', '0', '2', '0', '2', '', '1514362387', '1514362387');

-- ----------------------------
-- Table structure for `lmq_stock_subaccount_money`
-- ----------------------------
DROP TABLE IF EXISTS `lmq_stock_subaccount_money`;
CREATE TABLE `lmq_stock_subaccount_money` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `stock_subaccount_id` int(11) NOT NULL COMMENT '外键id 关联lmq_stock_subaccount表里的唯一主键ID',
  `commission_scale` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT '佣金比例（单位：万分之几） 如：5 代表万分之五',
  `min_commission` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT '最低佣金（单位：元）',
  `rate_scale` decimal(15,2) DEFAULT NULL COMMENT '配资管理费分成比例',
  `profit_share_scale` decimal(15,2) DEFAULT NULL COMMENT '盈利分成比例',
  `avail` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT '可用金额',
  `available_amount` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT '可提现金额',
  `freeze_amount` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT '冻结金额',
  `return_money` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT '盈亏金额',
  `return_rate` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT '盈亏利率',
  `deposit_money` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT '保证金',
  `borrow_money` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT '配资金额',
  `stock_addfinancing` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT '扩大配资累计金额',
  `stock_addmoney` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT '追加保证金累计金额',
  `stock_drawprofit` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT '申请提取盈利累计金额',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='子账户资金信息表';

-- ----------------------------
-- Records of lmq_stock_subaccount_money
-- ----------------------------
INSERT INTO `lmq_stock_subaccount_money` VALUES ('1', '1', '8.00', '5.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00');
INSERT INTO `lmq_stock_subaccount_money` VALUES ('2', '2', '8.00', '500.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00');
INSERT INTO `lmq_stock_subaccount_money` VALUES ('3', '3', '0.00', '500.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00');

-- ----------------------------
-- Table structure for `lmq_stock_subaccount_risk`
-- ----------------------------
DROP TABLE IF EXISTS `lmq_stock_subaccount_risk`;
CREATE TABLE `lmq_stock_subaccount_risk` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `stock_subaccount_id` int(11) NOT NULL COMMENT '外键id 关联lmq_stock_subaccount表里的唯一主键ID',
  `loss_warn` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT '预警线（单位：%）',
  `loss_close` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT '平仓线（单位：%）',
  `position` int(5) NOT NULL DEFAULT '100' COMMENT '个股持仓比例（单位：%），区间0-100，100表示不限仓',
  `prohibit_open` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否禁止开仓，1：允许开仓 0：禁止开仓',
  `prohibit_close` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否禁止平仓，1：允许平仓 0：禁止平仓',
  `prohibit_back` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否禁止撤单，1：允许撤单  0：禁止撤单',
  `renewal` tinyint(2) DEFAULT NULL COMMENT '是否开启自动续期 0：不开启  1：开启',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='子账户风控设置表';

-- ----------------------------
-- Records of lmq_stock_subaccount_risk
-- ----------------------------
INSERT INTO `lmq_stock_subaccount_risk` VALUES ('1', '1', '60.00', '30.00', '100', '1', '1', '1', '1');
INSERT INTO `lmq_stock_subaccount_risk` VALUES ('2', '2', '60.00', '30.00', '100', '1', '1', '1', '1');
INSERT INTO `lmq_stock_subaccount_risk` VALUES ('3', '3', '80.00', '60.00', '100', '1', '1', '1', '1');

-- ----------------------------
-- Table structure for `lmq_stock_subaccount_self`
-- ----------------------------
DROP TABLE IF EXISTS `lmq_stock_subaccount_self`;
CREATE TABLE `lmq_stock_subaccount_self` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `sub_id` int(10) NOT NULL COMMENT '外键id 关联lmq_stock_subaccount表里的唯一主键ID',
  `gupiao_name` varchar(50) NOT NULL DEFAULT '' COMMENT '股票名称',
  `gupiao_code` varchar(50) NOT NULL DEFAULT '' COMMENT '股票代码',
  `create_time` int(11) NOT NULL COMMENT '添加时间',
  `add_ip` int(20) NOT NULL COMMENT '添加IP',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='股票自选表';

-- ----------------------------
-- Records of lmq_stock_subaccount_self
-- ----------------------------
INSERT INTO `lmq_stock_subaccount_self` VALUES ('1', '1', '京东方A', '000725', '1514256235', '2130706433');
INSERT INTO `lmq_stock_subaccount_self` VALUES ('2', '1', '农业银行', '601288', '1514256235', '2130706433');

-- ----------------------------
-- Table structure for `lmq_stock_trust`
-- ----------------------------
DROP TABLE IF EXISTS `lmq_stock_trust`;
CREATE TABLE `lmq_stock_trust` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `sub_id` int(11) NOT NULL COMMENT '外键子账户ID',
  `lid` varchar(255) DEFAULT NULL COMMENT '证券交易账号ID 如：29666537',
  `jytName` varchar(255) DEFAULT '' COMMENT '交易账户名',
  `soruce` varchar(100) DEFAULT '' COMMENT '证券来源',
  `login_name` varchar(255) DEFAULT '' COMMENT '证券账户',
  `gupiao_code` varchar(50) NOT NULL COMMENT '证券代码',
  `gupiao_name` varchar(50) NOT NULL COMMENT '证券名称',
  `trust_date` int(11) DEFAULT NULL COMMENT '委托日期',
  `trust_time` varchar(50) DEFAULT NULL COMMENT '委托时间',
  `trust_no` varchar(150) NOT NULL COMMENT '委托编号',
  `trust_price` decimal(15,2) DEFAULT NULL COMMENT '委托价格',
  `trust_count` decimal(15,2) DEFAULT NULL COMMENT '委托数量',
  `volume` int(15) DEFAULT NULL COMMENT '成交数量',
  `amount` int(15) DEFAULT NULL COMMENT '成交金额',
  `flag1` varchar(50) DEFAULT NULL COMMENT '买卖标志1',
  `flag2` varchar(50) DEFAULT NULL COMMENT '买卖标志2',
  `gudong_code` varchar(100) DEFAULT NULL COMMENT '股东代码',
  `cancel_order_flag` varchar(50) DEFAULT NULL COMMENT '撤单标志',
  `cancel_order_count` int(15) DEFAULT NULL COMMENT '撤单数量',
  `type` tinyint(2) DEFAULT NULL COMMENT '账号类别',
  `status` varchar(255) DEFAULT NULL COMMENT '状态说明',
  `add_time` int(11) DEFAULT NULL COMMENT '操作时间',
  `beizhu` varchar(255) DEFAULT NULL COMMENT '备注',
  `info` varchar(255) DEFAULT NULL COMMENT '保留信息',
  `type_today` tinyint(2) DEFAULT NULL COMMENT '当日委托',
  `type_today_back` tinyint(2) DEFAULT NULL COMMENT '当日可撤销委托',
  `type_history` tinyint(2) DEFAULT NULL COMMENT '历史委托',
  PRIMARY KEY (`id`),
  UNIQUE KEY `trust_no` (`trust_no`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='股票委托存储表';

-- ----------------------------
-- Records of lmq_stock_trust
-- ----------------------------
INSERT INTO `lmq_stock_trust` VALUES ('1', '1', '29666537', '', '中信建投', 'ceshi', '601288', '农业银行', '1509552000', '10:07:37', '147643', '3.66', '100.00', '0', '0', '0', '证券买入', 'A778979623', null, '100', '1', '已撤', '1509552000', '', null, null, null, '1');
