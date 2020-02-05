<?php
class Page
{
  // class Page's attributes
  public $content;
  private $title = 'Power of 4 Dorm Life ';
  private $keywords = 'Team Dorm Life, PHP, 
                   SQL, Fun, United States Naval Academy';
	
	private $xmlheader = "<!DOCTYPE html>
		<html>";
  

  //constructor 
  public function __construct($title){
	$this->__set("title", $title);
  }
  
  //set private attributes
  	public function __set($varName, $varValue)
	{	
		$varValue = trim($varValue);
		$varValue = strip_tags($varValue);
		if (!get_magic_quotes_gpc()){
			$varValue = addslashes($varValue);
		}
		$this->$varName = $varValue;
	}
	
	//get function
	public function __get($varName)
	{
		return $this->$varName;
	}
  
  //output the page
  public function display()
  {
	echo $this->xmlheader;
	echo "<head>\n";
    $this -> displayTitle();
    $this -> displayKeywords();
    $this -> displayStyles();
	echo '<link rel="stylesheet" type="text/css" href="styles.css">';
    echo "</head>\n<body>\n";
    $this -> displayContentHeader();
    echo $this->content;
    $this -> displayContentFooter();
    echo "</body>\n</html>\n";
  }

  //output the title 
  public function displayTitle()
  {
    echo '<title> '.$this->title.' </title>';
  }

  public function displayKeywords()
  {
    echo "<meta name=\"keywords\" content=\"$this->keywords\" charset=\"utf-8\"/>";
  }

  //display the embedded stylesheet
  public function displayStyles()
  { 
?>   
  <style>
    h1.header {color:yellow; background-color: blue; font-size:24pt; 			
		text-align:center; 
        font-family:arial,sans-serif}
    p {font-size:12pt; text-align:justify; 
       font-family:arial,sans-serif}
    p.footer {color:yellow; background-color: blue; font-size:9pt; 		
			text-align:center; 
            font-family:arial,sans-serif; font-weight:bold}
  </style>
<?php
  }

  //display the header part of the visible page
  public function displayContentHeader()
  { 
?>  
	<!-- navigation top bar -->
	<nav id="topBar">
		<ul>
			<li><a href="profilePage.php">Home</a></li>
			<li><a href="membersPage.php">Members</a></li>
			<li><a href="messagePage.php">Messenger</a></li>
			<li><a href="exchangePage.php">Exchanges</a></li>
			<li><a href="ratings.php">Ratings</a></li>
			<li><a href="logoutPage.php">Log Out</a></li>
		</ul>
	</nav>	
	
	 <h1 class = "header"> <br /> DormLife <br /> <br /></h1>
	 <h1 style = "text-align:center" >Welcome to DormLife</h1>
	 <hr class="flare">
<?php
  }

  //display the footer part of the visible page
  public function displayContentFooter()
  {
?>
    <br><br><hr class="flare">
    <p class="footer"> <br />&copy; <em>Making your Dorm Life Better!!</em> <br /> <br /></p>

<?php
  }
}
?>
