<?
	/**
	 * This is where the user will take a quiz. The user is redirected to the signin page if they are not already signed in
	 */
	require_once('requires/Globals.php');
	session_start();
	$userName = $_SESSION['userName'];
	if (!isset($userName)) { //if not logged in already, go to login page
		header('Location: signin.php');
		exit();
	}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>CSC 185 Practice Exams</title>
		<meta name="description" content="Practice Exams for CSC 185">
		<meta name="author" content="Wes Gilleland">
		<meta name="published" content="TODO">
		<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
		<script type="text/javascript" src="scripts/main.js"></script>
		<link type="text/css" rel="stylesheet" href="styles/reset.css">
		<link type="text/css" rel="stylesheet" href="styles/main.css">
	</head>
<body>
<header id="topNav">
	<div id="logo">
	</div>
	<div id="dropdown">
	</div>
	<p id="pageTitle"></p>
	<select><?
			//populate the dropdown
			$listOfTests = $Tests->fetchAll();
			foreach ($listOfTests as $name){
				$testName = $name['testName'];
				echo "<option>$testName</option>";
			}
		?>
	</select>
	<button type="button" id="load">Load</button>
</header>
<p id="userGreeting">
	<? echo "Hello, $userName!"; ?>
</p>
<div id="holding_div"></div>
<?
	require('requires/footer.php');
	$DB->close();
?>