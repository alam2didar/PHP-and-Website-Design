<?php
require('page.inc.php');
require('user.inc.php');
require('profile.inc.php');
require('exchange.inc.php');
require('myConnectDB.inc.php');

session_start(); //start or continue a session
//create a page object
$page = new Page("Exchanges");

//connect to the database
//@ $db = new mysqli('hostname','username','password','database');
@ $db = new myConnectDB();

///check for errors
$errno = mysqli_connect_errno();
if ($errno){
	$page->content .= "<p>Error $errno while connecting to the database. 
	Try back later or contact your system admin. Error message: "
	. mysqli_connect_error() ."</p>";
	$page->display();
	return;
}

if (isset($_SESSION['name']) && !empty($_SESSION['name'])){

	if (isset($_POST['log'])) {
		$exchange = new Exchange($_POST['title'], $_POST['type'], $_POST['mark'], $_POST['borrower'], $_SESSION['name']);
		$response = $exchange->addExchange($db);
		if ($response){
			$page->content .= '<p>Your exchange has been logged.</p>';
		} 
		else {
			$page->content .=  "\n" . 'This is not a valid combination, try again.';
		}
		unset($_POST['log']);
	}

	//display all exchanges the user has taken part in
	$page->content .= Exchange::viewExchanges($db);
	
	//Enter an exchange
	$page->content .= '<p><b>Log an exchange</b><br/><form action="exchangePage.php" method="post">';
	$page->content .=Exchange::items($db);
	$page->content .= 'Title: <input type="text" name="title"><br/>';
	$page->content .= 'Type: <input type="text" name="type"><br/>';
	$page->content .= 'Description: <br/><textarea name="description" rows="2"></textarea><br/>';
	$page->content .=Exchange::friends($db);
	$page->content .= '<input type="submit" name = "log" value = "Log Exchange"/></form></p>';

//display the page to the browser
 }
 else {
	$page = new Page("UnAuthorized Access");
	$page->content = '<h1 style="color:red" >You do not have access to this page please login or try again!</h1>';
	$page -> content .= '<a href= "loginPage.php">Login</a>';
	$page->display();
	exit;
}
$page->display();

//close db connection
$db->close();

?>