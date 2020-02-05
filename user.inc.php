<?php

class User {
	// This class is used to create and maintain authentication
	
	public $userID;
	public $password;
	public $fname;
	public $lname;
	
	//This is the constructor 
	public function __construct($userID, $pass, $fname, $lname) {
		$this->__set('userID', $userID);
		$this->__set('password', $pass);
		$this->__set('fname', $fname);
		$this->__set('lname', $lname);
	}
//////////////////////////////////////////////////////////////////
	
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
	
	
	
	//////////////////////////////////////////
	
	//add a new user to the database
	public function addUser($db){	
		//This adds a user object to the AppUsers table
		$userID = $this->userID;
		$password = $this->password;
		$fname = $this->fname;
		$lname = $this->lname;
		$hashpwd = sha1($password);
		$query = "INSERT INTO Member (Email, Passwd, FirstName, LastName) VALUES ('$userID','$hashpwd','$fname', '$lname')";
        $result = $db->query($query);
		if (!$result || $db->affected_rows == 0){
			return false;
		}
		else{
			return true;
		}
			
    }	

	//check whether a user is in the database
    public function validate($db) {
            $userID = $this->userID;
            $password = $this->password;
			$hashpwd = sha1($password);
            $query = "SELECT Email FROM Member WHERE Email = '$userID' and passwd = '$hashpwd'";
            $result = $db->query($query);
			if (!$result){
				echo "<h2>ERROR executing query *$query*. Error message: ". $db->errno ." ". $db->error ."</h2>";
				return false;
			}
            $num_rows = $result->num_rows;
			//if query returned something, the user is valid
         	if ($num_rows >= 1){
	         	//I could get the type of user and store it in a session variable for later use
               $row = $result->fetch_array();
               $type = $row['Email'];
			   //not doing anything with type for now
			   return true;				
            }
			//else, invalid username/password combination
			return false;
			
	}
}
?>

