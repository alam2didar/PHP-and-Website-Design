<?php

class Item {
	// This class is used to create and maintain authentication
	
	public $title;
	public $type;
	public $description;

	
	//This is the constructor 
	public function __construct($title, $type, $description) {
		$this->__set('title', $title);
		$this->__set('type', $type);
		$this->__set('description', $description);
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

	public function addItem($db) {
		$email = $_SESSION['name'];
		$title = $this->title;
		$type = $this->type;
		$description = $this->description;
		
		$query = "INSERT INTO Items (Title, Type, description, mark, possession)
				values('$title', '$type', '$description', '$email', '$email')";
        $result = $db->query($query);
		if (!$result || $db->affected_rows == 0){
			return false;
		}
		else{
			return true;
		}	
	
	}
	
}
?>