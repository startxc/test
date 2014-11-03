DROP TABLE IF EXISTS lj_adv
CREATE TABLE `lj_adv` (  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,  `position_id` int(11) NOT NULL COMMENT '广告位置',  `name` varchar(100) NOT NULL COMMENT '广告名称',  `link` varchar(100) NOT NULL COMMENT '广告链接',  `image` varchar(200) NOT NULL COMMENT '广告图片',  `enabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否禁用',  `description` varchar(255) NOT NULL,  `sort` tinyint(3) NOT NULL,  `click_count` int(11) NOT NULL COMMENT ' 点击次数',  `create_uid` int(11) NOT NULL,  `create_user` varchar(80) NOT NULL,  `create_time` int(11) NOT NULL,  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8
DROP TABLE IF EXISTS lj_adv_position
CREATE TABLE `lj_adv_position` (  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,  `name` varchar(60) NOT NULL COMMENT '位置名称',  `width` mediumint(9) NOT NULL COMMENT '宽度',  `height` mediumint(9) NOT NULL COMMENT '高度',  `description` varchar(250) NOT NULL COMMENT '广告注释信息',  `code` varchar(30) NOT NULL COMMENT '广告模板',  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8
DROP TABLE IF EXISTS lj_cart
CREATE TABLE `lj_cart` (  `id` int(10) NOT NULL AUTO_INCREMENT,  `member_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户id',  `goods_id` int(10) NOT NULL COMMENT '商品id',  `goods_sn` varchar(50) NOT NULL COMMENT '商品货号',  `goods_name` varchar(120) NOT NULL COMMENT '商品名',  `price` decimal(10,2) NOT NULL COMMENT '价格',  `wholesale_price` decimal(10,2) NOT NULL,  `number` mediumint(8) NOT NULL COMMENT '商品数量',  `image` varchar(200) NOT NULL COMMENT '商品图片',  `sku_id` varchar(50) NOT NULL COMMENT 'sku id 后续减库存',  `sku_info` varchar(200) NOT NULL,  `pre_price` decimal(10,2) NOT NULL,  `is_prebuy` tinyint(1) NOT NULL DEFAULT '0',  `prebuyid` int(11) NOT NULL COMMENT '团购id',  `create_time` int(11) NOT NULL COMMENT '生成时间',  `purchase_price` decimal(10,2) NOT NULL COMMENT '进货价',  `factory_id` int(11) NOT NULL,  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8
DROP TABLE IF EXISTS lj_category
CREATE TABLE `lj_category` (  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,  `name` varchar(250) NOT NULL COMMENT '分类名称',  `image` varchar(250) NOT NULL COMMENT '分类图片',  `order_index` tinyint(4) unsigned NOT NULL COMMENT '排序',  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '启用状态:1正常，0禁用',  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8
DROP TABLE IF EXISTS lj_config
CREATE TABLE `lj_config` (  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,  `name` varchar(120) NOT NULL,  `parent_id` int(11) NOT NULL,  `code` varchar(30) NOT NULL,  `type` varchar(20) NOT NULL,  `value` varchar(250) NOT NULL,  `store_range` varchar(255) NOT NULL,  `sort` tinyint(3) NOT NULL,  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8
DROP TABLE IF EXISTS lj_email_list
CREATE TABLE `lj_email_list` (  `id` int(11) NOT NULL AUTO_INCREMENT,  `member_id` int(11) NOT NULL,  `email_name` varchar(60) NOT NULL,  `create_time` int(11) NOT NULL,  `send_time` int(11) NOT NULL,  `is_send` tinyint(1) NOT NULL DEFAULT '0',  `tpl_code` varchar(60) NOT NULL,  `tpl_params` varchar(255) NOT NULL,  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8
DROP TABLE IF EXISTS lj_email_log
CREATE TABLE `lj_email_log` (  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,  `email_id` int(11) NOT NULL,  `error_msg` varchar(255) NOT NULL,  `send_code` tinyint(1) NOT NULL DEFAULT '0',  `send_time` int(11) NOT NULL,  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8
DROP TABLE IF EXISTS lj_email_template
CREATE TABLE `lj_email_template` (  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,  `name` varchar(60) NOT NULL,  `code` varchar(60) NOT NULL,  `subject` varchar(250) NOT NULL,  `body` text NOT NULL,  `create_time` int(11) NOT NULL,  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8
DROP TABLE IF EXISTS lj_goods
CREATE TABLE `lj_goods` (  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,  `category_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品分类',  `name` varchar(200) NOT NULL COMMENT '商品名称',  `price` decimal(10,2) NOT NULL DEFAULT '0.00',  `image` varchar(200) DEFAULT NULL COMMENT '封面图片',  `description` varchar(5000) DEFAULT NULL COMMENT '商品描述',  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '商品状态：1正常，0禁用',  `sale_count` int(11) NOT NULL DEFAULT '0' COMMENT '销量',  `spec` varchar(20) NOT NULL DEFAULT ' ' COMMENT '规格',  `order_index` tinyint(4) NOT NULL DEFAULT '0' COMMENT '商品排序',  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除：1删除，0正常',  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8
DROP TABLE IF EXISTS lj_goods_image
CREATE TABLE `lj_goods_image` (  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,  `goods_id` int(10) unsigned NOT NULL COMMENT '商品ID',  `image` varchar(100) NOT NULL COMMENT '图片地址',  `create_time` int(11) NOT NULL,  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8
DROP TABLE IF EXISTS lj_group
CREATE TABLE `lj_group` (  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,  `goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品ID',  `category_id` int(11) NOT NULL DEFAULT '0' COMMENT '类目ID',  `name` varchar(200) NOT NULL,  `image` varchar(255) DEFAULT NULL COMMENT '团购封面',  `order_moq` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '起订量',  `sale_count` int(11) NOT NULL DEFAULT '0' COMMENT '销售数量',  `spec` varchar(20) DEFAULT NULL COMMENT '销售规格',  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:显示 0:隐藏 2::',  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除：1删除，0正常',  `create_time` int(11) NOT NULL DEFAULT '0',  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8
DROP TABLE IF EXISTS lj_group_apply
CREATE TABLE `lj_group_apply` (  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,  `goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品ID',  `category_id` int(11) NOT NULL DEFAULT '0' COMMENT '类目ID',  `name` varchar(200) NOT NULL,  `image` varchar(255) DEFAULT NULL COMMENT '团购封面',  `order_moq` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '起订量',  `sale_count` int(11) NOT NULL DEFAULT '0' COMMENT '销售数量',  `spec` varchar(20) DEFAULT NULL COMMENT '销售规格',  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:显示 0:隐藏 2::',  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除：1删除，0正常',  `create_time` int(11) NOT NULL DEFAULT '0',  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8
DROP TABLE IF EXISTS lj_group_image
CREATE TABLE `lj_group_image` (  `id` int(11) NOT NULL AUTO_INCREMENT,  `group_id` int(11) NOT NULL DEFAULT '0' COMMENT 'group id',  `image` varchar(255) DEFAULT NULL COMMENT '图片地址',  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='伙拼图片表'
DROP TABLE IF EXISTS lj_group_phase
CREATE TABLE `lj_group_phase` (  `id` int(11) NOT NULL AUTO_INCREMENT,  `group_id` int(11) NOT NULL DEFAULT '0' COMMENT '关联团购表ID',  `start_time` int(11) NOT NULL DEFAULT '0' COMMENT '开始时间',  `end_time` int(11) NOT NULL DEFAULT '0' COMMENT '结束时间',  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态：1正常，0关闭，-1删除',  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',  PRIMARY KEY (`id`),  KEY `group_id` (`group_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='团购周期表'
DROP TABLE IF EXISTS lj_member
CREATE TABLE `lj_member` (  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,  `mobile` char(11) NOT NULL COMMENT '手机号',  `password` char(64) NOT NULL COMMENT '密码',  `nickname` varchar(30) NOT NULL COMMENT '昵称',  `pay_code` varchar(40) NOT NULL DEFAULT ' ' COMMENT '支付密码',  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '用户状态 | 1:正常 | 2:禁用 | -1:删除',  `avatar` varchar(60) NOT NULL COMMENT '会员头像',  `province_id` int(11) NOT NULL COMMENT '用户省份ID',  `city_id` int(11) NOT NULL COMMENT '城市ID',  `area_id` int(11) NOT NULL,  `province_name` varchar(60) NOT NULL COMMENT '省份名称',  `city_name` varchar(60) NOT NULL COMMENT '城市名称',  `area_name` varchar(60) NOT NULL,  `address` varchar(100) NOT NULL COMMENT '地址',  `last_login_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',  `create_time` int(11) unsigned NOT NULL COMMENT '注册时间',  `update_time` int(11) unsigned NOT NULL COMMENT '更新时间',  `last_login_ip` varchar(40) NOT NULL COMMENT '最后登录IP',  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8
DROP TABLE IF EXISTS lj_member_account
CREATE TABLE `lj_member_account` (  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,  `member_id` int(11) NOT NULL COMMENT '操作ID',  `nick_name` varchar(60) NOT NULL DEFAULT ' ' COMMENT '操作名称',  `amount` decimal(10,2) NOT NULL COMMENT '金额数目',  `create_time` int(11) NOT NULL,  `update_time` int(11) NOT NULL,  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8
DROP TABLE IF EXISTS lj_member_account_log
CREATE TABLE `lj_member_account_log` (  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,  `member_id` int(11) NOT NULL COMMENT '会员id',  `amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '流水金额（有正有负）',  `remark` varchar(50) NOT NULL COMMENT '流水备注',  `remain_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '余额',  `create_time` int(11) NOT NULL,  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8
DROP TABLE IF EXISTS lj_member_address
CREATE TABLE `lj_member_address` (  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,  `member_id` int(11) NOT NULL COMMENT '会员ID',  `consignee` varchar(60) NOT NULL COMMENT '收货人名称',  `province_id` mediumint(10) unsigned NOT NULL COMMENT '省份ID',  `city_id` mediumint(11) NOT NULL COMMENT '城市ID',  `area_id` mediumint(11) NOT NULL COMMENT '区号',  `zip` int(11) NOT NULL COMMENT '邮政编码',  `province_name` varchar(50) NOT NULL COMMENT '省份名称',  `city_name` varchar(50) NOT NULL COMMENT '城市名称',  `region_name` varchar(50) NOT NULL COMMENT ' 区号名称',  `address` varchar(250) NOT NULL COMMENT '详细地址',  `tel` varchar(60) NOT NULL COMMENT '固定电话',  `mobile` varchar(60) NOT NULL COMMENT '手机号码',  `is_default` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否默认地址 1:为默认的 0:不是默认的',  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8
DROP TABLE IF EXISTS lj_menu
CREATE TABLE `lj_menu` (  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '模块表',  `name` varchar(250) NOT NULL COMMENT '模块名称',  `url` varchar(250) NOT NULL COMMENT '模块链接地址',  `child` varchar(255) NOT NULL COMMENT '菜单下的子栏目',  `status` tinyint(2) NOT NULL COMMENT '菜单状态',  `sort` tinyint(7) NOT NULL COMMENT '排序字段',  `create_time` int(11) NOT NULL COMMENT '添加时间',  `update_time` int(11) NOT NULL COMMENT '菜单排序',  PRIMARY KEY (`id`)) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8
INSERT INTO lj_menu VALUES( '1','系统管理','Data','69,6,2,87,100,112,118,124','1','0','1345950360','1390472443')
INSERT INTO lj_menu VALUES( '2','会员管理','Member','7,96,113,114,116,120,130','1','2','1345950415','1393315197')
INSERT INTO lj_menu VALUES( '3','广告管理','AdvPosition','108,109','1','3','1345953198','1380263250')
INSERT INTO lj_menu VALUES( '4','订单管理','Order','97,115,122,123,129,132,138,141','1','4','1345953238','1408526644')
INSERT INTO lj_menu VALUES( '5','Works管理','WorksCategory/','133,134,137','1','5','1345953330','1408365278')
INSERT INTO lj_menu VALUES( '6','商品管理','Goods','98,103,105,106,107,110,111,117,128','1','6','1345953360','1392606217')
INSERT INTO lj_menu VALUES( '7','网站首页','Index/main','101','1','9','1372041110','1380264266')
INSERT INTO lj_menu VALUES( '8','项目管理','Item','','-1','7','1376882235','0')
INSERT INTO lj_menu VALUES( '9','信息管理','Info','95,94,136','1','8','1382580023','1408364959')
INSERT INTO lj_menu VALUES( '10','慈善管理','Charity','125,126,131','1','1','1390472743','1394012570')
INSERT INTO lj_menu VALUES( '11','财务管理','Financial','96,113,127','1','0','1390472973','0')
INSERT INTO lj_menu VALUES( '12','孵化管理','HatchApply','139,140','1','8','1408365660','1408524312')
DROP TABLE IF EXISTS lj_message
CREATE TABLE `lj_message` (  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,  `member_id` int(11) NOT NULL COMMENT '所属用户ID',  `foreign_id` int(11) NOT NULL COMMENT '外链ID',  `foreign_name` varchar(80) NOT NULL COMMENT '外链名称',  `foreign_avatar` varchar(80) NOT NULL COMMENT '外链头像',  `content` varchar(100) NOT NULL,  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:喜欢我的 1：转发我的',  `work_id` int(200) NOT NULL,  `work_image` varchar(200) NOT NULL,  `create_time` int(11) NOT NULL,  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8
DROP TABLE IF EXISTS lj_notice
CREATE TABLE `lj_notice` (  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,  `title` varchar(100) NOT NULL,  `desc` varchar(250) NOT NULL,  `image` varchar(100) NOT NULL,  `content` int(11) NOT NULL,  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:正常 1：other',  `create_time` int(11) NOT NULL,  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8
DROP TABLE IF EXISTS lj_notice_content
CREATE TABLE `lj_notice_content` (  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,  `title` varchar(100) NOT NULL,  `desc` varchar(250) NOT NULL,  `image` varchar(100) NOT NULL,  `content` text NOT NULL,  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:正常 1：other',  `create_time` int(11) NOT NULL,  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8
DROP TABLE IF EXISTS lj_notice_receive
CREATE TABLE `lj_notice_receive` (  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,  `member_id` int(11) NOT NULL,  `notice_id` int(11) NOT NULL COMMENT '关联内容ID',  `is_read` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否已读：1已读，0未读',  `create_time` int(11) NOT NULL,  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8
DROP TABLE IF EXISTS lj_notice_target
CREATE TABLE `lj_notice_target` (  `id` int(11) NOT NULL AUTO_INCREMENT,  `notice_id` int(11) NOT NULL DEFAULT '0' COMMENT 'notice id',  `role_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '角色, 1：普通用户，2：批发商，3：工厂，4：找OEM',  `member_id` int(11) NOT NULL DEFAULT '0' COMMENT 'target member id ',  `create_time` int(11) NOT NULL DEFAULT '0',  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='消息接收方关系表'
DROP TABLE IF EXISTS lj_order
CREATE TABLE `lj_order` (  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,  `combine_pay_no` varchar(30) NOT NULL,  `order_no` varchar(30) NOT NULL COMMENT '订单编号',  `member_id` int(10) unsigned NOT NULL COMMENT '会员ID',  `order_status` enum('not_paid','paid','shipped','canceled') NOT NULL DEFAULT 'not_paid' COMMENT '订单状态',  `shipping_status` tinyint(2) NOT NULL COMMENT '运输状态',  `pay_status` tinyint(2) NOT NULL COMMENT '付款状态',  `consignee` varchar(60) NOT NULL COMMENT '收货人',  `province_id` smallint(6) NOT NULL COMMENT '省份ID',  `city_id` smallint(6) NOT NULL COMMENT '城市ID',  `area_id` smallint(6) NOT NULL COMMENT '区域ID',  `address` varchar(255) NOT NULL COMMENT '详细地址',  `tel` varchar(60) NOT NULL COMMENT '电话',  `mobile` varchar(60) NOT NULL COMMENT '手机',  `pay_method` enum('wechat','alipay') NOT NULL COMMENT '付款方式ID',  `goods_amount` decimal(10,2) NOT NULL COMMENT '商品的总金',  `order_amount` decimal(10,2) NOT NULL COMMENT '订单应付总金额',  `payed_amount` decimal(10,2) NOT NULL COMMENT '支付总金额',  `shipping_fee` decimal(10,2) NOT NULL COMMENT '配送费用',  `invoice_title` varchar(60) NOT NULL DEFAULT '0' COMMENT '发票抬头',  `confirm_time` int(10) unsigned NOT NULL COMMENT '确认时间',  `note` varchar(255) NOT NULL DEFAULT ' ' COMMENT '备注',  `buyer_note` varchar(200) NOT NULL DEFAULT ' ' COMMENT '买家留言',  `pay_time` int(11) NOT NULL COMMENT '付款时间',  `order_type` enum('normal','group') NOT NULL DEFAULT 'normal' COMMENT '订单类型：normal普通订单，group伙拼订单',  `expire_time` int(11) NOT NULL DEFAULT '0' COMMENT '过期时间',  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '生成订单时间',  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8
DROP TABLE IF EXISTS lj_order_action
CREATE TABLE `lj_order_action` (  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,  `order_id` int(10) unsigned NOT NULL COMMENT '订单ID',  `order_status` enum('sales_return','sales_return_agree','sales_return_appiled','commented','received','shipped','refund','refund_appiled','payed','canceled','shipped','shipped_part','created') NOT NULL DEFAULT 'created' COMMENT '订单状态',  `shipping_status` tinyint(3) NOT NULL COMMENT '配送状态',  `pay_status` tinyint(3) NOT NULL COMMENT '付款状态',  `action_user` varchar(60) NOT NULL COMMENT '操作人员',  `goods_sn` varchar(30) NOT NULL,  `user_id` int(10) NOT NULL,  `action_place` tinyint(2) NOT NULL COMMENT '操作地址',  `money` decimal(10,2) NOT NULL,  `action_note` varchar(250) NOT NULL COMMENT '操作备注',  `log_time` int(10) unsigned NOT NULL COMMENT '日志时间',  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8
DROP TABLE IF EXISTS lj_order_goods
CREATE TABLE `lj_order_goods` (  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,  `member_id` int(10) NOT NULL COMMENT '用户id',  `order_id` int(10) unsigned NOT NULL COMMENT '订单ID',  `goods_id` int(10) unsigned NOT NULL COMMENT '商品ID',  `goods_name` varchar(120) NOT NULL COMMENT '商品名称',  `image` varchar(120) NOT NULL COMMENT '商品缩略图',  `number` int(10) unsigned NOT NULL COMMENT '商品数量',  `price` decimal(10,2) NOT NULL COMMENT '商品价格',  `create_time` int(11) NOT NULL,  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8
DROP TABLE IF EXISTS lj_order_pay_log
CREATE TABLE `lj_order_pay_log` (  `id` int(11) NOT NULL AUTO_INCREMENT,  `order_no` varchar(30) NOT NULL,  `trade_no` varchar(40) NOT NULL,  `bank_seq_no` varchar(30) NOT NULL,  `money` decimal(10,2) NOT NULL,  `buyer_email` varchar(50) NOT NULL,  `buyer_id` varchar(30) NOT NULL,  `pay_type` varchar(15) NOT NULL,  `pay_time` int(11) NOT NULL,  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8
DROP TABLE IF EXISTS lj_quick
CREATE TABLE `lj_quick` (  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,  `uid` int(11) NOT NULL COMMENT '用户UID',  `name` varchar(255) NOT NULL COMMENT '快捷链接的名称',  `url` varchar(255) NOT NULL COMMENT '链接的URL',  `sort` tinyint(4) NOT NULL COMMENT '排序字段',  PRIMARY KEY (`id`)) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8
INSERT INTO lj_quick VALUES( '16','2','备份还原','/thinkcms/index.php/Data','0')
DROP TABLE IF EXISTS lj_rbac_access
CREATE TABLE `lj_rbac_access` (  `role_id` smallint(6) unsigned NOT NULL,  `node_id` smallint(6) unsigned NOT NULL,  `level` tinyint(1) NOT NULL,  `pid` smallint(6) NOT NULL,  `module` varchar(50) DEFAULT NULL,  KEY `groupId` (`role_id`),  KEY `nodeId` (`node_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8
INSERT INTO lj_rbac_access VALUES( '8','1','1','0','')
INSERT INTO lj_rbac_access VALUES( '2','69','2','1','')
INSERT INTO lj_rbac_access VALUES( '2','30','2','1','')
INSERT INTO lj_rbac_access VALUES( '3','1','1','0','')
INSERT INTO lj_rbac_access VALUES( '2','40','2','1','')
INSERT INTO lj_rbac_access VALUES( '2','50','3','40','')
INSERT INTO lj_rbac_access VALUES( '3','50','3','40','')
INSERT INTO lj_rbac_access VALUES( '1','50','3','40','')
INSERT INTO lj_rbac_access VALUES( '3','7','2','1','')
INSERT INTO lj_rbac_access VALUES( '3','39','3','30','')
INSERT INTO lj_rbac_access VALUES( '2','32','3','30','')
INSERT INTO lj_rbac_access VALUES( '2','37','3','30','')
INSERT INTO lj_rbac_access VALUES( '4','1','1','0','')
INSERT INTO lj_rbac_access VALUES( '4','2','2','1','')
INSERT INTO lj_rbac_access VALUES( '4','3','2','1','')
INSERT INTO lj_rbac_access VALUES( '4','4','2','1','')
INSERT INTO lj_rbac_access VALUES( '4','5','2','1','')
INSERT INTO lj_rbac_access VALUES( '4','6','2','1','')
INSERT INTO lj_rbac_access VALUES( '4','7','2','1','')
INSERT INTO lj_rbac_access VALUES( '4','11','2','1','')
INSERT INTO lj_rbac_access VALUES( '5','25','1','0','')
INSERT INTO lj_rbac_access VALUES( '5','51','2','25','')
INSERT INTO lj_rbac_access VALUES( '1','7','2','1','')
INSERT INTO lj_rbac_access VALUES( '1','69','2','1','')
INSERT INTO lj_rbac_access VALUES( '1','30','2','1','')
INSERT INTO lj_rbac_access VALUES( '1','40','2','1','')
INSERT INTO lj_rbac_access VALUES( '3','69','2','1','')
INSERT INTO lj_rbac_access VALUES( '3','30','2','1','')
INSERT INTO lj_rbac_access VALUES( '3','40','2','1','')
INSERT INTO lj_rbac_access VALUES( '1','33','3','30','')
INSERT INTO lj_rbac_access VALUES( '1','34','3','30','')
INSERT INTO lj_rbac_access VALUES( '1','35','3','30','')
INSERT INTO lj_rbac_access VALUES( '1','36','3','30','')
INSERT INTO lj_rbac_access VALUES( '1','37','3','30','')
INSERT INTO lj_rbac_access VALUES( '1','39','3','30','')
INSERT INTO lj_rbac_access VALUES( '1','49','3','30','')
INSERT INTO lj_rbac_access VALUES( '2','39','3','30','')
INSERT INTO lj_rbac_access VALUES( '2','49','3','30','')
INSERT INTO lj_rbac_access VALUES( '7','1','1','0','')
INSERT INTO lj_rbac_access VALUES( '7','30','2','1','')
INSERT INTO lj_rbac_access VALUES( '7','40','2','1','')
INSERT INTO lj_rbac_access VALUES( '7','69','2','1','')
INSERT INTO lj_rbac_access VALUES( '7','50','3','40','')
INSERT INTO lj_rbac_access VALUES( '7','39','3','30','')
INSERT INTO lj_rbac_access VALUES( '7','49','3','30','')
INSERT INTO lj_rbac_access VALUES( '1','1','1','0','')
INSERT INTO lj_rbac_access VALUES( '2','1','1','0','')
INSERT INTO lj_rbac_access VALUES( '2','7','2','1','')
INSERT INTO lj_rbac_access VALUES( '2','31','3','30','')
INSERT INTO lj_rbac_access VALUES( '1','6','2','1','')
INSERT INTO lj_rbac_access VALUES( '1','2','2','1','')
INSERT INTO lj_rbac_access VALUES( '1','98','2','1','')
INSERT INTO lj_rbac_access VALUES( '1','95','2','1','')
INSERT INTO lj_rbac_access VALUES( '1','87','2','1','')
INSERT INTO lj_rbac_access VALUES( '1','92','2','1','')
INSERT INTO lj_rbac_access VALUES( '1','93','2','1','')
INSERT INTO lj_rbac_access VALUES( '1','94','2','1','')
INSERT INTO lj_rbac_access VALUES( '1','96','2','1','')
INSERT INTO lj_rbac_access VALUES( '1','97','2','1','')
INSERT INTO lj_rbac_access VALUES( '9','1','1','0','')
INSERT INTO lj_rbac_access VALUES( '9','101','2','1','')
INSERT INTO lj_rbac_access VALUES( '9','99','2','1','')
INSERT INTO lj_rbac_access VALUES( '9','97','2','1','')
INSERT INTO lj_rbac_access VALUES( '9','96','2','1','')
INSERT INTO lj_rbac_access VALUES( '9','94','2','1','')
INSERT INTO lj_rbac_access VALUES( '9','93','2','1','')
INSERT INTO lj_rbac_access VALUES( '9','92','2','1','')
INSERT INTO lj_rbac_access VALUES( '9','87','2','1','')
INSERT INTO lj_rbac_access VALUES( '9','95','2','1','')
INSERT INTO lj_rbac_access VALUES( '9','98','2','1','')
INSERT INTO lj_rbac_access VALUES( '9','6','2','1','')
INSERT INTO lj_rbac_access VALUES( '9','7','2','1','')
INSERT INTO lj_rbac_access VALUES( '9','69','2','1','')
INSERT INTO lj_rbac_access VALUES( '9','31','3','30','')
INSERT INTO lj_rbac_access VALUES( '9','32','3','30','')
INSERT INTO lj_rbac_access VALUES( '9','39','3','30','')
INSERT INTO lj_rbac_access VALUES( '9','49','3','30','')
INSERT INTO lj_rbac_access VALUES( '9','30','2','1','')
INSERT INTO lj_rbac_access VALUES( '9','40','2','1','')
INSERT INTO lj_rbac_access VALUES( '9','50','3','40','')
INSERT INTO lj_rbac_access VALUES( '9','102','3','40','')
INSERT INTO lj_rbac_access VALUES( '8','40','2','1','')
INSERT INTO lj_rbac_access VALUES( '8','30','2','1','')
DROP TABLE IF EXISTS lj_rbac_group
CREATE TABLE `lj_rbac_group` (  `id` smallint(3) unsigned NOT NULL AUTO_INCREMENT,  `name` varchar(25) NOT NULL,  `title` varchar(50) NOT NULL,  `create_time` int(11) unsigned NOT NULL,  `update_time` int(11) unsigned NOT NULL DEFAULT '0',  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',  `sort` smallint(3) unsigned NOT NULL DEFAULT '0',  `show` tinyint(1) unsigned NOT NULL DEFAULT '0',  PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8
INSERT INTO lj_rbac_group VALUES( '2','App','应用中心','1222841259','0','1','0','0')
DROP TABLE IF EXISTS lj_rbac_node
CREATE TABLE `lj_rbac_node` (  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,  `name` varchar(20) NOT NULL,  `title` varchar(50) DEFAULT NULL,  `status` tinyint(1) DEFAULT '0',  `remark` varchar(255) DEFAULT NULL,  `sort` smallint(6) unsigned DEFAULT NULL,  `pid` smallint(6) unsigned NOT NULL,  `level` tinyint(1) unsigned NOT NULL,  `type` tinyint(1) NOT NULL DEFAULT '0',  `group_id` tinyint(3) unsigned DEFAULT '0',  PRIMARY KEY (`id`),  KEY `level` (`level`),  KEY `pid` (`pid`),  KEY `status` (`status`),  KEY `name` (`name`)) ENGINE=MyISAM AUTO_INCREMENT=142 DEFAULT CHARSET=utf8
INSERT INTO lj_rbac_node VALUES( '49','read','查看','1','','','30','3','0','0')
INSERT INTO lj_rbac_node VALUES( '40','Index','默认模块','1','','5','1','2','0','2')
INSERT INTO lj_rbac_node VALUES( '39','index','列表','1','','','30','3','0','0')
INSERT INTO lj_rbac_node VALUES( '37','resume','恢复','1','','','30','3','0','0')
INSERT INTO lj_rbac_node VALUES( '36','forbid','禁用','1','','','30','3','0','0')
INSERT INTO lj_rbac_node VALUES( '35','foreverdelete','删除','1','','','30','3','0','0')
INSERT INTO lj_rbac_node VALUES( '34','update','更新','1','','','30','3','0','0')
INSERT INTO lj_rbac_node VALUES( '33','edit','编辑','1','','','30','3','0','0')
INSERT INTO lj_rbac_node VALUES( '32','insert','写入','1','','','30','3','0','0')
INSERT INTO lj_rbac_node VALUES( '31','add','新增','1','','','30','3','0','0')
INSERT INTO lj_rbac_node VALUES( '30','Public','公共模块','1','','6','1','2','0','2')
INSERT INTO lj_rbac_node VALUES( '69','Data','备份还原','1','','10','1','2','0','2')
INSERT INTO lj_rbac_node VALUES( '7','Member','会员列表','1','','10','1','2','0','2')
INSERT INTO lj_rbac_node VALUES( '6','Role','角色管理','1','','8','1','2','0','2')
INSERT INTO lj_rbac_node VALUES( '2','Node','节点管理','1','','7','1','2','0','2')
INSERT INTO lj_rbac_node VALUES( '1','home','系统后台管理','1','','2','0','1','0','2')
INSERT INTO lj_rbac_node VALUES( '50','main','空白首页','1','','','40','3','0','0')
INSERT INTO lj_rbac_node VALUES( '83','home','系统前台页面','-1','前台控制权限','1','0','1','0','2')
INSERT INTO lj_rbac_node VALUES( '84','add','添加','1','添加','','2','3','0','0')
INSERT INTO lj_rbac_node VALUES( '98','Goods','商品管理','1','商品管理','6','1','2','0','2')
INSERT INTO lj_rbac_node VALUES( '95','InfoCategory','信息分类','1','信息分类','1','1','2','0','2')
INSERT INTO lj_rbac_node VALUES( '86','control','控制','1','控制操作','','30','0','0','0')
INSERT INTO lj_rbac_node VALUES( '87','Menu','菜单管理','1','菜单管理','3','1','2','0','2')
INSERT INTO lj_rbac_node VALUES( '88','Index','网站首页','1','网站首页','','83','0','0','0')
INSERT INTO lj_rbac_node VALUES( '89','add','添加操作','1','添加操作','','87','0','0','0')
INSERT INTO lj_rbac_node VALUES( '90','edit','编辑操作','1','编辑操作','','87','0','0','0')
INSERT INTO lj_rbac_node VALUES( '91','index','菜单列表','1','菜单列表','','87','0','0','0')
INSERT INTO lj_rbac_node VALUES( '92','Image','图片管理','1','图片管理','2','1','2','0','2')
INSERT INTO lj_rbac_node VALUES( '93','Category','产品分类','-1','类别管理 优化的类别管理','1','1','2','0','2')
INSERT INTO lj_rbac_node VALUES( '94','Info','信息管理','1','资讯管理模块','2','1','2','0','2')
INSERT INTO lj_rbac_node VALUES( '96','Withdraw/apply','提现申请','1','提现申请','1','1','2','0','2')
INSERT INTO lj_rbac_node VALUES( '97','Order','商品订单','1','订单列表','10','1','2','0','2')
INSERT INTO lj_rbac_node VALUES( '99','Extend','拓展分类','1','拓展分类','0','1','2','0','2')
INSERT INTO lj_rbac_node VALUES( '100','User','用户管理','1','用户管理','0','1','2','0','2')
INSERT INTO lj_rbac_node VALUES( '101','Index/index','网站首页','1','网站首页','0','1','2','0','2')
INSERT INTO lj_rbac_node VALUES( '102','index','首页操作','1','首页操作','','40','3','0','2')
INSERT INTO lj_rbac_node VALUES( '103','Category','商品分类','1','产品分类','2','1','2','0','2')
INSERT INTO lj_rbac_node VALUES( '104','index','产品列表','1','产品列表','','103','3','0','2')
INSERT INTO lj_rbac_node VALUES( '105','Property','属性管理','1','属性管理','1','1','2','0','2')
INSERT INTO lj_rbac_node VALUES( '106','Service','售后服务','1','售后服务','1','1','2','0','2')
INSERT INTO lj_rbac_node VALUES( '107','Shop','店铺管理','1','体验店管理','1','1','2','0','2')
INSERT INTO lj_rbac_node VALUES( '108','AdvPosition','广告位置','1','位置管理','2','1','2','0','0')
INSERT INTO lj_rbac_node VALUES( '109','Adv','广告图片','1','广告图片','1','1','2','0','0')
INSERT INTO lj_rbac_node VALUES( '110','Goods/recover','商品回收','1','商品回收','1','1','2','0','2')
INSERT INTO lj_rbac_node VALUES( '111','Brand','品牌管理','1','品牌管理','2','1','2','0','0')
INSERT INTO lj_rbac_node VALUES( '112','Data/cache','缓存管理','1','缓存管理','0','1','2','0','2')
INSERT INTO lj_rbac_node VALUES( '113','Withdraw/manage','会员总账','1','会员总账','1','1','2','0','2')
INSERT INTO lj_rbac_node VALUES( '114','Wholesaler','批发商认证','1','批发商认证','0','1','2','0','2')
INSERT INTO lj_rbac_node VALUES( '115','Comment','评论管理','1','评论管理','0','1','2','0','2')
INSERT INTO lj_rbac_node VALUES( '116','Invite','邀请管理','1','邀请管理','1','1','2','0','2')
INSERT INTO lj_rbac_node VALUES( '117','Wishtobuy','WishToBuy','1','WishToBuy管理','0','1','2','0','2')
INSERT INTO lj_rbac_node VALUES( '118','Setting','系统设置','1','系统设置','0','1','2','0','2')
INSERT INTO lj_rbac_node VALUES( '119','Factory','厂商申请','-1','厂商申请','','0','1','0','2')
INSERT INTO lj_rbac_node VALUES( '120','Factory','厂商申请','1','厂商申请','0','1','2','0','2')
INSERT INTO lj_rbac_node VALUES( '121','Order/returnList','退货管理','1','退货管理','','96','3','0','2')
INSERT INTO lj_rbac_node VALUES( '122','Order/refundList','退款管理','1','退款管理','0','1','2','0','2')
INSERT INTO lj_rbac_node VALUES( '123','Order/returnList','退货管理','1','退货管理','0','1','2','0','2')
INSERT INTO lj_rbac_node VALUES( '124','Email','邮件模板','1','邮件模板','0','1','2','0','2')
INSERT INTO lj_rbac_node VALUES( '125','Charity','慈善列表','1','慈善列表','0','1','2','0','2')
INSERT INTO lj_rbac_node VALUES( '126','Charity/apply','慈善申请','1','慈善申请','0','1','2','0','2')
INSERT INTO lj_rbac_node VALUES( '127','Financial','工厂货款','1','工厂货款','0','1','2','0','2')
INSERT INTO lj_rbac_node VALUES( '128','Presale','预售管理','1','预售管理','1','1','2','0','2')
INSERT INTO lj_rbac_node VALUES( '129','Order/recycleList','订单回收','1','订单回收','0','1','2','0','2')
INSERT INTO lj_rbac_node VALUES( '130','Member/recoverMember','会员回收','1','会员回收','0','1','2','0','2')
INSERT INTO lj_rbac_node VALUES( '131','Charity/recycle','项目回收','1','Charity/recycle','0','1','2','0','2')
INSERT INTO lj_rbac_node VALUES( '132','PayLog','支付记录','1','支付记录','0','1','2','0','2')
INSERT INTO lj_rbac_node VALUES( '133','WorksCategory','Works分类','1','Works分类','0','1','2','0','2')
INSERT INTO lj_rbac_node VALUES( '134','Label','标签管理','1','标签管理','0','1','2','0','2')
INSERT INTO lj_rbac_node VALUES( '135','Notice','通知管理','1','通知管理','0','1','2','0','2')
INSERT INTO lj_rbac_node VALUES( '136','Comment/userComment','会员评论管理','1','会员评论管理','0','1','2','0','2')
INSERT INTO lj_rbac_node VALUES( '137','Works/index','works管理','1','','0','1','2','0','2')
INSERT INTO lj_rbac_node VALUES( '138','Hatch/hatch_order','买断与孵化订单','1','买断与孵化订单','2','1','2','0','2')
INSERT INTO lj_rbac_node VALUES( '139','HatchApply','孵化申请管理','1','孵化申请管理','0','1','2','0','2')
INSERT INTO lj_rbac_node VALUES( '140','Hatch','孵化管理','1','孵化管理','0','1','2','0','2')
INSERT INTO lj_rbac_node VALUES( '141','OrderWholesale','批发订单列表','1','批发订单列表','','1','2','0','2')
DROP TABLE IF EXISTS lj_rbac_role
CREATE TABLE `lj_rbac_role` (  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,  `name` varchar(20) NOT NULL,  `pid` smallint(6) DEFAULT NULL,  `status` tinyint(1) unsigned DEFAULT NULL,  `remark` varchar(255) DEFAULT NULL,  `ename` varchar(5) DEFAULT NULL,  `create_time` int(11) unsigned NOT NULL,  `update_time` int(11) unsigned NOT NULL,  `sort` tinyint(5) NOT NULL,  PRIMARY KEY (`id`),  KEY `parentId` (`pid`),  KEY `ename` (`ename`),  KEY `status` (`status`)) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8
INSERT INTO lj_rbac_role VALUES( '1','领导组','0','1','','','1208784792','1254325558','4')
INSERT INTO lj_rbac_role VALUES( '2','员工组','0','1','','','1215496283','1254325566','3')
INSERT INTO lj_rbac_role VALUES( '7','演示组','0','1','','','1254325787','0','2')
INSERT INTO lj_rbac_role VALUES( '8','APP用户','0','1','APP登陆用','','1339512011','1392802457','1')
INSERT INTO lj_rbac_role VALUES( '9','管理员组','0','1','管理员组','','1373531792','0','0')
DROP TABLE IF EXISTS lj_region
CREATE TABLE `lj_region` (  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,  `parent_id` smallint(5) unsigned NOT NULL DEFAULT '0',  `name` varchar(120) NOT NULL DEFAULT '',  `type` tinyint(1) NOT NULL DEFAULT '2',  `agency_id` smallint(5) unsigned NOT NULL DEFAULT '0',  PRIMARY KEY (`id`),  KEY `parent_id` (`parent_id`),  KEY `region_type` (`type`),  KEY `agency_id` (`agency_id`)) ENGINE=MyISAM AUTO_INCREMENT=3412 DEFAULT CHARSET=utf8
DROP TABLE IF EXISTS lj_user
CREATE TABLE `lj_user` (  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,  `username` varchar(64) NOT NULL COMMENT '账号',  `password` char(32) NOT NULL COMMENT '密码',  `nickname` varchar(50) NOT NULL COMMENT '昵称',  `realname` varchar(50) NOT NULL COMMENT '真实名字',  `gender` tinyint(2) NOT NULL COMMENT '性别',  `login_count` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '总的登录次数',  `verify` varchar(32) NOT NULL,  `email` varchar(50) NOT NULL,  `remark` varchar(255) NOT NULL COMMENT '标记',  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '用户状态',  `sort` tinyint(5) NOT NULL,  `last_login_ip` varchar(40) NOT NULL COMMENT '最后登录IP',  `last_login_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',  `create_time` int(11) unsigned NOT NULL,  `update_time` int(11) unsigned NOT NULL,  PRIMARY KEY (`id`),  UNIQUE KEY `username` (`username`) USING BTREE) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8
INSERT INTO lj_user VALUES( '1','admin','81dc9bdb52d04dc20036dbd8313ed055','管理员','','0','1587','8888','liu21st@gmail.com','备注信息','1','0','127.0.0.1','1413862737','1222907803','1392621969')
INSERT INTO lj_user VALUES( '2','demo','fe01ce2a7fbac8fafaed7c982a04e229','演示','','0','92','8888','','','1','4','127.0.0.1','1378721916','1239783735','1254325770')
INSERT INTO lj_user VALUES( '3','member','aa08769cdcb26674c6706093503ff0a3','员工','','0','17','','','','1','9','127.0.0.1','1326266720','1253514375','1392623016')
INSERT INTO lj_user VALUES( '4','leader','c444858e0aaeb727da73d2eae62321ad','领导','','0','17','','','领导','1','3','127.0.0.1','1373534817','1253514575','1254325705')
INSERT INTO lj_user VALUES( '36','liu86th','e10adc3949ba59abbe56e057f20f883e','测试','','0','11','','','用户备足','1','8','127.0.0.1','1378719850','1339829161','1392623009')
INSERT INTO lj_user VALUES( '37','samliu','e10adc3949ba59abbe56e057f20f883e','samliu','','0','0','','','samliu','1','0','','0','1373447740','0')
INSERT INTO lj_user VALUES( '38','testuser','16d7a4fca7442dda3ad93c9a726597e4','testuser','','0','10','','','','1','0','183.16.193.58','1398650491','1392621397','0')
INSERT INTO lj_user VALUES( '39','PaulChen','96e79218965eb72c92a549dd5a330112','Paul','','0','0','','','President','1','0','','0','1392802288','0')
INSERT INTO lj_user VALUES( '40','AndyLin','96e79218965eb72c92a549dd5a330112','Andy','','0','0','','','','1','0','','0','1392802306','0')
INSERT INTO lj_user VALUES( '41','KlausLuo','96e79218965eb72c92a549dd5a330112','Klaus','','0','0','','','','1','0','','0','1392802368','0')
INSERT INTO lj_user VALUES( '42','stella','96e79218965eb72c92a549dd5a330112','tester','','0','2','','','tester','-1','0','183.16.195.135','1393209117','1392976464','1392976771')
INSERT INTO lj_user VALUES( '43','AaronGuo','96e79218965eb72c92a549dd5a330112','Aaron','','0','0','','','','1','0','','0','1393209743','0')
INSERT INTO lj_user VALUES( '44','StellaXu','96e79218965eb72c92a549dd5a330112','Stella','','0','0','','','','1','0','','0','1393209949','0')
INSERT INTO lj_user VALUES( '45','jeffliu','96e79218965eb72c92a549dd5a330112','jeffliu','','0','0','','','','1','0','','0','1394258721','0')
INSERT INTO lj_user VALUES( '46','derrickwang','96e79218965eb72c92a549dd5a330112','derrick','','0','0','','','','1','0','','0','1394762841','0')
INSERT INTO lj_user VALUES( '47','tonyhe','ddc5f5e86d2f85e1b1ff763aff13ce0a','tony','','0','0','','','此用户只能读写自己的记录，不能读写其他用户记录信息。','1','0','','0','1395656135','1395656375')
INSERT INTO lj_user VALUES( '48','andywu','da41bceff97b1cf96078ffb249b3d66e','andy','','0','0','','','此用户只能读写自己的记录，不能读写其他用户记录信息。','1','0','','0','1395656200','1395656395')
INSERT INTO lj_user VALUES( '49','williamcheng','fd820a2b4461bddd116c1518bc4b0f77','william','','0','0','','','此用户只能读写自己的记录，不能读写其他用户记录信息。','1','0','','0','1395656250','1395656410')
