<? 
session_start(); 
require_once "func.php"; 

checkLogged(); 
connect();

# rango a mostrar: todos los contactos o los que comiencen por una letra determinada
$letra= $_GET[letra]; if ( !$letra ) { $letra = "*"; }

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


echo html_open(), head( title("Agenda. Contactos."), css("style.css"), javascript($s) ), body_open(); 
printMessages();

$imprimir= $_GET[imprimir]; 
# botonera con letras 
if ( !$imprimir): 
	$botonera= input( "submit", "letra", "*", 'class="boton"' );
	for ( $i=65; $i <= 90; $i++ ): 
		if ( chr($i) == $letra ): 
			$botonera .= input( "submit", "letra", chr($i), 'class="boton selected"' ); 
		else: 
			$botonera .= input( "submit", "letra", chr($i), 'class="boton"' ); 
		endif;
	endfor;
	$botonera .= input( "submit", "letra", "etc", 'class="boton"');
	$botonera .= input( "submit", "letra", "dirty", 'class="boton"' );
	print form( 'action="listados.php" method="GET" class="botonera"', $botonera ); 
endif;

if ( ! $imprimir ): 
	print div( 'align="right" style="margin:6px;"', 
			a("listados.php?imprimir=1&letra=$letra", "Imprimir.", 'target="_blank" class="boton selected"') ); 
else: 
	echo "<div style=\"margin: 0% 5%\">\n"; 
endif; 

$qry= "SELECT * FROM contactos";
if ($letra != "*"):
	if ($letra == "etc"): 
		$where .= " WHERE LEFT(UPPER(nomap),1) NOT BETWEEN 'A' AND 'Z'";
	elseif ($letra == "dirty"): 
		$where .= " WHERE dirty = -1";
	else: 
		$where .= " WHERE UPPER(nomap) LIKE '$letra%'";
	endif;
endif; 		

$qry .= $where . " ORDER BY nomap"; 
#print p( b( "$letra => $qry" ) ); 

$res= mysql_query( $qry );
$n= mysql_num_rows($res); 
if ( ! $imprimir ) { print h3("$n contactos."); }
if ( $imprimir ) { if ( $letra != "*" ) { print h1( "<center>$letra</center>" ); } }
while($row = mysql_fetch_assoc($res)) {
	$codigo= $row['codigo']; 
	$nombre= $row['nombre']; 
	$nomap= $row['nomap'];	
	$apellido= $row['apellido']; 
	$referencia= $row['referencia']; 
	$fonos= cntReadFonos($codigo); 
	$fonolist= ""; 
	foreach($fonos as $f):
		if(!empty($f[dsc])) {$fonolist .= $f[dsc] . ": "; }
		$fonolist .= $f[fono] . ". "; 
	endforeach; 		
	$emails= cntReadEmails($codigo); 
	$emaillist= "";
	foreach($emails as $e):
		// OJO: con los links que no sean emails, esto va a fallar
		$emaillist .= a( "mailto:$e[email]", $e[email] ) . ". "; 
	endforeach; 		
	
	if ( ! $imprimir ): 
		$tools= cntDelLink($codigo) . " " . cntEditLink($codigo) . " " . cntViewLink($codigo) . b(" $codigo. ");
	else: 
		$tools= ""; 
	endif; 
	// TODO: estilos para entrada y referencia en lugar de este ugly FONT
	print p( $tools . b( h( nomap($nombre, $apellido) ) ) . ". <font face=Tahoma size=-1>$referencia</font> ". $fonolist . i( $emaillist ) ); 
}

if ( $imprimir ) { print "</div>\n"; }

# CRUDE HACK -- lo que voy a imprimir ya no es dirty (como si ya estuviera impreso)
if( $imprimir ): 
	$updq= "UPDATE contactos SET dirty=0 $where"; 
	mysql_query( $updq	);
endif;

echo body_close(), html_close(); 

?>