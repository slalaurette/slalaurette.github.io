<? 
session_start(); 
require_once "func.php"; 

checkLogged(); 
connect();

// TO DO: listar dependencias en el listado por pantalla y para imprimir, 
//        ya que ahora no están en el campo de referencia

# rango a mostrar: todos los contactos o los que comiencen por una letra determinada
$letra= $_GET[letra]; if ( !$letra ) { $letra = "*"; }
$imprimir= isset($_GET[imprimir]) ? $_GET[imprimir] : false; 
//print pre( var_dump( $_GET ) ); 

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


$hdr= html_open() . 
      head( title("Agenda. Contactos.") . 
	  css("style.css"), javascript($s) ) . 
	  body_open(); 

print $hdr; 

$divopen= '<div>'; 
print $divopen;


if ( !$imprimir): 
	//print menu_main(); 
	print botonera_main();
	print botonera_list();
endif;

if ( ! $imprimir ): 
	print div( 'style="margin:6px; float:right;"', 
			a("listados.php?imprimir=1&letra=$letra", "Imprimir.", 'target="_blank" class="boton selected"') );
	print '<div style="width:80%; float:right;">'; 
else: 
	echo "<div style=\"margin: 0% 5%\">\n"; 
endif; 

printMessages();

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

$res= mysql_query( $qry );
$n= mysql_num_rows($res); 
if ( ! $imprimir ) { print h3("$n contactos."); }
if ( $imprimir ) { if ( $letra != "*" ) { print h1( "<center>$letra</center>" ); } }
while($row = mysql_fetch_assoc($res)) {
	print cntListLine( $row, ( $imprimir )? 'imp' : 'list' ); 
}

if ( $imprimir ) { print div( 'style="float:right"', i( "($n)" ) ); }

//if ( $imprimir ) { 
print "</div>\n"; 
print "</div>\n"; 
//}

# CRUDE HACK -- lo que voy a imprimir ya no es dirty (como si ya estuviera impreso)
if( $imprimir ): 
	$updq= "UPDATE contactos SET dirty=0 $where"; 
	mysql_query( $updq	);
endif;

echo body_close(), html_close(); 

?>