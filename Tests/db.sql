SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=8 ;

DROP TABLE IF EXISTS `cp1_authenticate`;
CREATE TABLE IF NOT EXISTS `cp1_authenticate` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `password` varchar(256) NOT NULL,
  `password_salt` varchar(256) NOT NULL,
  `cookie_token` varchar(50) NOT NULL,
  `time` int(12) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

DROP TABLE IF EXISTS `cp1_authorisation_rbac`;
CREATE TABLE IF NOT EXISTS `cp1_authorisation_rbac` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `group` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

DROP TABLE IF EXISTS `cp1_authorisation_resource_default`;
CREATE TABLE IF NOT EXISTS `cp1_authorisation_resource_default` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `_resource_id` varchar(256) NOT NULL,
  `_transformation` varchar(256) NOT NULL,
  `_access` mediumint(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

DROP TABLE IF EXISTS `cp1_authorisation_user_default`;
CREATE TABLE IF NOT EXISTS `cp1_authorisation_user_default` (
  `user_id` int(10) NOT NULL,
  `group_id` mediumint(10) NOT NULL,
  KEY `authIdUserDefault` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cp1_class`;
CREATE TABLE IF NOT EXISTS `cp1_class` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=34 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

DROP TABLE IF EXISTS `cp1_design_controller`;
CREATE TABLE IF NOT EXISTS `cp1_design_controller` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `controller_class` varchar(255) NOT NULL,
  `design_class` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `cp1_design_layout`;
CREATE TABLE IF NOT EXISTS `cp1_design_layout` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `controller` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `cp1_design_mapper_static`;
CREATE TABLE IF NOT EXISTS `cp1_design_mapper_static` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `view_id` varchar(256) NOT NULL,
  `position` varchar(256) NOT NULL,
  `priority` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

DROP TABLE IF EXISTS `cp1_news`;
CREATE TABLE IF NOT EXISTS `cp1_news` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `text` text NOT NULL,
  `author` int(5) NOT NULL,
  `time` int(15) NOT NULL,
  `access` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

DROP TABLE IF EXISTS `cp1_news_comments`;
CREATE TABLE IF NOT EXISTS `cp1_news_comments` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `news_id` int(5) NOT NULL,
  `user_id` varchar(30) NOT NULL,
  `text` text NOT NULL,
  `time` int(15) NOT NULL,
  `IP` varchar(20) CHARACTER SET latin1 COLLATE latin1_german2_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=35 ;

DROP TABLE IF EXISTS `cp1_rbac_group`;
CREATE TABLE IF NOT EXISTS `cp1_rbac_group` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

DROP TABLE IF EXISTS `cp1_rbac_transformation`;
CREATE TABLE IF NOT EXISTS `cp1_rbac_transformation` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `transaction_id` int(10) NOT NULL,
  `transformation` varchar(256) NOT NULL,
  `right` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

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
  `priority` int(2) NOT NULL DEFAULT '6',
  `class_loader` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

DROP TABLE IF EXISTS `cp1_route_administration`;
CREATE TABLE IF NOT EXISTS `cp1_route_administration` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `class` varchar(256) NOT NULL,
  `file` varchar(256) NOT NULL,
  `resource_id` varchar(256) NOT NULL,
  `resource_transformation` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `cp1_route_dynamic`;
CREATE TABLE IF NOT EXISTS `cp1_route_dynamic` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  `GET` varchar(511) NOT NULL,
  `POST` varchar(511) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

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
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `cp1_class` (`id`, `name`, `file`) VALUES
(1, 'Chrome_Converter', 'lib/core/converter/converter.php'),
(2, 'Chrome_User_Registration', 'lib/View/content/user/registration.class.php'),
(3, 'Chrome_User_EMail', 'lib/User/user_email.php'),
(4, 'Chrome_Validator', 'lib/core/validator/validator.php'),
(5, 'Chrome_View_Helper_HTML', 'plugins/View/html.php'),
(6, 'Chrome_Language', 'lib/core/language.php'),
(7, 'Chrome_Converter_Value', 'lib/core/converter/converter.php'),
(8, 'Chrome_Form_Abstract', 'lib/core/form/form.php'),
(9, 'Chrome_Template', 'lib/core/template/template.php'),
(10, 'Chrome_User_Login', 'lib/classes/user/user.php'),
(11, 'Chrome_Controller_Index', 'modules/content/index/controller.php'),
(12, 'Chrome_Route_Static', 'lib/core/router/route/static.php'),
(13, 'Chrome_Route_Dynamic', 'lib/core/router/route/dynamic.php'),
(14, 'Chrome_Captcha', 'lib/captcha/captcha.php'),
(15, 'Chrome_RBAC', 'lib/rbac/rbac.php'),
(16, 'Chrome_Logger_Null', 'plugins/Log/null.php'),
(17, 'Chrome_Authentication_Chain_Database', 'lib/core/authentication/chain/database.php'),
(18, 'Chrome_Authentication_Chain_Session', 'lib/core/authentication/chain/session.php'),
(19, 'Chrome_Authentication_Chain_Cookie', 'lib/core/authentication/chain/cookie.php'),
(20, 'Chrome_Exception_Handler_Authentication', 'lib/exception/authentication.php'),
(21, 'Chrome_Authentication', 'lib/core/authentication/authentication.php'),
(22, 'Chrome_Authorisation', 'lib/core/authorisation/authorisation.php'),
(23, 'Chrome_Model_Authentication_Database', 'lib/core/authentication/chain/database.php'),
(24, 'Chrome_Redirection', 'lib/core/redirection.php'),
(25, 'Chrome_Controller_User_Login_Page', 'modules/content/user/login/page.php'),
(26, 'Chrome_Database_Right_Handler_Interface', 'lib/core/database/right_handler.php'),
(27, 'Chrome_Authorisation_Adapter_Default', 'lib/core/authorisation/adapter/default.php'),
(28, 'Chrome_Authorisation_Adapter_Interface', 'lib/core/authorisation/authorisation.php'),
(29, 'Chrome_Route_Administration', 'lib/core/router/route/administration.php'),
(30, 'Chrome_Model_User', 'lib/classes/user/model.php'),
(31, 'Chrome_Form_Decorator_Individual_Abstract', 'lib/core/form/decorator.php'),
(32, 'Chrome_Database_Connection_Mysql', 'lib/core/database/connection/mysql.php'),
(33, 'Chrome_Database_Connection_Postgresql', 'lib/core/database/connection/postgresql.php');

INSERT INTO `cp1_config` (`name`, `subclass`, `value`, `type`, `modul`, `hidden`) VALUES
('blacklist_host', 'Registration', 'localhost,', 'string', '', 0),
('comment_block_sec', 'News', '30', 'int', '', 0),
('Default_Design', 'Design', 'chrome', 'string', '', 0),
('default_user_group', 'Registration', '1', 'int', '', 0),
('email_sender', 'Registration', 'registration@localhost.de', 'string', '', 0),
('email_sender_name', 'Registration', 'Registrierung', 'string', '', 0),
('email_subject', 'Registration', 'Registrierung auf Localhost!', 'string', '', 0),
('expiration', 'Registration', '604800', 'int', '', 0),
('Gallery_Page_Limit', 'Gallery', '9', 'int', '', 0),
('Meta_Desc', 'Site', '', 'string', '', 0),
('Meta_Keywords', 'Site', '', 'string', '', 0),
('News_Comment_Limit', 'News', '15', 'int', '', 0),
('News_Page_Limit', 'News', '6', 'int', '', 0),
('Title_Beginning', 'Site', 'Chrome-PHP', 'string', '', 0),
('Title_Ending', 'Site', '', 'string', '', 0),
('Title_Separator', 'Site', ' :: ', 'string', '', 0),
('name', 'Site', 'CHROME-PHP', 'string', '', 0);

INSERT INTO `cp1_design` (`id`, `name`, `file`, `class`, `position`, `order`) VALUES
(1, 'right_box', 'modules/box/test/test.php', 'Chrome_View_Box_Test', 'right_box', 1),
(2, 'right_box', 'modules/box/test/test.php', 'Chrome_View_Box_Test', 'right_box', 2),
(3, 'right_box', 'modules/box/test/test.php', 'Chrome_View_Box_Test', 'right_box', 3),
(4, 'left_box', 'modules/box/test/test.php', 'Chrome_View_Box_Test', 'left_box', 1),
(5, 'Benchmark', 'modules/footer/benchmark/benchmark.php', 'Chrome_View_Footer_Benchmark', 'footer', 1),
(6, 'Header', 'modules/header/header/header.php', 'Chrome_View_Header_Header', 'header', 1),
(7, 'Login', 'modules/box/login/controller.php', 'Chrome_Controller_Box_Login', 'controller', 0);

INSERT INTO `cp1_design_mapper_static` (`id`, `view_id`, `position`, `priority`) VALUES
(1, 'Chrome_View_HTML_Bottom_JsIncluder', 'bottom', 0),
(2, 'Chrome_View_Footer_Benchmark', 'footer', 0),
(3, 'Chrome_View_HTML_Head_CssIncluder', 'head', 0),
(4, 'Chrome_View_Footer_VarDump', 'footer', 0),
(5, 'Chrome_View_Header_Header', 'header', 0),
(6, 'Chrome_View_Box_Login', 'left_box', 0),
(7, 'Chrome_View_Box_Test', 'right_box', 0);

INSERT INTO `cp1_require` (`id`, `name`, `path`, `activated`, `priority`, `class_loader`) VALUES
(1, 'Chrome_Require_Filter', 'plugins/Require/filter.php', 1, 4, 1),
(2, 'Chrome_Require_Exception', 'plugins/Require/exception.php', 1, 4, 1),
(3, 'Chrome_Require_Validator', 'plugins/Require/validator.php', 1, 4, 1),
(5, 'Chrome_Require_Design', 'plugins/Require/design.php', 1, 4, 1),
(6, 'Chrome_Require_Form', 'plugins/Require/form.php', 1, 4, 1),
(7, 'Chrome_View_Helper_HTML', 'plugins/View/html.php', 1, 6, 0),
(8, 'Chrome_View_Helper_Decorator', 'plugins/View/decorator.php', 1, 6, 0),
(9, 'Chrome_View_Helper_Error', 'plugins/View/error.php', 1, 6, 0),
(10, 'Chrome_Filter_Chain_Preprocessor', 'plugins/Filter/chain/preprocessor.php', 1, 6, 0),
(11, 'Chrome_Filter_Chain_Postprocessor', 'plugins/Filter/chain/postprocessor.php', 1, 6, 0),
(12, 'Chrome_Exception_Handler_Authentication', 'lib/exception/authentication.php', 1, 6, 0),
(13, 'Chrome_Authentication', 'lib/core/authentication/authentication.php', 1, 6, 0),
(14, 'Chrome_Authentication_Chain_Database', 'lib/core/authentication/chain/database.php', 1, 6, 0),
(15, 'Chrome_Authentication_Chain_Cookie', 'lib/core/authentication/chain/cookie.php', 1, 6, 0),
(16, 'Chrome_Authentication_Chain_Session', 'lib/core/authentication/chain/session.php', 1, 6, 0),
(17, 'Chrome_Authorisation', 'lib/core/authorisation/authorisation.php', 1, 6, 0),
(18, 'Chrome_Authorisation_Adapter_Default', 'lib/core/authorisation/adapter/default.php', 1, 6, 0),
(19, 'Chrome_Database_Right_Handler_Interface', 'lib/core/database/right_handler.php', 1, 5, 0),
(20, 'Chrome_Route_Static', 'lib/core/router/route/static.php', 1, 6, 0),
(21, 'Chrome_Route_Dynamic', 'lib/core/router/route/dynamic.php', 1, 6, 0),
(22, 'Chrome_Route_Administration', 'lib/core/router/route/administration.php', 1, 6, 0),
(23, 'Chrome_Request_Handler_AJAX', 'lib/core/request/ajax.php', 1, 6, 0),
(24, 'Chrome_Request_Handler_HTTP', 'lib/core/request/http.php', 1, 6, 0),
(25, 'Chrome_Controller_Module_Abstract', 'lib/core/controller/module.php', 1, 6, 0);

INSERT INTO `cp1_authorisation_user_default` (`user_id`, `group_id`) VALUES
(0, 1); -- guest, this must be set

INSERT INTO `cp1_authenticate` (`id`, `password`, `password_salt`, `cookie_token`, `time`) VALUES
(1, 'testAuthenticate', 'testAuthenticateSalt', NULL, 12345678),
(2, '4c85bf07d5d7c1ee8a6edba0f7646a58b6cb6ce9ea88b08d', 'ahFB319VKaD', NULL, 12345678); -- password is test

INSERT INTO `cp1_authorisation_resource_default` (`_resource_id`, `_transformation`, `_access`) VALUES
-- just for testing authorisation
('test', 'read', 1234666),
('test', 'write', 913785),
('test2', 'anyTrafo', 18462),
('testIsAllowed', 'guestAllowed', 1),
('testIsAllowed', 'guestNotAllowed', 123456);

INSERT INTO `cp1_authorisation_user_default` (`user_id`, `group_id`) VALUES
(1, 123456),
(2, 89123),
(3, 8388607),
(4, 168804);

DROP TABLE IF EXISTS `testing`;
CREATE TABLE IF NOT EXISTS `testing` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `var1` varchar(50) NOT NULL,
  `var2` varchar(50) NOT NULL,
  `var3` varchar(100) NOT NULL,
  `var4` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

ALTER TABLE `cp1_authorisation_user_default`
  ADD CONSTRAINT `authIdUserDefault` FOREIGN KEY (`user_id`) REFERENCES `cp1_authenticate` (`id`) ON UPDATE CASCADE;

ALTER TABLE `cp1_user`
  ADD CONSTRAINT `authId` FOREIGN KEY (`id`) REFERENCES `cp1_authenticate` (`id`) ON UPDATE CASCADE;
SET FOREIGN_KEY_CHECKS=1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
