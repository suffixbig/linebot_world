-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- 主機： localhost:33306
-- 產生時間： 2019 年 11 月 15 日 13:54
-- 伺服器版本： 10.3.9-MariaDB
-- PHP 版本： 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `line_bot`
--

-- --------------------------------------------------------

--
-- 資料表結構 `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `user` varchar(450) DEFAULT NULL,
  `pwd` varchar(450) DEFAULT NULL,
  `create_at` timestamp NULL DEFAULT current_timestamp(),
  `end_time` varchar(450) DEFAULT NULL,
  `pid` varchar(45) DEFAULT '0',
  `sort` varchar(45) DEFAULT '0',
  `status` varchar(45) DEFAULT '1',
  `is_deleted` varchar(45) DEFAULT '0',
  `pay` varchar(45) DEFAULT NULL,
  `mac` varchar(45) DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `end_pay` varchar(32) DEFAULT NULL,
  `count` varchar(32) DEFAULT NULL,
  `hongli` int(32) DEFAULT 0,
  `dianshu` int(32) DEFAULT 0,
  `line` varchar(32) DEFAULT NULL,
  `is_auth` tinyint(2) UNSIGNED NOT NULL DEFAULT 0 COMMENT '驗證是否通過'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 傾印資料表的資料 `user`
--

INSERT INTO `user` (`id`, `user`, `pwd`, `create_at`, `end_time`, `pid`, `sort`, `status`, `is_deleted`, `pay`, `mac`, `ip`, `phone`, `end_pay`, `count`, `hongli`, `dianshu`, `line`, `is_auth`) VALUES
(753, 'admin', '123456', '2019-07-30 07:32:10', '2174-02-06', '0', '0', '1', '1', '9526', '08:00:27:0E:38:B3', '10.0.2.15', '13693239757', NULL, NULL, 93919, 50586, NULL, 0),
(754, 'qazxsw', '123456', '2019-07-30 07:32:10', '2020-02-10', '0', '0', '1', '1', '30.09', '00:DB:3B:57:B9:04', '172.16.2.15', '13693239757', NULL, NULL, NULL, NULL, NULL, 0),
(755, '110110', '110110', '2019-07-30 07:32:10', '2019-06-16', '0', '0', '1', '1', '0.01', '84:2C:80:48:1A:A7', '192.168.31.146', '13520254393', NULL, NULL, NULL, NULL, NULL, 0),
(756, '123456a', '123456a', '2019-07-30 07:32:10', NULL, '0', '0', '1', '1', NULL, '44:2C:05:04:44:CC', '192.168.0.105', '18719047418', NULL, NULL, NULL, NULL, NULL, 0),
(757, 'abcdef', '123456', '2019-07-30 07:32:10', NULL, '0', '0', '1', '1', NULL, '6C:EF:C6:82:8D:E8', '192.168.31.109', '13693239745', NULL, NULL, NULL, NULL, NULL, 0),
(758, '666777', '666777', '2019-07-30 07:32:10', NULL, '0', '0', '1', '1', NULL, '00:DB:3B:57:B9:04', '172.16.2.15', '18533334555', NULL, NULL, NULL, NULL, NULL, 0),
(759, '666777y', '666777', '2019-07-30 07:32:10', NULL, '0', '0', '1', '1', NULL, '6C:EF:C6:82:8D:E8', '192.168.31.109', '16666666666', NULL, NULL, NULL, NULL, NULL, 0),
(760, '1333333', '123456', '2019-07-30 07:32:10', '2019-07-16', '0', '0', '1', '1', NULL, 'D4:B7:61:0A:9F:2E', '192.168.1.102', '13333333333', NULL, NULL, NULL, NULL, NULL, 0),
(761, '123456', '654321', '2019-07-30 07:32:10', '2019-09-13', '0', '0', '1', '1', NULL, '00:DB:F5:66:11:1C', '172.16.2.15', '17600799861', NULL, NULL, NULL, NULL, NULL, 0),
(762, 'qwedsa', '123456', '2019-07-30 07:32:10', '2019-06-16', '0', '0', '1', '1', '0.01', '00:DB:28:A3:55:04', '172.16.2.15', '13693239757', NULL, NULL, NULL, NULL, NULL, 0),
(763, 'asdcxz', '123456', '2019-07-30 07:32:10', '2019-06-16', '0', '0', '1', '1', '0.01', '00:DB:82:09:02:88', '172.16.2.15', '13693239757', NULL, NULL, NULL, NULL, NULL, 0),
(764, '328889558', '123456', '2019-07-30 07:32:10', NULL, '0', '0', '1', '1', NULL, '00:DB:C9:EE:3D:B1', '172.16.2.15', '18674401111', NULL, NULL, NULL, NULL, NULL, 0),
(765, 'admin', '123456', '2019-07-30 07:32:10', '2174-02-06', '0', '0', '1', '0', '9526', '08:00:27:0E:38:B3', '10.0.2.15', '', NULL, '9999', 93919, 41932, '', 0),
(766, 'qazwsx', '123456', '2019-07-30 07:32:10', NULL, '0', '0', '1', '0', NULL, '1C:B7:96:B3:59:4E', '192.168.1.2', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(767, 'asdzxc', '', '2019-07-30 07:32:10', NULL, '0', '0', '1', '0', NULL, '00:DB:9E:DF:F9:B0', '172.16.2.30', '', NULL, NULL, NULL, 10, '', 0),
(768, 'zxcvbn', '123456', '2019-07-30 07:32:10', NULL, '0', '0', '1', '0', NULL, '00:DB:3F:0D:D3:A5', '172.16.2.15', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(769, 'cs0001', '123456', '2019-07-31 03:47:47', '2019-10-03', '0', '0', '1', '0', NULL, '00:DB:3B:57:B9:04', '172.16.2.15', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(770, '12345699', '12345699', '2019-08-01 07:38:31', '2019-10-20', '0', '0', '1', '0', '100.02', '88:D5:0C:77:A2:C7', '192.168.31.194', NULL, NULL, '100', NULL, NULL, NULL, 0),
(771, 'qwerty', '123456', '2019-08-02 07:27:24', NULL, '0', '0', '1', '0', NULL, '00:DB:7D:7A:1B:79', '172.16.2.20', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(772, 'plmokn', '123456', '2019-08-08 01:41:59', NULL, '0', '0', '1', '0', NULL, '00:DB:3F:0D:D3:A5', '172.16.2.15', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(773, 'nkoplm', '123456', '2019-08-08 01:43:40', NULL, '0', '0', '1', '0', NULL, '00:DB:3F:0D:D3:A5', '172.16.2.15', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(774, 'opklnm', '123456', '2019-08-08 01:45:10', NULL, '0', '0', '1', '0', NULL, '00:DB:3F:0D:D3:A5', '172.16.2.15', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(775, 'polkmn', '123456', '2019-08-08 01:47:00', NULL, '0', '0', '1', '0', NULL, '00:DB:3F:0D:D3:A5', '172.16.2.15', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(776, 'ijnuhb', '123456', '2019-08-08 01:55:33', NULL, '0', '0', '1', '0', NULL, '00:DB:3F:0D:D3:A5', '172.16.2.15', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(777, 'zxcvbnz', '123456', '2019-08-09 01:56:12', NULL, '0', '0', '1', '0', NULL, '00:DB:3F:0D:D3:A5', '172.16.2.15', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(778, 'zxcvbnm', '123456', '2019-08-09 01:57:17', NULL, '0', '0', '1', '0', NULL, '00:DB:3F:0D:D3:A5', '172.16.2.15', NULL, NULL, NULL, NULL, NULL, NULL, 0),
(779, '123456789', '123456789', '2019-08-09 07:24:15', '2020-03-20', '0', '0', '1', '0', '80', '00:DB:5A:12:40:F2', '172.16.2.82', NULL, NULL, '100', NULL, NULL, NULL, 0),
(780, '12345678', '123456', '2019-08-09 08:24:48', '2028-02-16', '0', '0', '1', '0', '7974', '1C:B7:96:B3:59:4E', '192.168.1.2', '12345678911', NULL, '8000', 47269, 190, '12345677', 0),
(781, '999900', '888800', '2019-08-21 06:22:53', '2020-07-19', '0', '0', '1', '0', '10.1', '88:D5:0C:77:A2:C7', '192.168.31.194', NULL, NULL, '100', NULL, 12, NULL, 0),
(782, 'abcwdh', '123456', '2019-09-23 05:36:08', NULL, '0', '0', '1', '0', NULL, NULL, NULL, '111111', NULL, NULL, NULL, NULL, '2313123', 0),
(783, 'wdyh002', '123456', '2019-09-23 05:45:18', NULL, '0', '0', '1', '0', NULL, NULL, NULL, '222', NULL, NULL, 0, 0, '111', 0),
(784, 'wdyh003', '123456', '2019-09-23 05:46:08', NULL, '0', '0', '1', '0', NULL, NULL, NULL, '222', NULL, NULL, 0, 0, '111', 0),
(785, 'wdyh004', '123456', '2019-09-23 06:05:19', NULL, '0', '0', '1', '0', NULL, NULL, NULL, '222', NULL, NULL, 0, 0, '111', 0),
(786, 'wdyh005', '123456', '2019-09-23 06:12:04', NULL, '0', '0', '1', '0', NULL, NULL, NULL, '123456', NULL, NULL, 0, 0, '123456', 0),
(787, 'qwertyui', '123456', '2019-09-23 06:18:36', NULL, '0', '0', '1', '0', NULL, NULL, NULL, '123456', NULL, NULL, 0, 0, '123456', 0),
(788, '0988638618', 'z0988638618', '2019-09-23 18:14:34', '0', '0', '0', '1', '1', NULL, '08:00:27:0E:38:B3', '10.0.2.15', NULL, NULL, NULL, 0, 110, NULL, 0),
(789, '0976102247', '0988638618', '2019-09-23 18:17:26', NULL, '0', '0', '1', '0', NULL, NULL, NULL, '0976102247', NULL, NULL, 0, 0, 'zxc778899', 0),
(790, 'qaz123', '123456', '2019-09-24 06:42:11', '2019-10-28', '0', '0', '1', '0', '99', '9C:5C:8E:E2:D7:E4', '192.168.50.80', '0932668985', NULL, '100', 0, 0, '0932668985', 0),
(791, 'assssasas', '123456', '2019-09-24 07:14:32', NULL, '0', '0', '1', '0', NULL, '1111', '1111', '11111', NULL, NULL, 0, 0, '11111', 0),
(792, '1111111', '123456', '2019-09-24 07:24:07', NULL, '0', '0', '1', '0', NULL, '88:D5:0C:77:A2:C7', '192.168.31.194', '1234567891', NULL, NULL, 0, 0, '1234567891', 0),
(793, '小DD北方金发ifof', '电话大姐福建方你拒绝', '2019-09-24 07:24:48', NULL, '0', '0', '1', '0', NULL, '88:D5:0C:77:A2:C7', '192.168.31.194', '4649565646', NULL, NULL, 0, 0, '4649565646', 0),
(794, '我是新用户', '123456', '2019-09-24 07:30:58', NULL, '0', '0', '1', '0', NULL, '88:D5:0C:77:A2:C7', '192.168.31.194', '1111111111', NULL, NULL, 0, 0, '1111111111', 0),
(795, '0908451180', '123456', '2019-09-24 09:31:57', NULL, '0', '0', '1', '0', NULL, NULL, NULL, '0908451180', NULL, NULL, 0, 0, 'gdirhdhi', 0),
(796, '陳家蚵', 'cs760726', '2019-09-25 09:30:48', NULL, '0', '0', '1', '0', NULL, NULL, NULL, '0961533116', NULL, NULL, 0, 0, 'cs760726', 0),
(797, 'cs0003', '12345679', '2019-09-27 02:25:50', '2019-10-07', '0', '0', '1', '0', NULL, '00:DB:3B:57:B9:04', '172.16.2.15', '465464654654646', NULL, NULL, 0, 0, '3165416531', 0),
(798, '111111', '111111', '2019-09-27 18:53:12', NULL, '0', '0', '1', '0', NULL, '', '100.92.32.64', '1111111111', NULL, NULL, 0, 300, '111111', 0),
(799, '222222', '222222', '2019-09-27 18:56:45', NULL, '0', '0', '1', '0', NULL, '00:E0:4C:62:72:D2', '172.20.10.9', '2222222222', NULL, NULL, 0, 0, '222222', 0),
(800, 'Qwerrr', 'qwwerr', '2019-10-03 19:12:09', NULL, '0', '0', '1', '0', NULL, NULL, NULL, 'qwerty', NULL, NULL, 0, 0, 'wertty', 0),
(801, '沙发和卡拉罚款交了', 'kjsagfjkahsklfa ', '2019-10-11 08:22:18', NULL, '0', '0', '1', '0', NULL, NULL, NULL, 'asfklafk', NULL, NULL, 0, 0, 'vsafjkajkfa', 0),
(802, '尽快v感到恐惧和v科技时代', 'dsgfsdgsdgsd ', '2019-10-11 08:22:43', NULL, '0', '0', '1', '0', NULL, NULL, NULL, 'sdgsdhfhfdfda', NULL, NULL, 0, 0, 'sdgdfhgdfhfghsf ', 0),
(803, '999999', '999999', '2019-10-14 08:39:06', NULL, '0', '0', '1', '0', NULL, '9C:5C:8E:E1:58:E2', '192.168.50.252', '0977888999', NULL, NULL, 0, 0, '0977888999', 0),
(804, 'q0988638618', 'z22553531', '2019-10-14 14:11:08', NULL, '0', '0', '1', '0', NULL, '9C:5C:8E:E1:58:E2', '192.168.50.252', '0988638618', NULL, '100', 0, 100, '0988638618', 0),
(805, '77885766', 'dygcuhhi', '2019-10-14 19:22:48', NULL, '0', '0', '1', '0', NULL, NULL, NULL, '0988765456', NULL, NULL, 0, 0, 'ryugjhg', 0),
(806, 'r91kf52', 'Roger2269rr', '2019-10-16 12:05:39', NULL, '0', '0', '1', '0', NULL, NULL, NULL, '0902216519', NULL, NULL, 0, 0, 'r91kf5273', 0),
(807, '467778889', 'fujgjgjv', '2019-10-21 13:45:37', NULL, '0', '0', '1', '0', NULL, NULL, NULL, '0988789789', NULL, NULL, 0, 0, 'biggkfjg', 0),
(808, 'pucca2248', 'love0919', '2019-10-28 09:03:44', '0', '0', '0', '1', '0', NULL, '', '10.142.81.40', '0936129975', NULL, '', 0, 200, '0936129975', 0),
(809, '1738483', 'qheyrjru', '2019-11-01 08:36:44', NULL, '0', '0', '0', '1', NULL, NULL, NULL, 'dhdudh', NULL, NULL, 0, 0, 'hdhdjdj', 0),
(810, 'fongok', 'fongok1971216', '2019-11-02 09:47:09', NULL, '0', '0', '1', '0', NULL, NULL, NULL, '0976586328', NULL, NULL, 0, 0, '0976586328', 0),
(811, 'fengok', '1971216888', '2019-11-02 09:47:50', NULL, '0', '0', '1', '0', NULL, NULL, NULL, '0976586328', NULL, NULL, 0, 0, '0976586328', 0),
(812, 'qqqqqq', '123456', '2019-11-04 08:33:38', NULL, '0', '0', '1', '0', NULL, '00:DB:7A:3A:FE:F6', '172.16.2.15', '13693239757', NULL, NULL, 0, 0, '1234565', 0),
(813, 'admin22', '1234567', '2019-11-04 12:30:21', NULL, '0', '0', '1', '0', NULL, 'iphone', '192.168.1.13', '', NULL, NULL, 0, 0, '', 0),
(814, 'agp0820', '08200820', '2019-11-06 08:13:12', NULL, '0', '0', '1', '0', NULL, '', '10.78.3.109', '0926262699', NULL, NULL, 0, 0, '0926262699', 0),
(815, '1234567890', 'Qwertyuiop', '2019-11-07 10:30:45', NULL, '0', '0', '1', '0', NULL, 'iphone', '10.39.120.6', '17611003815', NULL, NULL, 0, 0, '', 0);

-- --------------------------------------------------------

--
-- 資料表結構 `verification_lineid`
--

CREATE TABLE `verification_lineid` (
  `id` int(11) NOT NULL,
  `line_id` varchar(33) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'line_id',
  `uid` int(11) DEFAULT NULL COMMENT '會員UID',
  `verificationcode` varchar(35) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '驗證碼',
  `approved` tinyint(2) DEFAULT 0 COMMENT '預設是0驗證通過後是1',
  `add_time` datetime DEFAULT NULL COMMENT '資料新增時間',
  `ok_time` datetime DEFAULT NULL COMMENT '驗證通過時間'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='驗證帳號系統';

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `verification_lineid`
--
ALTER TABLE `verification_lineid`
  ADD PRIMARY KEY (`id`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=816;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `verification_lineid`
--
ALTER TABLE `verification_lineid`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
