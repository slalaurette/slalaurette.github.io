<?

/*************************************************************************************************/
function depAdd( $cnt, $dep, $rel ) {
	// $cnt es el contacto padre del que dependerá $dep 
	// $rel es la cadena que aparecerá en los listados vinculando a ambos
	$qr= "INSERT INTO dep (padre, hijo, rel) VALUES ('$cnt', '$dep', '$rel')";
	mysql_query($qr); 
}

/*************************************************************************************************/
function depDel( $cnt, $dep ) {
	// $cnt es el contacto padre del que depende $dep 
	$qr= "DELETE FROM dep WHERE padre = $cnt AND hijo = $dep";
	mysql_query($qr); 
}

/*************************************************************************************************/
function depList( $padre ) {
/*
	$addform= ajaxform( array( name => 'dep_search', method => 'POST', action => "dep.php?cmd=search&cnt=$padre" ), 
					input( 'submit', 'submit', 'Agregar dependencia.', 'class="boton selected"')
				);
	array_push( $page, $addform ); 
*/
	
	$deplist= ''; 
	$cnt= cntRead( $padre );
	foreach( $cnt[deps] as $d ): 
		$rel=           $d[ rel    ]; 
		$hijo= cntRead( $d[ hijo   ] ); 
		$cod=        $hijo[ codigo ]; 
		$nomap=      $hijo[ nomap  ]; 
		$deplist .= cntListLine( $hijo, 'dep_list', $padre, $rel ); //p( "$rel: $cod. $nomap" );
	endforeach; 
	return $deplist; 

}

?>
