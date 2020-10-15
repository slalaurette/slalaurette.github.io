<? 
session_start(); 
require "func.php"; 
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Salir.</title>
<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>

<? 
mysql_close(); 
session_destroy(); 
?>

<h1>Salir.</h1>
<h2><a href="index.php">Entrar.</a></h2>
<? printMessages(); ?>

</body>
</html>
