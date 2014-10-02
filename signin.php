<?
	/**
	 * This page will allow the user to sign in. If they are not already, the user will be redirected to the index
	 */
	session_start();
	require_once("autoloader.php");
	if ($Utils->checkIsSet(array($_SESSION['userName']), array(""))) {
		$Utils->redirectTo('index.php');
	}
	$UI = new UI($_SERVER['PHP_SELF'], "Sign-in - EKULMS");
	$UI->show("header");
?>
	<div id="bodyContainer">
		<p>User Name:</p>
		<label for="userName"></label><input type="text" id="userName"><br/>

		<p>Password:</p>
		<label>
			<input type="password">
		</label><br/>
		<input type="button" value="login" id="loginButton"><br/>
		<a href="signup.php">Not registered? Sign up</a>
		<br/>
		<br/>
		<a href="password_reset.php">Forgot your password?</a>

		<p id="message" class="hide">Stuff</p>
	</div>
<?
	$UI->show("footer");
	exit();
?>