SET FOREIGN_KEY_CHECKS=0;
TRUNCATE `cp1_admin_navi`;
TRUNCATE `cp1_authenticate`;
TRUNCATE `cp1_authorisation_rbac`;
TRUNCATE `cp1_authorisation_resource_default`;
TRUNCATE `cp1_authorisation_user_default`;
TRUNCATE `cp1_design_controller`;
TRUNCATE `cp1_design_layout`;
TRUNCATE `cp1_news`;
TRUNCATE `cp1_news_comments`;
TRUNCATE `cp1_rbac_group`;
TRUNCATE `cp1_rbac_group_role`;
TRUNCATE `cp1_rbac_role`;
TRUNCATE `cp1_rbac_role_transaction`;
TRUNCATE `cp1_rbac_transaction`;
TRUNCATE `cp1_rbac_transformation`;
TRUNCATE `cp1_rbac_user_group`;
TRUNCATE `cp1_rbac_user_role`;
TRUNCATE `cp1_route_administration`;
TRUNCATE `cp1_route_dynamic`;
TRUNCATE `cp1_route_static`;
TRUNCATE `cp1_user`;
TRUNCATE `cp1_user_regist`;
TRUNCATE `testing`;

INSERT INTO `cp1_authorisation_user_default` (`user_id`, `group_id`) VALUES
(0, 1),
(1, 123456),
(2, 89123),
(3, 8388607),
(4, 168804);

INSERT INTO `cp1_authenticate` (`id`, `password`, `password_salt`, `cookie_token`, `time`) VALUES
(NULL, 'testAuthenticate', 'testAuthenticateSalt', NULL, 12345678),
(NULL, '4c85bf07d5d7c1ee8a6edba0f7646a58b6cb6ce9ea88b08d', 'ahFB319VKaD', NULL, 12345678);
-- password is test

INSERT INTO `cp1_authorisation_resource_default` (`_resource_id`, `_transformation`, `_access`) VALUES
('test', 'read', 1234666),
('test', 'write', 913785),
('test2', 'anyTrafo', 18462),
('testIsAllowed', 'guestAllowed', 1),
('testIsAllowed', 'guestNotAllowed', 123456);

DROP TABLE IF EXISTS `testing`;
CREATE TABLE IF NOT EXISTS `testing` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `var1` varchar(50) NOT NULL,
  `var2` varchar(50) NOT NULL,
  `var3` varchar(100) NOT NULL,
  `var4` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
SET FOREIGN_KEY_CHECKS=1;
