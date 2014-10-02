<?php
	/**
	 * Created by PhpStorm.
	 * User: Deathnerd
	 * Date: 8/3/14
	 * Time: 4:45 PM
	 */

	require_once('autoloader.php');
	require_once('requires/phpmailer/PHPMailerAutoload.php');

	if (!$Utils->checkIsSet(array($_GET['action']),
		array("Action is not set"))
	) {
		exit();
	}

	$action = $_GET['action'];
	$key = $_GET['key'];

	$Users = new Users($DB);

	switch ($action) {
		case 'reset':
			if (!$Utils->checkIsSet(array($_GET['key'], $_GET['user_name'], $_GET['new_password']),
				array("Key is not set", "User name is not set", "A new password was not provided"))
			) {
				exit();
			}
			$user_name = $_GET['user_name'];
			$new_password = $_GET['new_password'];
			if ($Users->userExists($user_name)) {
				$user_info = $Users->fetchUser($user_name);
				$user_id = $user_info['id'];
			} else {
				$Utils->exitWithMessage("User does not exist");
			}

			if ($Users->resetPassword($new_password, $user_id, $key)) {
				$Utils->exitWithMessage("Success");
			} else {
				$Utils->exitWithMessage("Failed to create new password. Please inform the administrator");
			}

			break;
		case 'send_email':
			$Utils->checkIsSet(array($_GET['email'], $_GET['user_name']),
				array("Email not supplied", "User name not supplied"));
			if (!filter_var($_GET['email'], FILTER_VALIDATE_EMAIL)) {
				$Utils->exitWithMessage("The email provided is not a valid email. Please check your input and try again");
			}
			$user_name = $_GET['user_name'];
			$email = $_GET['email'];
			if (!$Users->checkEmail($user_name, $email)) {
				$Utils->exitWithMessage("Failed to verify email. Make sure it is correct and is the email you signed up with. If so, then contact your administrator");
			}
			$reset_key = "";
			if (($reset_key != $Users->generateResetKey($user_name))) {
				$Utils->exitWithMessage("Failed to generate a key. Please contact your administrator");
			}

			$to = array(array("address" => $email, "name" => ""));
			$from = array(array("address" => "wes.gilleland@gmail.com", "name" => "Wes Gilleland"));
			$subject = "Password Reset for EKULMS";
			$body = "<html>
						<body>
							<p>This email is to inform you that you have requested a password reset for EKULMS. Please visit http://ekulms.dev/reset.php?key=$reset_key&user_name=$user_name&action=reset
							to reset your password. If you believe you have recieved this email in error, please contact the administrator</p>
						</body>
					</html>";

			$config_vals = array("host"      => SMTP_SERVER,
			                     "port"      => SMTP_PORT,
			                     "user_name" => SMTP_USER,
			                     "password"  => SMTP_PASSWORD);

			$Utils->sendEmail($to, $from, $subject, $body, $config_vals);
			$Utils->exitWithMessage("Success");
	}

