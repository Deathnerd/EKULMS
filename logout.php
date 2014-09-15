<?
	/**
	 * This page kills a user session
	 */

	require_once('autoloader.php');
	session_start();
	$DB = new Db;
	$Users = new Users($DB);
	$Utils = new Utilities($DB);

	if ($Users->logout($_SESSION['userName'])) {
		header('Location: signin.php');
	}
	$Utils->closeAndExit("Ruh-roh, Raggy!");