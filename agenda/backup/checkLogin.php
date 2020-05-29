<? 
require "func.php"; 

$username= $_POST[username]; 
$password= $_POST[password]; 

if ( $username != "bit" or $password != "mingames" )
	{ goto("index", "wronglogin"); }
	else {
		session_start(); 
		$_SESSION[userid]= $username;
		goto("main"); 
	}
?>
