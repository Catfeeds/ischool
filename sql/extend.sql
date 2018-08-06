-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 2016-12-02 10:14:57
-- 服务器版本： 5.7.14
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `yii2`
--

-- --------------------------------------------------------

--
-- 表的结构 `auth_assignment`
--

CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `auth_assignment`
--

INSERT INTO `auth_assignment` (`item_name`, `user_id`, `created_at`) VALUES
('客服', '1', 1480482769),
('客服', '2', 1480468055);

-- --------------------------------------------------------

--
-- 表的结构 `auth_item`
--

CREATE TABLE `auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `auth_item`
--

INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES
('school/delete', 2, '学校记录删除', NULL, NULL, 1480467987, 1480467987),
('school/edit', 2, '学校信息编辑', NULL, NULL, 1480467918, 1480467918),
('school/index', 2, '学校记录查看', NULL, NULL, 1480467826, 1480467826),
('school/update', 2, '学校记录更新', NULL, NULL, 1480467965, 1480467965),
('客服', 1, '普通客服', NULL, NULL, 1480466772, 1480561773);

-- --------------------------------------------------------

--
-- 表的结构 `auth_item_child`
--

CREATE TABLE `auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `auth_item_child`
--

INSERT INTO `auth_item_child` (`parent`, `child`) VALUES
('客服', 'school/delete'),
('客服', 'school/edit'),
('客服', 'school/index'),
('客服', 'school/update');

-- --------------------------------------------------------

--
-- 表的结构 `auth_rule`
--

CREATE TABLE `auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `migration`
--

CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1479785795),
('m140209_132017_init', 1479785805),
('m140403_174025_create_account_table', 1479785806),
('m140504_113157_update_tables', 1479785810),
('m140504_130429_create_token_table', 1479785811),
('m140830_171933_fix_ip_field', 1479785812),
('m140830_172703_change_account_table_name', 1479785812),
('m141222_110026_update_ip_field', 1479785813),
('m141222_135246_alter_username_length', 1479785814),
('m150614_103145_update_social_account_table', 1479785816),
('m150623_212711_fix_username_notnull', 1479785817),
('m151218_234654_add_timezone_to_profile', 1479785818),
('m140506_102106_rbac_init', 1479786073);

-- --------------------------------------------------------

--
-- 表的结构 `profile`
--

CREATE TABLE `profile` (
  `user_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `public_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gravatar_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gravatar_id` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bio` text COLLATE utf8_unicode_ci,
  `timezone` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `profile`
--

INSERT INTO `profile` (`user_id`, `name`, `public_email`, `gravatar_email`, `gravatar_id`, `location`, `website`, `bio`, `timezone`) VALUES
(1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `social_account`
--

CREATE TABLE `social_account` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `provider` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `client_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `code` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `token`
--

CREATE TABLE `token` (
  `user_id` int(11) NOT NULL,
  `code` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) NOT NULL,
  `type` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `confirmed_at` int(11) DEFAULT NULL,
  `unconfirmed_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `blocked_at` int(11) DEFAULT NULL,
  `registration_ip` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `flags` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password_hash`, `auth_key`, `confirmed_at`, `unconfirmed_email`, `blocked_at`, `registration_ip`, `created_at`, `updated_at`, `flags`) VALUES
(1, 'lee', 'test@lee.com', '$2y$10$1BPCWtoNFfjsmYV8k7nbVuXxhD3TycPdJ5.35Rr18kc5tR8GoCp/W', 'KbD8UekX1wJ2hj39MQT06QWYgZo_7_gE', 1479786477, NULL, NULL, NULL, 1479786477, 1479786477, 0),
(2, 'ttt', 'ttt@lee.com', '$2y$10$FXnLT4d.wkZPXBusz.OCQ.aT86yA5z3KGYcK13ckExjJ5VSrefdBy', '_nfFGejRZGsUL4vKZw5rexLpzPXos7yr', 1480466665, NULL, NULL, '127.0.0.1', 1480466665, 1480466665, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD PRIMARY KEY (`item_name`,`user_id`);

--
-- Indexes for table `auth_item`
--
ALTER TABLE `auth_item`
  ADD PRIMARY KEY (`name`),
  ADD KEY `rule_name` (`rule_name`),
  ADD KEY `idx-auth_item-type` (`type`);

--
-- Indexes for table `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD PRIMARY KEY (`parent`,`child`),
  ADD KEY `child` (`child`);

--
-- Indexes for table `auth_rule`
--
ALTER TABLE `auth_rule`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `migration`
--
ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `social_account`
--
ALTER TABLE `social_account`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `account_unique` (`provider`,`client_id`),
  ADD UNIQUE KEY `account_unique_code` (`code`),
  ADD KEY `fk_user_account` (`user_id`);

--
-- Indexes for table `token`
--
ALTER TABLE `token`
  ADD UNIQUE KEY `token_unique` (`user_id`,`code`,`type`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_unique_email` (`email`),
  ADD UNIQUE KEY `user_unique_username` (`username`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `social_account`
--
ALTER TABLE `social_account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- 限制导出的表
--

--
-- 限制表 `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 限制表 `auth_item`
--
ALTER TABLE `auth_item`
  ADD CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- 限制表 `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 限制表 `profile`
--
ALTER TABLE `profile`
  ADD CONSTRAINT `fk_user_profile` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- 限制表 `social_account`
--
ALTER TABLE `social_account`
  ADD CONSTRAINT `fk_user_account` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- 限制表 `token`
--
ALTER TABLE `token`
  ADD CONSTRAINT `fk_user_token` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
