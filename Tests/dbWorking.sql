-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 21. Mrz 2013 um 13:57
-- Server Version: 5.5.16
-- PHP-Version: 5.3.8

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `chrome_2`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cp1_admin_navi`
--

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

--
-- Daten für Tabelle `cp1_admin_navi`
--

INSERT INTO `cp1_admin_navi` (`id`, `parentid`, `isparent`, `name`, `action`, `url`, `access`) VALUES
(1, 0, 1, 'Gallery', 'Gallery', 'gallery/gallery.php', 2),
(2, 1, 0, 'Events', 'Gallery_Events', 'gallery/events.php', 2),
(3, 1, 0, 'Bilder', 'Gallery_Images', 'gallery/images.php', 2),
(4, 0, 1, 'News', 'News', 'news/news.php', 2),
(6, 4, 0, 'Hinzuf&uuml;gen', 'News_add', 'news/news_add.php', 2),
(7, 1, 0, 'Bild Hochladen', 'Gallery_Image_Upload', 'gallery/upload_image.php', 2);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cp1_authenticate`
--

DROP TABLE IF EXISTS `cp1_authenticate`;
CREATE TABLE IF NOT EXISTS `cp1_authenticate` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `password` varchar(256) NOT NULL,
  `password_salt` varchar(256) NOT NULL,
  `cookie_token` varchar(50) NOT NULL,
  `time` int(12) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Daten für Tabelle `cp1_authenticate`
--

INSERT INTO `cp1_authenticate` (`id`, `password`, `password_salt`, `cookie_token`, `time`) VALUES
(0, '', '', '', 0),
(3, 'b10617e307e7731817dac8b39f19d1418bde2e49db95139b', 'Gd{|Yw"BA4z4,czCw~g0', '7d70621628d3bbc2eba84b0a1d8685bddcc8ad19c6d39e9e', 1361099522);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cp1_authorisation_rbac`
--

DROP TABLE IF EXISTS `cp1_authorisation_rbac`;
CREATE TABLE IF NOT EXISTS `cp1_authorisation_rbac` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `group` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `cp1_authorisation_rbac`
--

INSERT INTO `cp1_authorisation_rbac` (`id`, `user_id`, `group`) VALUES
(1, 1, 'user');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cp1_authorisation_resource_default`
--

DROP TABLE IF EXISTS `cp1_authorisation_resource_default`;
CREATE TABLE IF NOT EXISTS `cp1_authorisation_resource_default` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `_resource_id` varchar(256) NOT NULL,
  `_transformation` varchar(256) NOT NULL,
  `_access` mediumint(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Daten für Tabelle `cp1_authorisation_resource_default`
--

INSERT INTO `cp1_authorisation_resource_default` (`id`, `_resource_id`, `_transformation`, `_access`) VALUES
(1, 'test', 'read', 2097),
(2, 'test', 'write', 2097151),
(3, 'register', 'register', 1),
(4, 'test_resource_1', '0', 157),
(5, 'admin_index', '', 4);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cp1_authorisation_user_default`
--

DROP TABLE IF EXISTS `cp1_authorisation_user_default`;
CREATE TABLE IF NOT EXISTS `cp1_authorisation_user_default` (
  `user_id` int(10) NOT NULL,
  `group_id` mediumint(10) NOT NULL,
  KEY `authIdUserDefault` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `cp1_authorisation_user_default`
--

INSERT INTO `cp1_authorisation_user_default` (`user_id`, `group_id`) VALUES
(0, 1),
(3, 4);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cp1_class`
--

DROP TABLE IF EXISTS `cp1_class`;
CREATE TABLE IF NOT EXISTS `cp1_class` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=35 ;

--
-- Daten für Tabelle `cp1_class`
--

INSERT INTO `cp1_class` (`id`, `name`, `file`) VALUES
(NULL, 'Chrome_Converter', 'lib/core/converter/converter.php'),
(NULL, 'Chrome_User_Registration', 'lib/View/content/user/registration.class.php'),
(NULL, 'Chrome_User_EMail', 'lib/User/user_email.php'),
(NULL, 'Chrome_Validator', 'lib/core/validator/validator.php'),
(NULL, 'Chrome_View_Helper_HTML', 'plugins/View/html.php'),
(NULL, 'Chrome_Language', 'lib/core/language.php'),
(NULL, 'Chrome_Converter_Value', 'lib/core/converter/converter.php'),
(NULL, 'Chrome_Form_Abstract', 'lib/core/form/form.php'),
(NULL, 'Chrome_Template', 'lib/core/template/template.php'),
(NULL, 'Chrome_User_Login', 'lib/classes/user/user.php'),
(NULL, 'Chrome_Controller_Index', 'modules/content/index/controller.php'),
(NULL, 'Chrome_Route_Static', 'lib/core/router/route/static.php'),
(NULL, 'Chrome_Route_Dynamic', 'lib/core/router/route/dynamic.php'),
(NULL, 'Chrome_Captcha', 'lib/captcha/captcha.php'),
(NULL, 'Chrome_RBAC', 'lib/rbac/rbac.php'),
(NULL, 'Chrome_Logger_Null', 'plugins/Log/null.php'),
(NULL, 'Chrome_Authentication_Chain_Database', 'lib/core/authentication/chain/database.php'),
(NULL, 'Chrome_Authentication_Chain_Session', 'lib/core/authentication/chain/session.php'),
(NULL, 'Chrome_Authentication_Chain_Cookie', 'lib/core/authentication/chain/cookie.php'),
(NULL, 'Chrome_Exception_Handler_Authentication', 'lib/exception/authentication.php'),
(NULL, 'Chrome_Authentication', 'lib/core/authentication/authentication.php'),
(NULL, 'Chrome_Authorisation', 'lib/core/authorisation/authorisation.php'),
(NULL, 'Chrome_Model_Authentication_Database', 'lib/core/authentication/chain/database.php'),
(NULL, 'Chrome_Redirection', 'lib/core/redirection.php'),
(NULL, 'Chrome_Controller_User_Login_Page', 'modules/content/user/login/page.php'),
(NULL, 'Chrome_Database_Right_Handler_Interface', 'lib/core/database/right_handler.php'),
(NULL, 'Chrome_Authorisation_Adapter_Default', 'lib/core/authorisation/adapter/default.php'),
(NULL, 'Chrome_Authorisation_Adapter_Interface', 'lib/core/authorisation/authorisation.php'),
(NULL, 'Chrome_Route_Administration', 'lib/core/router/route/administration.php'),
(NULL, 'Chrome_Model_User_Database', 'lib/classes/user/model.php'),
(NULL, 'Chrome_Form_Decorator_Individual_Abstract', 'lib/core/form/decorator.php'),
(NULL, 'Chrome_Database_Connection_Mysql', 'lib/core/database/connection/mysql.php'),
(NULL, 'Chrome_Database_Connection_Postgresql', 'lib/core/database/connection/postgresql.php'),
(NULL, 'Chrome_Captcha_Interface', 'lib/captcha/captcha.php');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cp1_config`
--

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

--
-- Daten für Tabelle `cp1_config`
--

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
('recaptcha_theme', 'Captcha', 'clean', 'string', '', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cp1_design`
--

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

--
-- Daten für Tabelle `cp1_design`
--

INSERT INTO `cp1_design` (`id`, `name`, `file`, `class`, `position`, `order`) VALUES
(1, 'right_box', 'modules/box/test/test.php', 'Chrome_View_Box_Test', 'right_box', 1),
(2, 'right_box', 'modules/box/test/test.php', 'Chrome_View_Box_Test', 'right_box', 2),
(3, 'right_box', 'modules/box/test/test.php', 'Chrome_View_Box_Test', 'right_box', 3),
(4, 'left_box', 'modules/box/test/test.php', 'Chrome_View_Box_Test', 'left_box', 1),
(5, 'Benchmark', 'modules/footer/benchmark/benchmark.php', 'Chrome_View_Footer_Benchmark', 'footer', 1),
(6, 'Header', 'modules/header/header/header.php', 'Chrome_View_Header_Header', 'header', 1),
(7, 'Login', 'modules/box/login/controller.php', 'Chrome_Controller_Box_Login', 'controller', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cp1_design_controller`
--

DROP TABLE IF EXISTS `cp1_design_controller`;
CREATE TABLE IF NOT EXISTS `cp1_design_controller` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `controller_class` varchar(255) NOT NULL,
  `design_class` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cp1_design_layout`
--

DROP TABLE IF EXISTS `cp1_design_layout`;
CREATE TABLE IF NOT EXISTS `cp1_design_layout` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `controller` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cp1_design_mapper_static`
--

DROP TABLE IF EXISTS `cp1_design_mapper_static`;
CREATE TABLE IF NOT EXISTS `cp1_design_mapper_static` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `view_id` varchar(256) NOT NULL,
  `position` varchar(256) NOT NULL,
  `priority` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Daten für Tabelle `cp1_design_mapper_static`
--

INSERT INTO `cp1_design_mapper_static` (`id`, `view_id`, `position`, `priority`) VALUES
(1, 'Chrome_View_HTML_Bottom_JsIncluder', 'bottom', 0),
(2, 'Chrome_View_Footer_Benchmark', 'footer', 0),
(3, 'Chrome_View_HTML_Head_CssIncluder', 'head', 0),
(4, 'Chrome_View_Footer_VarDump', 'footer', 0),
(5, 'Chrome_View_Header_Header', 'header', 0),
(6, 'Chrome_View_Box_Login', 'left_box', 0),
(7, 'Chrome_View_Box_Test', 'right_box', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cp1_news`
--

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

--
-- Daten für Tabelle `cp1_news`
--

INSERT INTO `cp1_news` (`id`, `title`, `text`, `author`, `time`, `access`) VALUES
(1, 'TEST NEWS', 'Nurn Test ^^ joa mei', 1, 1241729480, 4);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cp1_news_comments`
--

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

--
-- Daten für Tabelle `cp1_news_comments`
--

INSERT INTO `cp1_news_comments` (`id`, `news_id`, `user_id`, `text`, `time`, `IP`) VALUES
(14, 1, '1', 'test jow...', 1244977088, '127.0.0.1'),
(13, 1, '1', 'nochn test :P', 1244976216, '127.0.0.1'),
(15, 1, '1', '..... test ^^', 1244977130, '127.0.0.1'),
(16, 1, '1', 'damn... nur 1500 XD', 1244977161, '127.0.0.1'),
(17, 1, '1', 'nochn test...', 1244977207, '127.0.0.1'),
(18, 1, '1', 'test lol :P rbtsn', 1244977258, '127.0.0.1'),
(19, 1, '1', 'jzsnshzmwthmgh', 1244977293, '127.0.0.1'),
(20, 1, '1', 'komment nr. 11', 1244977350, '127.0.0.1'),
(21, 1, '1', 'test....', 1244977725, '127.0.0.1'),
(25, 1, '1', 'hm geht\\&#039;s?', 1245435336, '127.0.0.1'),
(26, 1, '1', '\\&#039; ! \\&quot; &sect; $ % &amp; / ( ) = ?', 1245435647, '127.0.0.1'),
(27, 1, '1', '\\&#039; ! \\&quot; &sect; $ % &amp;amp; / ( ) = ?', 1245435702, '127.0.0.1'),
(28, 1, '1', '&#039; ! &quot; &sect; $ % &amp;amp;amp; / ( ) = ?', 1245435833, '127.0.0.1'),
(34, 1, '1', 'test................', 1257512176, '127.0.0.1'),
(32, 1, '1', 't&ouml;&ouml;&ouml;&ouml;&ouml;&ouml;&ouml;&ouml;&ouml;&ouml;&ouml;&ouml;&ouml;&ouml;&ouml;&ouml;&ouml;&ouml;&ouml;&ouml;st ^^ hrr :P', 1255276662, '127.0.0.1');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cp1_rbac_group`
--

DROP TABLE IF EXISTS `cp1_rbac_group`;
CREATE TABLE IF NOT EXISTS `cp1_rbac_group` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Daten für Tabelle `cp1_rbac_group`
--

INSERT INTO `cp1_rbac_group` (`id`, `name`) VALUES
(1, 'guest'),
(2, 'user'),
(3, 'superUser'),
(4, 'admin');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cp1_rbac_group_role`
--

DROP TABLE IF EXISTS `cp1_rbac_group_role`;
CREATE TABLE IF NOT EXISTS `cp1_rbac_group_role` (
  `group_id` int(10) NOT NULL,
  `role_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `cp1_rbac_group_role`
--

INSERT INTO `cp1_rbac_group_role` (`group_id`, `role_id`) VALUES
(1, 1),
(4, 3);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cp1_rbac_role`
--

DROP TABLE IF EXISTS `cp1_rbac_role`;
CREATE TABLE IF NOT EXISTS `cp1_rbac_role` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Daten für Tabelle `cp1_rbac_role`
--

INSERT INTO `cp1_rbac_role` (`id`, `name`) VALUES
(1, 'news'),
(2, 'news_comment'),
(3, 'news_moderator'),
(4, 'news_special');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cp1_rbac_role_transaction`
--

DROP TABLE IF EXISTS `cp1_rbac_role_transaction`;
CREATE TABLE IF NOT EXISTS `cp1_rbac_role_transaction` (
  `role_id` int(10) NOT NULL,
  `transaction_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `cp1_rbac_role_transaction`
--

INSERT INTO `cp1_rbac_role_transaction` (`role_id`, `transaction_id`) VALUES
(1, 1),
(3, 2);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cp1_rbac_transaction`
--

DROP TABLE IF EXISTS `cp1_rbac_transaction`;
CREATE TABLE IF NOT EXISTS `cp1_rbac_transaction` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Daten für Tabelle `cp1_rbac_transaction`
--

INSERT INTO `cp1_rbac_transaction` (`id`, `name`) VALUES
(1, 'news'),
(2, 'news_modify'),
(3, 'news_mark');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cp1_rbac_transformation`
--

DROP TABLE IF EXISTS `cp1_rbac_transformation`;
CREATE TABLE IF NOT EXISTS `cp1_rbac_transformation` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `transaction_id` int(10) NOT NULL,
  `transformation` varchar(256) NOT NULL,
  `right` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Daten für Tabelle `cp1_rbac_transformation`
--

INSERT INTO `cp1_rbac_transformation` (`id`, `transaction_id`, `transformation`, `right`) VALUES
(1, 1, 'read', 1),
(2, 1, 'write', 0),
(3, 2, 'read', 1),
(4, 2, 'write', 1),
(5, 3, 'mark', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cp1_rbac_user_group`
--

DROP TABLE IF EXISTS `cp1_rbac_user_group`;
CREATE TABLE IF NOT EXISTS `cp1_rbac_user_group` (
  `user_id` int(10) NOT NULL,
  `group_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `cp1_rbac_user_group`
--

INSERT INTO `cp1_rbac_user_group` (`user_id`, `group_id`) VALUES
(1, 4);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cp1_rbac_user_role`
--

DROP TABLE IF EXISTS `cp1_rbac_user_role`;
CREATE TABLE IF NOT EXISTS `cp1_rbac_user_role` (
  `user_id` int(10) NOT NULL,
  `role_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `cp1_rbac_user_role`
--

INSERT INTO `cp1_rbac_user_role` (`user_id`, `role_id`) VALUES
(1, 4);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cp1_require`
--

DROP TABLE IF EXISTS `cp1_require`;
CREATE TABLE IF NOT EXISTS `cp1_require` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `activated` int(1) DEFAULT NULL,
  `priority` int(2) NOT NULL DEFAULT '6',
  `class_loader` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31 ;

--
-- Daten für Tabelle `cp1_require`
--

INSERT INTO `cp1_require` (`id`, `name`, `path`, `activated`, `priority`, `class_loader`) VALUES
(NULL, 'Chrome_Require_Loader_Filter', 'plugins/Require/filter.php', 1, 4, 1),
(NULL, 'Chrome_Require_Loader_Exception', 'plugins/Require/exception.php', 1, 4, 1),
(NULL, 'Chrome_Require_Loader_Validator', 'plugins/Require/validator.php', 1, 4, 1),
(NULL, 'Chrome_Require_Loader_Design', 'plugins/Require/design.php', 1, 4, 1),
(NULL, 'Chrome_Require_Loader_Form', 'plugins/Require/form.php', 1, 4, 1),
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
(NULL, 'Chrome_Controller_Module_Abstract', 'lib/core/controller/module.php', 1, 6, 0),
(NULL, 'Chrome_Response_Handler_HTTP', 'lib/core/response/response/http.php', 1, 6, 0),
(NULL, 'Chrome_Response_Handler_JSON', 'lib/core/response/response/json.php', 1, 6, 0),
(NULL, 'Chrome_Response_Handler_Console', 'lib/core/response/response/console.php', 1, 6, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cp1_route_administration`
--

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

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cp1_route_dynamic`
--

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

--
-- Daten für Tabelle `cp1_route_dynamic`
--

INSERT INTO `cp1_route_dynamic` (`id`, `name`, `class`, `file`, `GET`, `POST`) VALUES
(1, 'news_show', 'Chrome_Controller_News', 'modules/content/news/controller.php', 'action=show', '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cp1_route_static`
--

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Daten für Tabelle `cp1_route_static`
--

INSERT INTO `cp1_route_static` (`id`, `name`, `search`, `class`, `file`, `POST`, `GET`) VALUES
(1, 'index', '', 'Chrome_Controller_Index', 'modules/content/index/controller.php', '', ''),
(2, 'index', 'index', 'Chrome_Controller_Index', 'modules/content/index/controller.php', '', ''),
(3, 'login', 'login', 'Chrome_Controller_Content_Login', 'modules/content/user/login/controller.php', '', ''),
(4, 'site_not_found', '404', 'Chrome_Controller_SiteNotFound', 'modules/content/SiteNotFound/controller.php', '', ''),
(5, 'register', 'registrieren', 'Chrome_Controller_Register', 'modules/content/register/controller.php', '', 'action=register'),
(6, 'news', 'news', 'Chrome_Controller_News', 'modules/content/news/controller.php', '', 'action=show'),
(7, 'logout', 'logout', 'Chrome_Controller_Content_Logout', 'modules/content/user/logout/controller.php', '', ''),
(8, 'register_confirm', 'registrierung_bestaetigen', 'Chrome_Controller_Register', 'modules/content/register/controller.php', '', 'action=confirm_registration'),
(9, 'captcha', 'captcha', 'Chrome_Controller_Captcha', 'modules/content/captcha/controller.php', '', '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cp1_user`
--

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

--
-- Daten für Tabelle `cp1_user`
--

INSERT INTO `cp1_user` (`id`, `name`, `email`, `group`, `time`, `avatar`, `address`, `design`) VALUES
(3, 'Alex', 'redchrome@gmx.de', 0, 1349179579, '', '', 'default');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cp1_user_regist`
--

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `cp1_authorisation_user_default`
--
ALTER TABLE `cp1_authorisation_user_default`
  ADD CONSTRAINT `authIdUserDefault` FOREIGN KEY (`user_id`) REFERENCES `cp1_authenticate` (`id`) ON UPDATE CASCADE;

--
-- Constraints der Tabelle `cp1_user`
--
ALTER TABLE `cp1_user`
  ADD CONSTRAINT `authId` FOREIGN KEY (`id`) REFERENCES `cp1_authenticate` (`id`) ON UPDATE CASCADE;
SET FOREIGN_KEY_CHECKS=1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
