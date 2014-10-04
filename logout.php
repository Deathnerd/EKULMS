<?
	/**
	 * This page kills a user session
	 */

	require_once('autoloader.php');
	session_start();
	$Users = new Users($DB);

	if (!$Utils->checkIsSet(array($_SESSION['userName']), array()) || $Users->logout($_SESSION['userName'])) {
		header('Location: signin.php');
	}
	$Utils->exitWithMessage("Ruh-roh, Raggy!");