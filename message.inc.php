<?php

class Message {
	// This class is used to create and maintain authentication
	
	public $messageID;
	public $msgType;
	public $dateReceived;
	public $Message;
	public $SenderEmail;
	public $ReceiverEmail;
	
	//This is the constructor 
	public function __construct($msgType, $Message, $SenderEmail, $ReceiverEmail) {
		$this->__set('SenderEmail', $SenderEmail);
		$this->__set('Message', $Message);
		$this->__set('ReceiverEmail', $ReceiverEmail);
		$this->__set('msgType', $msgType);
	}
	
	//set function
	public function __set($varName, $value){
		//this is the default set function invoked when the private fields are set
		//this is a good place to do sanity/security checks
		$value = trim($value);
		$value = strip_tags($value);
		if (!get_magic_quotes_gpc()){
			$value = addslashes($value);
		}
		//finally set the value
		$this->$varName = $value;
	}

	public function __get($varName){
		return $this->$varName;
	}
	
	//add a new user to the database
	public function addMessage($db){	
		//This adds a user object to the AppUsers table
		$Message = $this->Message;
		$SenderEmail = $this->SenderEmail;
		$ReceiverEmail = $this->ReceiverEmail;
		$msgType = $this->msgType;
		$dateReceived = date("Y-m-d");
		
		$query = "INSERT INTO Messages(msgType, dateReceived, Message, SenderEmail, ReceiverEmail)
				VALUES ('$msgType', '$dateReceived','$Message','$SenderEmail', '$ReceiverEmail')";
        $result = $db->query($query);
		if (!$result || $db->affected_rows == 0){
			return false;
		}
		else{
			return true;
		}	
    }
	
	// gets data from database and puts it into a table
	public static function printMessagesReceiver($db)	{
		$userID = $_SESSION['name'];
		$query = "SELECT messageID, dateReceived, Message, SenderEmail from 
			messages where ReceiverEmail = '$userID' and DATEDIFF(NOW(),dateReceived) < 30 order by dateReceived ASC";
		$result = $db->query($query);
		if (!$result)	{
			return false;
		}
		
		$num_results = $result->num_rows;
		$table = '';
		if ($num_results > 0)	{
			$table = $table . '<table border = "2"><tr><th>Received From</th><th>Message</th><th>Date Received</th></tr>';
			
			for ($i = 0; $i < $num_results; $i++)	{
				$row = $result->fetch_assoc();
				$table = $table . '<tr>'
					. '<td>' . $row['SenderEmail'] . '</td>'
					. '<td>' . $row['Message'] . '</td>'
					. '<td>' . $row['dateReceived'] . '</td>'
					. '</tr>';
			}
			$table = $table . '</table><br/><br/>';
		}
		$result->free(); // free memory
		return $table;
	}
	
	public static function printMessagesSender($db)	{
		$userID = $_SESSION['name'];
		$query = "select messageID, dateReceived, Message, ReceiverEmail from messages where SenderEmail = '$userID' and DATEDIFF(NOW(),dateReceived) < 30 order by dateReceived ASC";
		$result = $db->query($query);
		if (!$result)	{
			return false;
		}
		
		$num_results = $result->num_rows;
		$table = '';
		if ($num_results > 0)	{
			$table = $table . '<table border = "2"><tr><th>Sent To</th><th>Message</th><th>Date Sent</th></tr>';
			
			for ($i = 0; $i < $num_results; $i++)	{
				$row = $result->fetch_assoc();
				$table = $table . '<tr>'
					. '<td>' . $row['ReceiverEmail'] . '</td>'
					. '<td>' . $row['Message'] . '</td>'
					. '<td>' . $row['dateReceived'] . '</td>'
					. '</tr>';
			}
			$table = $table . '</table><br/><br/>';
		}
		$result->free(); // free memory
		return $table;
	}
	
	public static function members($db){
		$email = $_SESSION['name'];
		//query for items
		$query = "SELECT FirstName, LastName, Email FROM Member";

		$result = $db->query($query);
		//check if  ok
		if (!$result){
			$info = '<p>You have no friends</p>';
			return $info;
		}
		$num_results = $result->num_rows;

		if ($num_results > 0){
			$info = 'To:<br/><table border="1">
				<tr><th>First Name</th><th>Last Name</th><th>Email</th><th>Select</th></tr>';
			for ($i=0; $i<$num_results; $i++){
				//read back one row at a time
				$row = $result->fetch_assoc(); //retrieves a row from the result
				$info .= 
				'<tr>'
				. '<td>'. $row['FirstName'] . '</td>'
				. '<td>'. $row['LastName'] . '</td>'
				. '<td>'. $row['Email'] . '</td>'
				. '<td><input name="messageReceiver" type="radio" value="' . $row['Email'] .'"></td>'
				.'</tr>';
			}
			$info .= '</table></p>';
		}
		$result->free(); //free memory
        return $info;
	}
}
?>