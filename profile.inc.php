<?php
/* 
profile class
*/
class Profile {
	// This class is used to create and maintain products for the survey
	
	
	public static function userInfo($db){
		$email = $_SESSION['name'];
		//query for user's name
		$query = "SELECT FirstName, LastName FROM Member WHERE Email = '$email'";
		$result = $db->query($query);
		//check if  ok
		if (!$result){
			return false;
		}
		//get number of rows returned
	    $num_results = $result->num_rows;
	    $info = '';
		$name= '';
		if ($num_results > 0){
			$row = $result->fetch_assoc();
			$name = $row['FirstName'].' '.$row['LastName'];
			$info = '<p>First Name: '.$row['FirstName'].'<br/>'.
					'Last Name: ' .$row['LastName']. '<br/>';

		}
		$result->free(); //free memory
		//query for company number
		$query = "SELECT CoNumber FROM Company
				Where CoNumber in (SELECT CoNumber FROM Midshipmen
									WHERE Email = '$email')
				or CoNumber in (SELECT CoNumber FROM CO
									WHERE Email = '$email')
				or CoNumber in (SELECT CoNumber FROM SEL
									WHERE Email = '$email');";
		$result = $db->query($query);
		//check if  ok
		if (!$result){
			return false;
		}
		$num_results = $result->num_rows;
		if ($num_results > 0){
			$row = $result->fetch_assoc();
			$info .= 'Company: '.$row['CoNumber']. '</p>';
		}
		$result->free(); //free memory
		//query for items
		$query = "SELECT Title, Type, Description FROM Items Where mark = '$email';";
		$result = $db->query($query);
		//check if  ok
		if (!$result){
			$info = '<p>You do not own any items</p>';
			return $info;
		}
		$num_results = $result->num_rows;

		if ($num_results > 0){
			$info .= '<p><b>Items</b><br/><table border="1">
			<tr><th>Title</th><th>Type</th><th>Description</th></tr>';//<th>Select</th></tr>';
			for ($i=0; $i<$num_results; $i++){
				//read back one row at a time
				$row = $result->fetch_assoc(); //retrieves a row from the result
				$info .= 
				'<tr>'
				. '<td>'. $row['Title'] . '</td>'
				. '<td>'. $row['Type'] . '</td>'
				. '<td>'. $row['Description'] . '</td>'
				.'</tr>';
			}
			$info .= '</table></p>';
		}
		else{
			$info .= '<p>You have no items</p>';
		}
		$result->free(); //free memory
        return array($name,$info);
	}
	


}
?>