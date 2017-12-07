-- MySQL dump 10.14  Distrib 5.5.56-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: think_niexin_me
-- ------------------------------------------------------
-- Server version	5.5.56-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `think_jdprice`
--

DROP TABLE IF EXISTS `think_jdprice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `think_jdprice` (
  `product_name` mediumtext COLLATE utf8_unicode_ci NOT NULL COMMENT '产品名称',
  `product_category` mediumtext COLLATE utf8_unicode_ci NOT NULL COMMENT '产品类别',
  `product_Price` int(1) NOT NULL COMMENT '产品价格',
  `product_number` int(1) NOT NULL COMMENT '产品编号',
  `product_time` datetime DEFAULT NULL COMMENT '价格更新时间',
  PRIMARY KEY (`product_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `think_member`
--

DROP TABLE IF EXISTS `think_member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `think_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '会员ID号',
  `member_user` varchar(25) COLLATE utf8_unicode_ci NOT NULL COMMENT '注册名称',
  `member_password` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT '注册密码',
  `member_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '真实姓名',
  `member_sex` varchar(1) COLLATE utf8_unicode_ci NOT NULL COMMENT '性别',
  `member_qq` int(11) NOT NULL COMMENT 'QQ号',
  `member_phone` varchar(15) COLLATE utf8_unicode_ci NOT NULL COMMENT '手机号',
  `member_email` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'email',
  PRIMARY KEY (`id`),
  UNIQUE KEY `member_account` (`member_user`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `think_task`
--

DROP TABLE IF EXISTS `think_task`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `think_task` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '计划编号',
  `status` int(1) NOT NULL COMMENT '任务执行状态',
  `task_value` mediumtext COLLATE utf8_unicode_ci NOT NULL COMMENT '计划详情',
  `task_time` datetime DEFAULT NULL COMMENT '最后更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `think_webinfo`
--

DROP TABLE IF EXISTS `think_webinfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `think_webinfo` (
  `id` int(1) NOT NULL AUTO_INCREMENT COMMENT '保存编号',
  `domain` mediumtext COLLATE utf8_unicode_ci NOT NULL COMMENT '绑定域名',
  `title` mediumtext COLLATE utf8_unicode_ci NOT NULL COMMENT '站点标题',
  `tomailser` mediumtext COLLATE utf8_unicode_ci NOT NULL COMMENT '邮件推送服务器',
  `toport` int(1) NOT NULL COMMENT '邮件推送服务器端口',
  `toname` mediumtext COLLATE utf8_unicode_ci NOT NULL COMMENT '推送授权账号',
  `topwd` mediumtext COLLATE utf8_unicode_ci NOT NULL COMMENT '推送授权密码',
  `getmailser` mediumtext COLLATE utf8_unicode_ci NOT NULL COMMENT '邮箱服务器',
  `getport` int(1) NOT NULL COMMENT '邮箱服务器端口',
  `getname` mediumtext COLLATE utf8_unicode_ci NOT NULL COMMENT '邮箱账号',
  `getpwd` mediumtext COLLATE utf8_unicode_ci NOT NULL COMMENT '邮箱授权密码',
  `up_time` datetime DEFAULT NULL COMMENT '最后更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-12-07 13:47:18
