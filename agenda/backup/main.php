<? 
session_start(); 
require "func.php"; 
require "tablas.php"; 
checkLogged(); 
connect();
checkTables(); 

$frames= '
<div width="18%" style="float:left"> 
<h1>Agenda.</h1>
<div style="height: 100%; border-right: thick solid darkbrown;">
<h2><a href="contactos.php" target="main" class="boton">Contactos.</a></h2>
<h2><a href="listados.php" target="main" class="boton selected">Listados.</a></h2>
<h2><a href="seteos.php" target="main" class="boton">Opciones.</a></h2>
<h2><a href="salir.php" target="_top" class="boton">Salir.</a></h2>
</div>
</div>
<iframe name="botonera"	src="botonera.php" 	width="80%" height="40" frameborder="NO" align="right"></iframe>
<iframe name="main" 	src="listados.php" 	width="80%" height="700" frameborder="NO" align="right"></iframe>
';
/*
<iframe name="header"	src="header.html" 	width="18%" height="100" frameborder="NO" align="left"></iframe>
<iframe name="menu"		src="menu.html" 	width="100%" height="600" frameborder="NO" align="left"></iframe>
*/

$s= '
<script src="shortcut.js" type="text/javascript"></script>
<script type="text/javascript">
function init() {
	shortcut.add("Shift+F1",function() {
		alert("Help Me!");
	});
	shortcut.add("Ctrl+S",function() {
		alert("Saved!");
	});
	shortcut.add("Right",function() {
		alert("Right");
	});
}
addEvent(window,"load",init);
</script>
';

print html( 
			head( title("Agenda."), css("style.css"), javascript($s) ), 
			$frames
		  ); 
?>
