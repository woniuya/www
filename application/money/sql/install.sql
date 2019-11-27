/*
Navicat MySQL Data Transfer

Source Server         : 测试服务器
Source Server Version : 50539
Source Host           : 123.56.98.40:3306
Source Database       : new_peizi_v0.001

Target Server Type    : MYSQL
Target Server Version : 50539
File Encoding         : 65001

Date: 2017-12-23 11:41:55
*/



-- ----------------------------
-- Table structure for `lmq_money`
-- ----------------------------
DROP TABLE IF EXISTS `lmq_money`;
CREATE TABLE `lmq_money` (
  `id` int(10) NOT NULL,
  `mid` int(10) NOT NULL COMMENT '用户ID',
  `account` int(11) NOT NULL DEFAULT '0' COMMENT '账户金额(单位 分)',
  `freeze` int(11) NOT NULL DEFAULT '0' COMMENT '冻结金额(单位 分)',
  `operate_account` int(11) NOT NULL DEFAULT '0' COMMENT '操盘总资金总额(单位 分)',
  `bond_account` int(11) NOT NULL DEFAULT '0' COMMENT '保证金总额(单位 分)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_money_mid` (`mid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员资金表';

-- ----------------------------
-- Records of lmq_money
-- ----------------------------

-- ----------------------------
-- Table structure for `lmq_money_recharge`
-- ----------------------------
DROP TABLE IF EXISTS `lmq_money_recharge`;
CREATE TABLE `lmq_money_recharge` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_no` varchar(32) NOT NULL COMMENT '订单号',
  `mid` int(10) NOT NULL COMMENT '用户id',
  `money` int(11) NOT NULL COMMENT '充值金额(单位 分)',
  `type` char(15) NOT NULL COMMENT '充值方式',
  `fee` int(6) NOT NULL DEFAULT '0' COMMENT '充值手续费（预留 单位 分）',
  `add_time` int(10) NOT NULL COMMENT '提交时间',
  `add_ip` bigint(20) NOT NULL,
  `line_bank` varchar(255) NOT NULL COMMENT '线下支付银行信息',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_recharge_order` (`order_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员资金充值记录';

-- ----------------------------
-- Records of lmq_money_recharge
-- ----------------------------

-- ----------------------------
-- Table structure for `lmq_money_recharge_audit`
-- ----------------------------
DROP TABLE IF EXISTS `lmq_money_recharge_audit`;
CREATE TABLE `lmq_money_recharge_audit` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_no` varchar(32) NOT NULL COMMENT '充值订单号',
  `admin_id` int(10) NOT NULL COMMENT '处理管理员id',
  `money` int(11) NOT NULL COMMENT '订单金额(单位 分)',
  `add_time` int(10) NOT NULL COMMENT '处理时间',
  `add_ip` bigint(20) NOT NULL COMMENT 'IP地址',
  PRIMARY KEY (`id`),
  UNIQUE KEY `money_recharge_audit_order` (`order_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员资金充值审核记录';

-- ----------------------------
-- Records of lmq_money_recharge_audit
-- ----------------------------

-- ----------------------------
-- Table structure for `lmq_money_record`
-- ----------------------------
DROP TABLE IF EXISTS `lmq_money_record`;
CREATE TABLE `lmq_money_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(10) unsigned NOT NULL COMMENT '交易用户id',
  `affect` int(11) NOT NULL DEFAULT '0' COMMENT '影响金额',
  `account` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '账户余额',
  `type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '资金类型',
  `add_time` int(10) NOT NULL COMMENT '交易时间',
  `add_ip` bigint(20) NOT NULL COMMENT '交易IP',
  PRIMARY KEY (`id`),
  KEY `moneylog_mid` (`mid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='资金操作记录表';

-- ----------------------------
-- Records of lmq_money_record
-- ----------------------------

-- ----------------------------
-- Table structure for `lmq_money_withdraw`
-- ----------------------------
DROP TABLE IF EXISTS `lmq_money_withdraw`;
CREATE TABLE `lmq_money_withdraw` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(10) NOT NULL COMMENT '用户id',
  `order_no` varchar(32) NOT NULL COMMENT '订单号',
  `money` int(11) NOT NULL COMMENT '提现资金(单位 分)',
  `fee` int(6) NOT NULL DEFAULT '0' COMMENT '手续费(单位 分)',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '提现状态0处理中 1 提现成功 2 提现失败',
  `bank_id` int(10) NOT NULL COMMENT '提现银行卡id',
  `add_time` int(10) NOT NULL COMMENT '提现时间',
  `add_ip` bigint(20) NOT NULL COMMENT '申请IP',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_withdraw_order` (`order_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='资金提现记录标';

-- ----------------------------
-- Records of lmq_money_withdraw
-- ----------------------------

-- ----------------------------
-- Table structure for `lmq_money_withdraw_audit`
-- ----------------------------
DROP TABLE IF EXISTS `lmq_money_withdraw_audit`;
CREATE TABLE `lmq_money_withdraw_audit` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_no` varchar(32) NOT NULL COMMENT '提现订单号',
  `admin_id` int(10) NOT NULL COMMENT '审核处理管理员id',
  `money` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '提现金额(单位 分)',
  `add_time` int(10) NOT NULL COMMENT '处理时间',
  `add_ip` bigint(20) NOT NULL COMMENT 'IP地址',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_money_withdraw_audit_order` (`order_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='资金提现审核记录';

-- ----------------------------
-- Records of lmq_money_withdraw_audit
-- ----------------------------
