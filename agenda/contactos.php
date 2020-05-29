<? 
session_start(); 
require_once "func.php"; 
checkLogged(); 
connect();

// si existe un campo llamado "nombre", poner el cursor ahí
$js= "
   function formfocus() {
      document.getElementById('nombre').focus();
   }
   window.onload = formfocus;
"; 

$title= "Agenda."; 
$page= array(); 

$cmd= $_GET[cmd]; 
switch ($cmd) {
	case NULL: 
		array_push($page, h2( a("contactos.php?cmd=add", "Agregar.") ) ); 
		array_push($page, h2( a("contactos.php?cmd=list", "Listar.") ) );
		array_push($page, h3( a("main.php", "Volver.") ) ); 
		break;

	case "add": 
		$cnt= cntFromPost();
		$cnt[nomap]= str_replace( "'", "", nomap($cnt[nombre], $cnt[apellido], 1) ); 
		$cnt[editado]= date('Y-m-d'); 
		
		if ( ! $cnt[nomap] ) 
			{ array_push( $page, "¡No hay nombre ni apellido!" ); }
		else
		{ 	// chequeamos que no haya otro contacto con mismo nombre y apellido
			$qr= "SELECT * FROM contactos WHERE nombre = '$cnt[nombre]' AND apellido = '$cnt[apellido]'";
			$result= mysql_query( $qr ); 
			if ( mysql_num_rows( $result ) > 0 ) { 	// hay colisión
				$lista= ''; 
				while ( $row = mysql_fetch_assoc( $result ) ) {
					$lista .= cntListLine( $row ); 
				}
				array_push( $page, 
					form( // formulario para confirmar escritura
						array(action => 'contactos.php?cmd=add_confirm', method => "POST"), 
						p( '*** COLISIÓN ***'),		// sólo devuelve un contacto, modificar para procesar múltiples colisiones
						$lista, 
						input( 'hidden', 'cnt_data',  urlencode( serialize( $cnt ) ) ),
						input( 'submit', 'boton', 'Confirmar.', 'class="boton selected"'), 
						input( 'submit', 'boton', 'Cancelar.',  'class="boton selected"')
					)
				); 		
			} else {								// no hay colisión, puedo escribir tranquilo
				cntWrite( $cnt, 'new' ); 
				go_to("search.php?cadena=$cnt[nomap]&force_list=1");
			}
		}
		break; 
	
	case "add_confirm": 
		$cnt= array(); 
		if ( $_POST[ boton ] == 'Confirmar.' ) {
			$cntdata= $_POST[cnt_data]; 
			$cnt=  unserialize( urldecode($cntdata)  ); 
			cntWrite( $cnt, 'new' );
		}
		go_to( "search.php?cadena=$cnt[nomap]&force_list=1" ); 
		break;
		
	case "delete": 
		$cod= $_GET[codigo]; 
		if ( !cod ) { go_to("listados"); }
		$action= $_POST[ submit ]; 

		connect(); 
		if ( $action == "Borrar contacto." ):
			$result= mysql_query("DELETE FROM contactos WHERE codigo = $cod"); 
			$result= mysql_query("DELETE FROM fonos     WHERE cnt    = $cod");
			$result= mysql_query("DELETE FROM email     WHERE cnt    = $cod"); 		
			$result= mysql_query("DELETE FROM dep       WHERE padre  = $cod"); 		
			$result= mysql_query("DELETE FROM dep       WHERE hijo   = $cod"); 		
			go_to("listados"); 
		elseif ( $action == "Cancelar."):
			go_to("listados"); 
		else:
			$cnt= cntRead( $cod ); 
			array_push( $page, p( '¿Seguro que querés borrar este contacto?' ) ); 
			array_push( $page, cntListLine( $cnt ) ); 
			
			array_push($page, 
				form( array(action => "contactos.php?cmd=delete&codigo=$cod", 
				            method => "POST", 
				            name => "cntDelConfirm"), 
				      input('submit', 'submit', 'Borrar contacto.', 'class="boton selected"'), 
				      input('submit', 'submit', 'Cancelar.',        'class="boton selected"')
				) ); 
		endif;		
		break;

	case "edit": 
		$cod= $_GET["codigo"]; 
		if ( !cod ) { go_to("listados"); }
		connect(); 

		$cnt= cntRead($cod); 
		$title= "Editando contacto $cod."; 
		array_push($page, h2( "Editando contacto $cod: " . nomap($cnt[nombre], $cnt[apellido]) . ". &nbsp;") ); 
		array_push($page, cntEditForm( $cnt ) );
//		array_push($page, h3( a("contactos.php", "Volver") ) );
		break;

	case "list": 
		/*
		array_push($page, h1("Listado de contactos") ); 
		array_push($page, h3( a("contactos.php", "Volver") ) );
		*/
		go_to("listados"); 
		break;

	case "new": 
		array_push($page, h3("Agregar un contacto.") ); 
		array_push($page, cntEditForm( 0 ) );
//		array_push($page, h3( a("contactos.php", "Volver") ) );
		break;

	case "show": 
		$cod= $_GET["codigo"]; 
		if ( !cod ) { go_to("listados"); }
		connect(); 
		$cnt= cntRead($cod);
		$title= nomap($cnt['nombre'], $cnt['apellido']);  
		array_push($page, cntListLine( $cnt )); 
		array_push($page, pre(print_r($cnt, TRUE)) );
		break;

	case "update": 
		$codigo= $_GET[codigo]; 
		$nap= str_replace( "'", "", nomap($_POST[nombre], $_POST[apellido], 1) ); 

		if ( ! $nap ):
			perish("¡No hay nombre ni apellido!");
		else:
			connect();
			$editado= date('Y-m-d'); 
			$qr= "UPDATE contactos SET "; 
			$qr .= 		"nombre='$_POST[nombre]', apellido='$_POST[apellido]', referencia='$_POST[referencia]', "; 
			$qr .= 		"nomap='$nap', editado='$editado', dirty=-1 "; 
			$qr .= 			"WHERE codigo = $codigo"; 
			$result= mysql_query($qr); 
			$result= mysql_query("DELETE FROM fonos WHERE cnt= $codigo"); 
			foreach ( $_POST[fonos] as $f ): 
				if (!empty($f[fono])): 
					$qr="INSERT INTO fonos(cnt,fono,dsc) VALUES ('$codigo', '$f[fono]', '$f[dsc]')"; 
					mysql_query($qr); 			
				endif;		
			endforeach;		
			$result= mysql_query("DELETE FROM email WHERE cnt= $codigo"); 
			foreach ( $_POST[emails] as $e ): 
				if (!empty($e[email])): 
					$qr="INSERT INTO email(cnt,tipo,email) VALUES ('$codigo', 'EMAIL', '$e[email]')"; 
					mysql_query($qr); 			
				endif;		
			endforeach;		
			
			go_to("contactos.php?cmd=show&codigo=$codigo");
		endif;
		break; 

}

print html( 
	head( title($title), css ("style.css"), javascript($js) ), 
	body(
		//menu_main(), 
		botonera_main(),
		messages() ,
		implode( "\n", $page ) ) ); 

?>