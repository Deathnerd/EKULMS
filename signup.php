<?
	/**
	 * This page allows the user to sign up. If they are already logged in, the user will be redirected to the index. If the username already exists, the user will be notified and not allowed to register with that name
	 */
	session_start();
	require_once('autoloader.php');
	if ($Utils->checkIsSet(array($_SESSION['userName']), array(""))) { //if there's already a user logged in, redirect them to the index
		$Utils->redirectTo("index.php");
	}

	$UI = new UI($_SERVER['PHP_SELF'], "Sign-up - EKULMS");
	$UI->executeHeaderTemplate('header_v2')->show("header");
?>
	<div id="bodyContainer" class="col-lg-3">
		<label for="userName">User Name:</label>
		<input type="text" id="userName" class="form-control" autofocus>
		<br/>

		<label for="password">Password:</label>
		<input type="password" id="password" class="form-control">
		<br/>

		<label for="email">Email:</label>
		<input type="text" id="email" class="form-control">
		<br/>

		<button id="signUpButton" class="btn btn-default">Sign up</button>
		<br/>

		<p id="message" class="hide"></p>
	</div>
<?
	$UI->show("footer");
	exit();
?>