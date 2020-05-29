<? 
require "func.php"; 

$username= $_POST[username]; 
$password= $_POST[password]; 

if ( $username != "bit" or $password != "mingames" )
	{ go_to("index", "wronglogin"); }
	else {
		session_start(); 
		$_SESSION[userid]= $username;
		go_to("main"); 
	}
?>
