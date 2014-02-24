<?
	//logout.php
	//ends a user session
	session_start();
	session_destroy();
	header('Location: index.php');
?>