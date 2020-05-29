<? 
session_start(); 
require_once "func.php"; 
checkLogged(); 
connect();


$title= "Agenda."; 
$page= array(); 

$cmd= $_GET[cmd]; 
switch ($cmd) {
	case "add": 
		$padre= $_POST[padre]; 
		$hijo=  $_POST[hijo];
		$rel=   $_POST[rel];
		if ( empty($padre) || empty($hijo) ) {
			array_push( $page, '¡Necesito los dos contactos a vincular!' ); 
		} else {
			depAdd( $padre, $hijo, $rel ); 
		}
		go_to( "dep.php?cmd=list&padre=$padre" );
		break;
		
	case "delete": 
		$padre= $_GET[padre]; 
		$hijo=  $_GET[hijo];
		if ( empty($padre) || empty($hijo) ) {
			array_push( $page, '¡Necesito los dos contactos a desvincular!' ); 
		} else {
			depDel( $padre, $hijo ); 
		}
		go_to( "dep.php?cmd=list&padre=$padre" );
		break;
		
	case "list": 
		$padre= $_GET[padre]; 
		if ( empty( $padre ) ) {
			perish( 'No hay contacto para buscar dependencias.' ); 
		} else {
			$addform= ajaxform( array( name => 'dep_search', method => 'POST', action => "dep.php?cmd=search&cnt=$padre" ), 
							input( 'submit', 'submit', 'Agregar dependencia.', 'class="boton selected"')
						);
			array_push( $page, $addform ); 
			
/*
			$deplist= ''; 
			$cnt= cntRead( $padre );
			foreach( $cnt[deps] as $d ): 
				$rel=           $d[ rel    ]; 
				$hijo= cntRead( $d[ hijo   ] ); 
				$cod=        $hijo[ codigo ]; 
				$nomap=      $hijo[ nomap  ]; 
				$deplist .= cntListLine( $hijo, 'dep_list', $padre, $rel ); //p( "$rel: $cod. $nomap" );
			endforeach; 
			array_push( $page, $deplist );
*/
			array_push( $page, depList( $padre ) );
		}
		break; 
	
	case "search":
		$padre= $_GET[cnt];
		array_push( $page, 
					ajaxform( array( method => 'POST', action => "dep.php?cmd=select&padre=$padre"), 
						  depList( $padre ),
						  h( ' Código. ' ),  
						  input( 'text', 'codigo', $hijo,  array( size => 6, onfocus => 'this.select()') ), 
						  h( ' | NomAp. ' ), 
						  input( 'text', 'nomap',  $nomap, array( size => 10, onfocus => 'this.select()') ), 
						  input( 'submit', 'boton', 'Buscar.', 'class="boton selected"')
						) );
		break;
	
	case "select": 
		$padre= $_GET[padre];
		$hijo= $_POST[codigo]; 
		if ( empty ( $hijo ) ) {
			if ( empty ( $_POST[nomap] ) ) 
				{ perish( "¡Necesito un código o NomAp para buscar!" ); } 
			else 
				{ $qr= "SELECT * FROM contactos WHERE nomap LIKE '%$_POST[nomap]%'"; } }
		else { $qr= "SELECT * FROM contactos WHERE codigo = $_POST[codigo]"; } 
		$res= mysql_query( $qr );
		// TO DO: chequear que haya resultados!
		while($row = mysql_fetch_assoc($res)) {
			array_push( $page, cntListLine( $row, 'dep_add', $padre ) ); 
		}
		break;
		
	case "set_rel":
		$padre= $_GET[padre]; 
		$hijo=  $_GET[hijo];
		array_push( $page, 
			form( array( method => 'POST', action => "dep.php?cmd=add" ), 
//		          'Padre. ' . cntListLine( cntRead( $padre ) ) , 
				  input( 'hidden', 'padre', $padre ),
			      cntListLine( cntRead( $hijo  ) ),
				  input( 'hidden', 'hijo', $hijo ),
				  h( ' Relación. ' ),
				  input( 'text', 'rel', '', array( size => 30 ) ), 
				  input( 'submit', 'submit', ' Vincular. ', 'class="boton selected"' )
				  ) ); 
		break;
		
	default:
		array_push($page, h2( a("contactos.php?cmd=add",  "Agregar.") ) ); 
		array_push($page, h2( a("contactos.php?cmd=list", "Listar.") ) );
		array_push($page, h3( a("main.php", "Volver.") ) ); 
		break;

	}

print html( head( title($title), css('style.css') ), 
			body( //botonera_main(), 
				  //messages(), 
				  implode( "\n", $page ) ) ); 

?>