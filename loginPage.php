<?php
require('page.inc.php');
require('user.inc.php');
require('myConnectDB.inc.php');

session_start();

//create a page object
$page = new Page("Dorm Life login");
$page->content = '';

$page->content .= '<img src ="banner_usna.png" alt =  "banner_usna" width="777" height="90"/> ';

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

//process the  login
if (isset($_POST['login'])) {
if(empty($_POST['user_ID']) || empty($_POST['password'])){
$page->content = "<h1> BAD INTENTION </h1>";
$page->display();
exit;
}
	$user = new User($_POST['user_ID'], $_POST['password']);
  $response = $user->validate($db);
  if ($response){
	//everything OK, set the session variable $_SESSION['name'] and go to songPage
     $_SESSION['name'] = $_POST['user_ID'];
     header("Location: profilePage.php");
  } 
  else {
	//user-password combination not valid - ask again
     $page->content .=  "\n" . 'This is not a valid combination, try again.';
  }
  unset($_POST['login']);
}


if(isset($_POST['logout'])){
$page->content .= '<h3 style="color:salmon"><em>Thanks for Visiting us!!</em></h3>';
 session_destroy();
 
 unset($_POST['logout']);
}

//create a form to get user name and password
$page->content .= '<div style="margin-left: 200px; margin-right: auto"><h2>Authentication</h2>';
$page->content .= '<form action="loginPage.php" method="post">';
$page->content .= '<label>Enter your user ID: <input type="text" name="user_ID"/></label><br>
    <label>Enter your password: <input type="password" name="password"/></label><br>
	<input type="submit" name = "login" value = "Login"/>
    </form>';

$page->content .= '<p>New Users: <a href = "signupPage.php">Sign UP here</a></p></div>';	
	
//display the page to the browser
$page->display();

//close db connection
$db->close();
?>


