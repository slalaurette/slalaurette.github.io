<?

/*************************************************************************************************/
function cntEditForm( $cnt ) {

if ( empty ( $cnt ) ): 				// nuevo contacto
	$script= "contactos.php?cmd=add"; 
	$label= "Agregar contacto.";
	$dataline= ''; 
	$iframe= '';
else:								// contacto existente
	$script= "contactos.php?cmd=update&codigo=$cnt[codigo]"; 
	$label= "Guardar cambios."; 
	$dataline= cntListLine( $cnt ); 
	$iframe= div( 'style="float:right; height:100%"', 
		fieldset ( legend( " Dependencias. "), 

/**/
		iframe( 'deps', "dep.php?cmd=list&padre=$cnt[codigo]", 
			array( width => "100%", 
			       height => "500", 
				   frameborder => "NO" ) ) ) );  
/*
		ajaxform( array( name => 'dep_search', method => 'post', action => "dep.php?cmd=search&cnt=$cnt[codigo]" ) 
							, depList( $cnt[codigo] ) 
							, input( 'submit', 'submit', 'Agregar dependencia.', 'class="boton selected"') 
						) ) );
*/
endif; 

	$form= form( array( name => 'cnt_edit_form', action => $script, method => "post" ), 

				fieldset( legend(" Datos básicos. "), 
				table('border="0" cellspacing="10"', 
					tr( td( div( array('class' => "label"), "Nombre." ) ), 
						td( input("text", "nombre", h($cnt[nombre]), 
									array(id => 'nombre', 'class' => 'datos', size => 35, onfocus => 'this.select()')) , 
							input('text', 'apellido', h($cnt[apellido]), 
									array('class' => 'datos', size => 35, onfocus => 'this.select') ) )
					), 
					tr( td( div( array('class' => "label"), "Referencia." ) ), 
						td( textarea('referencia', h($cnt[referencia]), 
							array('class' => 'datos', rows => 2, cols => 80, onfocus => "this.select") ) ) 		
					))), 
					
				fieldset( legend(" Fonos. "),
				          cntEditFormFonos($cnt[fonos]) ), 

				fieldset( legend(" Direcciones electrónicas. "), 
				          cntEditFormEmails($cnt[emails]) ), 
				
				// TODO: estilo para esto
				$cnt[editado] ? p( i( font( '', -1, "Editado el $cnt[editado]." ) ) ) : '', 
					
				fieldset( legend(" Acciones. "), 
					div(array('style' => 'float:right'), 
						input('submit', 'submit', $label, 'class="boton selected"'))		
				) 

				);

/*
	$ajaxform= form( array( action => 'test.pl', method => "POST", 
	                    onSubmit => "HTML_AJAX.formSubmit(this, 'result'); return false; "), 
						input( 'submit', 'submit', 'test', 'class="boton selected"' ) ); 
*/

//	return $dataline . $form . $iframe; 
//	return $dataline . $iframe . div('style="float:left"', $form) . $ajaxform;
	return $dataline . $iframe . $form . $ajaxform;
//		. div( array( 'id' => 'result' ), '...' )

		;

}

/*************************************************************************************************/
function cntEditFormEmails($f) {
	$f[]= ""; 
	$s= ""; $c= sizeof($f); 
	for ($i= 0; $i <= $c; $i++):
					// TODO: dropdown para tipos de direcciones electrónicas que no sean EMAIL 
			$s .= div( array('class' => 'fleft', style => 'margin:3px'), 
		          input("text", "emails[$i][email]", h($f[$i][email]), array('class' => "email", size => 35, onfocus => "this.select") ) 						); 
	endfor; 

	return $s;
}

/*************************************************************************************************/
function cntEditFormFonos($f) {
	$f[]= ""; 
	$s= ""; $c= sizeof($f); 
	for ($i= 0; $i <= $c; $i++):
			$s .= div( array('class' => 'fleft', style => 'margin:3px'), 
		          input("text", "fonos[$i][fono]", h($f[$i][fono]), array('class' => "fono", size => 15, onfocus => "this.select") ) .  
		          input("text", "fonos[$i][dsc]", h($f[$i][dsc]), array('class' => "fondesc", size => 22, onfocus => "this.select") )
				); 
	endfor; 

	return $s;
}

/*************************************************************************************************/
function cntFromPost() {
	// construir el hash con los datos del contacto a partir del formulario enviado vía POST
	$cnt= array();
	$cnt[codigo]=		0; 		// se obtiene del GET o de mysql_insert_id()
	$cnt[nombre]= 		$_POST[nombre]; 
	$cnt[apellido]= 	$_POST[apellido]; 
	$cnt[referencia]= 	$_POST[referencia]; 
	$cnt[fonos]=		$_POST[fonos]; 
	$cnt[emails]=		$_POST[emails]; 
	return $cnt;
	

}

/*************************************************************************************************/
function cntListLine( $row, $why = '', $padre = 0, $rel = '' ) {
/*
	$row es una fila en el resultado de una consulta SQL
	
	$why me indica el uso: 
		- 'list' o nada (default) para listado por pantalla
		- 'imp' para imprimir
		- 'dep_list' para lista de dependencias en pantalla de edición de un contacto 
		- 'dep_add' para elegir un contacto y crear una dependencia
		
	$padre y $rel sólo se pasan si lo que se está listando son dependencias
*/  
	
	$data= ''; 

	$codigo= $row['codigo']; 
	$nombre= $row['nombre']; 
	$nomap= $row['nomap'];	
	$apellido= $row['apellido']; 
	$referencia= $row['referencia']; 
	$fonos= cntReadFonos( $codigo ); 
	$fonolist= " "; 
	foreach($fonos as $f):
		if(!empty($f[dsc])) {$fonolist .= $f[dsc] . ": "; }
		$fonolist .= $f[fono] . ". "; 
	endforeach; 		
	$emails= cntReadEmails($codigo); 
	$emaillist= " ";
	foreach($emails as $e):
		// OJO: con los links que no sean emails, esto va a fallar
		$emaillist .= a( "mailto:$e[email]", $e[email] ) . ". "; 
	endforeach; 		
	$deps= cntReadDeps($codigo); 
	$deplist= " "; 
	foreach($deps as $d):
		// OJO: con los links que no sean emails, esto va a fallar
		$depdata= cntRead( $d[hijo] ); 
		$deplist .= font( '', -1, "$d[rel]: " . nomap( $depdata[nombre], $depdata[apellido] ) . ". " );
	endforeach; 		
	
	$cntDelLink=  a( "contactos.php?cmd=delete&codigo=$codigo",       img("delete.gif") );
	$cntEditLink= a( "contactos.php?cmd=edit&codigo=$codigo",         img("edit.gif") );
	$cntViewLink= a( "contactos.php?cmd=show&codigo=$codigo",         img("info.gif"), 'target="_blank"' ); 
	$depAddLink=  a( "dep.php?cmd=set_rel&padre=$padre&hijo=$codigo", "[ + ]"); 
	$depDelLink=  a( "dep.php?cmd=delete&padre=$padre&hijo=$codigo",  img("unlink.gif") );
	$depRelLink=  a( "dep.php?cmd=set_rel&padre=$padre&hijo=$codigo",  "[ _ ]");
	
	// TODO: estilos para entrada y referencia en lugar de este ugly FONT
	switch ( $why ) {
		case '': case 'list': 
			$tools= $cntDelLink . " " . $cntEditLink . " " . $cntViewLink . b(" $codigo. ");
			$data= b( h( nomap($nombre, $apellido) ) ) . ". " . font('Tahoma', -1, $referencia) . $fonolist . i( $emaillist );
			break; 
		case 'imp': 
			$tools= ''; 
			$data= b( h( nomap($nombre, $apellido) ) ) . ". " . font('Tahoma', -1, $referencia) . $fonolist . i( $emaillist );
			break; 
		case 'dep_list': 
			// TO DO: listar los contactos con sus relaciones y poner links para borrar y editar
			$tools= $depDelLink . ' ' . $depRelLink . ' ';
			$data= "$rel: " . b( "$codigo. " . h( nomap($nombre, $apellido) ) ) . '. ' . $fonolist . i( $emaillist );
			break; 
		case 'dep_add': 
			$tools= $depAddLink . ' ';
			$data= b( "$padre -> $codigo. " ) . h( nomap( $nombre, $apellido) ) . ". $fonolist " . i( $emaillist );
			break;
		}

	return p( $tools . $data . $deplist); 
}

/*************************************************************************************************/
function cntRead( $cod ) {
	// leer datos básicos del contacto 
	$res= mysql_query("SELECT * FROM contactos WHERE codigo = $cod"); 
	$cnt= mysql_fetch_assoc($res); 

	// leer teléfonos y agregarlos al array
	$cnt[fonos]= cntReadFonos($cod); 

	// leer emails y agregarlos al array
	$cnt[emails]= cntReadEmails($cod);

	// leer dependencias y agregarlas al array
	$cnt[deps]= cntReadDeps($cod);
	
	return $cnt;
}

/*************************************************************************************************/
function cntReadFonos( $cod ) {
	$fonos= array(); 
	$res= mysql_query("SELECT * FROM fonos WHERE cnt = $cod"); 
	while ($fono = mysql_fetch_assoc($res)) { array_push($fonos, $fono); }
	return $fonos; 
}

/*************************************************************************************************/
function cntReadEmails( $cod ) {
	$ems= array(); 
	$res= mysql_query("SELECT * FROM email WHERE cnt = $cod"); 
	while ($em = mysql_fetch_assoc($res)) { array_push($ems, $em); }
	return $ems; 
}

/*************************************************************************************************/
function cntReadDeps( $cod ) {
	$deps= array(); 
	$res= mysql_query("SELECT * FROM dep WHERE padre = $cod"); 
	while ($dep = mysql_fetch_assoc($res)) { array_push($deps, $dep); }
	return $deps; 
}

/*************************************************************************************************/
function cntWrite( $cnt, $new= false ) {
	if ( $new ) {
		// escribir contacto nuevo (SQL INSERT) 
		connect(); 
		mysql_select_db("bit_agenda"); 
		$result= mysql_query("INSERT INTO contactos(nombre,apellido,nomap,referencia,editado,dirty) 
				VALUES ('$cnt[nombre]', '$cnt[apellido]', '$cnt[nomap]', '$cnt[referencia]','$cnt[$editado]','-1')"); 
		$codigo= mysql_insert_id(); 		// código del nuevo contacto insertado 
		$result= mysql_query("DELETE FROM fonos WHERE cnt= $codigo"); 
		foreach ( $cnt[fonos] as $f ): 
			if (!empty($f[fono])): 
				$qr="INSERT INTO fonos(cnt,fono,dsc) VALUES ('$codigo', '$f[fono]', '$f[dsc]')"; 
				mysql_query($qr); 			
			endif;		
		endforeach;		
		$result= mysql_query("DELETE FROM EMAIL WHERE cnt= $codigo"); 
		foreach ( $cnt[emails] as $f ): 
			if (!empty($f[email])): 
				// TODO: dropdown para tipos de direcciones electrónicas que no sean EMAIL 
				$qr="INSERT INTO email(cnt,tipo,email) VALUES ('$codigo', 'EMAIL', '$f[email]')"; 
				mysql_query($qr); 			
			endif;		
		endforeach;	
	} else {
		// actualizar contacto existente (SQL UPDATE) 
		
	}
}

?>