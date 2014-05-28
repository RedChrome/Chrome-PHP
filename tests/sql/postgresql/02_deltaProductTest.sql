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


DELETE FROM "chrome"."cp1_authenticate" WHERE "id" > 0;
ALTER SEQUENCE "chrome"."cp1_authenticate_id_seq" RESTART WITH 1;
INSERT INTO "chrome"."cp1_authenticate" ("password", "password_salt", "cookie_token", "time") VALUES
('eec1d7d507bf854c586a64f7a0db6e8a8db088eae96ccbb6', 'ahFB319VKaD', NULL, 12345678), -- password is test
('testAuthenticate2', 'testAuthenticateSalt', NULL, 12345678),
('testAuthenticate3', 'testAuthenticateSalt', NULL, 12345678),
('testAuthenticate4', 'testAuthenticateSalt', NULL, 12345678),
('testAuthenticate5', 'testAuthenticateSalt', NULL, 12345678);

INSERT INTO "chrome"."cp1_authorisation_user_default" ("authentication_id", "group_id") VALUES
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

INSERT INTO "chrome"."cp1_user" ("email", "authentication_id") VALUES 
('LoginTest_EmailResolver', 1),
('RegistrationTest_testEmailFromUser', 2);

INSERT INTO "chrome"."cp1_resource" ("id", "name") VALUES
(1000, 'test'),
(1001, 'test2'),
(1002, 'testIsAllowed');

INSERT INTO "chrome"."cp1_authorisation_resource_default" ("resource_id", "transformation", "resource_group") VALUES
(1000, 'read', 1234666),
(1000, 'write', 913785),
(1001, 'anyTrafo', 18462),
(1002, 'guestAllowed', 1),
(1002, 'guestNotAllowed', 123456);

DROP TABLE IF EXISTS "chrome"."testing";
CREATE TABLE IF NOT EXISTS "chrome"."testing" (
  "id" SERIAL NOT NULL,
  "var1" varchar(50) NULL,
  "var2" varchar(50) NULL,
  "var3" varchar(100) NULL,
  "var4" varchar(100) NULL,
  PRIMARY KEY ("id")
);
