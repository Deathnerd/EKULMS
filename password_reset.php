<?php
	/**
	 * Created by PhpStorm.
	 * User: Deathnerd
	 * Date: 8/3/14
	 * Time: 4:45 PM
	 */

	require_once('autoloader.php');
	if ($Utils->checkIsSet(array($_REQUEST['reset_key']), array(""))) {
		$Utils->redirectTo("reset.php?key=" . $_REQUEST['reset_key']);
	}

	$UI = new  UI($_SERVER['PHP_SELF'], "Password Reset - EKULMS");
	$UI->executeHeaderTemplate('header_v2');
?>
	<div id="bodyContainer">
		<!--<p>User Name:</p>
		<input type="text" id="userName"><br/>

		<p>Password:</p>
		<input type="password"><br/>-->
		<p>Email: </p>
		<label for="email"></label><input id="email" type="text"/><br/>
		<input type="button" value="Reset my password" id="resetButton"><br/>

		<p id="message" class="hide">Stuff</p>
	</div>
<?
	$UI->show("footer");
	exit();