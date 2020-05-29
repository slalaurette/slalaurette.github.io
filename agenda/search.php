<? 
session_start(); 
require_once "func.php"; 

checkLogged(); 
connect();

$page= array(); 

# cadena de búsqueda
$cadena= trim($_GET[cadena]); 
if ( !$cadena ) { array_push( $page, p( '¡Nada para buscar!' ) . a( 'main.php', 'Volver.', 'class="boton selected"') ); }
else {
	$qr= "SELECT * FROM contactos WHERE nomap LIKE '%$cadena%' ORDER BY nomap"; 
	//array_push(p($qr); 
	$res= mysql_query( $qr );
	$n= mysql_num_rows($res); 
	if ( $n == 0 ) {
		array_push( $page, p( 'No hay resultados al buscar ' . b($cadena) ) . a( 'main.php', 'Volver.', 'class="boton selected"') );
	}
	elseif ( $n == 1 && !$_GET[force_list] ) { 
			$row= mysql_fetch_assoc($res); 
			$codigo= $row['codigo']; 
			go_to( "contactos.php?cmd=edit&codigo=$codigo" ); 
			}
		else
			{ 
			if ( !$_GET[force_list] ) { array_push( $page, h3( "$n resultados para la búsqueda " . i("'$cadena'")) ); }
			while($row = mysql_fetch_assoc($res)) {
				array_push( $page, cntListLine( $row, 'list' ) ); 
				}
			}

		}

print html( head( css('style.css') ), 
			body( botonera_main(), 
			      implode( "\n", $page )
			) ); 


//goto( 'main.php' ); 
?>
