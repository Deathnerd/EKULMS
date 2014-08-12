<?
	/**
	 * This page allows the user to sign up. If they are already logged in, the user will be redirected to the index. If the username already exists, the user will be notified and not allowed to register with that name
	 */
	session_start();
	require_once('autoloader.php');
	$Utils = new Utilities();
	if ($Utils->checkIsSet(array($_SESSION['userName']), array(""))) { //if there's already a user logged in, redirect them to the index
		$Utils->redirectAndExit("index.php");
	}

	$UI = new UI();
	$UI->show("header");
?>
	<div id="bodyContainer">
		<p>User Name:</p>
		<input type="text" id="userName"><br/>

		<p>Password:</p>
		<input type="password"><br/>
		<p>Email:</p>
		<input type="email"><br/>
		<br>
		<input type="button" value="Sign up" id="signUpButton"><br/>

		<p id="message" style="display: none;">Default text</p>
	</div>
<?
	$UI->show("footer");
?>