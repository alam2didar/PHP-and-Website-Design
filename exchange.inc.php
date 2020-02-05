<?php
/* 
exchange class
*/
class Exchange {

	public $title;
	public $type;
	public $mark;
	public $borrower;
	public $lender;
	
	//This is the constructor 
	public function __construct($title, $type, $mark, $borrower, $lender) {
		$this->__set('title', $title);
		$this->__set('type', $type);
		$this->__set('mark', $mark);
		$this->__set('borrower', $borrower);
		$this->__set('lender', $lender);
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
	//get function
	public function __get($varName){
		return $this->$varName;
	}
	
	//log an exchange and add it to the database
	public function addExchange($db){
		$title = $this->title;
		$type = $this->type;
		$mark = $this->mark;
		$lender = $this->lender;
		$borrower = $this->borrower;
		$date = date("Y-m-d");
		
		$query = "insert into Exchanges (Title, Type, mark, Lender, Borrower, ExchangeDate)
				values('$title', '$type', '$mark', '$lender','$borrower', '$date');";
        $result = $db->query($query);
		if (!$result || $db->affected_rows == 0){
			return false;
		}
		else{
			return true;
		}
			
    }
	//View previous exchanges
	public static function viewExchanges($db){
		$user = $_SESSION['name'];
		//query for Exchanges
		$query = "SELECT * FROM Exchanges WHERE Lender = '$user'";
				$result = $db->query($query);
		//check if ok
		if (!$result){
			return false;
		}
		//get number of rows returned
	    $num_results = $result->num_rows;
	    $info = '<p><b>Your Exchanges</b><br/><table border="1">
		<tr><th>Title</th><th>Type</th><th>Owner</th><th>Lent To</th><th>Date</th></tr>';
		if ($num_results > 0){
			for ($i=0; $i<$num_results; $i++){
				//read back one row at a time
				$row = $result->fetch_assoc(); //retrieves a row from the result
				$info .= 
				'<tr>'
				. '<td>'. $row['Title'] . '</td>'
				. '<td>'. $row['Type'] . '</td>'
				. '<td>'. $row['mark'] . '</td>'
				. '<td>'. $row['Borrower'] . '</td>'
				. '<td>'. $row['ExchangeDate'] . '</td>'
				.'</tr>';
			}
		}
		$info .= '</table><br/>';
		$result->free(); //free memory
		
		$query = "SELECT * FROM Exchanges WHERE Borrower = '$user'";
				$result = $db->query($query);
		//check if ok
		if (!$result){
			return false;
		}
		//get number of rows returned
	    $num_results = $result->num_rows;
	    $info .= '<table border="1">
		<tr><th>Title</th><th>Type</th><th>Owner</th><th>Borrowed From</th><th>Date</th></tr>';
		if ($num_results > 0){
			for ($i=0; $i<$num_results; $i++){
				//read back one row at a time
				$row = $result->fetch_assoc(); //retrieves a row from the result
				$info .= 
				'<tr>'
				. '<td>'. $row['Title'] . '</td>'
				. '<td>'. $row['Type'] . '</td>'
				. '<td>'. $row['mark'] . '</td>'
				. '<td>'. $row['Lender'] . '</td>'
				. '<td>'. $row['ExchangeDate'] . '</td>'
				.'</tr>';
			}
		}
		$info .= '</table></p>';
		$result->free(); //free memory
		return $info;
	}
	
	
	public function items($db) {
		$email = $_SESSION['name'];
		$query = "SELECT Title, Type, Description, mark FROM Items Where possession = '$email'";
		$result = $db->query($query);
		//check if  ok
		if (!$result){
			$info = '<p>You do not own any items</p>';
			return $info;
		}
		$num_results = $result->num_rows;
		$info="";
		if ($num_results > 0){
			$info .= 'Lend:<br/><table border="1">
			<tr><th>Title</th><th>Type</th><th>Description</th><th>Select</th></tr>';
			for ($i=0; $i<$num_results; $i++){
				//read back one row at a time
				$row = $result->fetch_assoc(); //retrieves a row from the result
				$info .= 
				'<tr>'
				. '<td>'. $row['Title'] . '</td>'
				. '<td>'. $row['Type'] . '</td>'
				. '<td>'. $row['Description'] . '</td>'
				. '<td><input name="mark" type="radio" value="' . $row['mark'] .'"></td>'
				//. '<input type="hidden" name="title" value="' . $row['Title'] .'">'
				//. '<input type="hidden" name="type" value="' . $row['Type'] .'">'
				//. '<input type="hidden" name="description" value="' . $row['Description'] .'">'
				.'</tr>';
			}
			$info .= '</table></p>';
		}
		else{
			$info .= '<p>You have no items lend.</p>';
		}
		$result->free(); //free memory
        return $info;
	}
	
	public static function friends($db){
		$email = $_SESSION['name'];
		//query for items
		$query = "SELECT FirstName, LastName, Email FROM Member";
		/*$query = "SELECT distinct Email, FirstName, LastName
				FROM 
					member,
					(Select distinct ReceiverEmail as re, count(messageID) as Rcount 
						from messages 
						where SenderEmail='$email' AND DATEDIFF(NOW(),dateReceived) <= 30
						Group by ReceiverEmail 
						having Rcount >= 3) as recv,

					(Select distinct SenderEmail as se, count(messageID) as Scount 
						from messages 
						where ReceiverEmail='$email' AND DATEDIFF(NOW(),dateReceived) <= 30 
						Group by SenderEmail 
						having Scount >= 1) as sndr,

					(Select distinct ReceiverEmail as nre, count(messageID) as nRcount 
						from messages 
						where SenderEmail='$email' AND DATEDIFF(NOW(),dateReceived) <= 30 and msgType='nice' 
						Group by ReceiverEmail) as nrecv,

					(Select distinct SenderEmail as nre, count(messageID) as nScount 
						from messages 
						where ReceiverEmail='$email' AND DATEDIFF(NOW(),dateReceived) <= 30 and msgType='nice' 
						Group by SenderEmail) as nsndr

				WHERE re=se AND Email=re AND Email=se AND nRcount/Rcount > 0.9 AND nScount/Scount > 0.9;";*/
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
				. '<td><input name="borrower" type="radio" value="' . $row['Email'] .'"></td>'
				.'</tr>';
			}
			$info .= '</table></p>';
		}
		$result->free(); //free memory
        return $info;
	}
}
?>