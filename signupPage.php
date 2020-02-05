<?php
//Alam
//150084
require('page.inc.php');
require('user.inc.php');
require('myConnectDB.inc.php');

session_start();

//create a page object
$page = new Page("Dorm Life login");
$page->content = '';

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

//process sign UP
if(isset($_POST['adduser'])){
 if(empty($_POST['user_ID']) || empty($_POST['password']) || empty($_POST['fname']) || empty($_POST['lname'])){
$page->content = "<h1> BAD INTENTION </h1> <p>Make sure all the fields are filled</p>";
$page->display();
exit;
}
 $user = new User($_POST['user_ID'], $_POST['password'], $_POST['fname'], $_POST['lname']);
 $good = $user->addUser($db);
 if ($good)	{
	$page->content .= "<p style=\"color:green\">Insert success!</p>";
}
 if(!$good){
 $page->content .=  "<p style=\"color:red\">This is not a valid combination, try again.</p>";
 }
 unset($_POST['adduser']);
}

//create a form to get user name and password
$page->content .= '<h2>Sign UP to Use Dorm Life</h2>';
$page->content .= '<form action="signupPage.php" method="post">';
$page->content .= '<label>Enter your Email: <input type="text" name="user_ID"/></label><br>
    <label>Enter your password: <input type="password" name="password"/></label><br>
    <label>First Name: <input type="text" name="fname"/></label><br>
    <label>Last Name: <input type="text" name="lname"/></label><br>
    <input type="submit" name = "adduser" value = "Sign Up"/><br>
    </form>';
	
$page -> content .= '<a href= "loginPage.php">Back to Login</a>';

//display the page to the browser
$page->display();
?>