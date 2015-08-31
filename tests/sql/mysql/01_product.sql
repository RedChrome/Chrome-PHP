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
(NULL, '1873e707e31706b141d1199fb3c8da179b1395db492ede8b', 'Gd{|Yw"BA4z4,czCw~g0', '5e4869588d85631bb513bcfd7a4d811469836f20a6cc05a0', 1374572687);
 -- password is tiger

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
  `authentication_id` INTEGER NOT NULL,
  `group_id` INTEGER NOT NULL,
  KEY `authIdUserDefault` (`authentication_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `cpp_authorisation_user_default` (`authentication_id`, `group_id`) VALUES
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
('Chrome\\Converter\\Converter', 'lib/core/converter/converter.php'),
('Chrome\\Converter\\ConverterList', 'lib/core/converter/converter.php'),
('Chrome\\Exception\\Handler\\AuthenticationHandler', 'lib/exception/authentication.php'),
('Chrome\\Validator\\AbstractValidator', 'lib/core/validator/validator.php'),
('Chrome_View_Helper_HTML', 'plugins/view/html.php'),
('Chrome_Language', 'lib/core/language.php'),
('Chrome_Database_Right_Handler_Interface', 'lib/core/database/right_handler.php'),
('Chrome\\Database\\Connection\\Mysql', 'lib/core/database/connection/mysql.php'),
('Chrome\\Database\\Connection\\Postgresql', 'lib/core/database/connection/postgresql.php'),
('Chrome_Form_Abstract', 'lib/core/form/form.php'),
('Chrome\\Template\\PHP', 'lib/core/template/template.php'),
('Chrome\\Router\\Route\\StaticRoute', 'lib/core/router/route/static.php'),
('Chrome\\Router\\Route\\DynamicRoute', 'lib/core/router/route/dynamic.php'),
('Chrome\\Router\\Route\\FallbackRoute', 'lib/core/router/route/dynamic.php'),
('Chrome\\Captcha\\Captcha_Interface', 'lib/captcha/captcha.php'),
('Chrome\\Captcha\\Captcha', 'lib/captcha/captcha.php'),
('Chrome_RBAC', 'lib/rbac/rbac.php'),
('Chrome\\Model\\Authentication\\Database', 'lib/core/authentication/chain/database.php'),
('Chrome\\Authentication\\Chain\\DatabaseChain', 'lib/core/authentication/chain/database.php'),
('Chrome\\Authentication\\Chain\\SessionChain', 'lib/core/authentication/chain/session.php'),
('Chrome\\Authentication\\Chain\\CookieChain', 'lib/core/authentication/chain/cookie.php'),
('Chrome\\Authentication\\Authentication', 'lib/core/authentication/authentication.php'),
('Chrome\\Authentication\\Authentication_Interface', 'lib/core/authentication/authentication.php'),
('Chrome\\Authorisation\\Authorisation', 'lib/core/authorisation/authorisation.php'),
('Chrome\\Authorisation\\Adapter\\Adapter_Interface', 'lib/core/authorisation/authorisation.php'),
('Chrome\\Authorisation\\Adapter\\Simple', 'lib/core/authorisation/adapter/simple.php'),
('Chrome\\Redirection\\Redirection_Interface', 'lib/core/redirection.php'),
('Chrome\\Redirection\\Redirection', 'lib/core/redirection.php'),
('Chrome_Controller_User_Login_Page', 'modules/content/user/login/page.php'),
('Chrome\\Model\\Database\\JsonStatement', 'lib/core/database/facade/model.php'),
('Chrome_Form_Decorator_Individual_Abstract', 'lib/core/form/decorator.php'),
('Chrome_User_Registration', 'lib/View/content/user/registration.class.php'),
('Chrome_User_EMail', 'lib/User/user_email.php'),
('Chrome_User_Login', 'lib/classes/user/user.php'),
('Chrome\\Controller\\Index', 'modules/content/index/controller.php'),
('Chrome\\Controller\\User\\Register', 'modules/content/register/controller.php'),
('Chrome\\Controller\\User\\Logout', 'modules/content/user/logout/controller.php'),
('Chrome\\Controller\\User\\Login', 'modules/content/user/login/controller.php'),
('Chrome\\Controller\\Captcha', 'modules/content/captcha/controller.php'),
('Chrome\\Controller\\SiteNotFound', 'modules/content/SiteNotFound/controller.php'),
('Chrome\\Controller\\Box\\Login', 'modules/box/login/controller.php'),
('Chrome\\Controller\\Footer\\VarDump', 'modules/footer/var_dump/controller.php'),
('Chrome\\Interactor\\User\\Registration',  'lib/modules/user/interactors/registration.php'),
('Chrome\\Interactor\\User\\Login', 'lib/modules/user/interactors/login.php'),
('Chrome\\Interactor\\User\\Logout', 'lib/modules/user/interactors/logout.php'),
('Chrome\\Linker\\Linker_Interface', 'lib/core/linker/linker.php'),
('Chrome\\Linker\\HTTP\\Linker', 'lib/core/linker/linker.php'),
('Chrome\\Linker\\Console\\Linker', 'lib/core/linker/console.php'),
('Chrome_View_Box_Test', 'modules/box/test/test.php'),
('Chrome_View_Footer_Benchmark', 'modules/footer/benchmark/benchmark.php'),
('Chrome_View_Header_Header', 'modules/header/header/header.php'),
('Chrome_View_Html_Head_CssIncluder', 'modules/html/head/cssIncluder/view.php'),
('Chrome_View_HTML_Bottom_JsIncluder', 'modules/html/bottom/jsIncluder/view.php'),
('Chrome\\Helper\\User\\AuthenticationResolver\\Email', 'lib/modules/user/helpers/authenticationresolver/email.php');


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
('enable_https', 'Captcha/Recaptcha', 'false', 'boolean', '', FALSE),
('recaptcha_theme', 'Captcha/Recaptcha', 'clean', 'string', '', FALSE),
('default_theme', 'Theme', 'chrome', 'string', '', FALSE),
('fallback_class', 'Chrome/Router', '\\Chrome\\Controller\\SiteNotFound', 'string', '', FALSE);

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
  `class` VARCHAR(150) NOT NULL,
  `position` VARCHAR(50) NOT NULL,
  `type` VARCHAR(10) NOT NULL,
  `theme` VARCHAR(256) NOT NULL,
  `order` INTEGER(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

<<<<<<< HEAD
INSERT INTO `cpp_design_static` (`name`, `file`, `class`, `position`, `type`, `theme`, `order`) VALUES
('right_box', 'modules/box/test/test.php', 'Chrome_View_Box_Test', 'rightBox', 'view', 'chrome', 1),
('right_box', 'modules/box/test/test.php', 'Chrome_View_Box_Test', 'rightBox', 'view', 'chrome', 2),
('right_box', 'modules/box/test/test.php', 'Chrome_View_Box_Test', 'rightBox', 'view', 'chrome', 3),
('left_box', 'modules/box/test/test.php', 'Chrome_View_Box_Test', 'leftBox', 'view', 'chrome', 1),
('Benchmark', 'modules/footer/benchmark/benchmark.php', 'Chrome\\View\\Footer\\Benchmark', 'footer', 'view', 'chrome', 1),
('Header', 'modules/header/header/header.php', '\\Chrome\\View\\Header\\Header', 'preBodyIn', 'view', 'chrome', 1),
('Login', 'modules/box/login/controller.php', '\\Chrome\\Controller\\Box\\Login', 'leftBox', 'controller', 'chrome', 0),
('cssIncluder', 'modules/html/head/cssIncluder/view.php', '\\Chrome\\View\\Html\\Head\\CssIncluder', 'head', 'view', 'chrome', 0),
('VarDump', 'modules/footer/var_dump/controller.php', '\\Chrome\\Controller\\Footer\\VarDump', 'footer', 'controller', 'chrome', 2),
('jsIncluder', 'modules/html/bottom/jsIncluder/view.php', '\\Chrome\\View\\html\\Bottom\\JsIncluder', 'postBodyIn', 'view', 'chrome', 0),
('right_box', 'modules/box/test/test.php', 'Chrome_View_Box_Test', 'rightBox', 'view', 'chrome_one_sidebar', 1),
('right_box', 'modules/box/test/test.php', 'Chrome_View_Box_Test', 'rightBox', 'view', 'chrome_one_sidebar', 2),
('right_box', 'modules/box/test/test.php', 'Chrome_View_Box_Test', 'rightBox', 'view', 'chrome_one_sidebar', 3),
('left_box', 'modules/box/test/test.php', 'Chrome_View_Box_Test', 'rightBox', 'view', 'chrome_one_sidebar', 1),
('Benchmark', 'modules/footer/benchmark/benchmark.php', 'Chrome\\View\\Footer\\Benchmark', 'footer', 'view', 'chrome_one_sidebar', 1),
('Header', 'modules/header/header/header.php', '\\Chrome\\View\\Header\\Header', 'preBodyIn', 'view', 'chrome_one_sidebar', 1),
('Login', 'modules/box/login/controller.php', '\\Chrome\\Controller\\Box\\Login', 'rightBox', 'controller', 'chrome_one_sidebar', 0),
('cssIncluder', 'modules/html/head/cssIncluder/view.php', '\\Chrome\\View\\Html\\Head\\CssIncluder', 'head', 'view', 'chrome_one_sidebar', 0),
('VarDump', 'modules/footer/var_dump/controller.php', '\\Chrome\\Controller\\Footer\\VarDump', 'footer', 'controller', 'chrome_one_sidebar', 2),
('jsIncluder', 'modules/html/bottom/jsIncluder/view.php', '\\Chrome\\View\\Html\\Bottom\\JsIncluder', 'postBodyIn', 'view', 'chrome_one_sidebar', 0);
=======
INSERT INTO `cpp_design_static` (`name`, `class`, `position`, `type`, `theme`, `order`) VALUES
('right_box', 'Chrome_View_Box_Test', 'rightBox', 'view', 'chrome', 1),
('right_box', 'Chrome_View_Box_Test', 'rightBox', 'view', 'chrome', 2),
('right_box', 'Chrome_View_Box_Test', 'rightBox', 'view', 'chrome', 3),
('left_box', 'Chrome_View_Box_Test', 'leftBox', 'view', 'chrome', 1),
('Benchmark', 'Chrome_View_Footer_Benchmark', 'footer', 'view', 'chrome', 1),
('Header', 'Chrome_View_Header_Header', 'preBodyIn', 'view', 'chrome', 1),
('Login', '\\Chrome\\Controller\\Box\\Login', 'leftBox', 'controller', 'chrome', 0),
('cssIncluder', 'Chrome_View_Html_Head_CssIncluder', 'head', 'view', 'chrome', 0),
('VarDump','\\Chrome\\Controller\\Footer\\VarDump', 'footer', 'controller', 'chrome', 2),
('jsIncluder', 'Chrome_View_HTML_Bottom_JsIncluder', 'postBodyIn', 'view', 'chrome', 0),
('right_box', 'Chrome_View_Box_Test', 'rightBox', 'view', 'chrome_one_sidebar', 1),
('right_box', 'Chrome_View_Box_Test', 'rightBox', 'view', 'chrome_one_sidebar', 2),
('right_box', 'Chrome_View_Box_Test', 'rightBox', 'view', 'chrome_one_sidebar', 3),
('left_box', 'Chrome_View_Box_Test', 'rightBox', 'view', 'chrome_one_sidebar', 1),
('Benchmark', 'Chrome_View_Footer_Benchmark', 'footer', 'view', 'chrome_one_sidebar', 1),
('Header', 'Chrome_View_Header_Header', 'preBodyIn', 'view', 'chrome_one_sidebar', 1),
('Login', '\\Chrome\\Controller\\Box\\Login', 'rightBox', 'controller', 'chrome_one_sidebar', 0),
('cssIncluder', 'Chrome_View_Html_Head_CssIncluder', 'head', 'view', 'chrome_one_sidebar', 0),
('VarDump', '\\Chrome\\Controller\\Footer\\VarDump', 'footer', 'controller', 'chrome_one_sidebar', 2),
('jsIncluder', 'Chrome_View_HTML_Bottom_JsIncluder', 'postBodyIn', 'view', 'chrome_one_sidebar', 0);
>>>>>>> 6ce74eae23c960bea832dc2da29400c6e7f127b0

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
('\\Chrome\\Classloader\\Resolver\\Filter', 'plugins/classloader/filter.php', TRUE, 4, TRUE),
('\\Chrome\\Classloader\\Resolver\\Exception', 'plugins/classloader/exception.php', TRUE, 4, TRUE),
('\\Chrome\\Classloader\\Resolver\\Validator', 'plugins/classloader/validator.php', TRUE, 4, TRUE),
('\\Chrome\\Classloader\\Resolver\\Form', 'plugins/classloader/form.php',TRUE, 4, TRUE),
('\\Chrome\\Classloader\\Resolver\\Converter', 'plugins/classloader/converter.php',TRUE, 4, TRUE),
('\\Chrome\\Classloader\\Resolver\\Captcha', 'plugins/classloader/captcha.php', TRUE, 4, TRUE),
('\\Chrome\\Classloader\\Resolver\\Theme', 'plugins/classloader/theme.php', TRUE, 4, TRUE),
('\\Chrome_View_Plugin_HTML', 'plugins/view/html.php', TRUE, 6, FALSE),
('\\Chrome_View_Plugin_Decorator', 'plugins/view/decorator.php', TRUE, 6, FALSE),
('\\Chrome\\Filter\\Chain\\Preprocessor', 'plugins/filter/chain/preprocessor.php', TRUE, 6, FALSE),
('\\Chrome\\Filter\\Chain\\Postprocessor', 'plugins/filter/chain/postprocessor.php', TRUE, 6, FALSE),
('\\Chrome\\Exception\Authentication', 'lib/exception/authentication.php', TRUE, 6, FALSE),
('\\Chrome\\Authentication\\Authentication', 'lib/core/authentication/authentication.php', TRUE, 6, FALSE),
('\\Chrome\\Authentication\\Chain\\DatabaseChain', 'lib/core/authentication/chain/database.php', TRUE, 6, FALSE),
('\\Chrome\\Authentication\\Chain\\CookieChain', 'lib/core/authentication/chain/cookie.php', TRUE, 6, FALSE),
('\\Chrome\\Authentication\\Chain\\SessionChain', 'lib/core/authentication/chain/session.php', TRUE, 6, FALSE),
('\\Chrome\\Authorisation\\Authorisation', 'lib/core/authorisation/authorisation.php', TRUE, 6, FALSE),
('\\Chrome\\Authorisation\\Adapter\\Simple', 'lib/core/authorisation/adapter/simple.php', TRUE, 6, FALSE),
('\\Chrome\\Router\\Route\\StaticRoute', 'lib/core/router/route/static.php', TRUE, 6, FALSE),
('\\Chrome\\Router\\Route\\DynamicRoute', 'lib/core/router/route/dynamic.php', TRUE, 6, FALSE),
('\\Chrome\\Router\\Route\\FallbackRoute', 'lib/core/router/route/fallback.php', TRUE, 6, FALSE),
('\\Chrome\\Request\\Handler\\ConsoleHandler', 'lib/core/request/request/console.php', TRUE, 6, FALSE),
('\\Chrome\\Request\\Handler\\HTTPHandler', 'lib/core/request/request/http.php', TRUE, 6, FALSE),
('\\Chrome\\Response\\Handler\\HTTPHandler', 'lib/core/response/response/http.php', TRUE, 6, FALSE),
('\\Chrome\\Response\\Handler\\JSONHandler', 'lib/core/response/response/json.php', TRUE, 6, FALSE),
('\\Chrome\\Response\\Handler\\ConsoleHandler', 'lib/core/response/response/console.php', TRUE, 6, FALSE),
('\\Chrome\\Controller\\AbstractModule', 'lib/core/controller/module.php', TRUE, 6, FALSE);

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
(1, '', '\\Chrome\\Controller\\Index', '', ''),
(1, 'index.html', '\\Chrome\\Controller\\Index', '', ''),
(2, 'login.html', '\\Chrome\\Controller\\User\\Login', '', ''),
(3, 'registrieren.html', '\\Chrome\\Controller\\User\\Register', '', 'action=register'),
(4, 'logout.html', '\\Chrome\\Controller\\User\\Logout', '', ''),
(6, 'registrierung_bestaetigen.html', '\\Chrome\\Controller\\User\\Register', '', 'action=confirm_registration'),
(7, 'captcha.html', '\\Chrome\\Controller\\Captcha', '', '');

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
  `authentication_id` INTEGER UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `cpp_user` (`id`, `name`, `email`, `time`, `avatar`, `address`, `design`, `authentication_id`) VALUES
(1, 'Alex', 'redchrome@gmx.de', 1349179579, '', '', 'default', 1);

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
  ADD CONSTRAINT `authIdUserDefault` FOREIGN KEY (`authentication_id`) REFERENCES `cpp_authenticate` (`id`) ON UPDATE CASCADE;

ALTER TABLE `cpp_user`
  ADD CONSTRAINT `authId` FOREIGN KEY (`authentication_id`) REFERENCES `cpp_authenticate` (`id`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
SET FOREIGN_KEY_CHECKS = 1;
