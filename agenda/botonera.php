<? 
session_start(); 
require "func.php"; 
checkLogged(); 
connect(); 

switch ( $_GET[ boton ] ) {
	case 'Agregar contacto.': 
		go_to("contactos.php?cmd=new"); 
		break; 
	case 'Listados.': 
		go_to("listados.php?letra=*"); 
		break; 
	case 'Buscar.':
		go_to("search.php?cadena=$_GET[cadena]"); 
}

?>