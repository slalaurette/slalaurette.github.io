<? 
# session_start(); 
require_once "func.php"; 
require_once("arrays.php"); 

/*
checkLogged(); 
connect();
*/

# P: protagonistas; A: antagonistas; E: escenarios; T: tramas
$lista= $_GET[l]; 

if ( $lista == 'P' ): $n= $aP; $d= $aPD; $c= count($aP); $s= 'PROTAGONISTAS'; endif; 
if ( $lista == 'A' ): $n= $aA; $d= $aAD; $c= count($aA); $s= 'ANTAGONISTAS' ; endif; 
if ( $lista == 'E' ): $n= $aE; $d= $aED; $c= count($aE); $s= 'ESCENARIOS'   ; endif; 
if ( $lista == 'T' ): $n= $aT; $d= $aTD; $c= count($aT); $s= 'TRAMAS'       ; endif; 

echo html_open(), head( title("Listado"), css("style.css") ), body_open(); 
#printMessages();

# echo p( a( 'index.php', '&lt;-- ' ) ); 

print h3( $s );

for ( $x= 0; $x < count($n); $x++ ){
	print p( "listitem", 
		span( "tiny", $x+1      . ', '  ) .
		span( "tiny", $x+1+$c   . ', '  ) .
		span( "tiny", $x+1+2*$c . '. '  ) .
#		span( "tiny", $x+1+3*$c . ', '  ) .
#		span( "tiny", $x+1+4*$c . '. '  ) .
		b( $n[$x]     . '. ' ) . 
		$d[$x] ); 	
}

echo body_close(), html_close(); 

?>