<?
	require_once("Tests.php");
	require_once("Debug_Functions.php");
	$Tests = new Tests();
	$listOfTests = $Tests->listAll();
	prettyPrint($listOfTests);