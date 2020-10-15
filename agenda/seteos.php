<? 
session_start(); 
require "func.php"; 
checkLogged(); 
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Agenda Personal -- Contactos</title>
<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>

<? 
require "func.php"; 
?>

<h1>Contactos.</h1>
<? printMessages(); ?>

</body>
</html>
