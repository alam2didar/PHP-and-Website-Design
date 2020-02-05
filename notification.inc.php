<?php
/* 
notification class
*/
class Notification {
	// This class is used to create and maintain products for the survey
	
	
	public static function listExchangeNotifications($db){
		$email = $_SESSION['name'];
		$query = "SELECT * FROM Notifications WHERE target='$email' and notificationType='exchange' and DATEDIFF(NOW(),dateCreated) <= 7";
		$result = $db->query($query);
		//check if  ok
		if (!$result){
			return false;
		}
		//get number of rows returned
		$num_results = $result->num_rows;
		$info = '';
		if ($num_results > 0){
			for ($i=0; $i<$num_results; $i++){
				//read back one row at a time
				$row = $result->fetch_assoc(); //retrieves a row from the result
				$info .= '<li>' . $row['itemName'] . " " . $row['message'] . " " . $row['borrower'] . " on " . $row['dateCreated'] . '</li>';
			}
		}
		$info .= '</ul>';
        return $info;
	}
	
	public static function listNotifications($db){
		//query for all users
		$email = $_SESSION['name'];
		$query = "select mark, target, message, dateCreated from Items, notifications
				where possession = target and notificationType='rating'
				and mark='$email' and DATEDIFF(NOW(),dateCreated) <= 7;";
		$result = $db->query($query);
		//check if  ok
		$info = '<h2>Notifications</h2>';
		if (!$result){
			$info .= '<p>You have no notifications</p>';
			return $info;
		}
		//get number of rows returned
		$num_results = $result->num_rows;
		$info .= '<ul>';
		if ($num_results > 0){
			for ($i=0; $i<$num_results; $i++){
				//read back one row at a time
				$row = $result->fetch_assoc(); //retrieves a row from the result
				$info .= '<li>' . $row['target'] . '\'s ' . $row['message'] . " on " . $row['dateCreated'] . '</li>';
			}
		}
        return $info;
	}

}
?>