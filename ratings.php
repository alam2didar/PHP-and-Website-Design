<?php
//Alam
//150084
require('page.inc.php');
require('rating.inc.php');
require('myConnectDB.inc.php');


session_start();

if(!isset($_SESSION['name'])){
	$page = new Page("UnAuthorized Access");

	$page->content = '<h1 style="color:red" >You do not have access to this page please login or try again!</h1>';
	$page -> content .= '<a href= "loginPage.php">Login</a>';
	$page->display();
	exit;
}

//create a page object
$page = new Page("Ratings");
$page->content = '<h1>See Your Ratings</h1>';
// <p> The ratings for you so far for the following exchanges: </p> ';

//connect to the database
@$db = new myConnectDB();

///check for errors
$errno = mysqli_connect_errno();
if ($errno){
	$page->content = "<p>Error $errno while connecting to the database.". 
		'Try back later or contact your system admin. The error message is: '.mysqli_connect_error(). '</p>';
	$page->display();
	return;
}

if(isset($_POST['rate'])){
	$rate = new Rating($_POST['id'], $_POST['BeingRated'],$_POST['score'],$_POST['comments']);
	$result = $rate->rate($db);
	
	//$result = Rate::rate($db, $_POST['id'], $_POST['BeingRated'],$_POST['score'],$_POST['comments']);
	if(!$result){
		$page->content .= "<p style=\"color:red\" ><b>Error: contact admin</b></p>";
		$page->display();
		exit;
	}

	$page->content .= "<span style=\"color:lime\">You have succesfully rated!!</span> <br> <br>";
	unset($_POST['rate']);
}

if(isset($_POST['update'])){
	$rate = new Rating($_POST['id'], $_POST['BeingRated'],$_POST['score'],$_POST['comments']);
	$result = $rate->updateComment($db);

	if(!$result){
		$page->content .= "<p style=\"color:red\" ><b>Error: contact admin</b></p>";
		$page->display();
		exit;
	}

	$page->content .= "<span style=\"color:lime\">You have succesfully rated!!</span> <br> <br>";
	unset($_POST['update']);
}

	$table = Rating::receivedRatings($db);

	if(!$table){
		$page->content .=  "<p style=\"color:red\">There is an error or You do not have a rating!! So start lending :)</p>";
	}
	 //add the list of current ratings
	$page->content .= $table;

	//update a comment in the ratings table
	$page->content .= '<p><br/><form action="ratings.php" method="post">';
	$table = Rating::sentRatings($db);
	$page->content .= $table;
	$page->content .= 'ExchangeID: <input type="text" name="id"><br/>';
	$page->content .= 'Score: <input type="text" name="score"><br/>';
	$page->content .= 'Comments:<br> <textarea name="comments" row="2"></textarea><br/>';
	$page->content .= '<input type="submit" name = "update" value = "Update Comment"/></form></p>';
			
	//display all exchanges the user has taken part in
	
	
	//Enter an exchange
	$page->content .= '<p><b>Submit a Rating</b><br/><form action="ratings.php" method="post">';
	$page->content .= Rating::viewExchanges($db);
	$page->content .= 'ExchangeID: <input type="text" name="id"><br/>';
	$page->content .= 'Stars: <label><input name="score" type="radio" value="1">1</label>
							<label><input name="score" type="radio" value="2">2</label>
							<label><input name="score" type="radio" value="3">3</label>
							<label><input name="score" type="radio" value="4">4</label>
							<label><input name="score" type="radio" value="5">5</label><br/>
							Comments:</br><textarea name="comments" row="3" ></textarea><br/>';
	$page->content .= '<input type="submit" name = "rate" value = "Insert Rating"/></form></p>';

//display the page to the browser
$page->display();

//close db connection
$db->close();


?>
