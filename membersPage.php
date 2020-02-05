<?php
require('page.inc.php');
require('user.inc.php');
require('profile.inc.php');
require('exchange.inc.php');
require('member.inc.php');
require('myConnectDB.inc.php');

session_start(); //start or continue a session
//create a page object
$page = new Page("Members");

//connect to the database
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

	$page->content .= '<h1>DormLife Members</h1>';
	$info = Member::listMembers($db);
	$page->content .= $info;
	$friends = Member::listFriends($db);
	$page->content .= $friends;
	
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