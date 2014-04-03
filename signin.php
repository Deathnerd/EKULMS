<?
	/**
	 * This page will allow the user to sign in. If they are not already, the user will be redirected to the index
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
		<input type="button" value="login" id="loginButton"><br/>
		<a href="signup.php">Not registered? Sign up</a>

		<p id="message" style="display: none">Stuff</p>
	</div>
<?
	require('requires/footer.php');
?>