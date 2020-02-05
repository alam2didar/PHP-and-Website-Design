<?php
/* 
rate class
*/
class Rating {

	public $id;
	public $BeingRated;
	public $score;
	public $comments;
	
	//This is the constructor 
	public function __construct($id, $BeingRated, $score, $comments) {
		$this->__set('id', $id);
		$this->__set('BeingRated', $BeingRated);
		$this->__set('score', $score);
		$this->__set('comments', $comments);
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
	
	//get ratings you received
	public static function receivedRatings($db){	
		$userID = $_SESSION['name'];
		$query = "select * from ratings where BeingRated = '$userID'";
        $result = $db->query($query);
		if (!$result || $db->affected_rows == 0){
			return false;
		}
		//process the results and construct the table
	    $num_results = $result->num_rows;
	    $table = '';
		if ($num_results > 0){
		
		   $table = $table . 
		   '<table border = "2"><caption>Received</caption>
  		   <tr><th width="150">Rater</th><th width="150">Score</th><th width="150">comments</th></tr>';
		
		   for ($i=0; $i<$num_results; $i++)	//loop through all songs
		   {
              $row = $result->fetch_assoc(); //retrieves a row from the result
              $table = $table 
				.'<tr>'
				. '<td>'. $row['Rater'] . '</td>'
				. '<td>'. $row['score'] . '</td>'
				. '<td>'. $row['comments'] . '</td>'
				.'</tr>';
		   }
			$table = $table . '</table><br/><br/>';
		}	
		$result->free(); //de-allocate the memory for the results
		
        return $table;
			
    }
	//get ratings you sent
	public static function sentRatings($db){	
		$userID = $_SESSION['name'];
		$query = "select * from ratings where Rater = '$userID'";
        $result = $db->query($query);
		if (!$result || $db->affected_rows == 0){
			return false;
		}
		//process the results and construct the table
	    $num_results = $result->num_rows;
	    $table = '';
		if ($num_results > 0){
		
		   $table = $table . 
		   '<table border = "2"><caption>Sent</caption>
  		   <tr><th>Exchange ID</th><th>Being Rated</th><th>Score</th><th>Comments</th><th>Select</th></tr>';
		
		   for ($i=0; $i<$num_results; $i++)	//loop through all songs
		   {
              $row = $result->fetch_assoc(); //retrieves a row from the result
              $table = $table 
				.'<tr>'
				. '<td>'. $row['exchangeID'] . '</td>'
				. '<td>'. $row['BeingRated'] . '</td>'
				. '<td>'. $row['score'] . '</td>'
				. '<td>'. $row['comments'] . '</td>'
				
				. '<td><input name="BeingRated" type="radio" value="' . $row['BeingRated'] .'"></td>'
				
				.'</tr>';
		   }
			$table = $table . '</table><br/><br/>';
		}	
		$result->free(); //de-allocate the memory for the results
		
        return $table;
			
    }
	
	//insert rating
	public function rate($db){
		$email = $_SESSION['name'];
		$id = $this->id;
		$BeingRated = $this->BeingRated;
		$score = $this->score;
		$comments = $this->comments;
		$query = "insert into  Ratings(exchangeID, BeingRated, Rater, score, comments) values ($id, '$BeingRated','$email', $score,'$comments');";
		$result = $db->query($query);

		if (!$result || $db->affected_rows == 0){
			echo '<br>insert unsuccessful';
			return false;
		}
		return true;
	}
	
	//View previous exchanges
	public static function viewExchanges($db){
		$user = $_SESSION['name'];
		//query for Exchanges
		$query = "SELECT * FROM Exchanges WHERE Lender = '$user' and DATEDIFF(NOW(),ExchangeDate) <= 14";
				$result = $db->query($query);
		//check if ok
		if (!$result){
			return false;
		}
		//get number of rows returned
	    $num_results = $result->num_rows;
	    $info = '<p>Exchanges<br/><table border="1">
		<tr><th>Exchange ID</th><th>Title</th><th>Type</th><th>Owner</th><th>Lent To</th><th>Date</th><th>Select</th></tr>';
		if ($num_results > 0){
			for ($i=0; $i<$num_results; $i++){
				//read back one row at a time
				$row = $result->fetch_assoc(); //retrieves a row from the result
				$info .= 
				'<tr>'
				. '<td>'. $row['ExchangeID'] . '</td>'
				. '<td>'. $row['Title'] . '</td>'
				. '<td>'. $row['Type'] . '</td>'
				. '<td>'. $row['mark'] . '</td>'
				. '<td>'. $row['Borrower'] . '</td>'
				. '<td>'. $row['ExchangeDate'] . '</td>'
				. '<td><input name="BeingRated" type="radio" value="' . $row['Borrower'] .'"></td>'
				//. '<input type="hidden" name="id" value="' . $row['ExchangeID'] .'">'
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
		<tr><th>Exchange ID</th><th>Title</th><th>Type</th><th>Owner</th><th>Borrowed From</th><th>Date</th><th>Select</th></tr>';
		if ($num_results > 0){
			for ($i=0; $i<$num_results; $i++){
				//read back one row at a time
				$row = $result->fetch_assoc(); //retrieves a row from the result
				$info .= 
				'<tr>'
				. '<td>'. $row['ExchangeID'] . '</td>'
				. '<td>'. $row['Title'] . '</td>'
				. '<td>'. $row['Type'] . '</td>'
				. '<td>'. $row['mark'] . '</td>'
				. '<td>'. $row['Lender'] . '</td>'
				. '<td>'. $row['ExchangeDate'] . '</td>'
				. '<td><input name="BeingRated" type="radio" value="' . $row['Lender'] .'"></td>'
				//. '<input type="hidden" name="id" value="' . $row['ExchangeID'] .'">'
				.'</tr>';
			}
		}
		$info .= '</table></p>';
		$result->free(); //free memory
		return $info;
	}
	
	//update rating comment
	public function updateComment($db){
		$id = $this->id;
		$BeingRated = $this->BeingRated;
		$comments = $this->comments;

		$query = "UPDATE Ratings SET comments='$comments' WHERE exchangeID='$id' and BeingRated='$BeingRated';";
		$result = $db->query($query);

		if (!$result || $db->affected_rows == 0){
			echo '<br>insert unsuccessful';
			return false;
		}
		return true;
	}
}
?>