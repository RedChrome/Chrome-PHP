SET FOREIGN_KEY_CHECKS = 0;
SET SESSION SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

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
('Chrome\\Database\\Connection\\Mysql', 'lib/core/database/connection/mysql.php'),
('Chrome\\Database\\Connection\\Postgresql', 'lib/core/database/connection/postgresql.php'),
('Chrome\\Form\\AbstractForm', 'lib/core/form/form.php'),
('Chrome\\Form\\Element\\AbstractBasicElement', 'lib/core/form/form.php'),
('Chrome\\Template\\PHP', 'lib/core/template/template.php'),
('Chrome\\Router\\Route\\StaticRoute', 'lib/core/router/route/static.php'),
('Chrome\\Router\\Route\\DynamicRoute', 'lib/core/router/route/dynamic.php'),
('Chrome\\Router\\Route\\FallbackRoute', 'lib/core/router/route/dynamic.php'),
('Chrome\\Router\\Route\\DirectoryRoute', 'lib/core/router/route/directory.php'),
('Chrome\\Captcha\\Captcha_Interface', 'lib/captcha/captcha.php'),
('Chrome\\Captcha\\Captcha', 'lib/captcha/captcha.php'),
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
('Chrome\\Model\\Database\\JsonStatement', 'lib/core/database/facade/model.php'),
('Chrome\\Controller\\Index', 'modules/content/index/controller.php'),
('Chrome\\Controller\\User\\Register', 'modules/content/user/register/controller.php'),
('Chrome\\Controller\\User\\Logout', 'modules/content/user/logout/controller.php'),
('Chrome\\Controller\\User\\Login', 'modules/content/user/login/controller.php'),
('Chrome\\Controller\\Captcha', 'modules/content/captcha/controller.php'),
('Chrome\\Controller\\RouteNotFound', 'modules/content/routenotfound/controller.php'),
('Chrome\\Controller\\Box\\Login', 'modules/box/login/controller.php'),
('Chrome\\Controller\\Footer\\VarDump', 'modules/footer/var_dump/controller.php'),
('Chrome\\Interactor\\User\\Registration',  'lib/modules/user/interactors/registration.php'),
('Chrome\\Interactor\\User\\Login', 'lib/modules/user/interactors/login.php'),
('Chrome\\Interactor\\User\\Logout', 'lib/modules/user/interactors/logout.php'),
('Chrome\\Linker\\Linker_Interface', 'lib/core/linker/linker.php'),
('Chrome\\Linker\\HTTP\\Linker', 'lib/core/linker/linker.php'),
('Chrome\\Linker\\Console\\Linker', 'lib/core/linker/console.php'),
('Chrome\\View\\Test\\Test', 'modules/box/test/test.php'),
('Chrome\\View\\Footer\\Benchmark', 'modules/footer/benchmark/benchmark.php'),
('Chrome\\View\\Header\\Header', 'modules/header/header/header.php'),
('Chrome\\View\\Html\\Head\\CssIncluder', 'modules/html/head/cssincluder/view.php'),
('Chrome\\View\\Html\\Bottom\\JsIncluder', 'modules/html/bottom/jsincluder/view.php'),
('Chrome\\View\\User\\Login\\FormRenderer', 'modules/content/user/login/view.php'),
('Chrome\\View\\Form\\Module\\Captcha\\Captcha', 'modules/content/captcha/include.php'),
('Chrome\\Helper\\User\\AuthenticationResolver\\Email', 'lib/modules/user/helpers/authenticationresolver/email.php'),
('Chrome\\View\\Captcha\\Captcha', 'modules/content/captcha/view.php'),
('Chrome\\Form\\Module\\Captcha\\Captcha', 'modules/content/captcha/include.php'),
('Chrome\\Controller\\RouteNotFound', 'modules/content/routenotfound/controller.php'),
('Chrome\\View\\RouteNotFound\\RouteNotFound', 'modules/content/routenotfound/view.php'),
('Chrome\\Form\\Module\\User\\Login', 'modules/content/user/login/include.php'),
('Chrome\\View\\Form\\Module\\User\\Login', 'modules/content/user/login/include.php'),
('Chrome\\View\\User\\Login', 'modules/content/user/login/view.php'),
('Chrome\\View\\User\\UserMenu', 'modules/box/login/view.php'),
('Chrome\\View\\User\\Register', 'modules/content/user/register/view.php'),
('Chrome\\Form\\Module\\User\\Register\\StepOne', 'modules/content/user/register/include.php'),
('Chrome\\Form\\Module\\User\\Register\\StepTwo', 'modules/content/user/register/include.php');


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
('fallback_class', 'Chrome/Router', '\\Chrome\\Controller\\RouteNotFound', 'string', '', FALSE);

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

INSERT INTO `cpp_design_static` (`name`, `class`, `position`, `type`, `theme`, `order`) VALUES
('right_box', '\\Chrome\\View\\Test\\Test', 'rightBox', 'view', 'chrome', 1),
('right_box', '\\Chrome\\View\\Test\\Test', 'rightBox', 'view', 'chrome', 2),
('right_box', '\\Chrome\\View\\Test\\Test', 'rightBox', 'view', 'chrome', 3),
('left_box', '\\Chrome\\View\\Test\\Test', 'leftBox', 'view', 'chrome', 1),
('Benchmark', '\\Chrome\\View\\Footer\\Benchmark', 'footer', 'view', 'chrome', 1),
('Header', '\\Chrome\\View\\Header\\Header', 'preBodyIn', 'view', 'chrome', 1),
('Login', '\\Chrome\\Controller\\Box\\Login', 'leftBox', 'controller', 'chrome', 0),
('cssIncluder', '\\Chrome\\View\\Html\\Head\\CssIncluder', 'head', 'view', 'chrome', 0),
('VarDump', '\\Chrome\\Controller\\Footer\\VarDump', 'footer', 'controller', 'chrome', 2),
('jsIncluder', '\\Chrome\\View\\html\\Bottom\\JsIncluder', 'postBodyIn', 'view', 'chrome', 0),
('right_box', '\\Chrome\\View\\Test\\Test', 'rightBox', 'view', 'chromeonesidebar', 1),
('right_box', '\\Chrome\\View\\Test\\Test', 'rightBox', 'view', 'chromeonesidebar', 2),
('right_box', '\\Chrome\\View\\Test\\Test', 'rightBox', 'view', 'chromeonesidebar', 3),
('left_box', '\\Chrome\\View\\Test\\Test', 'rightBox', 'view', 'chromeonesidebar', 1),
('Benchmark', '\\Chrome\\View\\Footer\\Benchmark', 'footer', 'view', 'chromeonesidebar', 1),
('Header', '\\Chrome\\View\\Header\\Header', 'preBodyIn', 'view', 'chromeonesidebar', 1),
('Login', '\\Chrome\\Controller\\Box\\Login', 'rightBox', 'controller', 'chromeonesidebar', 0),
('cssIncluder', '\\Chrome\\View\\Html\\Head\\CssIncluder', 'head', 'view', 'chromeonesidebar', 0),
('VarDump', '\\Chrome\\Controller\\Footer\\VarDump', 'footer', 'controller', 'chromeonesidebar', 2),
('jsIncluder', '\\Chrome\\View\\Html\\Bottom\\JsIncluder', 'postBodyIn', 'view', 'chromeonesidebar', 0);

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
('\\Chrome\\View\\Plugin\\Html', 'plugins/view/html.php', TRUE, 6, FALSE),
('\\Chrome\\View\\Plugin\\Decorator', 'plugins/view/decorator.php', TRUE, 6, FALSE),
('\\Chrome\\Filter\\Chain\\Preprocessor', 'plugins/filter/chain/preprocessor.php', TRUE, 6, FALSE),
('\\Chrome\\Filter\\Chain\\Postprocessor', 'plugins/filter/chain/postprocessor.php', TRUE, 6, FALSE),
('\\Chrome\\Exception\Authentication', 'lib/exception/authentication.php', TRUE, 6, FALSE),
('\\Chrome\\Authentication\\Authentication', 'lib/core/authentication/authentication.php', TRUE, 6, FALSE),
('\\Chrome\\Authentication\\Chain\\DatabaseChain', 'lib/core/authentication/chain/database.php', TRUE, 6, FALSE),
('\\Chrome\\Authentication\\Chain\\CookieChain', 'lib/core/authentication/chain/cookie.php', TRUE, 6, FALSE),
('\\Chrome\\Authentication\\Chain\\SessionChain', 'lib/core/authentication/chain/session.php', TRUE, 6, FALSE),
('\\Chrome\\Authorisation\\Authorisation', 'lib/core/authorisation/authorisation.php', TRUE, 6, FALSE),
('\\Chrome\\Authorisation\\Adapter\\Simple', 'lib/core/authorisation/adapter/simple.php', TRUE, 6, FALSE),
('\\Chrome\\Router\\Route\\FixedRoute', 'lib/core/router/route/fixed.php', TRUE, 6, FALSE),
('\\Chrome\\Router\\Route\\DynamicRoute', 'lib/core/router/route/dynamic.php', TRUE, 6, FALSE),
('\\Chrome\\Router\\Route\\FallbackRoute', 'lib/core/router/route/fallback.php', TRUE, 6, FALSE),
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
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Daten für Tabelle `cpp_resource`
--

INSERT INTO `cpp_resource` (`id`, `name`) VALUES (0, '');
UPDATE `cpp_resource` SET `id` = 0;
ALTER TABLE `cpp_resource` AUTO_INCREMENT = 1;

INSERT INTO `cpp_resource` (`id`, `name`) VALUES
(3, 'iden:\\Chrome\\Controller\\User\\Register|');

INSERT INTO `cpp_route_dynamic` (`id`, `name`, `class`, `GET`, `POST`) VALUES
(1, 'news_show', 'Chrome_Controller_News', 'action=show', '');

DROP TABLE IF EXISTS `cpp_route_fixed`;
CREATE TABLE IF NOT EXISTS `cpp_route_fixed` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `search` varchar(256) NOT NULL,
  `class` varchar(256) NOT NULL,
  `POST` varchar(512) NOT NULL,
  `GET` varchar(512) NOT NULL,
  PRIMARY KEY (`id`)
);

INSERT INTO `cpp_route_fixed` (`name`, `search`, `class`, `POST`, `GET`) VALUES
('index', '/', '\\Chrome\\Controller\\Index', '', ''),
('index', '/index.html', '\\Chrome\\Controller\\Index', '', ''),
('login', '/login.html', '\\Chrome\\Controller\\User\\Login', '', ''),
('register', '/registrieren.html', '\\Chrome\\Controller\\User\\Register', '', 'action=register'),
('logout', '/logout.html', '\\Chrome\\Controller\\User\\Logout', '', ''),
('registrationConfirm', '/registrierung_bestaetigen.html', '\\Chrome\\Controller\\User\\Register', '', 'action=confirm_registration'),
('testCaptcha', '/captcha.html', '\\Chrome\\Controller\\Captcha', '', ''),
('testCaptcha2', '/captcha/test/', '\\Chrome\\Controller\\Captcha', '', '');

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
