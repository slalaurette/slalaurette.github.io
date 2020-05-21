<? 
session_start(); 
require_once "func.php"; 
checkLogged(); 

global $codigo, $nombre, $apellido, $referencia; 
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
		$nombre= $_POST[nombre]; 
		$apellido= $_POST[apellido];
		$referencia= $_POST[referencia]; 
		$fonos= $_POST[fonos]; 
		$emails= $_POST[emails]; 
		$nap= str_replace( "'", "", nomap($nombre, $apellido, 1) ); 
		$editado= date('Y-m-d'); 
		connect(); 
		if ( ! $nap ) 
			{ perish("¡No hay nombre ni apellido!"); }
		else
		{	mysql_select_db("bit_agenda"); 
			$result= mysql_query("INSERT INTO contactos(nombre,apellido,nomap,referencia,editado,dirty) 
					VALUES ('$nombre', '$apellido', '$nap', '$referencia','$editado','-1')"); 
			$codigo= mysql_insert_id(); 		// código del nuevo contacto insertado 
			$result= mysql_query("DELETE FROM fonos WHERE cnt= $codigo"); 
			foreach ($fonos as $f): 
				if (!empty($f[fono])): 
					$qr="INSERT INTO fonos(cnt,fono,dsc) VALUES ('$codigo', '$f[fono]', '$f[dsc]')"; 
					mysql_query($qr); 			
				endif;		
			endforeach;		
			$result= mysql_query("DELETE FROM EMAIL WHERE cnt= $codigo"); 
			foreach ($emails as $f): 
				if (!empty($f[email])): 
					// TODO: dropdown para tipos de direcciones electrónicas que no sean EMAIL 
					$qr="INSERT INTO email(cnt,tipo,email) VALUES ('$codigo', 'EMAIL', '$f[email]')"; 
					mysql_query($qr); 			
				endif;		
			endforeach;		
	
		}
		
//		goto("contactos.php?cmd=new"); 
			goto("contactos.php?cmd=show&codigo=$codigo");

		break; 
		
	case "delete": 
		$cod= $_GET[codigo]; 
		if ( !cod ) { goto("listados"); }
		$action= $_POST[submit]; 

		connect(); 
		if ( $action == "Borrar contacto." ):
			$result= mysql_query("DELETE FROM contactos WHERE codigo = $cod"); 
			$result= mysql_query("DELETE FROM fonos     WHERE cnt    = $cod");
			$result= mysql_query("DELETE FROM email     WHERE cnt    = $cod"); 		
			$result= mysql_query("DELETE FROM dep       WHERE padre  = $cod"); 		
			$result= mysql_query("DELETE FROM dep       WHERE hijo   = $cod"); 		
			goto("listados"); 
		elseif ( $action == "Cancelar."):
			goto("listados"); 
		else:
			array_push($page, 
				form( array(action => "contactos.php?cmd=delete&codigo=$cod", 
				            method => "POST", 
				            name => "cntDelConfirm"), 
				      input('submit', 'submit', 'Borrar contacto.', 'class="boton"'), 
				      input('submit', 'submit', 'Cancelar.', 'class="boton"')
				) ); 
		endif;		
		break;

	case "edit": 
		$cod= $_GET["codigo"]; 
		if ( !cod ) { goto("listados"); }
		connect(); 

		$cnt= cntRead($cod); 
		$codigo= $cnt['codigo']; 
		$nombre= $cnt['nombre']; 
		$nomap= $cnt['nomap'];	
		$apellido= $cnt['apellido']; 
		$referencia= $cnt['referencia']; 
		$editado= $cnt['editado']; 
		$fonos= $cnt['fonos'];
		$emails= $cnt['emails'];
		$title= "Editando contacto $codigo."; 
		array_push($page, h2( "Editando contacto: " . nomap($nombre, $apellido) . ". &nbsp;") ); 
		array_push($page, cntEditForm() );
		array_push($page, h3( a("contactos.php", "Volver") ) );
		break;

	case "list": 
		array_push($page, h1("Listado de contactos") ); 
		array_push($page, h3( a("contactos.php", "Volver") ) );
		break;

	case "new": 
		array_push($page, h3("Agregar un contacto.") ); 
		array_push($page, cntEditForm(1) );
		array_push($page, h3( a("contactos.php", "Volver") ) );
		break;

	case "show": 
		$cod= $_GET["codigo"]; 
		if ( !cod ) { goto("listados"); }
		connect(); 
		$cnt= cntRead($cod);
		$title= nomap($cnt['nombre'], $cnt['apellido']);  
		array_push($page, pre(print_r($cnt, TRUE)) );
		array_push($page, cntEditLink($cod)); 
		break;

	case "update": 
		$codigo=$_GET[codigo]; 
		$nombre= $_POST[nombre]; 
		$apellido= $_POST[apellido];
		$referencia= $_POST[referencia]; 
		$fonos= $_POST[fonos]; 
		$emails= $_POST[emails]; 
		$nap= str_replace( "'", "", nomap($nombre, $apellido, 1) ); 

		if ( ! $nap ):
			perish("¡No hay nombre ni apellido!");
		else:
			connect();
			$editado= date('Y-m-d'); 
			$qr= "UPDATE contactos SET "; 
			$qr .= 		"nombre='$nombre', apellido='$apellido', referencia='$referencia', nomap='$nap', "; 
			$qr .= 		"editado='$editado', dirty=-1 "; 
			$qr .= 			"WHERE codigo = $codigo"; 
			$result= mysql_query($qr); 
			$result= mysql_query("DELETE FROM fonos WHERE cnt= $codigo"); 
			foreach ($fonos as $f): 
				if (!empty($f[fono])): 
					$qr="INSERT INTO fonos(cnt,fono,dsc) VALUES ('$codigo', '$f[fono]', '$f[dsc]')"; 
					mysql_query($qr); 			
				endif;		
			endforeach;		
			$result= mysql_query("DELETE FROM email WHERE cnt= $codigo"); 
			foreach ($emails as $f): 
				if (!empty($f[email])): 
					$qr="INSERT INTO email(cnt,tipo,email) VALUES ('$codigo', 'EMAIL', '$f[email]')"; 
					mysql_query($qr); 			
				endif;		
			endforeach;		
	
			goto("contactos.php?cmd=show&codigo=$codigo");
		endif;
		break; 

}

print html_open() . head( title($title), css ("style.css"), javascript($js) ) . body_open();
printMessages(); 
print implode("\n", $page); 
print body_close() . html_close(); 

?>