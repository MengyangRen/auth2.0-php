/*
Navicat MySQL Data Transfer

Source Server         : root@zhicloud.dev
Source Server Version : 50636
Source Host           : 172.18.10.168:3306
Source Database       : ZhiCloudAuth

Target Server Type    : MYSQL
Target Server Version : 50636
File Encoding         : 65001

Date: 2018-04-02 16:10:50
*/

CREATE  DATABASE ZhiCloudAuth CHARSET UTF8;

use ZhiCloudAuth;

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for auth_authorization_codes
-- ----------------------------
DROP TABLE IF EXISTS `auth_authorization_codes`;
CREATE TABLE `auth_authorization_codes` (
  `authorization_code` varchar(40) NOT NULL COMMENT '授予的认证Code ',
  `client_id` varchar(80) NOT NULL COMMENT '第三方企业的OpenKey',
  `user_id` varchar(255) DEFAULT NULL COMMENT '该用户的Id',
  `expires` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '过期时间',
  `scope` varchar(2000) DEFAULT NULL COMMENT '权限列表，getUserInfo,getCompanyInfo',
  PRIMARY KEY (`authorization_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='对第三企业授予的认证码';

-- ----------------------------
-- Records of auth_authorization_codes
-- ----------------------------
INSERT INTO `auth_authorization_codes` VALUES ('c3a8ad070c745db455e30a51511dca819b607bf7', '1102084294', '1152', '2018-04-03 05:24:15', 'getUserInfo,getCompanyInfo');



-- ----------------------------
-- Table structure for auth_clients
-- ----------------------------
DROP TABLE IF EXISTS `auth_clients`;
CREATE TABLE `auth_clients` (
  `client_id` varchar(10) NOT NULL COMMENT '授予第三方企业open_key ',
  `client_secret` varchar(16) NOT NULL COMMENT '授予第三方企业的16位md5值 密钥',
  `scope` varchar(2000) DEFAULT NULL COMMENT '权限{getUserInfo,getCompanyInfo}',
  PRIMARY KEY (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='认证的第三方企业Key与权限';

-- ----------------------------
-- Records of auth_clients
-- ----------------------------
INSERT INTO `auth_clients` VALUES ('1102084294', 'aku8hCyXBd9SqYkT', 'basic');




INSERT INTO `auth_clients` VALUES ('1102084295', 'bku9hCyxjd9SqYGT', 'basic');
INSERT INTO `auth_clients` VALUES ('1102084296', 'bkx4hCyXBd4cqYNT', 'basic');
INSERT INTO `auth_clients` VALUES ('1102084297', 'ckx4hCyXBd4cqYCT', 'basic');
INSERT INTO `auth_clients` VALUES ('1102084298', 'gku6hCyXBd9SqYkC', 'basic');
INSERT INTO `auth_clients` VALUES ('1102084299', 'yku5hCyXBd6SqYCT', 'basic');
INSERT INTO `auth_clients` VALUES ('1102084300', 'lku3hCyXBd3SqYNT', 'basic');
INSERT INTO `auth_clients` VALUES ('1102084301', 'qku2hCyXBd8SqYCT', 'basic');
INSERT INTO `auth_clients` VALUES ('1102084302', 'kku2hCyXBd2SqYGT', 'basic');
INSERT INTO `auth_clients` VALUES ('1102084303', 'aku6xCyXBd9SqYkT', 'basic');
INSERT INTO `auth_clients` VALUES ('1102084304', 'aku7GCyXBd9SqYkT', 'basic');
INSERT INTO `auth_clients` VALUES ('1102084305', 'hku1HCyXBd9SqYkT', 'basic');
INSERT INTO `auth_clients` VALUES ('1102084306', 'cku7fCyXBd3SqYkT', 'basic');
INSERT INTO `auth_clients` VALUES ('1102084307', 'hku3fCyXBd5SqYkT', 'basic');
INSERT INTO `auth_clients` VALUES ('1102084308', 'eku4fCyXBd5SqYkT', 'basic');
INSERT INTO `auth_clients` VALUES ('1102084309', 'vku4fCyXBd2SqYJT', 'basic');
INSERT INTO `auth_clients` VALUES ('1102084310', 'xku5hCyXBd0SqYkT', 'basic');
INSERT INTO `auth_clients` VALUES ('1102084311', 'mku2rCyXBd7SqYCT', 'basic');
INSERT INTO `auth_clients` VALUES ('1102084312', 'bku5aCyXBd4SqYMT', 'basic');
INSERT INTO `auth_clients` VALUES ('1102084313', 'xku8gCyXBd3SqYCT', 'basic');
INSERT INTO `auth_clients` VALUES ('1102084314', 'pku7kCyXBd8SqYUT', 'basic');

INSERT INTO `auth_developer_company` VALUES 
('1102084295', '税云网络科技服务有限公司', '2503', '2504', '2509', '402', '张三', '13881741227', 'basiczhangsan@163.com');
INSERT INTO `auth_developer_company` VALUES 
('1102084296', '新疆京瑞科贸有限公司', '2503', '2504', '2509', '402', '李四', '13881741257', 'basiclisi@163.com');
INSERT INTO `auth_developer_company` VALUES 
('1102084297', '克拉玛依新科澳油田运输有限公司', '2503', '2504', '2509', '402', '王五', '13881641247', 'basicwu@163.com');
INSERT INTO `auth_developer_company` VALUES 
('1102084298', '新疆千翼建设工程有限公司', '2503', '2504', '2509', '402', '青牛', '13881841247', 'basicqn@163.com');
INSERT INTO `auth_developer_company` VALUES 
('1102084299', '新疆启明火炬信息技术有限公司', '2503', '2504', '2509', '402', '起风', '13881731247', 'basicnb@163.com');
INSERT INTO `auth_developer_company` VALUES 
('1102084300', '新疆国检检测集团有限公司', '2503', '2504', '2509', '402', '温馨', '13881791247', 'basicop@163.com');
INSERT INTO `auth_developer_company` VALUES 
('1102084301', '新疆华信普为信息技术有限公司', '2503', '2504', '2509', '402', '土丘', '13681741247', 'basictc@163.com');
INSERT INTO `auth_developer_company` VALUES 
('1102084302', '新疆天阳房地产开发有限公司', '2503', '2504', '2509', '402', '戴安娜', '13581741247', 'basicot@163.com');
INSERT INTO `auth_developer_company` VALUES 
('1102084303', '新疆美汇佳源园林绿化工程有限公司', '2503', '2504', '2509', '402', '亚索', '13891741247', 'basicit@163.com');
INSERT INTO `auth_developer_company` VALUES 
('1102084304', '新疆冠铧兆丰商贸有限公司', '2503', '2504', '2509', '402', '牛人', '13881711247', 'basicof@163.com');
INSERT INTO `auth_developer_company` VALUES 
('1102084305', '新疆再生资源集团有限公司', '2503', '2504', '2509', '402', '德玛', '13881721247', 'basicin@163.com');
INSERT INTO `auth_developer_company` VALUES 
('1102084306', '乌鲁木齐协创电子科技有限公司', '2503', '2504', '2509', '402', '赵信', '13881741226', 'basiczhaoxin@163.com');
INSERT INTO `auth_developer_company` VALUES 
('1102084307', '新疆兴盛宏达商贸有限公司', '2503', '2504', '2509', '402', '赵钱', '13881741251', 'basiczhaoqian@163.com');
INSERT INTO `auth_developer_company` VALUES 
('1102084308', '乌鲁木齐新西友安全防范有限责任公司', '2503', '2504', '2509', '402', '孙武', '13881641242', 'basicsuiwu@163.com');
INSERT INTO `auth_developer_company` VALUES 
('1102084309', '新疆威力特生物技术有限公司', '2503', '2504', '2509', '402', '钱峰', '13881841243', 'basicqfeng@163.com');
INSERT INTO `auth_developer_company` VALUES 
('1102084310', '新疆楷慧德电子有限公司', '2503', '2504', '2509', '402', '李六', '13881731248', 'basiclisi@163.com');
INSERT INTO `auth_developer_company` VALUES 
('1102084311', '新疆远大茂业投资有限责任公司', '2503', '2504', '2509', '402', '王七', '13841791242', 'basicwq@163.com');
INSERT INTO `auth_developer_company` VALUES 
('1102084312', '新疆沐百川能源有限公司', '2503', '2504', '2509', '402', '小明', '13281741245', 'basicxm@163.com');
INSERT INTO `auth_developer_company` VALUES 
('1102084313', '乌鲁木齐市灵创宏达电气有限公司', '2503', '2504', '2509', '402', '凯撒', '13481741241', 'basicks@163.com');
INSERT INTO `auth_developer_company` VALUES 
('1102084314', '新疆中岳恒基环保科技有限公司', '2503', '2504', '2509', '402', '诸葛亮', '13291741245', 'basiczgl@163.com');

-- ----------------------------
-- Table structure for auth_developer_company
-- ----------------------------
DROP TABLE IF EXISTS `auth_developer_company`;
CREATE TABLE `auth_developer_company` (
  `client_id` varchar(10) NOT NULL COMMENT '授予第三方企业open_key ',
  `developer_company` varchar(300) NOT NULL COMMENT '第三方企业',
  `province` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '省',
  `city` mediumint(8) unsigned NOT NULL COMMENT '市',
  `zone` mediumint(8) unsigned NOT NULL COMMENT '区',
  `trade` mediumint(8) unsigned NOT NULL COMMENT '行业',
  `contacts` varchar(300) NOT NULL COMMENT '联系人',
  `phone` varchar(11) NOT NULL COMMENT '手机号',
  `email` varchar(300) NOT NULL DEFAULT '' COMMENT '邮件',
  PRIMARY KEY (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='认证的第三方企业';

-- ----------------------------
-- Records of auth_developer_company
-- ----------------------------
INSERT INTO `auth_developer_company` VALUES ('1102084294', 'TestBack', '2503', '2504', '2509', '402', '任x样', '13881741247', 'renmenxxx@163.com');

-- ----------------------------
-- Table structure for auth_scopes
-- ----------------------------
DROP TABLE IF EXISTS `auth_scopes`;
CREATE TABLE `auth_scopes` (
  `scope` varchar(300) DEFAULT NULL COMMENT '权限名',
  `as` varchar(300) DEFAULT NULL COMMENT '权限别名',
  `is_default` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='对第三方企业提供的权限列';

-- ----------------------------
-- Records of auth_scopes
-- ----------------------------
INSERT INTO `auth_scopes` VALUES ('getUserInfo', 'basic', null);
INSERT INTO `auth_scopes` VALUES ('getCompanyInfo', 'basic', null);




DROP TABLE IF EXISTS `app_rotation_pic`;

CREATE TABLE `app_rotation_pic`(
  
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `title` varchar(200) NOT NULL DEFAULT '' COMMENT '广告',
  `url` varchar(500) NOT NULL DEFAULT '' COMMENT '广告图片存放路径',
  `sort` tinyint(1) NOT NULL DEFAULT '0' COMMENT '排序字段',
  `desc` text NOT NULL COMMENT '描述',
  `created` int(10) unsigned NOT NULL COMMENT '创建时间',
  `num` int(22) NOT NULL DEFAULT '0' COMMENT '点击次数',
  `hzs_id` mediumint(8) unsigned NOT NULL DEFAULT '8888' COMMENT '合作商id，8888致云',
  `_md5` varchar(32) NOT NULL DEFAULT '' COMMENT 'md5(id/name/url),主要考虑统计'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='对第三方企业提供的权限列';
