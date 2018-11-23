-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 26, 2017 at 07:20 AM
-- Server version: 5.1.53
-- PHP Version: 5.3.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Table structure for table `admins`
--

CREATE TABLE IF NOT EXISTS `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--


-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(300) CHARACTER SET utf8 NOT NULL,
  `cat_sub` int(11) NOT NULL,
  `cat_type` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`cid`, `cat_name`, `cat_sub`, `cat_type`) VALUES
(1, 'pcat_Company', 2, 0),
(2, 'pcat_Organization', 2, 0),
(3, 'pcat_Institution', 2, 0),
(4, 'pcat_Brand', 3, 0),
(5, 'pcat_Product', 3, 0),
(6, 'pcat_Artist', 4, 0),
(7, 'pcat_Band', 4, 0),
(8, 'pcat_Public_Figure', 4, 0),
(9, 'pcat_Other', 5, 0),
(10, 'pcat_Community', 6, 0),
(11, 'pcat_Travelc', 2, 1),
(12, 'pcat_Tobacco', 2, 1),
(13, 'pcat_Telecom', 2, 1),
(14, 'pcat_Engineering', 2, 1),
(15, 'pcat_ScienceT', 2, 1),
(16, 'pcat_School', 2, 1),
(17, 'pcat_Retialc', 2, 1),
(18, 'pcat_Religiouso', 2, 1),
(19, 'pcat_Preschool', 2, 1),
(20, 'pcat_Politicalp', 2, 1),
(21, 'pcat_Politicalo', 2, 1),
(22, 'pcat_Nonp', 2, 1),
(23, 'pcat_NGO', 2, 1),
(24, 'pcat_Motorc', 2, 1),
(25, 'pcat_Minningc', 2, 1),
(26, 'pcat_schoolm', 2, 1),
(27, 'pcat_Median', 2, 1),
(28, 'pcat_Labouru', 2, 1),
(29, 'pcat_Internetc', 2, 1),
(30, 'pcat_Insurancec', 2, 1),
(31, 'pcat_Industrialc', 2, 1),
(32, 'pcat_schoolh', 2, 1),
(33, 'pcat_Healthbeauty', 2, 1),
(34, 'pcat_Government_o', 2, 1),
(35, 'pcat_Energy_Company', 2, 1),
(36, 'pcat_Elementry_s', 2, 1),
(37, 'pcat_Education', 2, 1),
(38, 'pcat_Consulting_Agency', 2, 1),
(39, 'pcat_Computer_c', 2, 1),
(40, 'pcat_Community_o', 2, 1),
(41, 'pcat_University', 2, 1),
(42, 'pcat_College', 2, 1),
(43, 'pcat_College_University', 2, 1),
(44, 'pcat_Cargo', 2, 1),
(45, 'pcat_Biotechnology', 2, 1),
(46, 'pcat_Cause', 2, 1),
(47, 'pcat_Chemical_company', 2, 1),
(48, 'pcat_Aerospace_company', 2, 1),
(49, 'pcat_Community_services', 2, 1),
(140, 'pcat_Actor', 4, 1),
(139, 'pcat_Athlete', 4, 1),
(50, 'pcat_Spiritsw', 3, 1),
(51, 'pcat_Website', 3, 1),
(52, 'pcat_Vitaminss', 3, 1),
(53, 'pcat_Videog', 3, 1),
(54, 'pcat_Toolse', 3, 1),
(55, 'pcat_Software', 3, 1),
(56, 'pcat_Products', 3, 1),
(57, 'pcat_Phonet', 3, 1),
(58, 'pcat_Pharmaceuticals', 3, 1),
(59, 'pcat_Pets', 3, 1),
(60, 'pcat_Patiov', 3, 1),
(61, 'pcat_Offices', 3, 1),
(62, 'pcat_Kichenc', 3, 1),
(63, 'pcat_Jewelry', 3, 1),
(64, 'pcat_Households', 3, 1),
(65, 'pcat_Homed', 3, 1),
(66, 'pcat_Health_beatuy_2', 3, 1),
(67, 'pcat_Games_ty', 3, 1),
(68, 'pcat_Games_ty', 3, 1),
(69, 'pcat_Electronics', 3, 1),
(70, 'pcat_Computers', 3, 1),
(71, 'pcat_Commerciale', 3, 1),
(72, 'pcat_Clothing', 3, 1),
(73, 'pcat_Cars', 3, 1),
(74, 'pcat_Camera', 3, 1),
(75, 'pcat_Buildingm', 3, 1),
(76, 'pcat_BoardG', 3, 1),
(77, 'pcat_Bags', 3, 1),
(78, 'pcat_Babyg', 3, 1),
(79, 'pcat_Appliances', 3, 1),
(80, 'pcat_App', 3, 1),
(81, 'pcat_Writer', 4, 1),
(82, 'pcat_Videoc', 4, 1),
(83, 'pcat_Scientist', 4, 1),
(84, 'pcat_Producer', 4, 1),
(85, 'pcat_Politician', 4, 1),
(86, 'pcat_Politicalc', 4, 1),
(87, 'pcat_Photographer', 4, 1),
(88, 'pcat_Newsp', 4, 1),
(89, 'pcat_Musicianb', 4, 1),
(90, 'pcat_Musicians', 4, 1),
(91, 'pcat_Moviec', 4, 1),
(92, 'pcat_Motivationals', 4, 1),
(93, 'pcat_Journalist', 4, 1),
(94, 'pcat_Governmento', 4, 1),
(95, 'pcat_Filmd', 4, 1),
(96, 'pcat_Fashionm', 4, 1),
(97, 'pcat_Entrepreneur', 4, 1),
(98, 'pcat_Dancer', 4, 1),
(99, 'pcat_Comedian', 4, 1),
(100, 'pcat_Coach', 4, 1),
(101, 'pcat_Chef', 4, 1),
(102, 'pcat_Blogger', 4, 1),
(104, 'pcat_TVa', 5, 1),
(138, 'pcat_Author', 4, 1),
(105, 'pcat_Moviea', 5, 1),
(106, 'pcat_TVs', 5, 1),
(107, 'pcat_TVn', 5, 1),
(108, 'pcat_TVc', 5, 1),
(109, 'pcat_Theatricalpp', 5, 1),
(110, 'pcat_Theatricalp', 5, 1),
(111, 'pcat_Stadiumsasv', 5, 1),
(112, 'pcat_Sportst', 5, 1),
(113, 'pcat_Sportsl', 5, 1),
(114, 'pcat_Song', 5, 1),
(115, 'pcat_Schoolss', 5, 1),
(116, 'pcat_Radios', 5, 1),
(117, 'pcat_Podcast', 5, 1),
(118, 'pcat_Performingaa', 5, 1),
(119, 'pcat_Performancea', 5, 1),
(120, 'pcat_Performanceev', 5, 1),
(121, 'pcat_Musicv', 5, 1),
(122, 'pcat_Musicc', 5, 1),
(123, 'pcat_Musica', 5, 1),
(124, 'pcat_Moviets', 5, 1),
(125, 'pcat_Moviet', 5, 1),
(126, 'pcat_Moviec', 5, 1),
(127, 'pcat_Movie', 5, 1),
(128, 'pcat_Magazine', 5, 1),
(129, 'pcat_Literarya', 5, 1),
(130, 'pcat_Library', 5, 1),
(131, 'pcat_Festival', 5, 1),
(132, 'pcat_Concertt', 5, 1),
(133, 'pcat_Bookstoresss', 5, 1),
(134, 'pcat_Bookss', 5, 1),
(135, 'pcat_Books', 5, 1),
(136, 'pcat_Amateurs', 5, 1),
(137, 'pcat_Association', 6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `chat_forms`
--

CREATE TABLE IF NOT EXISTS `chat_forms` (
  `form_id` int(11) NOT NULL AUTO_INCREMENT,
  `form_type` tinyint(4) NOT NULL DEFAULT '1',
  `form_icon` varchar(250) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'default.png',
  `form_cover` varchar(250) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'default.png',
  `form_name` text COLLATE utf8_unicode_ci NOT NULL,
  `form_description` text COLLATE utf8_unicode_ci NOT NULL,
  `form_date` date NOT NULL,
  `form_by` int(11) NOT NULL,
  `form_to` int(11) NOT NULL,
  `form_active` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`form_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for table `chat_messages`
--

CREATE TABLE IF NOT EXISTS `chat_messages` (
  `mid` int(11) NOT NULL AUTO_INCREMENT,
  `m_type` tinyint(4) NOT NULL,
  `m_text` text COLLATE utf8_unicode_ci NOT NULL,
  `by` int(11) NOT NULL,
  `form_id` int(11) NOT NULL,
  `posted_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`mid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for table `chat_users`
--

CREATE TABLE IF NOT EXISTS `chat_users` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `form_id` int(11) NOT NULL,
  `on_form` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for table `extensions`
--

CREATE TABLE IF NOT EXISTS `extensions` (
  `eid` int(11) NOT NULL AUTO_INCREMENT,
  `ext_name` varchar(250) NOT NULL,
  `ext_extras` varchar(800) NOT NULL,
  `ext_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ext_status` tinyint(11) NOT NULL,
  `ext_author` varchar(250) NOT NULL,
  `ext_description` varchar(400) NOT NULL,
  PRIMARY KEY (`eid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Table structure for table `friendships`
--

CREATE TABLE IF NOT EXISTS `friendships` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user1` int(11) NOT NULL,
  `user2` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_username` varchar(200) NOT NULL,
  `group_name` varchar(256) NOT NULL,
  `group_icon` int(11) NOT NULL,
  `group_cover` varchar(256) NOT NULL DEFAULT 'default.png',
  `group_owner` int(11) NOT NULL,
  `group_description` text NOT NULL,
  `group_location` varchar(256) NOT NULL,
  `group_web` varchar(256) NOT NULL,
  `group_email` varchar(256) NOT NULL,
  `group_privacy` int(11) NOT NULL DEFAULT '1',
  `group_approval_type` int(11) NOT NULL DEFAULT '1',
  `group_posting` int(11) NOT NULL DEFAULT '1',
  `group_users` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Table structure for table `group_logs`
--

CREATE TABLE IF NOT EXISTS `group_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `target_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Table structure for table `group_users`
--

CREATE TABLE IF NOT EXISTS `group_users` (
  `gid` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `group_role` int(11) NOT NULL,
  `p_post` tinyint(4) NOT NULL DEFAULT '0',
  `p_cover` tinyint(4) NOT NULL DEFAULT '0',
  `p_activity` tinyint(4) NOT NULL DEFAULT '0',
  `f_feeds` tinyint(4) NOT NULL DEFAULT '1',
  `group_partner_id` int(11) NOT NULL,
  `group_status` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`gid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Table structure for table `notifications`
--

CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `not_from` int(11) NOT NULL,
  `not_to` int(11) NOT NULL,
  `not_content_id` int(11) NOT NULL,
  `not_content` int(11) NOT NULL,
  `not_type` int(11) NOT NULL,
  `not_read` int(11) NOT NULL,
  `not_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `page_id` int(11) NOT NULL AUTO_INCREMENT,
  `page_username` varchar(200) NOT NULL,
  `page_name` varchar(300) NOT NULL,
  `page_verified` tinyint(4) NOT NULL DEFAULT '0',
  `page_icon` varchar(256) NOT NULL,
  `page_cover` varchar(256) NOT NULL,
  `page_owner` int(11) NOT NULL,
  `page_cat` int(11) NOT NULL,
  `page_sub_cat` varchar(200) NOT NULL,
  `page_location` varchar(256) NOT NULL,
  `page_description` text NOT NULL,
  `page_email` varchar(256) NOT NULL,
  `page_web` varchar(256) NOT NULL,
  `page_likes` int(11) NOT NULL,
  `page_follows` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`page_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Table structure for table `page_likes`
--

CREATE TABLE IF NOT EXISTS `page_likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NOT NULL,
  `by_id` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Table structure for table `page_logs`
--

CREATE TABLE IF NOT EXISTS `page_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `target_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Table structure for table `page_roles`
--

CREATE TABLE IF NOT EXISTS `page_roles` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  `page_partner_id` int(11) NOT NULL,
  `page_role` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Table structure for table `page_users`
--

CREATE TABLE IF NOT EXISTS `page_users` (
  `pfid` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  `f_feeds` tinyint(4) NOT NULL DEFAULT '1',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pfid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Table structure for table `patches`
--

CREATE TABLE IF NOT EXISTS `patches` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `p_name` varchar(250) NOT NULL,
  `p_name_main` varchar(250) NOT NULL,
  `p_description` varchar(500) NOT NULL,
  `p_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ;

--
-- Table structure for table `post_comments`
--

CREATE TABLE IF NOT EXISTS `post_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `by_id` int(11) NOT NULL,
  `commented_as` tinyint(4) NOT NULL DEFAULT '0',
  `comment_text` text COLLATE utf8_unicode_ci NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `safe` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for table `post_loves`
--

CREATE TABLE IF NOT EXISTS `post_loves` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `by_id` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for table `reports`
--

CREATE TABLE IF NOT EXISTS `reports` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `from` int(11) NOT NULL,
  `content_id` int(11) NOT NULL,
  `content_owner` int(11) NOT NULL,
  `type` tinyint(11) NOT NULL DEFAULT '0',
  `val1` tinyint(4) NOT NULL DEFAULT '0',
  `val2` tinyint(4) NOT NULL DEFAULT '0',
  `val3` tinyint(4) NOT NULL DEFAULT '0',
  `val4` tinyint(4) NOT NULL DEFAULT '0',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `key` varchar(256) NOT NULL,
  `value` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`key`, `value`) VALUES
('web_name', 'Breeze Ultimate'),
('title', 'Breeze Ultimate'),
('theme', 'Standards'),
('posts_per_page', '10'),
('photos_per_page', '18'),
('results_per_page', '10'),
('lovers_per_page', '20'),
('comments_per_widget', '6'),
('search_results_per_page', '35'),
('chats_per_page', '12'),
('max_post_length', '2000'),
('max_comment_length', '200'),
('max_message_length', '1000'),
('jpeg_quality', '100'),
('max_img_size', '10'),
('max_image_size', '10000'),
('max_main_pics', '2000'),
('max_cover_pics', '1280'),
('max_chat_icons', '2000'),
('max_chat_covers', '1280'),
('font_colors_welcome', 'white'),
('captcha', '0'),
('mentions_type', '0'),
('inf_scroll', '1'),
('smtp_email', '0'),
('smtp_host', ''),
('smtp_port', ''),
('smtp_auth', '0'),
('smtp_username', ''),
('smtp_password', ''),
('username_min_len', '6'),
('username_max_len', '32'),
('password_min_len', '6'),
('password_max_len', '32'),
('emails_verification', '0'),
('def_p_verified', '0'),
('def_p_image', 'default.png'),
('def_p_cover', 'default.png'),
('def_n_per_page', '10'),
('def_n_accept', '1'),
('def_n_type', '1'),
('def_n_follower', '1'),
('def_n_like', '1'),
('def_n_comment', '1'),
('def_p_moderators', '0'),
('def_p_posts', '0'),
('def_p_followers', '0'),
('def_p_followings', '0'),
('def_p_profession', '0'),
('def_p_hometown', '0'),
('def_p_location', '0'),
('def_p_private', '0'),
('def_b_posts', '0'),
('def_b_comments', '0'),
('def_b_users', '0'),
('def_r_posts_per_page', '5'),
('def_r_followers_per_page', '8'),
('def_r_followings_per_page', '8'),
('po_add_visit', ''),
('po_add_out', ''),
('po_add_home', ''),
('po_add_trending', ''),
('po_add_conn_user', ''),
('po_add_conn_post', ''),
('fi_add_home1', ''),
('fi_add_search', ''),
('fi_add_feed', ''),
('fi_add_trending', ''),
('fi_add_post', ''),
('fi_add_relatives', ''),
('post_backgrounds', '10,7,5,6,3,4,1,2,8,9'),
('default_lang', 'English');

-- --------------------------------------------------------

--
-- Table structure for table `updates`
--

CREATE TABLE IF NOT EXISTS `updates` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `u_name` varchar(250) NOT NULL,
  `u_description` varchar(500) NOT NULL,
  `u_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `u_version` varchar(250) NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `idu` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `salt` varchar(250) NOT NULL,
  `first_name` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `active` int(11) NOT NULL,
  `image` varchar(128) NOT NULL DEFAULT 'default.png',
  `cover` varchar(128) NOT NULL DEFAULT 'default.png',
  `verified` int(11) NOT NULL,
  `ip` varchar(45) NOT NULL,
  `onfeeds` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `onmessenger` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `b_day` varchar(64) NOT NULL DEFAULT '0',
  `profession` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `from` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `living` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `gender` tinyint(4) NOT NULL,
  `study` varchar(64) NOT NULL,
  `interest` tinyint(4) NOT NULL,
  `relationship` tinyint(4) NOT NULL,
  `website` varchar(64) NOT NULL,
  `bio` varchar(200) NOT NULL,
  `posts` text CHARACTER SET utf8 NOT NULL,
  `photos` text CHARACTER SET utf8 NOT NULL,
  `followers` text CHARACTER SET utf8 NOT NULL,
  `group_feeds` blob NOT NULL,
  `page_feeds` blob NOT NULL,
  `r_posts_per_page` int(11) NOT NULL DEFAULT '5',
  `r_followers_per_page` int(11) NOT NULL DEFAULT '10',
  `r_followings_per_page` int(11) NOT NULL DEFAULT '10',
  `p_moderators` tinyint(10) NOT NULL DEFAULT '0',
  `n_per_page` int(100) NOT NULL DEFAULT '5',
  `n_accept` int(11) NOT NULL DEFAULT '0',
  `n_type` tinyint(10) NOT NULL DEFAULT '1',
  `n_follower` tinyint(10) NOT NULL DEFAULT '1',
  `n_like` tinyint(10) NOT NULL DEFAULT '1',
  `n_comment` tinyint(10) NOT NULL DEFAULT '1',
  `n_mention` tinyint(4) NOT NULL DEFAULT '1',
  `e_accept` tinyint(4) NOT NULL,
  `e_follower` tinyint(4) NOT NULL,
  `e_like` tinyint(4) NOT NULL,
  `e_comment` tinyint(4) NOT NULL,
  `e_mention` tinyint(4) NOT NULL,
  `p_posts` tinyint(10) NOT NULL DEFAULT '0',
  `p_followers` tinyint(10) NOT NULL DEFAULT '0',
  `p_followings` tinyint(10) NOT NULL DEFAULT '0',
  `p_profession` tinyint(10) NOT NULL DEFAULT '0',
  `p_hometown` tinyint(10) NOT NULL DEFAULT '0',
  `p_location` tinyint(10) NOT NULL DEFAULT '0',
  `p_image` tinyint(4) NOT NULL DEFAULT '0',
  `p_cover` tinyint(4) NOT NULL DEFAULT '0',
  `p_mention` tinyint(4) NOT NULL DEFAULT '0',
  `p_private` tinyint(10) NOT NULL DEFAULT '0',
  `p_study` tinyint(4) NOT NULL,
  `p_relationship` tinyint(4) NOT NULL,
  `p_interest` tinyint(4) NOT NULL,
  `p_gender` tinyint(4) NOT NULL,
  `p_bday` tinyint(4) NOT NULL,
  `p_web` tinyint(4) NOT NULL,
  `b_posts` tinyint(1) NOT NULL DEFAULT '0',
  `b_comments` tinyint(1) NOT NULL DEFAULT '0',
  `b_users` tinyint(1) NOT NULL DEFAULT '0',
  `state` tinyint(4) NOT NULL DEFAULT '2',
  `safe` tinyint(4) NOT NULL DEFAULT '0',
  `p_chn` int(11) NOT NULL,
  UNIQUE KEY `id` (`idu`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Table structure for table `user_posts`
--

CREATE TABLE IF NOT EXISTS `user_posts` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT,
  `post_by_id` int(11) NOT NULL,
  `post_content` text COLLATE utf8_unicode_ci NOT NULL,
  `post_text` text COLLATE utf8_unicode_ci NOT NULL,
  `post_tags` varchar(256) CHARACTER SET utf8 NOT NULL,
  `post_type` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `posted_as` int(11) NOT NULL DEFAULT '0',
  `posted_at` int(11) NOT NULL DEFAULT '0',
  `post_loves` int(11) NOT NULL DEFAULT '0',
  `post_comments` int(11) NOT NULL DEFAULT '0',
  `post_extras` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0,0,0',
  `post_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `edited` tinyint(4) NOT NULL DEFAULT '0',
  `safe` tinyint(4) NOT NULL DEFAULT '0',
  `post_deleted` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`post_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;