<?
	/**
	 * This page allows the user to sign up. If they are already logged in, the user will be redirected to the index. If the username already exists, the user will be notified and not allowed to register with that name
	 */
	session_start();
	if (isset($_SESSION['userName'])) { //if there's already a user logged in, redirect them to the index
		header('Location: index.php');
	}
	require('requires/header.php');
?>
	<div id="bodyContainer">
		<p>User Name:</p>
		<input type="text" id="userName"><br/>

		<p>Password:</p>
		<input type="password"><br/>
		<br>
		<input type="button" value="Sign up" id="signUpButton"><br/>

		<p id="message" style="display: none">Default text</p>
	</div>
<?
	require('requires/footer.php');
?>