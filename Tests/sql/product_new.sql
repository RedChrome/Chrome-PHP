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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

INSERT INTO `cp1_admin_navi` (`id`, `parentid`, `isparent`, `name`, `action`, `url`, `access`) VALUES
(NULL, 0, 1, 'Gallery', 'Gallery', 'gallery/gallery.php', 2),
(NULL, 1, 0, 'Events', 'Gallery_Events', 'gallery/events.php', 2),
(NULL, 1, 0, 'Bilder', 'Gallery_Images', 'gallery/images.php', 2),
(NULL, 0, 1, 'News', 'News', 'news/news.php', 2),
(NULL, 4, 0, 'Hinzuf&uuml;gen', 'News_add', 'news/news_add.php', 2),
(NULL, 1, 0, 'Bild Hochladen', 'Gallery_Image_Upload', 'gallery/upload_image.php', 2);

DROP TABLE IF EXISTS `cp1_authenticate`;
CREATE TABLE IF NOT EXISTS `cp1_authenticate` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `password` varchar(256) NOT NULL,
  `password_salt` varchar(256) NOT NULL,
  `cookie_token` varchar(50) NULL,
  `time` int(12) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `cp1_authenticate` (`id`, `password`, `password_salt`, `cookie_token`, `time`) VALUES
(NULL, '', '', '', 0),
(NULL, 'b10617e307e7731817dac8b39f19d1418bde2e49db95139b', 'Gd{|Yw"BA4z4,czCw~g0', '5e4869588d85631bb513bcfd7a4d811469836f20a6cc05a0', 1374572687);

DROP TABLE IF EXISTS `cp1_authorisation_rbac`;
CREATE TABLE IF NOT EXISTS `cp1_authorisation_rbac` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `group` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `cp1_authorisation_rbac` (`id`, `user_id`, `group`) VALUES
(1, 1, 'user');

DROP TABLE IF EXISTS `cp1_authorisation_resource_default`;
CREATE TABLE IF NOT EXISTS `cp1_authorisation_resource_default` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `_resource_id` varchar(256) NOT NULL,
  `_transformation` varchar(256) NOT NULL,
  `_access` mediumint(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `cp1_authorisation_resource_default` (`id`, `_resource_id`, `_transformation`, `_access`) VALUES
(1, 'test', 'read', 2097),
(2, 'test', 'write', 2097151),
(3, 'register', 'register', 1),
(4, 'test_resource_1', '0', 157),
(5, 'admin_index', '', 4);

DROP TABLE IF EXISTS `cp1_authorisation_user_default`;
CREATE TABLE IF NOT EXISTS `cp1_authorisation_user_default` (
  `user_id` int(10) NOT NULL,
  `group_id` mediumint(10) NOT NULL,
  KEY `authIdUserDefault` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `cp1_authorisation_user_default` (`user_id`, `group_id`) VALUES
(0, 1),
(3, 4);

DROP TABLE IF EXISTS `cp1_class`;
CREATE TABLE IF NOT EXISTS `cp1_class` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `cp1_class` (`id`, `name`, `file`) VALUES
(NULL, 'Chrome_Converter', 'lib/core/converter/converter.php'),
(NULL, 'Chrome_Converter_List', 'lib/core/converter/converter.php'),
(NULL, 'Chrome_Exception_Handler_Authentication', 'lib/exception/authentication.php'),
(NULL, 'Chrome_Validator', 'lib/core/validator/validator.php'),
(NULL, 'Chrome_View_Helper_HTML', 'plugins/View/html.php'),
(NULL, 'Chrome_Language', 'lib/core/language.php'),
(NULL, 'Chrome_Database_Right_Handler_Interface', 'lib/core/database/right_handler.php'),
(NULL, 'Chrome_Database_Connection_Mysql', 'lib/core/database/connection/mysql.php'),
(NULL, 'Chrome_Database_Connection_Postgresql', 'lib/core/database/connection/postgresql.php'),
(NULL, 'Chrome_Form_Abstract', 'lib/core/form/form.php'),
(NULL, 'Chrome_Template', 'lib/core/template/template.php'),
(NULL, 'Chrome_Route_Administration', 'lib/core/router/route/administration.php'),
(NULL, 'Chrome_Route_Static', 'lib/core/router/route/static.php'),
(NULL, 'Chrome_Route_Dynamic', 'lib/core/router/route/dynamic.php'),
(NULL, 'Chrome_Captcha_Interface', 'lib/captcha/captcha.php'),
(NULL, 'Chrome_Captcha', 'lib/captcha/captcha.php'),
(NULL, 'Chrome_RBAC', 'lib/rbac/rbac.php'),
(NULL, 'Chrome_Logger_Null', 'plugins/Log/null.php'),
(NULL, 'Chrome_Model_Authentication_Database', 'lib/core/authentication/chain/database.php'),
(NULL, 'Chrome_Authentication_Chain_Database', 'lib/core/authentication/chain/database.php'),
(NULL, 'Chrome_Authentication_Chain_Session', 'lib/core/authentication/chain/session.php'),
(NULL, 'Chrome_Authentication_Chain_Cookie', 'lib/core/authentication/chain/cookie.php'),
(NULL, 'Chrome_Authentication', 'lib/core/authentication/authentication.php'),
(NULL, 'Chrome_Authorisation_Adapter_Interface', 'lib/core/authorisation/authorisation.php'),
(NULL, 'Chrome_Authorisation_Adapter_Default', 'lib/core/authorisation/adapter/default.php'),
(NULL, 'Chrome_Authorisation', 'lib/core/authorisation/authorisation.php'),
(NULL, 'Chrome_Redirection', 'lib/core/redirection.php'),
(NULL, 'Chrome_Controller_User_Login_Page', 'modules/content/user/login/page.php'),
(NULL, 'Chrome_Model_User_Database', 'lib/classes/user/model.php'),
(NULL, 'Chrome_Form_Decorator_Individual_Abstract', 'lib/core/form/decorator.php'),
(NULL, 'Chrome_User_Registration', 'lib/View/content/user/registration.class.php'),
(NULL, 'Chrome_User_EMail', 'lib/User/user_email.php'),
(NULL, 'Chrome_User_Login', 'lib/classes/user/user.php'),
(NULL, 'Chrome_Controller_Index', 'modules/content/index/controller.php');

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
('name', 'Site', 'CHROME-PHP', 'string', '', 0),
('public_key', 'Captcha', '6LcQrt4SAAAAAIPs9toLqZ761XTA39aS_AWP-Nog', 'string', '', 0),
('private_key', 'Captcha', '6LcQrt4SAAAAAF7flTN8uwi_9eSFy43jOuUcPGm3', 'string', '', 0),
('recaptcha_theme', 'Captcha', 'clean', 'string', '', 0),
('default_theme', 'Theme', 'chrome', 'string', '', 0);

DROP TABLE IF EXISTS `cp1_design_controller`;
CREATE TABLE IF NOT EXISTS `cp1_design_controller` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `controller_class` varchar(255) NOT NULL,
  `design_class` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cp1_design_layout`;
CREATE TABLE IF NOT EXISTS `cp1_design_layout` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `controller` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cp1_design_mapper_static`;
CREATE TABLE IF NOT EXISTS `cp1_design_mapper_static` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `view_id` varchar(256) NOT NULL,
  `position` varchar(256) NOT NULL,
  `priority` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `cp1_design_mapper_static` (`id`, `view_id`, `position`, `priority`) VALUES
(NULL, 'Chrome_View_HTML_Bottom_JsIncluder', 'bottom', 0),
(NULL, 'Chrome_View_HTML_Head_CssIncluder', 'head', 0),
(NULL, 'Chrome_View_Header_Header', 'header', 0),
(NULL, 'Chrome_View_Footer_Benchmark', 'footer', 0),
(NULL, 'Chrome_View_Footer_VarDump', 'footer', 0),
(NULL, 'Chrome_View_Box_Login', 'left_box', 0),
(NULL, 'Chrome_View_Box_Test', 'right_box', 0);

DROP TABLE IF EXISTS `cp1_design_static`;
CREATE TABLE IF NOT EXISTS `cp1_design_static` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `name` varchar(75) NOT NULL,
  `file` varchar(100) NOT NULL,
  `class` varchar(150) NOT NULL,
  `position` varchar(50) NOT NULL,
  `type` varchar(10) NOT NULL,
  `theme` varchar(256) NOT NULL,
  `order` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `cp1_design_static` (`id`, `name`, `file`, `class`, `position`, `type`, `theme`, `order`) VALUES
(NULL, 'right_box', 'modules/box/test/test.php', 'Chrome_View_Box_Test', 'rightBox', 'view', 'chrome', 1),
(NULL, 'right_box', 'modules/box/test/test.php', 'Chrome_View_Box_Test', 'rightBox', 'view', 'chrome', 2),
(NULL, 'right_box', 'modules/box/test/test.php', 'Chrome_View_Box_Test', 'rightBox', 'view', 'chrome', 3),
(NULL, 'left_box', 'modules/box/test/test.php', 'Chrome_View_Box_Test', 'leftBox', 'view', 'chrome', 1),
(NULL, 'Benchmark', 'modules/footer/benchmark/benchmark.php', 'Chrome_View_Footer_Benchmark', 'footer', 'view', 'chrome', 1),
(NULL, 'Header', 'modules/header/header/header.php', 'Chrome_View_Header_Header', 'preBodyIn', 'view', 'chrome', 1),
(NULL, 'Login', 'modules/box/login/controller.php', 'Chrome_Controller_Box_Login', 'leftBox', 'controller', 'chrome', 0),
(NULL, 'cssIncluder', 'modules/html/head/cssIncluder/view.php', 'Chrome_View_Html_Head_CssIncluder', 'head', 'view', 'chrome', 0),
(NULL, 'VarDum', 'modules/footer/var_dump/var_dump.php', 'Chrome_Controller_Footer_VarDump', 'footer', 'controller', 'chrome', 2),
(NULL, 'jsIncluder', 'modules/html/bottom/jsIncluder/view.php', 'Chrome_View_HTML_Bottom_JsIncluder', 'postBody', 'view', 'chrome', 0);

DROP TABLE IF EXISTS `cp1_rbac_group`;
CREATE TABLE IF NOT EXISTS `cp1_rbac_group` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `cp1_rbac_group` (`id`, `name`) VALUES
(1, 'guest'),
(2, 'user'),
(3, 'superUser'),
(4, 'admin');

DROP TABLE IF EXISTS `cp1_rbac_group_role`;
CREATE TABLE IF NOT EXISTS `cp1_rbac_group_role` (
  `group_id` int(10) NOT NULL,
  `role_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `cp1_rbac_group_role` (`group_id`, `role_id`) VALUES
(1, 1),
(4, 3);

DROP TABLE IF EXISTS `cp1_rbac_role`;
CREATE TABLE IF NOT EXISTS `cp1_rbac_role` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `cp1_rbac_role` (`id`, `name`) VALUES
(1, 'news'),
(2, 'news_comment'),
(3, 'news_moderator'),
(4, 'news_special');

DROP TABLE IF EXISTS `cp1_rbac_role_transaction`;
CREATE TABLE IF NOT EXISTS `cp1_rbac_role_transaction` (
  `role_id` int(10) NOT NULL,
  `transaction_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `cp1_rbac_role_transaction` (`role_id`, `transaction_id`) VALUES
(1, 1),
(3, 2);

DROP TABLE IF EXISTS `cp1_rbac_transaction`;
CREATE TABLE IF NOT EXISTS `cp1_rbac_transaction` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `cp1_rbac_transaction` (`id`, `name`) VALUES
(1, 'news'),
(2, 'news_modify'),
(3, 'news_mark');

DROP TABLE IF EXISTS `cp1_rbac_transformation`;
CREATE TABLE IF NOT EXISTS `cp1_rbac_transformation` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `transaction_id` int(10) NOT NULL,
  `transformation` varchar(256) NOT NULL,
  `right` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `cp1_rbac_transformation` (`id`, `transaction_id`, `transformation`, `right`) VALUES
(1, 1, 'read', 1),
(2, 1, 'write', 0),
(3, 2, 'read', 1),
(4, 2, 'write', 1),
(5, 3, 'mark', 1);

DROP TABLE IF EXISTS `cp1_rbac_user_group`;
CREATE TABLE IF NOT EXISTS `cp1_rbac_user_group` (
  `user_id` int(10) NOT NULL,
  `group_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `cp1_rbac_user_group` (`user_id`, `group_id`) VALUES
(1, 4);

DROP TABLE IF EXISTS `cp1_rbac_user_role`;
CREATE TABLE IF NOT EXISTS `cp1_rbac_user_role` (
  `user_id` int(10) NOT NULL,
  `role_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `cp1_rbac_user_role` (`user_id`, `role_id`) VALUES
(1, 4);

DROP TABLE IF EXISTS `cp1_require`;
CREATE TABLE IF NOT EXISTS `cp1_require` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `activated` int(1) DEFAULT NULL,
  `priority` int(2) NOT NULL DEFAULT '6',
  `class_loader` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `cp1_require` (`id`, `name`, `path`, `activated`, `priority`, `class_loader`) VALUES
(NULL, 'Chrome_Require_Loader_Filter', 'plugins/Require/filter.php', 1, 4, 1),
(NULL, 'Chrome_Require_Loader_Exception', 'plugins/Require/exception.php', 1, 4, 1),
(NULL, 'Chrome_Require_Loader_Validator', 'plugins/Require/validator.php', 1, 4, 1),
(NULL, 'Chrome_Require_Loader_Form', 'plugins/Require/form.php', 1, 4, 1),
(NULL, 'Chrome_Require_Loader_Converter', 'plugins/Require/converter.php', 1, 4, 1),
(NULL, 'Chrome_View_Plugin_HTML', 'plugins/View/html.php', 1, 6, 0),
(NULL, 'Chrome_View_Plugin_Decorator', 'plugins/View/decorator.php', 1, 6, 0),
(NULL, 'Chrome_View_Plugin_Error', 'plugins/View/error.php', 1, 6, 0),
(NULL, 'Chrome_Filter_Chain_Preprocessor', 'plugins/Filter/chain/preprocessor.php', 1, 6, 0),
(NULL, 'Chrome_Filter_Chain_Postprocessor', 'plugins/Filter/chain/postprocessor.php', 1, 6, 0),
(NULL, 'Chrome_Exception_Handler_Authentication', 'lib/exception/authentication.php', 1, 6, 0),
(NULL, 'Chrome_Authentication', 'lib/core/authentication/authentication.php', 1, 6, 0),
(NULL, 'Chrome_Authentication_Chain_Database', 'lib/core/authentication/chain/database.php', 1, 6, 0),
(NULL, 'Chrome_Authentication_Chain_Cookie', 'lib/core/authentication/chain/cookie.php', 1, 6, 0),
(NULL, 'Chrome_Authentication_Chain_Session', 'lib/core/authentication/chain/session.php', 1, 6, 0),
(NULL, 'Chrome_Authorisation', 'lib/core/authorisation/authorisation.php', 1, 6, 0),
(NULL, 'Chrome_Authorisation_Adapter_Default', 'lib/core/authorisation/adapter/default.php', 1, 6, 0),
(NULL, 'Chrome_Route_Static', 'lib/core/router/route/static.php', 1, 6, 0),
(NULL, 'Chrome_Route_Dynamic', 'lib/core/router/route/dynamic.php', 1, 6, 0),
(NULL, 'Chrome_Route_Administration', 'lib/core/router/route/administration.php', 1, 6, 0),
(NULL, 'Chrome_Request_Handler_Console', 'lib/core/request/request/console.php', 1, 6, 0),
(NULL, 'Chrome_Request_Handler_HTTP', 'lib/core/request/request/http.php', 1, 6, 0),
(NULL, 'Chrome_Response_Handler_HTTP', 'lib/core/response/response/http.php', 1, 6, 0),
(NULL, 'Chrome_Response_Handler_JSON', 'lib/core/response/response/json.php', 1, 6, 0),
(NULL, 'Chrome_Response_Handler_Console', 'lib/core/response/response/console.php', 1, 6, 0),
(NULL, 'Chrome_Controller_Module_Abstract', 'lib/core/controller/module.php', 1, 6, 0);

DROP TABLE IF EXISTS `cp1_route_administration`;
CREATE TABLE IF NOT EXISTS `cp1_route_administration` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `class` varchar(256) NOT NULL,
  `file` varchar(256) NOT NULL,
  `resource_id` varchar(256) NOT NULL,
  `resource_transformation` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cp1_route_dynamic`;
CREATE TABLE IF NOT EXISTS `cp1_route_dynamic` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  `GET` varchar(511) NOT NULL,
  `POST` varchar(511) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `cp1_route_dynamic` (`id`, `name`, `class`, `file`, `GET`, `POST`) VALUES
(1, 'news_show', 'Chrome_Controller_News', 'modules/content/news/controller.php', 'action=show', '');

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

INSERT INTO `cp1_route_static` (`id`, `name`, `search`, `class`, `file`, `POST`, `GET`) VALUES
(NULL, 'index', '', 'Chrome_Controller_Index', 'modules/content/index/controller.php', '', ''),
(NULL, 'index', 'index', 'Chrome_Controller_Index', 'modules/content/index/controller.php', '', ''),
(NULL, 'login', 'login', 'Chrome_Controller_Content_Login', 'modules/content/user/login/controller.php', '', ''),
(NULL, 'site_not_found', '404', 'Chrome_Controller_SiteNotFound', 'modules/content/SiteNotFound/controller.php', '', ''),
(NULL, 'register', 'registrieren', 'Chrome_Controller_Register', 'modules/content/register/controller.php', '', 'action=register'),
(NULL, 'news', 'news', 'Chrome_Controller_News', 'modules/content/news/controller.php', '', 'action=show'),
(NULL, 'logout', 'logout', 'Chrome_Controller_Content_Logout', 'modules/content/user/logout/controller.php', '', ''),
(NULL, 'register_confirm', 'registrierung_bestaetigen', 'Chrome_Controller_Register', 'modules/content/register/controller.php', '', 'action=confirm_registration'),
(NULL, 'captcha', 'captcha', 'Chrome_Controller_Captcha', 'modules/content/captcha/controller.php', '', '');

DROP TABLE IF EXISTS `cp1_user`;
CREATE TABLE IF NOT EXISTS `cp1_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `email` varchar(200) NOT NULL,
  `group` int(2) NOT NULL DEFAULT '0',
  `time` int(15) NOT NULL,
  `avatar` varchar(50) NULL,
  `address` varchar(300) NOT NULL,
  `design` varchar(20) NOT NULL DEFAULT 'default',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `cp1_user` (`id`, `name`, `email`, `group`, `time`, `avatar`, `address`, `design`) VALUES
(1, 'Alex', 'redchrome@gmx.de', 0, 1349179579, '', '', 'default');

DROP TABLE IF EXISTS `cp1_user_regist`;
CREATE TABLE IF NOT EXISTS `cp1_user_regist` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `pass` varchar(100) NOT NULL,
  `pw_salt` varchar(20) NOT NULL,
  `email` varchar(200) NOT NULL,
  `time` int(15) NOT NULL,
  `key` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


ALTER TABLE `cp1_authorisation_user_default`
  ADD CONSTRAINT `authIdUserDefault` FOREIGN KEY (`user_id`) REFERENCES `cp1_authenticate` (`id`) ON UPDATE CASCADE;

ALTER TABLE `cp1_user`
  ADD CONSTRAINT `authId` FOREIGN KEY (`id`) REFERENCES `cp1_authenticate` (`id`) ON UPDATE CASCADE;
SET FOREIGN_KEY_CHECKS=1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
