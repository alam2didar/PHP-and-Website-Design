<?php
require('page.inc.php');
require('user.inc.php');
require('myConnectDB.inc.php');
require('message.inc.php');

session_start();

if(!isset($_SESSION['name'])){
$page = new Page("UnAuthorized Access");

$page->content = '<h1 style="color:red" >You do not have access to this page please login or try again!</h1>';
$page -> content .= '<a href= "loginPage.php">Login</a>';
$page->display();
exit;
}

$page = new Page("Message");
@$db = new myConnectDB();

///check for errors
$errno = mysqli_connect_errno();
if ($errno){
	$page->content = "<p>Error $errno while connecting to the database.". 
		'Try back later or contact your system admin. The error message is: '.mysqli_connect_error(). '</p>';
	$page->display();
	return;
}

if (isset($_POST['send']))	{
    $message = new Message($_POST['type'], $_POST['message'], $_SESSION['name'], $_POST['messageReceiver']);
	$response = $message->addMessage($db);
	if ($response){
		$page->content .= '<p>Your message has been sent.</p>';
	} 
	else {
		$page->content .=  "\n" . 'Your message was not sent, try again.';
	}
	unset($_POST['send']);
}

$page->content .= '<h3>Send a message:</h3> <form action="messagePage.php" method="post">
			Message: <br><textarea onkeyup="textCounter(this,\'counter\',150);" id="message" name="message" row="5" ></textarea>
			<br> Characters left: <input disabled  maxlength="3" size="3" value="150" id="counter"><br><br>'.
		'<script>
			function textCounter(field,field2,maxlimit)
			{
			 var countfield = document.getElementById(field2);
			 if ( field.value.length > maxlimit ) {
				field.value = field.value.substring( 0, maxlimit );
				return false;
			   } else {
				  countfield.value = maxlimit - field.value.length;
			   }
			}
		</script>';

$page->content .= Message::members($db);

$page->content .= 'Type: <label><input name="type" type="radio" value="nice">Nice</label>
		<label><input name="type" type="radio" value="mean">Mean</label><br/>
		<input type = "submit" value = "Send message" name = "send" /><br><br>
		<input type = "submit" value = "View Sent Messages" name = "sent" />
		<input type = "submit" value = "View Received Messages" name = "received" />
		</form><br/>';

if (isset($_POST['sent']))	{
	$table = Message::printMessagesSender($db);
	$page->content .= $table;
	unset($_POST['sent']);
}

if (isset($_POST['received']))	{
	$table = Message::printMessagesReceiver($db);
	$page->content .= $table;
	unset($_POST['received']);
}
 
//display page
$page->display();

$db->close();
?>