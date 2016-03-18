SET FOREIGN_KEY_CHECKS=0;
TRUNCATE `cp1_authorisation_resource_default`;
TRUNCATE `cp1_authorisation_user_default`;
TRUNCATE `cp1_route_administration`;
TRUNCATE `cp1_route_dynamic`;
TRUNCATE `cp1_route_fixed`;
TRUNCATE `cp1_user`;
TRUNCATE `cp1_user_regist`;

INSERT INTO `cp1_authorisation_user_default` (`authentication_id`, `group_id`) VALUES
(1, 1),
(2, 123456),
(3, 89123),
(4, 8388607),
(5, 168804);

INSERT INTO `cp1_config` (`name`, `subclass`, `value`, `type`, `modul`, `hidden`) VALUES
('testValueString', 'testSubclass', 'testValue', 'string', 'anyModule', '0'),
('testValueInt', 'testSubclass', '42', 'integer', 'anyModule', '0'),
('testValueBool', 'testSubclass', '1', 'boolean', 'anyModule', '0'),
('testValueUnknown', 'testSubclass', 'any value, 1 1 ', 'unknown', 'anyModule', '0'),
('testValueDouble', 'testSubclass', '2.7182818', 'double', 'anyModule', '0');

DELETE FROM `cp1_authenticate` WHERE `id` > 0;
INSERT INTO `cp1_authenticate` (`id`,`password`, `password_salt`, `cookie_token`, `time`) VALUES
('1', 'eec1d7d507bf854c586a64f7a0db6e8a8db088eae96ccbb6', 'ahFB319VKaD', NULL, 12345678),
('2', 'testAuthenticate2', 'testAuthenticateSalt', NULL, 12345678),
('3', 'testAuthenticate3', 'testAuthenticateSalt', NULL, 12345678),
('4', 'testAuthenticate4', 'testAuthenticateSalt', NULL, 12345678);
-- password is test

INSERT INTO `cp1_user` (`email`, `authentication_id`) VALUES 
('LoginTest_EmailResolver', 1),
('RegistrationTest_testEmailFromUser', 2);

INSERT INTO `cpp_user_regist` (`email`, `key`) VALUES 
('RegistrationTest_testEmail1', 'activationKey00001'), 
('RegistrationTest_testEmail2', 'activationKey2'), 
('RegistrationTest_testEmail3', 'activationKey3'), 
('RegistrationTest_testEmail4', 'activationKey4');

INSERT INTO `cp1_user_regist` (`id`, `name`, `pass`, `pw_salt`, `email`, `time`, `key`) VALUES
(NULL, 'myName', 'examplePW', 'examplePWSalt', 'RegistrationTest_testEMAIL', '123', 'activationKey5');

INSERT INTO `cp1_resource` (`id`, `name`) VALUES
(1000, 'res:test|'),
(1001, 'res:test2|'),
(1002, 'res:testIsAllowed|');

INSERT INTO `cp1_authorisation_resource_default` (`resource_id`, `transformation`, `resource_group`) VALUES
(1000, 'read', 1234666),
(1000, 'write', 913785),
(1001, 'anyTrafo', 18462),
(1002, 'guestAllowed', 1),
(1002, 'guestNotAllowed', 123456);

DROP TABLE IF EXISTS `testing`;
CREATE TABLE IF NOT EXISTS `testing` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `var1` varchar(50) NULL,
  `var2` varchar(50) NULL,
  `var3` varchar(100) NULL,
  `var4` varchar(100) NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
SET FOREIGN_KEY_CHECKS=1;
