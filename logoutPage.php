<?php
require('page.inc.php');
require('user.inc.php');
require('profile.inc.php');
require('exchange.inc.php');
require('myConnectDB.inc.php');

session_start(); //start or continue a session
//create a page object
$page = new Page("Logout");
//destroy the session variable
unset($_SESSION['name']);

//Destroy all session variables
$_SESSION = array();

//destroy the session
session_destroy();

header("Location: loginPage.php");

?>