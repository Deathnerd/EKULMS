<?php
	/**
	 * Created by PhpStorm.
	 * User: Deathnerd
	 * Date: 4/2/14
	 * Time: 7:11 PM
	 */
	$debug = true;
	if($debug){
		$jquery = "scripts/jquery-2.1.0.js";
	} else {
		$jquery = "scripts/jquery-2.1.0.min.js";
	}
?>
<!DOCTYPE html>
<html>
<head>
<!--	<title>--><?// echo __FILE__; ?><!--</title>-->
	<meta name="description" content="Quiz Creation ">
	<meta name="author" content="Wes Gilleland">
	<meta name="published" content="TODO">
	<script type="text/javascript" src="<? echo $jquery; ?>"></script>
	<script type="text/javascript" src="scripts/create.js"></script>
	<script type="text/javascript" src="scripts/main.js"></script>
	<link type="text/css" rel="stylesheet" href="styles/reset.css">
	<link type="text/css" rel="stylesheet" href="styles/main.css">
</head>
<body>
<header id="topNav">
	<div id="logo">
		LOGO HERE
	</div>
	<div id="dropdown">
		<p>DROPDOWN HERE</p>
	</div>
	<p id="pageTitle">Quiz Creation</p>
</header>