TRUNCATE "chrome"."cp1_authenticate" CASCADE;
TRUNCATE "chrome"."cp1_authorisation_rbac";
TRUNCATE "chrome"."cp1_authorisation_resource_default";
TRUNCATE "chrome"."cp1_authorisation_user_default" CASCADE;
TRUNCATE "chrome"."cp1_rbac_group";
TRUNCATE "chrome"."cp1_rbac_group_role";
TRUNCATE "chrome"."cp1_rbac_role";
TRUNCATE "chrome"."cp1_rbac_role_transaction";
TRUNCATE "chrome"."cp1_rbac_transaction";
TRUNCATE "chrome"."cp1_rbac_transformation";
TRUNCATE "chrome"."cp1_rbac_user_group";
TRUNCATE "chrome"."cp1_rbac_user_role";
TRUNCATE "chrome"."cp1_route_administration";
TRUNCATE "chrome"."cp1_route_dynamic";
TRUNCATE "chrome"."cp1_route_static";
TRUNCATE "chrome"."cp1_user";
TRUNCATE "chrome"."cp1_user_regist";


INSERT INTO "chrome"."cp1_authenticate" ("id", "password", "password_salt", "cookie_token", "time") VALUES
('1', 'testAuthenticate', 'testAuthenticateSalt', NULL, 12345678),
('2', 'eec1d7d507bf854c586a64f7a0db6e8a8db088eae96ccbb6', 'ahFB319VKaD', NULL, 12345678), -- password is test
('3', 'testAuthenticate2', 'testAuthenticateSalt', NULL, 12345678),
('4', 'testAuthenticate3', 'testAuthenticateSalt', NULL, 12345678),
('5', 'testAuthenticate4', 'testAuthenticateSalt', NULL, 12345678);


INSERT INTO "chrome"."cp1_authorisation_user_default" ("user_id", "group_id") VALUES
(1, 1),
(2, 123456),
(3, 89123),
(4, 8388607),
(5, 168804);

INSERT INTO "chrome"."cp1_config" ("name", "subclass", "value", "type", "modul", "hidden") VALUES
('testValueString', 'testSubclass', 'testValue', 'string', 'anyModule', '0'),
('testValueInt', 'testSubclass', '42', 'integer', 'anyModule', '0'),
('testValueBool', 'testSubclass', '1', 'boolean', 'anyModule', '0'),
('testValueUnknown', 'testSubclass', 'any value, 1 1 ', 'unknown', 'anyModule', '0'),
('testValueDouble', 'testSubclass', '2.7182818', 'double', 'anyModule', '0');


INSERT INTO "chrome"."cp1_authorisation_resource_default" ("_resource_id", "_transformation", "_access") VALUES
('test', 'read', 1234666),
('test', 'write', 913785),
('test2', 'anyTrafo', 18462),
('testIsAllowed', 'guestAllowed', 1),
('testIsAllowed', 'guestNotAllowed', 123456);

DROP TABLE IF EXISTS "chrome"."testing";
CREATE TABLE IF NOT EXISTS "chrome"."testing" (
  "id" SERIAL NOT NULL,
  "var1" varchar(50) NULL,
  "var2" varchar(50) NULL,
  "var3" varchar(100) NULL,
  "var4" varchar(100) NULL,
  PRIMARY KEY ("id")
);
