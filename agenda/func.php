<?

require_once "func_html.php"; 
require_once "func_cnt.php";
require_once "func_dep.php";

require_once 'HTML/AJAX/Server.php';
$server = new HTML_AJAX_Server();
$server->handleRequest();

/*************************************************************************************************/
function botonera_main() {
	return div( array( "class" => "botonera" ) , 
				form ( array( action => 'botonera.php', method => 'GET' ), 
					   div( 'style="float:right"', 
					        input( 'text', 'cadena', '', 
							       array( 'class' => 'datos', size => 30, onfocus => 'this.select' )
								), 
							input( 'submit', 'boton', 'Buscar.', 'class="boton selected"' )
							),
					   input("submit", "boton", "Agregar contacto.", 'class="boton selected"'), 
					   input("submit", "boton", "Listados.", 'class="boton selected"')
					   
				)
				) ; 
}

/*************************************************************************************************/
function botonera_list() {
	# botonera con letras 
	$botonera= input( "submit", "letra", "*", 'class="boton"' );
	for ( $i=65; $i <= 90; $i++ ): 				// A - Z
		if ( chr($i) == $letra ): 
			$botonera .= input( "submit", "letra", chr($i), 'class="boton selected"' ); 
		else: 
			$botonera .= input( "submit", "letra", chr($i), 'class="boton"' ); 
		endif;
	endfor;
	$botonera .= input( "submit", "letra", "etc", 'class="boton"');
	$botonera .= input( "submit", "letra", "dirty", 'class="boton"' );
	return 
			form( 'action="listados.php" method="GET" class="botonera"', $botonera ); 
}

/*************************************************************************************************/
function checkLogged() {
	if ( ! $_SESSION['userid'] ) 
		{ go_to("index","expired"); }
}

/*************************************************************************************************/
function connect() {
// conexi�n con el servidor 
$dbconn= mysql_connect("localhost", "bit", "nh!mingames"); 
if (!$dbconn) { perish("No me pude conectar con el servidor. Sorry."); }

// conexi�n con la base de datos
$dbh= mysql_select_db("bit_agenda"); 
if (!$dbh) { perish("No me pude conectar con la base de datos. Sorry."); } 
}

/*************************************************************************************************/
function dblq($s) {
return '"'.$s.'"'; 
}

/*************************************************************************************************/
function go_to($script,$msg="") {
	$hdr= "Location: ".$script; 
	if (! instr(".php", $script)) { $hdr .= ".php"; }
	if ($msg) { $hdr .= "?msg=".$msg; }
	$hdr .= "\n";
	header($hdr); 
}

/*************************************************************************************************/
function habemusTabla($tbl) {
	// se fija si existe la tabla especificada en la base de datos actual 
	$rows= mysql_num_rows( mysql_query("SHOW TABLES LIKE '$tbl'") ); 
	return ( $rows == 0 ? FALSE : TRUE ); 
}

/*************************************************************************************************/
function instr($quebuscar, $buscaren, $casesensitive= FALSE) {
	if ($casesensitive): 
		return ( FALSE !== strpos($buscaren, $quebuscar) ); 
	else: 
		return ( FALSE !== stripos($buscaren, $quebuscar) ); 
	endif; 
}

/*************************************************************************************************/
function menu_main() {
	return div( array( "class" => "menu" ), 
				h1( 'Agenda.' ) .
				//div( 'style="height: 100%; border-right: thick solid darkbrown;"', 
					h2( a( 'contactos.php', 'Contactos.', 'class="boton"' ) ) . 
					h2( a( 'listados.php',  'Listados.',  'class="boton selected"' ) ) . 
					h2( a( 'seteos.php',    'Opciones.',  'class="boton"' ) ) . 
					h2( a( 'salir.php',     'Salir.',     'class="boton"' ) ) 
			//		)
				);
}

/*************************************************************************************************/
function messages() {
	// mensajes pasados a trav�s del par�metro MSG
	// 		TODO: posibilidad de presentar m�s de un mensaje (URL?msg=wronglogin|noconn|expired|allhellbrokeloose)
	//		TODO: tipos de mensajes (msg, warning, datadump) 
	if ( $_GET[msg] ) {
		$msg= $_GET[msg]; 
		switch ( $msg ) {
			case "allhellbrokeloose": 	$msgtext= "ZOMG ALL HELL BROKE LOOSE"; break; 		
			case "expired": 			$msgtext= "Parece que la sesi�n expir�. Volv� a entrar."; break; 		
			case "noconn": 				$msgtext= "No me pude conectar a la base de datos."; break; 		
			case "wronglogin": 			$msgtext= "Usuario o contrase�a incorrectos."; break; 		
		}
		{ return p( "msg", h( $msgtext ) ); }
	}
}		

/*************************************************************************************************/
function nomap( $nom, $ap, $tipo= 0 ) {
	if ( ! $nom ): 
		if ( ! $ap ): 			// no hay nombre ni apellido
			return "";
		else:
		 	return $ap;			// hay s�lo apellido
		endif;
	elseif ( ! $ap ): 			// hay s�lo nombre
		return $nom; 
	else:						// hay nombre y apellido
		if ( $tipo == 0 ):
			return "$nom $ap";
		else: 
			return "$ap, $nom";
		endif;
	endif;
}

/*************************************************************************************************/
function perish($msg) { die( p("msg", $msg) ); } 

/*************************************************************************************************/
function printMessages() {
	print messages(); 
}

/*************************************************************************************************/
function varprint($v) {
		print pre(print_r($v, TRUE)); 
}

?>