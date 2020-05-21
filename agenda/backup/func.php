<?

/*************************************************************************************************/
function checkLogged() {
	if ( ! $_SESSION['userid'] ) 
		{ goto("index","expired"); }
}

/*************************************************************************************************/
function cntDelLink($cod) {
	return a("contactos.php?cmd=delete&codigo=$cod", img("delete.gif")); 
}

/*************************************************************************************************/
function cntEditLink($cod) {
	return a("contactos.php?cmd=edit&codigo=$cod", img("edit.gif")); 
}

/*************************************************************************************************/
function cntViewLink($cod) {
	return a("contactos.php?cmd=show&codigo=$cod", img("info.gif"), 'target="_blank"'); 
}

/*************************************************************************************************/
function connect() {
// conexión con el servidor 
$dbconn= mysql_connect("localhost", "bit", "nhmingames"); 
if (!$dbconn) { perish("No me pude conectar con el servidor. Sorry."); }

// conexión con la base de datos
$dbh= mysql_select_db("bit_agenda"); 
if (!$dbh) { perish("No me pude conectar con la base de datos. Sorry."); } 
}

/*************************************************************************************************/
function dblq($s) {
return '"'.$s.'"'; 
}

/*************************************************************************************************/
function goto($script,$msg="") {
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
function messages() {
	// mensajes pasados a través del parámetro MSG
	// 		TODO: posibilidad de presentar más de un mensaje (URL?msg=wronglogin|noconn|expired|allhellbrokeloose)
	//		TODO: tipos de mensajes (msg, warning, datadump) 
	if ($_GET[msg]) {
		$msg= $_GET[msg]; 
		switch ($msg) {
			case "allhellbrokeloose": 	$msgtext= "ZOMG ALL HELL BROKE LOOSE"; break; 		
			case "expired": 			$msgtext= "Parece que la sesión expiró. Volvé a entrar."; break; 		
			case "noconn": 				$msgtext= "No me pude conectar a la base de datos."; break; 		
			case "wronglogin": 			$msgtext= "Usuario o contraseña incorrectos."; break; 		
		}
		{ return p("msg", h($msgtext)); }
	}
}		

/*************************************************************************************************/
function nomap($nom, $ap, $tipo= 0) {
	if ( ! $nom ): 
		if ( ! $ap ): 			// no hay nombre ni apellido
			return "";
		else:
		 	return $ap;			// hay sólo apellido
		endif;
	elseif ( ! $ap ): 			// hay sólo nombre
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
		print pre(print_r($v)); 
}

require_once "func_html.php"; 
require_once "func_cnt.php"; 
?>