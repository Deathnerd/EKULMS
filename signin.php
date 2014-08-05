<?
	/**
	 * This page will allow the user to sign in. If they are not already, the user will be redirected to the index
	 */
	session_start();
	require_once("autoloader.php");
	$Utilities = new Utilities();
	if($Utilities->checkIsSet(array($_SESSION['userName']), array(""))){
		$Utilities->redirectAndExit('index.php');
	}
	$UI = new UI();
	$UI->show("header");
?>
	<div id="bodyContainer">
		<p>User Name:</p>
		<input type="text" id="userName"><br/>

		<p>Password:</p>
		<input type="password"><br/>
		<input type="button" value="login" id="loginButton"><br/>
		<a href="signup.php">Not registered? Sign up</a>
		<a href="password_reset.php">Forgot your password?</a>

		<p id="message" style="display: none;">Stuff</p>
	</div>
<?
	$UI->show("footer");
?>