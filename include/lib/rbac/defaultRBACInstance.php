<?php

$rbac = new Chrome_RBAC();

$guest = new Chrome_RBAC_Group('guest');
$user  = new Chrome_RBAC_Group('user');
$superUser = new Chrome_RBAC_Group('superuser');
$siteAdmin = new Chrome_RBAC_Group('siteadmin');
$admin = new Chrome_RBAC_Group('admin');

$superUser->addGroup($user);
$siteAdmin->addGroup($superUser);
$admin->addGroup($siteAdmin);

/*
 * Registration
 */
// allow guests to register
$register = new Chrome_RBAC_Role('register');
$registerTransaction = new Chrome_RBAC_Transaction();
$registerTransaction->addTransformation('register', 1);
$register->addTransaction($registerTransaction);
$guest->addRole($register);
unset($register);

/*
 * Login
 */
// allow guests to login
$login = new Chrome_RBAC_Role('login');
$loginTransaction = new Chrome_RBAC_Transaction();
$loginTransaction->addTransformation('login', 1);
$login->addTransaction($loginTransaction);
$guest->addRole($login);

// deny all other groups to login
$login = new Chrome_RBAC_Role('login');
$loginTransaction = new Chrome_RBAC_Transaction();
$loginTransaction->addTransformation('logout', 1);
$login->addTransaction($loginTransaction);
$guest->addRole($login);

/*

SQL Query

SELECT * FROM
(`cp1_rbac_transaction` AS t
RIGHT JOIN cp1_rbac_role_transaction As rt ON rt.transaction_id = t.id )
RIGHT JOIN cp1_rbac_role AS r ON rt.role_id = r.id
RIGHT JOIN cp1_rbac_user_role AS ur ON r.id = ur.role_id
LEFT JOIN cp1_rbac_transformation As tr ON tr.transaction_id = t.id
LEFT JOIN cp1_rbac_user_group AS ug ON ug.user_id = ur.user_id
INNER JOIN cp1_rbac_group As g ON g.id = ug.group_id
WHERE tr.transformation = "read" AND tr.right = 1



SELECT * FROM `test`
INNER JOIN cp1_rbac_transaction as t ON t.id = `resource_id`
INNER JOIN cp1_rbac_transformation AS tran ON tran.transaction_id = t.id
INNER JOIN cp1_rbac_role_transaction AS rt ON rt.transaction_id = t.id
INNER JOIN cp1_rbac_group_role AS gr ON gr.role_id = rt.role_id
INNER JOIN cp1_rbac_user_group AS ug ON gr.group_id = ug.group_id
INNER JOIN cp1_rbac_user_role AS ur ON rt.role_id = ur.role_id
WHERE tran.transformation = "read" AND tran.right = 1 AND ug.user_id = 1 AND ur.user_id = 1
GROUP BY test.id

SELECT * FROM `test`
INNER JOIN cp1_rbac_transaction as t ON t.id = `resource_id`
INNER JOIN cp1_rbac_transformation AS tran ON tran.transaction_id = t.id
INNER JOIN cp1_rbac_role_transaction AS rt ON rt.transaction_id = t.id
INNER JOIN cp1_rbac_group_role AS gr ON gr.role_id = rt.role_id
LEFT JOIN cp1_rbac_user_group AS ug ON gr.group_id = ug.group_id
LEFT JOIN cp1_rbac_user_role AS ur ON rt.role_id = ur.role_id

*/














$rbac->addGroup($guest);
$rbac->addGroup($user);
$rbac->addGroup($superUser);
$rbac->addGroup($siteAdmin);
$rbac->addGroup($admin);
unset($guest, $user, $superUser, $siteAdmin, $admin);