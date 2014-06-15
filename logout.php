<?
	/**
	 * This page kills a user session
	 */
	session_start();
	session_destroy();
	header('Location: signin.php');