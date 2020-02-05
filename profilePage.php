<?php
require('page.inc.php');
require('item.inc.php');
require('user.inc.php');
require('profile.inc.php');
require('exchange.inc.php');
require('notification.inc.php');
require('myConnectDB.inc.php');

session_start(); //start or continue a session
//create a page object
$page = new Page("Home");

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

	$info = Profile::userInfo($db);
	$page->content .= '<h2 >Hello, '. $info[0].'!</h2>';
	$page->content .= '<h2>Your Info</h2>';
	
	$page->content .= $info[1];

	if (isset($_POST['addItem'])) {
		$item = new Item($_POST['title'], $_POST['type'], $_POST['description']);
		$response = $item->addItem($db);
		if ($response){
			$page->content .= '<p>Your item has been added.</p>';
		} 
		else {
			$page->content .=  "\n" . 'Item was not added, try again.';
		}
		unset($_POST['addItem']);
	}
	
	//form to add an item
	$page->content .= '<p><b>Enter a new item</b><br/><form action="profilePage.php" method="post">
		<label>Enter item name, item type: <input type="text" name="title" /></label>
		<label><input type="text" name="type" placeholder="book, dvd, etc"/></label><br/>
		<label>Describe your item: <br/><textarea name="description"
			placeholder="Example: movie duration or book length" rows="2" cols="30"/></textarea></label><br/>
		<input type="submit" name = "addItem" value = "Add Item"/>
		</form></p>';
	
	$info = Notification::listNotifications($db);
	$info .= Notification::listExchangeNotifications($db);
	$page->content .= $info;
	
//display the page to the browser
 }
 else {
	$page = new Page("UnAuthorized Access");
	$page->content = '<h1 style="color:red" >You do not have access to this page please login or try again!</h1>';
	$page->content .= '<a href= "loginPage.php">Login</a>';
	$page->display();
	exit;
}
$page->display();

//close db connection
$db->close();

?>