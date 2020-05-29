<?php 

$ver= "ElCuraLoco"; 
/* IDEARIO -- 20120426 

	TODO: 
	- guardar los datos en la DB en vez de arrays
	- interfaz de edición 
	- login para editar (no para usar)
	- estilos visuales
	- info sobre posición y cantidades
*/

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" href="style.css" />
<base target="_blank" />

<title>Ideario</title>
</head>

<?php

require_once("func.php"); 
require_once("arrays.php"); 

// elegimos al azar un protagonista, un antagonista, un escenario y una trama 
// los arrays tienen base 0, pero count() devuelve la cantidad real, por eso los -1
$p= rand(0,count($aP)-1);
$a= rand(0,count($aA)-1);
$e= rand(0,count($aE)-1);
$t= rand(0,count($aT)-1);

print p('tiny', count($aP) . ' P x ' . count($aA) . ' A x ' . count($aE) . ' E x ' . count($aT) . " T = " .
	count($aP)*count($aA)*count($aE)*count($aT) . " historias posibles."); 

print div(array('class' => 'box prot'), 
	p('chapa', a('listados.php?l=P','PROTAGONISTA')) . p('item', $aP[$p]) . p('desc', $aPD[$p])); 
print div(array('class' => 'box ant'), 
	p('chapa', a('listados.php?l=A','ANTAGONISTA'))  . p('item', $aA[$a]) . p('desc', $aAD[$a])); 
print div(array('class' => 'box esc'), 
	p('chapa', a('listados.php?l=E','ESCENARIO'))    . p('item', $aE[$e]) . p('desc', $aED[$e])); 
print div(array('class' => 'box tr'), 
	p('chapa', a('listados.php?l=T','TRAMA'))        . p('item', $aT[$t]) . p('desc', $aTD[$t])); 

?>

<body>
</body>
</html>
