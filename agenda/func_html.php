<?

/*************************************************************************************************/
function a( $link, $text= "", $parms= "") {
	// ATENCIÓN: el primer parámetro es el link; el segundo, si existe, el texto a mostrar, 
	// y si no existe, se muestra el propio link; el tercero es un array de parámetros 
	// (por ejemplo, TARGET) 

	$attr = attr($parms); 
	if (empty($text)): 
		return "<a href=\"$link\"$attr>$link</a>"; 
	else:
		return "<a href=\"$link\"$attr>$text</a>"; 
	endif;
}

/*************************************************************************************************/
function ajaxform() {		// (requiere librería HTML_AJAX) 

// en este caso, el primer argumento tiene que ser sí o sí un hash, e incluir al menos los parámetros 
//     id, method y action, que se usan tanto en el código Javascript como en el HTML
	$args= func_get_args(); 
	$parms= $args[0]; 

// le sumo el código AJAX 
	$p= attr( $parms ) . ' onSubmit="HTML_AJAX.formSubmit(this); return false; "'; 
	$content= implode("\n", array_slice($args, 1)); 
/*
	$j= '$.' . strtolower( $parms[method] ) . '( ' . dblq( $parms[action] ) . ', function( response ) { 
	    $(#' . dblq( $parms[id] ) . ').html(response); });';
	return javascript( $j ) . div( array( id => $parms[id] ), form( $p, $content ) ); 
*/
return form( $p, $content ); 

}

/*************************************************************************************************/
function attr($p) {
// convierte una serie de parámetros en un hash del tipo {size => 50, id => name, onclick => "..."}
// a una cadena del tipo ' size="50" id="name" onclick="..."' para su uso como serie de atributos HTML, 
// o también acepta una cadena y la devuelve con un espacio delante
	$s= ""; 
	if (empty($p)): 
		return ""; 
	elseif (is_array($p)): 
		foreach ($p as $parm => $val):
			if ( $parm == 'klass' ) { $parm= 'class'; } 
			$s .= " $parm=" . dblq($val); 
		endforeach;
	else: 
		$s .= " $p"; 
	endif;

return $s; 
}

/*************************************************************************************************/
function b($text) {
	return "<strong>$text</strong>";
}

/*************************************************************************************************/
function body() {
	$args= func_get_args();
	$text= implode("", $args); 
	return body_open() . $text . body_close(); 
}

/*************************************************************************************************/
function body_open() {
	return "<body>\n"; 
}

/*************************************************************************************************/
function body_close() {
	return "\n</body>"; 
}

/*************************************************************************************************/
function css() {
	$stylesheet= func_get_arg(0); 
	if (func_num_args() > 1):
		// el conteo de parámetros tiene base 0 
		$media= func_get_arg(1); 
	else:
		$media= ""; 
	endif;
	return "<link href=\"$stylesheet\" rel=\"stylesheet\" " . ($media ? "media=\"$media\" " : "" ). "type=\"text/css\">\n"; 
}

/*************************************************************************************************/
function div($attr, $content) {
	$args= func_get_args(); 
	$parms= attr($args[0]); 
	$content= implode("\n", array_slice($args, 1)); 
	return "<div " . attr($parms) . ">\n$content\n</div>\n"; 
}

/*************************************************************************************************/
function fieldset () {
	$args= func_get_args();
	$text= implode("\n", $args);
	return "<fieldset>\n$text\n</fieldset>\n"; 
}

/*************************************************************************************************/
function font ( $face, $size, $text ) {
	return '<font ' 
		. ( $face ? "face=\"$face\"" : ' ' )
		. ' ' 
		. ( $size ? "size=$size" : ' ' )
		. '>' 
		. $text 
		. '</font>'; 
}

/*************************************************************************************************/
function form () {
	// ATENCIÓN: el primer argumento es el array de parámetros 
	// y el resto el contenido del formulario 
	$args= func_get_args(); 
	$parms= attr($args[0]); 
	$content= implode("\n", array_slice($args, 1)); 
	return "<form $parms>\n$content\n</form>\n"; 
	
} 

/*************************************************************************************************/
function h($text) {
	return htmlentities($text); 
}

/*************************************************************************************************/
function h1($text) { return "\n<h1>$text</h1>\n"; }
function h2($text) { return "\n<h2>$text</h2>\n"; }
function h3($text) { return "\n<h3>$text</h3>\n"; }
function h4($text) { return "\n<h4>$text</h4>\n"; }
function h5($text) { return "\n<h5>$text</h5>\n"; }
function h6($text) { return "\n<h6>$text</h6>\n"; }

/*************************************************************************************************/
function head() {
	$args= func_get_args();
	$text= implode("\n", $args);
	$contenttype= '<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">';
	$favicon= '<link rel="shortcut icon" href="favicon.gif">'; 
	$jQuery=  js( 'jquery.js' ); 
	$HTML_AJAX= js( 'server.php?client=all' ); 
	return "<head>\n$contenttype\n$favicon\n$jQuery\n$HTML_AJAX\n$text\n</head>\n\n"; 
}

/*************************************************************************************************/
function html() {
	$args= func_get_args();
	$text= implode("\n", $args);
	return html_open() . $text . html_close();
}

/*************************************************************************************************/
function html_open() {
	$doctype= '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">';
	return "$doctype\n<html>\n"; 
}

/*************************************************************************************************/
function html_close() {
	return "\n</html>"; 
}

/*************************************************************************************************/
function i($text) {
	return "<em>$text</em>";
}

/*************************************************************************************************/
function iframe( $name, $src, $params ) {
	$attr= attr( $params ); 
	return "<iframe name=\"$name\" src=\"$src\" $attr></iframe>"; 
}

/*************************************************************************************************/
function img($img) {
	return "<img src=\"$img\" />"; 
}

/*************************************************************************************************/
function input($type, $name, $value, $parms= "") {
// $parms puede ser una cadena, del tipo ' size="50" id="name" onclick="..."', 
// o un hash, del tipo {size => 50, id => name, onclick => "..."}
	$code= "<input type=\"$type\" name=\"$name\" value=\"$value\""; 
	$code .= attr($parms); 
	$code .= ">"; 
	return $code; 
}

/*************************************************************************************************/
function javascript() {
// para incluir código directamente en la página; usar la función js() para vincular 
//      un archivo .js externo

	$args= func_get_args();
	$code= implode("\n", $args);
	return "<script type=\"text/javascript\">\n$code\n</script>"; 
}

/*************************************************************************************************/
function js( $src ) {
// para vincular un archivo .js externo; usar la función javascript() para incluir código 
//      directamente en la página 

return  "<script type=\"text/javascript\" src=\"$src\"></script>\n";
}

/*************************************************************************************************/
function legend($leg) {
	return "<legend>" . h($leg) . "</legend>"; 
}

/*************************************************************************************************/
function p() {
	// ATENCIÓN: si hay dos parámetros, el primero es la clase CSS y el segundo es el texto; 
	// si hay uno solo, es el texto, ¿estamos? 
	if (func_num_args() > 1):
		// el conteo de parámetros tiene base 0 
		$clase= func_get_arg(0);
		$text= func_get_arg(1); 
		return "<p class=\"$clase\">$text</p>\n"; 
	else:
		$text= func_get_arg(0);
		return "<p>$text</p>\n"; 
	endif;
}

/*************************************************************************************************/
function pre($t) {
	return "<pre>$t</pre>"; 
}

/*************************************************************************************************/
function span() {
	// ATENCIÓN: si hay dos parámetros, el primero es la clase CSS y el segundo es el texto; 
	// si hay uno solo, es el texto, ¿estamos? 
	if (func_num_args() > 1):
		// el conteo de parámetros tiene base 0 
		$clase= func_get_arg(0);
		$text= h(func_get_arg(1)); 
		return "<span class=\"$clase\">$text</span>\n"; 
	else:
		$text= h(func_get_arg(0));
		return "<span>$text</span>\n"; 
	endif;
}

/*************************************************************************************************/
function table () {
	// ATENCIÓN: el primer argumento son los parámetros de la tabla, 
	// el resto su contenido 
	$args= func_get_args(); 
	$parms= $args[0]; 
	$content= implode("\n", array_slice($args, 1)); 
	return "<table $parms>\n$content\n</table>\n"; 
	
} 

/*************************************************************************************************/
function td () {
	$args= func_get_args();
	$content= implode("", $args); 
	return "<td>\n$content\n</td>\n"; 
} 

/*************************************************************************************************/
function textarea($name, $value, $parms= "") {
// $parms puede ser una cadena, del tipo ' size="50" id="name" onclick="..."', 
// o un hash, del tipo {size => 50, id => name, onclick => "..."}
	$code= "<textarea name=\"$name\""; 
	$code .= attr($parms); 
	$code .= ">$value</textarea>"; 
	return $code; 
}

/*************************************************************************************************/
function th () {
	$args= func_get_args();
	$content= implode("", $args); 
	return "<th>\n$content\n</th>\n"; 
} 

/*************************************************************************************************/
function tr() {
	$args= func_get_args();
	$content= implode("\n", $args); 
	return "<tr>\n$content\n</tr>"; 
}

/*************************************************************************************************/
function title($text) {
	return "<title>$text</title>"; 
}

?>