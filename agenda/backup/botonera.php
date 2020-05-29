<? 
session_start(); 
require "func.php"; 
checkLogged(); 
connect(); 

switch ( $_POST[ boton ] ) {
	case "Agregar contacto.": 
		goto("contactos.php?cmd=new"); 
		break; 
	case "Listados.": 
		goto("listados.php?letra=*"); 
		break; 
	default: 
		print html( head( css("style.css") ), 
					body( form( array( action => "botonera.php", 
					                  'class' => "botonera", 
									   method => "POST",
									   target => "main"), 
							 input("submit", "boton", "Agregar contacto.", 'class="boton selected"'), 
							 input("submit", "boton", "Listados.", 'class="boton selected"') )
					) 
				);
}

?>