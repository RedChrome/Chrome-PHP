DROP TABLE IF EXISTS `cp1_ace`;
CREATE TABLE IF NOT EXISTS `cp1_ace` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `class` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `allow` varchar(100) NOT NULL,
  `deny` varchar(100) NOT NULL,
  `description` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cp1_ace_acg`;
CREATE TABLE IF NOT EXISTS `cp1_ace_acg` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `acg_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cp1_acg`;
CREATE TABLE IF NOT EXISTS `cp1_acg` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `cp1_admin_navi`;
CREATE TABLE IF NOT EXISTS `cp1_admin_navi` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `parentid` int(5) NOT NULL,
  `isparent` int(1) NOT NULL,
  `name` varchar(100) NOT NULL,
  `action` varchar(100) NOT NULL,
  `url` varchar(100) NOT NULL,
  `access` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `cp1_authenticate`;
CREATE TABLE IF NOT EXISTS `cp1_authenticate` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `password_salt` varchar(256) NOT NULL,
  `cookie_token` varchar(50) NOT NULL,
  `time` int(12) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cp1_authorisation_rbac`;
CREATE TABLE IF NOT EXISTS `cp1_authorisation_rbac` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `group` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cp1_authorisation_resource_default`;
CREATE TABLE IF NOT EXISTS `cp1_authorisation_resource_default` (
  `_resource_id` varchar(256) NOT NULL,
  `_transformation` varchar(256) NOT NULL,
  `_access` mediumint(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cp1_authorisation_user_default`;
CREATE TABLE IF NOT EXISTS `cp1_authorisation_user_default` (
  `user_id` int(10) NOT NULL,
  `group_id` mediumint(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cp1_auth_logging`;
CREATE TABLE IF NOT EXISTS `cp1_auth_logging` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `ip` varchar(20) NOT NULL,
  `time` int(12) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cp1_class`;
CREATE TABLE IF NOT EXISTS `cp1_class` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cp1_comments`;
CREATE TABLE IF NOT EXISTS `cp1_comments` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `class_name` varchar(50) NOT NULL,
  `class_id` int(5) NOT NULL,
  `user_id` int(11) NOT NULL,
  `IP` varchar(30) NOT NULL,
  `time` int(15) NOT NULL,
  `title` varchar(150) NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cp1_config`;
CREATE TABLE IF NOT EXISTS `cp1_config` (
  `name` varchar(50) NOT NULL,
  `subclass` varchar(50) NOT NULL,
  `value` varchar(256) NOT NULL,
  `type` varchar(10) NOT NULL,
  `modul` varchar(35) NOT NULL,
  `hidden` int(1) NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cp1_design`;
CREATE TABLE IF NOT EXISTS `cp1_design` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `name` varchar(75) NOT NULL,
  `file` varchar(100) NOT NULL,
  `class` varchar(150) NOT NULL,
  `position` varchar(50) NOT NULL,
  `order` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cp1_design_controller`;
CREATE TABLE IF NOT EXISTS `cp1_design_controller` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `controller_class` varchar(255) NOT NULL,
  `design_class` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cp1_gallery_event`;
CREATE TABLE IF NOT EXISTS `cp1_gallery_event` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `desc` text NOT NULL,
  `date` int(15) NOT NULL,
  `viewed` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cp1_gallery_images`;
CREATE TABLE IF NOT EXISTS `cp1_gallery_images` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `event_id` int(5) NOT NULL,
  `file` text NOT NULL,
  `title` varchar(50) NOT NULL,
  `desc` varchar(200) NOT NULL,
  `order` int(5) NOT NULL,
  `hash` varchar(32) NOT NULL,
  `viewed` int(10) NOT NULL,
  `traffic` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cp1_music`;
CREATE TABLE IF NOT EXISTS `cp1_music` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `name` varchar(300) NOT NULL,
  `artist` varchar(200) NOT NULL,
  `cat` varchar(200) NOT NULL,
  `time` int(15) NOT NULL,
  `time_id` int(9) NOT NULL,
  `link` varchar(500) NOT NULL,
  `nfo` varchar(500) NOT NULL,
  `downloads` int(9) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cp1_navi`;
CREATE TABLE IF NOT EXISTS `cp1_navi` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `side` varchar(10) NOT NULL,
  `order` int(2) NOT NULL,
  `file` varchar(100) NOT NULL,
  `class` varchar(100) NOT NULL,
  `access` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cp1_news`;
CREATE TABLE IF NOT EXISTS `cp1_news` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `text` text NOT NULL,
  `author` int(5) NOT NULL,
  `time` int(15) NOT NULL,
  `access` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cp1_news_comments`;
CREATE TABLE IF NOT EXISTS `cp1_news_comments` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `news_id` int(5) NOT NULL,
  `user_id` varchar(30) NOT NULL,
  `text` text NOT NULL,
  `time` int(15) NOT NULL,
  `IP` varchar(20) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cp1_pm`;
CREATE TABLE IF NOT EXISTS `cp1_pm` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `from` int(5) NOT NULL,
  `to` int(5) NOT NULL,
  `title` varchar(150) NOT NULL,
  `text` text NOT NULL,
  `time` int(15) NOT NULL,
  `read` int(1) NOT NULL DEFAULT '0',
  `replied` int(1) NOT NULL DEFAULT '0',
  `status` int(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cp1_pm_archive`;
CREATE TABLE IF NOT EXISTS `cp1_pm_archive` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `from` int(5) NOT NULL,
  `to` int(5) NOT NULL,
  `title` varchar(150) NOT NULL,
  `text` text NOT NULL,
  `time` int(15) NOT NULL,
  `owner` int(5) NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cp1_rbac_group`;
CREATE TABLE IF NOT EXISTS `cp1_rbac_group` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cp1_rbac_group_role`;
CREATE TABLE IF NOT EXISTS `cp1_rbac_group_role` (
  `group_id` int(10) NOT NULL,
  `role_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cp1_rbac_role`;
CREATE TABLE IF NOT EXISTS `cp1_rbac_role` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cp1_rbac_role_transaction`;
CREATE TABLE IF NOT EXISTS `cp1_rbac_role_transaction` (
  `role_id` int(10) NOT NULL,
  `transaction_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cp1_rbac_transaction`;
CREATE TABLE IF NOT EXISTS `cp1_rbac_transaction` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cp1_rbac_transformation`;
CREATE TABLE IF NOT EXISTS `cp1_rbac_transformation` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `transaction_id` int(10) NOT NULL,
  `transformation` varchar(256) NOT NULL,
  `right` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cp1_rbac_user_group`;
CREATE TABLE IF NOT EXISTS `cp1_rbac_user_group` (
  `user_id` int(10) NOT NULL,
  `group_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cp1_rbac_user_role`;
CREATE TABLE IF NOT EXISTS `cp1_rbac_user_role` (
  `user_id` int(10) NOT NULL,
  `role_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cp1_require`;
CREATE TABLE IF NOT EXISTS `cp1_require` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `activated` int(1) DEFAULT NULL,
  `order` int(2) NOT NULL DEFAULT '6',
  `require_class` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cp1_route_dynamic`;
CREATE TABLE IF NOT EXISTS `cp1_route_dynamic` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  `GET` varchar(511) NOT NULL,
  `POST` varchar(511) NOT NULL,
  `GET_key` varchar(255) NOT NULL,
  `GET_value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cp1_route_static`;
CREATE TABLE IF NOT EXISTS `cp1_route_static` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `search` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  `POST` varchar(511) NOT NULL,
  `GET` varchar(511) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cp1_user`;
CREATE TABLE IF NOT EXISTS `cp1_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `email` varchar(200) NOT NULL,
  `group` int(2) NOT NULL DEFAULT '0',
  `time` int(15) NOT NULL,
  `avatar` varchar(50) NOT NULL,
  `address` varchar(300) NOT NULL,
  `design` varchar(20) NOT NULL DEFAULT 'default',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cp1_user_regist`;
CREATE TABLE IF NOT EXISTS `cp1_user_regist` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `pass` varchar(100) NOT NULL,
  `pw_salt` varchar(20) NOT NULL,
  `email` varchar(200) NOT NULL,
  `time` int(15) NOT NULL,
  `key` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


INSERT INTO `cp1_require` (`id`, `name`, `path`, `activated`, `order`, `require_class`) VALUES
(12, 'Chrome_View_Helper_HTML', 'plugins/View/html.php', 1, 6, 0),
(13, 'Chrome_View_Helper_Decorator', 'plugins/View/decorator.php', 1, 6, 0),
(14, 'Chrome_Require_Filter', 'plugins/Require/filter.php', 1, 6, 1),
(15, 'Chrome_Require_Exception', 'plugins/Require/exception.php', 1, 6, 1),
(16, 'Chrome_Require_Validator', 'plugins/Require/validator.php', 1, 6, 1),
(17, 'Chrome_Require_Controller', 'plugins/Require/controller.php', 1, 6, 1),
(18, 'Chrome_Require_Design', 'plugins/Require/design.php', 1, 6, 1),
(19, 'Chrome_Require_Form', 'plugins/Require/form.php', 1, 6, 1),
(22, 'Chrome_View_Helper_Error', 'plugins/View/error.php', 1, 6, 0),
(23, 'Chrome_Filter_Chain_Preprocessor', 'plugins/Filter/chain/preprocessor.php', 1, 6, 0),
(24, 'Chrome_Filter_Chain_Postprocessor', 'plugins/Filter/chain/postprocessor.php', 1, 6, 0),
(25, 'Chrome_Exception_Handler_Authentication', 'lib/exception/authentication.php', 1, 6, 0),
(26, 'Chrome_Authentication', 'lib/core/authentication/authentication.php', 1, 6, 0),
(27, 'Chrome_Authentication_Chain_Database', 'lib/core/authentication/chain/database.php', 1, 6, 0),
(28, 'Chrome_Authentication_Chain_Cookie', 'lib/core/authentication/chain/cookie.php', 1, 6, 0),
(29, 'Chrome_Authentication_Chain_Session', 'lib/core/authentication/chain/session.php', 1, 6, 0),
(30, 'Chrome_Authorisation', 'lib/core/authorisation/authorisation.php', 1, 6, 0),
(31, 'Chrome_Authorisation_Adapter_Default', 'lib/core/authorisation/adapter/default.php', 1, 6, 0),
(32, 'Chrome_Database_Right_Handler_Interface', 'lib/core/database/right_handler.php', 1, 5, 0),
(33, 'Chrome_Route_Static', 'lib/core/router/route/static.php', 1, 6, 0),
(34, 'Chrome_Route_Dynamic', 'lib/core/router/route/dynamic.php', 1, 6, 0),
(35, 'Chrome_Route_Administration', 'lib/core/router/route/administration.php', 1, 6, 0),
(36, 'Chrome_Request_Handler_AJAX', 'lib/core/request/ajax.php', 1, 6, 0),
(37, 'Chrome_Request_Handler_HTTP', 'lib/core/request/http.php', 1, 6, 0);


INSERT INTO `cp1_class` (`id`, `name`, `file`) VALUES
(49, 'Chrome_Exception_Handler_Authentication', 'lib/exception/authentication.php'),
(48, 'Chrome_Authentication_Chain_Cookie', 'lib/core/authentication/chain/cookie.php'),
(46, 'Chrome_Authentication_Chain_Database', 'lib/core/authentication/chain/database.php'),
(47, 'Chrome_Authentication_Chain_Session', 'lib/core/authentication/chain/session.php'),
(11, 'Chrome_Converter', 'lib/core/converter/converter.php'),
(45, 'Chrome_Logger_Null', 'plugins/Log/null.php'),
(13, 'Chrome_User_Registration', 'lib/View/content/user/registration.class.php'),
(24, 'Chrome_Validator', 'lib/core/validator/validator.php'),
(21, 'Chrome_User_EMail', 'lib/User/user_email.php'),
(28, 'Chrome_View_Helper_HTML', 'plugins/View/html.php'),
(30, 'Chrome_Language', 'lib/core/language.php'),
(31, 'Chrome_Converter_Value', 'lib/core/converter/converter.php'),
(32, 'Chrome_Form_Abstract', 'lib/core/form/form.php'),
(39, 'Chrome_Controller_Index', 'modules/content/index/controller.php'),
(36, 'Chrome_Template', 'lib/core/template/template.php'),
(50, 'Chrome_Authentication', 'lib/core/authentication/authentication.php'),
(38, 'Chrome_User_Login', 'lib/classes/user/user.php'),
(40, 'Chrome_Route_Static', 'lib/core/router/route/static.php'),
(41, 'Chrome_Route_Dynamic', 'lib/core/router/route/dynamic.php'),
(44, 'Chrome_RBAC', 'lib/rbac/rbac.php'),
(43, 'Chrome_Captcha', 'lib/captcha/captcha.php'),
(51, 'Chrome_Authorisation', 'lib/core/authorisation/authorisation.php'),
(52, 'Chrome_Model_Authentication_Database', 'lib/core/authentication/chain/database.php'),
(53, 'Chrome_Redirection', 'lib/core/redirection.php'),
(54, 'Chrome_Controller_User_Login_Page', 'modules/content/user/login/page.php'),
(55, 'Chrome_Database_Right_Handler_Interface', 'lib/core/database/right_handler.php'),
(56, 'Chrome_Authorisation_Adapter_Default', 'lib/core/authorisation/adapter/default.php'),
(57, 'Chrome_Authorisation_Adapter_Interface', 'lib/core/authorisation/authorisation.php'),
(58, 'Chrome_Route_Administration', 'lib/core/router/route/administration.php');


INSERT INTO `cp1_config` (`name`, `subclass`, `value`, `type`, `modul`, `hidden`) VALUES
('Default_Design', 'Design', 'chrome', 'string', '', 0),
('Meta_Desc', 'Site', '', 'string', '', 0),
('Meta_Keywords', 'Site', '', 'string', '', 0),
('Title_Beginning', 'Site', 'Chrome-PHP', 'string', '', 0),
('Title_Separator', 'Site', ' :: ', 'string', '', 0),
('Email_Subject', 'Registration', 'Registrierung auf Localhost!', 'string', '', 0),
('pmsPerPage', 'Pm', '10', 'int', '', 0),
('maxTitleLength', 'Pm', '150', 'int', '', 0),
('News_Comment_Limit', 'News', '15', 'int', '', 0),
('News_Page_Limit', 'News', '6', 'int', '', 0),
('comment_block_sec', 'News', '30', 'int', '', 0),
('Gallery_Page_Limit', 'Gallery', '9', 'int', '', 0),
('Title_Ending', 'Site', '', 'string', '', 0),
('blacklist_host', 'Registration', 'localhost,', 'string', '', 0),
('expiration', 'Registration', '604800', 'int', '', 0);