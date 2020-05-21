<?

/*************************************************************************************************/
function cntEditForm( $add=0 ) {

global $codigo, $nombre, $apellido, $referencia, $editado, $fonos, $emails; 

if ( $add ): 
	$script= "contactos.php?cmd=add"; 
	$label= "Agregar contacto.";
else:
	$script= "contactos.php?cmd=update&codigo=$codigo"; 
	$label= "Guardar cambios."; 
endif; 

return form( array(action => $script, method => "POST"), 
	fieldset( legend(" Datos básicos. "), 
	table('width="400" border="0" cellspacing="10"', 
		tr( td( div( array('class' => "label"), "Nombre." ) ), 
			td( input("text", "nombre", h($nombre), 
						array(id => 'nombre', 'class' => 'datos', size => 20, onfocus => 'this.select()')) , 
			    input('text', 'apellido', h($apellido), 
						array('class' => 'datos', size => 20, onfocus => 'this.select') ) )
		), 
		tr( td( div( array('class' => "label"), "Referencia." ) ), 
//			td( input('text', 'referencia', h($referencia), array('class' => 'datos', size => 70, onfocus => "this.select") ) ) 		
			td( textarea('referencia', h($referencia), array('class' => 'datos', rows => 2, cols => 70, onfocus => "this.select") ) ) 		
		))), 
		
	fieldset( legend(" Fonos. "), cntEditFormFonos($fonos) ), 

	fieldset( legend(" Direcciones electrónicas. "), cntEditFormEmails($emails) ), 
	
	// TODO: estilo para esto
	p( "<em><font size=-1>Editado el $editado.</font></em>"), 
		
	fieldset( legend(" Acciones. "), 
		div(array('style' => 'float:right'), 
			input('submit', 'submit', $label, 'class="boton selected"')
		)		
)); 

}

/*************************************************************************************************/
function cntEditFormEmails($f) {
	$f[]= ""; 
	$s= ""; $c= sizeof($f); 
	for ($i= 0; $i < $c; $i++):
					// TODO: dropdown para tipos de direcciones electrónicas que no sean EMAIL 
			$s .= div( array('class' => 'fleft', style => 'margin:3px'), 
		          input("text", "emails[$i][email]", h($f[$i][email]), array('class' => "email", size => 30, onfocus => "this.select") ) 						); 
	endfor; 
//	return table("", $s);
	return $s;
}

/*************************************************************************************************/
function cntEditFormFonos($f) {
	$f[]= ""; $f[]= ""; 
	$s= ""; $c= sizeof($f); 
	for ($i= 0; $i < $c; $i++):
			$s .= div( array('class' => 'fleft', style => 'margin:3px'), 
		          input("text", "fonos[$i][fono]", h($f[$i][fono]), array('class' => "fono", size => 12, onfocus => "this.select") ) .  
		          input("text", "fonos[$i][dsc]", h($f[$i][dsc]), array('class' => "fondesc", size => 18, onfocus => "this.select") )
				); 
	endfor; 
//	return table("", $s);
	return $s;
}

/*************************************************************************************************/
function cntFromPost() {
	$cnt= array();
	$cnt[codigo]=		0; 		// se obtiene del GET o de mysql_insert_id()
	$cnt[nombre]= 		$_POST[nombre]; 
	$cnt[apellido]= 	$_POST[apellido]; 
	$cnt[referencia]= 	$_POST[referencia]; 
	$cnt[fonos]=		$_POST[fonos]; 
	$cnt[email]=		$_POST[email]; 
	

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

?>