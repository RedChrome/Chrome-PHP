SET FOREIGN_KEY_CHECKS = 0;
SET SESSION SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


DROP TABLE IF EXISTS `cpp_admin_navi`;
CREATE TABLE IF NOT EXISTS `cpp_admin_navi` (
  `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  `parentid` INTEGER NOT NULL,
  `isparent` INTEGER NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `action` VARCHAR(100) NOT NULL,
  `url` VARCHAR(100) NOT NULL,
  `access` INTEGER NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

INSERT INTO `cpp_admin_navi` (`parentid`, `isparent`, `name`, `action`, `url`, `access`) VALUES
( 0, 1, 'Gallery', 'Gallery', 'gallery/gallery.php', 2),
(1, 0, 'Events', 'Gallery_Events', 'gallery/events.php', 2),
(1, 0, 'Bilder', 'Gallery_Images', 'gallery/images.php', 2),
(0, 1, 'News', 'News', 'news/news.php', 2),
(4, 0, 'Hinzuf&uuml;gen', 'News_add', 'news/news_add.php', 2),
(1, 0, 'Bild Hochladen', 'Gallery_Image_Upload', 'gallery/upload_image.php', 2);

DROP TABLE IF EXISTS `cpp_authenticate` CASCADE;
CREATE TABLE IF NOT EXISTS `cpp_authenticate` (
  `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  `password` VARCHAR(256) NOT NULL,
  `password_salt` VARCHAR(256) NOT NULL,
  `cookie_token` VARCHAR(50) NULL,
  `time` INTEGER NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `cpp_authenticate` (`id`, `password`, `password_salt`, `cookie_token`, `time`) VALUES
(0, '', '', '', 0);
UPDATE `cpp_authenticate` SET `id` = 0 WHERE `id` = 1;
ALTER TABLE `cpp_authenticate` AUTO_INCREMENT = 1;
INSERT INTO `cpp_authenticate` (`id`, `password`, `password_salt`, `cookie_token`, `time`) VALUES
(NULL, 'b10617e307e7731817dac8b39f19d1418bde2e49db95139b', 'Gd{|Yw"BA4z4,czCw~g0', '5e4869588d85631bb513bcfd7a4d811469836f20a6cc05a0', 1374572687);

DROP TABLE IF EXISTS `cpp_authorisation_rbac`;
CREATE TABLE IF NOT EXISTS `cpp_authorisation_rbac` (
  `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INTEGER NOT NULL,
  `group` VARCHAR(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `cpp_authorisation_rbac` (`id`, `user_id`, `group`) VALUES
(1, 1, 'user');

DROP TABLE IF EXISTS `cpp_authorisation_resource_default`;
CREATE TABLE IF NOT EXISTS `cpp_authorisation_resource_default` (
  `id` INTEGER UNSIGNED  NOT NULL AUTO_INCREMENT,
  `resource_id` INTEGER(11) NOT NULL,
  `transformation` VARCHAR(256) NOT NULL,
  `resource_group` INTEGER NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `cpp_authorisation_resource_default` (`id`, `resource_id`, `transformation`, `resource_group`) VALUES
(3, 3, 'register', 1);

DROP TABLE IF EXISTS `cpp_authorisation_user_default`;
CREATE TABLE IF NOT EXISTS `cpp_authorisation_user_default` (
  `user_id` INTEGER NOT NULL,
  `group_id` INTEGER NOT NULL,
  KEY `authIdUserDefault` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `cpp_authorisation_user_default` (`user_id`, `group_id`) VALUES
(0, 1),
(1, 4);

DROP TABLE IF EXISTS `cpp_class`;
CREATE TABLE IF NOT EXISTS `cpp_class` (
  `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(256) NOT NULL,
  `file` VARCHAR(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `cpp_class` (`name`, `file`) VALUES
('Chrome_Converter', 'lib/core/converter/converter.php'),
('Chrome_Converter_List', 'lib/core/converter/converter.php'),
('Chrome_Exception_Handler_Authentication', 'lib/exception/authentication.php'),
('Chrome_Validator', 'lib/core/validator/validator.php'),
('Chrome_View_Helper_HTML', 'plugins/View/html.php'),
('Chrome_Language', 'lib/core/language.php'),
('Chrome_Database_Right_Handler_Interface', 'lib/core/database/right_handler.php'),
('Chrome_Database_Connection_Mysql', 'lib/core/database/connection/mysql.php'),
('Chrome_Database_Connection_Postgresql', 'lib/core/database/connection/postgresql.php'),
('Chrome_Form_Abstract', 'lib/core/form/form.php'),
('Chrome_Template', 'lib/core/template/template.php'),
('Chrome_Route_Administration', 'lib/core/router/route/administration.php'),
('Chrome_Route_Static', 'lib/core/router/route/static.php'),
('Chrome_Route_Dynamic', 'lib/core/router/route/dynamic.php'),
('Chrome_Captcha_Interface', 'lib/captcha/captcha.php'),
('Chrome_Captcha', 'lib/captcha/captcha.php'),
('Chrome_RBAC', 'lib/rbac/rbac.php'),
('Chrome_Model_Authentication_Database', 'lib/core/authentication/chain/database.php'),
('Chrome_Authentication_Chain_Database', 'lib/core/authentication/chain/database.php'),
('Chrome_Authentication_Chain_Session', 'lib/core/authentication/chain/session.php'),
('Chrome_Authentication_Chain_Cookie', 'lib/core/authentication/chain/cookie.php'),
('Chrome_Authentication', 'lib/core/authentication/authentication.php'),
('\\Chrome\\Authorisation\\Authorisation', 'lib/core/authorisation/authorisation.php'),
('\\Chrome\\Authorisation\\Adapter\\Adapter_Interface', 'lib/core/authorisation/authorisation.php'),
('\\Chrome\\Authorisation\\Adapter\\Simple', 'lib/core/authorisation/adapter/simple.php'),
('Chrome_Redirection', 'lib/core/redirection.php'),
('Chrome_Controller_User_Login_Page', 'modules/content/user/login/page.php'),
('Chrome_Model_User_Database', 'lib/classes/user/model.php'),
('Chrome_Model_Database_Statement', 'lib/core/database/interface/model.php'),
('Chrome_Form_Decorator_Individual_Abstract', 'lib/core/form/decorator.php'),
('Chrome_User_Registration', 'lib/View/content/user/registration.class.php'),
('Chrome_User_EMail', 'lib/User/user_email.php'),
('Chrome_User_Login', 'lib/classes/user/user.php'),
('Chrome_Controller_Index', 'modules/content/index/controller.php'),
('Chrome_Controller_Register', 'modules/content/register/controller.php'),
('Chrome_Controller_Content_Logout', 'modules/content/user/logout/controller.php'),
('Chrome_Controller_Content_Login', 'modules/content/user/login/controller.php'),
('Chrome_Controller_Captcha', 'modules/content/captcha/controller.php'),
('Chrome_Controller_SiteNotFound', 'modules/content/SiteNotFound/controller.php'),
('\\Chrome\\Interactor\\User\\Registration',  'lib/modules/user/interactors/registration.php');


DROP TABLE IF EXISTS `cpp_config`;
CREATE TABLE IF NOT EXISTS `cpp_config` (
  `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(256) NOT NULL,
  `subclass` VARCHAR(256) NOT NULL,
  `value` VARCHAR(256) NOT NULL,
  `type` VARCHAR(10) NOT NULL,
  `modul` VARCHAR(35) NOT NULL,
  `hidden` BOOLEAN NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `cpp_config` (`name`, `subclass`, `value`, `type`, `modul`, `hidden`) VALUES
('blacklist_host', 'general', 'localhost,', 'string', '', FALSE),
('comment_block_sec', 'News', '30', 'integer', '', FALSE),
('Default_Design', 'Design', 'chrome', 'string', '', FALSE),
('default_user_group', 'Registration', '1', 'integer', '', FALSE),
('email_sender', 'Registration', 'registration@localhost.de', 'string', '', FALSE),
('email_sender_name', 'Registration', 'Registrierung', 'string', '', FALSE),
('email_subject', 'Registration', 'Registrierung auf Localhost!', 'string', '', FALSE),
('expiration', 'Registration', '604800', 'integer', '', FALSE),
('name_min_length', 'user', '3', 'integer', '', FALSE),
('name_max_length', 'user', '50', 'integer', '', FALSE),
('Gallery_Page_Limit', 'Gallery', '9', 'integer', '', FALSE),
('Meta_Desc', 'Site', '', 'string', '', FALSE),
('Meta_Keywords', 'Site', '', 'string', '', FALSE),
('News_Comment_Limit', 'News', '15', 'integer', '', FALSE),
('News_Page_Limit', 'News', '6', 'integer', '', FALSE),
('Title_Beginning', 'Site', 'Chrome-PHP', 'string', '', FALSE),
('Title_Ending', 'Site', '', 'string', '', FALSE),
('Title_Separator', 'Site', ' :: ', 'string', '', FALSE),
('name', 'Site', 'CHROME-PHP', 'string', '', FALSE),
('public_key', 'Captcha/Recaptcha', '6LcQrt4SAAAAAIPs9toLqZ761XTA39aS_AWP-Nog', 'string', '', FALSE),
('private_key', 'Captcha/Recaptcha', '6LcQrt4SAAAAAF7flTN8uwi_9eSFy43jOuUcPGm3', 'string', '', FALSE),
('server_api', 'Captcha/Recaptcha', 'http://www.google.com/recaptcha/api', 'string', '', FALSE),
('recaptcha_theme', 'Captcha/Recaptcha', 'clean', 'string', '', FALSE),
('default_theme', 'Theme', 'chrome', 'string', '', FALSE);

DROP TABLE IF EXISTS `cpp_design_controller`;
CREATE TABLE IF NOT EXISTS `cpp_design_controller` (
  `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  `controller_class` VARCHAR(256) NOT NULL,
  `design_class` VARCHAR(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cpp_design_layout`;
CREATE TABLE IF NOT EXISTS `cpp_design_layout` (
  `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  `controller` VARCHAR(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cpp_design_static`;
CREATE TABLE IF NOT EXISTS `cpp_design_static` (
  `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(75) NOT NULL,
  `file` VARCHAR(100) NOT NULL,
  `class` VARCHAR(150) NOT NULL,
  `position` VARCHAR(50) NOT NULL,
  `type` VARCHAR(10) NOT NULL,
  `theme` VARCHAR(256) NOT NULL,
  `order` INTEGER(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `cpp_design_static` (`name`, `file`, `class`, `position`, `type`, `theme`, `order`) VALUES
('right_box', 'modules/box/test/test.php', 'Chrome_View_Box_Test', 'rightBox', 'view', 'chrome', 1),
('right_box', 'modules/box/test/test.php', 'Chrome_View_Box_Test', 'rightBox', 'view', 'chrome', 2),
('right_box', 'modules/box/test/test.php', 'Chrome_View_Box_Test', 'rightBox', 'view', 'chrome', 3),
('left_box', 'modules/box/test/test.php', 'Chrome_View_Box_Test', 'leftBox', 'view', 'chrome', 1),
('Benchmark', 'modules/footer/benchmark/benchmark.php', 'Chrome_View_Footer_Benchmark', 'footer', 'view', 'chrome', 1),
('Header', 'modules/header/header/header.php', 'Chrome_View_Header_Header', 'preBodyIn', 'view', 'chrome', 1),
('Login', 'modules/box/login/controller.php', 'Chrome_Controller_Box_Login', 'leftBox', 'controller', 'chrome', 0),
('cssIncluder', 'modules/html/head/cssIncluder/view.php', 'Chrome_View_Html_Head_CssIncluder', 'head', 'view', 'chrome', 0),
('VarDump', 'modules/footer/var_dump/var_dump.php', 'Chrome_Controller_Footer_VarDump', 'footer', 'controller', 'chrome', 2),
('jsIncluder', 'modules/html/bottom/jsIncluder/view.php', 'Chrome_View_HTML_Bottom_JsIncluder', 'postBodyIn', 'view', 'chrome', 0),
('right_box', 'modules/box/test/test.php', 'Chrome_View_Box_Test', 'rightBox', 'view', 'chrome_one_sidebar', 1),
('right_box', 'modules/box/test/test.php', 'Chrome_View_Box_Test', 'rightBox', 'view', 'chrome_one_sidebar', 2),
('right_box', 'modules/box/test/test.php', 'Chrome_View_Box_Test', 'rightBox', 'view', 'chrome_one_sidebar', 3),
('left_box', 'modules/box/test/test.php', 'Chrome_View_Box_Test', 'rightBox', 'view', 'chrome_one_sidebar', 1),
('Benchmark', 'modules/footer/benchmark/benchmark.php', 'Chrome_View_Footer_Benchmark', 'footer', 'view', 'chrome_one_sidebar', 1),
('Header', 'modules/header/header/header.php', 'Chrome_View_Header_Header', 'preBodyIn', 'view', 'chrome_one_sidebar', 1),
('Login', 'modules/box/login/controller.php', 'Chrome_Controller_Box_Login', 'rightBox', 'controller', 'chrome_one_sidebar', 0),
('cssIncluder', 'modules/html/head/cssIncluder/view.php', 'Chrome_View_Html_Head_CssIncluder', 'head', 'view', 'chrome_one_sidebar', 0),
('VarDump', 'modules/footer/var_dump/var_dump.php', 'Chrome_Controller_Footer_VarDump', 'footer', 'controller', 'chrome_one_sidebar', 2),
('jsIncluder', 'modules/html/bottom/jsIncluder/view.php', 'Chrome_View_HTML_Bottom_JsIncluder', 'postBodyIn', 'view', 'chrome_one_sidebar', 0);

DROP TABLE IF EXISTS `cpp_rbac_group`;
CREATE TABLE IF NOT EXISTS `cpp_rbac_group` (
  `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `cpp_rbac_group` (`id`, `name`) VALUES
(1, 'guest'),
(2, 'user'),
(3, 'superUser'),
(4, 'admin');

DROP TABLE IF EXISTS `cpp_rbac_group_role`;
CREATE TABLE IF NOT EXISTS `cpp_rbac_group_role` (
  `group_id` INTEGER NOT NULL,
  `role_id` INTEGER NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `cpp_rbac_group_role` (`group_id`, `role_id`) VALUES
(1, 1),
(4, 3);

DROP TABLE IF EXISTS `cpp_rbac_role`;
CREATE TABLE IF NOT EXISTS `cpp_rbac_role` (
  `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `cpp_rbac_role` (`id`, `name`) VALUES
(1, 'news'),
(2, 'news_comment'),
(3, 'news_moderator'),
(4, 'news_special');

DROP TABLE IF EXISTS `cpp_rbac_role_transaction`;
CREATE TABLE IF NOT EXISTS `cpp_rbac_role_transaction` (
  `role_id` INTEGER NOT NULL,
  `transaction_id` INTEGER NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `cpp_rbac_role_transaction` (`role_id`, `transaction_id`) VALUES
(1, 1),
(3, 2);

DROP TABLE IF EXISTS `cpp_rbac_transaction`;
CREATE TABLE IF NOT EXISTS `cpp_rbac_transaction` (
  `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `cpp_rbac_transaction` (`id`, `name`) VALUES
(1, 'news'),
(2, 'news_modify'),
(3, 'news_mark');

DROP TABLE IF EXISTS `cpp_rbac_transformation`;
CREATE TABLE IF NOT EXISTS `cpp_rbac_transformation` (
  `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  `transaction_id` INTEGER NOT NULL,
  `transformation` VARCHAR(256) NOT NULL,
  `right` INTEGER NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `cpp_rbac_transformation` (`id`, `transaction_id`, `transformation`, `right`) VALUES
(1, 1, 'read', 1),
(2, 1, 'write', 0),
(3, 2, 'read', 1),
(4, 2, 'write', 1),
(5, 3, 'mark', 1);

DROP TABLE IF EXISTS `cpp_rbac_user_group`;
CREATE TABLE IF NOT EXISTS `cpp_rbac_user_group` (
  `user_id` INTEGER NOT NULL,
  `group_id` INTEGER NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `cpp_rbac_user_group` (`user_id`, `group_id`) VALUES
(1, 4);

DROP TABLE IF EXISTS `cpp_rbac_user_role`;
CREATE TABLE IF NOT EXISTS `cpp_rbac_user_role` (
  `user_id` INTEGER NOT NULL,
  `role_id` INTEGER NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `cpp_rbac_user_role` (`user_id`, `role_id`) VALUES
(1, 4);

DROP TABLE IF EXISTS `cpp_autoload`;
CREATE TABLE IF NOT EXISTS `cpp_autoload` (
  `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(256) NOT NULL,
  `path` VARCHAR(256) NOT NULL,
  `activated` BOOLEAN NOT NULL DEFAULT '0',
  `priority` INTEGER NOT NULL DEFAULT '6',
  `is_class_resolver` BOOLEAN NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `cpp_autoload` (`name`, `path`, `activated`, `priority`, `is_class_resolver`) VALUES
('\\Chrome\\Classloader\\Resolver_Filter', 'plugins/classloader/filter.php', TRUE, 4, TRUE),
('\\Chrome\\Classloader\\Resolver_Exception', 'plugins/classloader/exception.php', TRUE, 4, TRUE),
('\\Chrome\\Classloader\\Resolver_Validator', 'plugins/classloader/validator.php', TRUE, 4, TRUE),
('\\Chrome\\Classloader\\Resolver_Form', 'plugins/classloader/form.php',TRUE, 4, TRUE),
('\\Chrome\\Classloader\\Resolver_Converter', 'plugins/classloader/converter.php',TRUE, 4, TRUE),
('\\Chrome_View_Plugin_HTML', 'plugins/View/html.php', TRUE, 6, FALSE),
('\\Chrome_View_Plugin_Decorator', 'plugins/View/decorator.php', TRUE, 6, FALSE),
('\\Chrome_Filter_Chain_Preprocessor', 'plugins/Filter/chain/preprocessor.php', TRUE, 6, FALSE),
('\\Chrome_Filter_Chain_Postprocessor', 'plugins/Filter/chain/postprocessor.php', TRUE, 6, FALSE),
('\\Chrome_Exception_Handler_Authentication', 'lib/exception/authentication.php', TRUE, 6, FALSE),
('\\Chrome_Authentication', 'lib/core/authentication/authentication.php', TRUE, 6, FALSE),
('\\Chrome_Authentication_Chain_Database', 'lib/core/authentication/chain/database.php', TRUE, 6, FALSE),
('\\Chrome_Authentication_Chain_Cookie', 'lib/core/authentication/chain/cookie.php', TRUE, 6, FALSE),
('\\Chrome_Authentication_Chain_Session', 'lib/core/authentication/chain/session.php', TRUE, 6, FALSE),
('\\Chrome\\Authorisation\\Authorisation', 'lib/core/authorisation/authorisation.php', TRUE, 6, FALSE),
('\\Chrome\\Authorisation\\Adapter\\Simple', 'lib/core/authorisation/adapter/simple.php', TRUE, 6, FALSE),
('\\Chrome_Route_Static', 'lib/core/router/route/static.php', TRUE, 6, FALSE),
('\\Chrome_Route_Dynamic', 'lib/core/router/route/dynamic.php', TRUE, 6, FALSE),
('\\Chrome_Route_Administration', 'lib/core/router/route/administration.php', TRUE, 6, FALSE),
('\\Chrome_Request_Handler_Console', 'lib/core/request/request/console.php', TRUE, 6, FALSE),
('\\Chrome_Request_Handler_HTTP', 'lib/core/request/request/http.php', TRUE, 6, FALSE),
('\\Chrome_Response_Handler_HTTP', 'lib/core/response/response/http.php', TRUE, 6, FALSE),
('\\Chrome_Response_Handler_JSON', 'lib/core/response/response/json.php', TRUE, 6, FALSE),
('\\Chrome_Response_Handler_Console', 'lib/core/response/response/console.php', TRUE, 6, FALSE),
('\\Chrome_Controller_Module_Abstract', 'lib/core/controller/module.php', TRUE, 6, FALSE);

DROP TABLE IF EXISTS `cpp_route_administration`;
CREATE TABLE IF NOT EXISTS `cpp_route_administration` (
  `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(256) NOT NULL,
  `class` VARCHAR(256) NOT NULL,
  `file` VARCHAR(256) NOT NULL,
  `resource_id` VARCHAR(256) NOT NULL,
  `resource_transformation` VARCHAR(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cpp_route_dynamic`;
CREATE TABLE IF NOT EXISTS `cpp_route_dynamic` (
  `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(256) NOT NULL,
  `class` VARCHAR(256) NOT NULL,
  `GET` VARCHAR(511) NOT NULL,
  `POST` VARCHAR(511) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cpp_resource`;
CREATE TABLE IF NOT EXISTS `cpp_resource` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `parameter` varchar(130) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE` (`name`,`parameter`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Daten für Tabelle `cpp_resource`
--

INSERT INTO `cpp_resource` (`id`, `name`, `parameter`) VALUES (0, '', '');
UPDATE `cpp_resource` SET `id` = 0;
ALTER TABLE `cpp_resource` AUTO_INCREMENT = 1;

INSERT INTO `cpp_resource` (`id`, `name`, `parameter`) VALUES
(1, 'index', ''),
(2, 'login', ''),
(3, 'register', ''),
(4, 'logout', ''),
(5, 'siteNotFound', ''),
(6, 'registrationConfirm', ''),
(7, 'testCaptcha', '');

INSERT INTO `cpp_route_dynamic` (`id`, `name`, `class`, `GET`, `POST`) VALUES
(1, 'news_show', 'Chrome_Controller_News', 'action=show', '');

DROP TABLE IF EXISTS `cpp_route_static`;
CREATE TABLE IF NOT EXISTS `cpp_route_static` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `resource_id` int(11) NOT NULL,
  `search` varchar(256) NOT NULL,
  `class` varchar(256) NOT NULL,
  `POST` varchar(512) NOT NULL,
  `GET` varchar(512) NOT NULL,
  PRIMARY KEY (`id`)
);

INSERT INTO `cpp_route_static` (`resource_id`, `search`, `class`, `POST`, `GET`) VALUES
(1, '', 'Chrome_Controller_Index', '', ''),
(1, 'index.html', 'Chrome_Controller_Index', '', ''),
(2, 'login.html', 'Chrome_Controller_Content_Login', '', ''),
(5, '404.html', 'Chrome_Controller_SiteNotFound', '', ''),
(3, 'registrieren.html', 'Chrome_Controller_Register', '', 'action=register'),
(4, 'logout.html', 'Chrome_Controller_Content_Logout', '', ''),
(6, 'registrierung_bestaetigen.html', 'Chrome_Controller_Register', '', 'action=confirm_registration'),
(7, 'captcha.html', 'Chrome_Controller_Captcha', '', '');

DROP TABLE IF EXISTS `cpp_user`;
CREATE TABLE IF NOT EXISTS `cpp_user` (
  `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `group` INTEGER NOT NULL DEFAULT '0',
  `time` INTEGER NOT NULL,
  `avatar` VARCHAR(256) NULL,
  `address` VARCHAR(300) NULL,
  `design` VARCHAR(256) NOT NULL DEFAULT 'default',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `cpp_user` (`id`, `name`, `email`, `time`, `avatar`, `address`, `design`) VALUES
(1, 'Alex', 'redchrome@gmx.de', 1349179579, '', '', 'default');

DROP TABLE IF EXISTS `cpp_user_regist`;
CREATE TABLE IF NOT EXISTS `cpp_user_regist` (
  `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `pass` VARCHAR(100) NOT NULL,
  `pw_salt` VARCHAR(20) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `time` INTEGER NOT NULL,
  `key` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


ALTER TABLE `cpp_authorisation_user_default`
  ADD CONSTRAINT `authIdUserDefault` FOREIGN KEY (`user_id`) REFERENCES `cpp_authenticate` (`id`) ON UPDATE CASCADE;

ALTER TABLE `cpp_user`
  ADD CONSTRAINT `authId` FOREIGN KEY (`id`) REFERENCES `cpp_authenticate` (`id`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
SET FOREIGN_KEY_CHECKS = 1;