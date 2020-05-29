<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
<title>Agenda Personal</title>
<link href="style.css" rel="stylesheet" type="text/css">
<link rel="shortcut icon" href="favicon.gif">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>

<? 
	# AGENDA PERSONAL
	#
	# v0.7 -- 20120821
	# 	- dirty (para imprimir contactos nuevos y modificados) 
	# 	- emails 
	# 	- fecha de edición (para evitar el "as of") 
	# v0.3 -- 20111103
	# 	- mejoras en estilo visual
	# 	- hoja de impresión en pestaña aparte 
	# v0.2 -- 20111026
	# 	- filtrado por letra inicial en listados 


	# TO DO 
	# 
	# - dependencias 
	# - búsquedas 
	# - control de duplicados
	# - JavaScript para añadir y ocultar campos (fonos, emails, detalles de dependencias)
	# - hotkeys
	# - skins (diferentes CSS)
	# - página de opciones

require_once "func.php"; 

	// parámetro PAGE: para ir a una página determinada después del login	
	if ($_GET[page] == NULL) 
		{ $redir= "checkLogin"; }
	else
		{ $redir= $_GET["page"]; }

print h1("Agenda Personal."); 

printMessages(); 

?>

<h4>Entr&aacute; con tu nombre de usuario y contrase&ntilde;a.</h4>

<form action="<? print $redir; ?>.php" method="post">
<table width="200" border="0" cellspacing="10">
  <tr>
    <td><div class="label">Usuario.</div></td>
    <td><input name="username" type="text"></td>
  </tr>
  <tr>
    <td><div class="label">Clave.</div></td>
    <td><input name="password" type="password"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input name="submit" type="submit" value="Entrar."></td>
  </tr>
</table>
</form>

</body>
</html>