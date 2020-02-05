<?php
/* 
member class
*/
class Member {
	// This class is used to create and maintain products for the survey
	
	
	public static function listMembers($db){
		//query for all users
		$query = "SELECT FirstName, LastName, Email FROM Member";
		$result = $db->query($query);
		//check if  ok
		if (!$result){
			return false;
		}
		//get number of rows returned
		$num_results = $result->num_rows;
		$info = '<p><table border="1">
		<tr><th>First Name</th><th>Last Name</th><th>Email</th></tr>';//<th>Select</th></tr>';
		if ($num_results > 0){
			for ($i=0; $i<$num_results; $i++){
				//read back one row at a time
				$row = $result->fetch_assoc(); //retrieves a row from the result
				$info .= 
				'<tr>'
				. '<td>'. $row['FirstName'] . '</td>'
				. '<td>'. $row['LastName'] . '</td>'
				. '<td>'. $row['Email'] . '</td>'
				.'</tr>';
			}
		}
		$info .= '</table></p>';
        return $info;
	}
	
	public static function listFriends($db){
		$email = $_SESSION['name'];
		//query for items
		$query = "SELECT distinct Email, FirstName, LastName
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

				WHERE re=se AND Email=re AND Email=se AND nRcount/Rcount > 0.9 AND nScount/Scount > 0.9;";
		$result = $db->query($query);
		//check if  ok
		if (!$result){
			$info = '<p>You have no friends</p>';
			return $info;
		}
		$num_results = $result->num_rows;
		$info = '<p><b>Your Friends</b><br/><table border="1">
		<tr><th>First Name</th><th>Last Name</th><th>Email</th></tr>';//<th>Select</th></tr>';
		if ($num_results > 0){
			for ($i=0; $i<$num_results; $i++){
				//read back one row at a time
				$row = $result->fetch_assoc(); //retrieves a row from the result
				$info .= 
				'<tr>'
				. '<td>'. $row['FirstName'] . '</td>'
				. '<td>'. $row['LastName'] . '</td>'
				. '<td>'. $row['Email'] . '</td>'
				.'</tr>';
			}
			$info .= '</table></p>';
		}
		$result->free(); //free memory
        return $info;
	}

}
?>