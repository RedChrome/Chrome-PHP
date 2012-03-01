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














$rbac->addGroup($guest);
$rbac->addGroup($user);
$rbac->addGroup($superUser);
$rbac->addGroup($siteAdmin);
$rbac->addGroup($admin);
unset($guest, $user, $superUser, $siteAdmin, $admin);