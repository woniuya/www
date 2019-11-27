-- -----------------------------
-- 导出时间 `2018-01-05 10:56:11`
-- -----------------------------

-- -----------------------------
-- 表结构 `lmq_member`
-- -----------------------------
DROP TABLE IF EXISTS `lmq_member`;
CREATE TABLE `lmq_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `mobile` bigint(11) NOT NULL COMMENT '手机号用于登陆',
  `passwd` varchar(96) NOT NULL COMMENT '登陆密码',
  `paywd` varchar(96) NOT NULL COMMENT '支付密码',
  `id_card` varchar(18) DEFAULT NULL COMMENT '身份证号码',
  `name` varchar(24) DEFAULT NULL COMMENT '姓名',
  `id_auth` tinyint(1) NOT NULL DEFAULT '0' COMMENT '身份证是否通过校验 0 处理中  1通过 2 错误',
  `create_ip` bigint(20) NOT NULL COMMENT '注册IP',
  `create_time` int(10) NOT NULL COMMENT '注册时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0： 禁止登录  1 ：正常使用  ',
  `is_del` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除 1 已删除或注销',
  `last_login_time` int(10) NOT NULL DEFAULT '0' COMMENT '最后一次登录时间',
  `last_login_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '最后一次登录ip',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_mobile` (`mobile`) USING BTREE,
  UNIQUE KEY `unique_card` (`id_card`),
  KEY `member_id` (`id`,`mobile`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='会员信息表';

-- -----------------------------
-- 表数据 `lmq_member`
-- -----------------------------
INSERT INTO `lmq_member` VALUES ('1', '15163054002', '$2y$10$F/lumJjBdluHTTirTmheeO7m4kk6LoMPKUXub1ZksNhUGj7yhCRm.', '$2y$10$B.z25BHil6hZbu1UDPweBuhXo0TUquRBzM5D/4D1wfPq1pN2Ca0si', '372901198703298794', '张锋1', '2', '2130706433', '1513649221', '1', '0', '1514962815', '2130706433');
INSERT INTO `lmq_member` VALUES ('2', '15163054003', '71CA0B537DD369350A996EBAFB95902', '71CA0B537DD369350A996EBAFB95902', '372901198703298791', '张锋', '1', '2130706433', '1513649221', '1', '0', '0', '0');

-- -----------------------------
-- 表结构 `lmq_member_message`
-- -----------------------------
DROP TABLE IF EXISTS `lmq_member_message`;
CREATE TABLE `lmq_member_message` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(10) NOT NULL COMMENT '用户MID',
  `info` text NOT NULL COMMENT '消息内容',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '查看状态 （1 已查看）',
  `type` tinyint(2) NOT NULL COMMENT '状态（备用）',
  `create_time` int(10) NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员消息表';

-- -----------------------------
-- 表数据 `lmq_member_message`
-- -----------------------------

-- -----------------------------
-- 表结构 `lmq_member_invitation_relation`
-- -----------------------------
DROP TABLE IF EXISTS `lmq_member_invitation_relation`;
CREATE TABLE `lmq_member_invitation_relation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `invitation_mid` int(10) NOT NULL COMMENT '被邀请人id',
  `mid` int(10) NOT NULL COMMENT '邀请人id',
  `create_time` int(10) NOT NULL COMMENT '邀请时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_invitation_m_id` (`invitation_mid`),
  KEY `invitation_referee_id` (`id`,`invitation_mid`,`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='邀请人与会员关系表';

-- -----------------------------
-- 表数据 `lmq_member_invitation_relation`
-- -----------------------------

-- -----------------------------
-- 表结构 `lmq_member_invitation_record`
-- -----------------------------
DROP TABLE IF EXISTS `lmq_member_invitation_record`;
CREATE TABLE `lmq_member_invitation_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(10) NOT NULL DEFAULT '0' COMMENT '邀请人member_id',
  `money` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '奖励金额(分)',
  `remark` varchar(255) NOT NULL COMMENT '备注',
  `create_time` int(10) NOT NULL COMMENT '奖励时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='邀请奖励记录表';

-- -----------------------------
-- 表数据 `lmq_member_invitation_record`
-- -----------------------------
INSERT INTO `lmq_member_invitation_record` VALUES ('1', '1', '10000', 'dsfadfasf', '3423');
