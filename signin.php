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
	$UI->executeHeaderTemplate('header_v2')->show("header");
?>
	<div class="clearfix col-md-3">
		<label for="userName">User Name:</label>
		<br/>
		<input type="text" id="userName" class="form-control" autofocus>
		<br/>
		<label for="password">Password:</label>
		<br/>
		<input type="password" id="password" class="form-control">
		<br/>
		<button class="btn btn-default" id="loginButton">Log In <span class="glyphicon glyphicon-log-in"></span></button>
		<br/>
		<br/>
		<a href="signup.php">Not registered? Sign up</a>
		<br/>
		<a href="password_reset.php">Forgot your password?</a>
	</div>

	<p id="message"></p>
<?
	$UI->show("footer");
	exit();
?>