<?php
/**
 * Created by PhpStorm.
 * User: Deathnerd
 * Date: 7/24/14
 * Time: 6:13 PM
 */
	require_once('requires/Globals.php');
	$userName = 'admin';
	$password = 'root';
	$Utilities->printPre("UserName: $userName", "Password: $password");
	$hashAndSalt = password_hash($password, PASSWORD_BCRYPT);
	$Utilities->printPre("Hash and Salt: $hashAndSalt");
	$password = password_verify($password, $hashAndSalt);
	$Utilities->printPre("Password verified: $password");