<? 

/*************************************************************************************************/
function checkTables() {
if ( ! habemusTabla("contactos") ): 
	mysql_query( "CREATE TABLE contactos (
				  codigo INT(6) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
				  nombre VARCHAR(50), 
				  apellido VARCHAR(30), 
				  nomap VARCHAR(82) NOT NULL,  
				  referencia VARCHAR(255), 
				  fechanac DATE, 
				  compartir BOOL, 
				  root BOOL
				  )" );
endif;

if ( ! habemusTabla("dep") ):
	mysql_query( "CREATE TABLE dep (
				  padre INT(6) NOT NULL, 
				  hijo INT(6) NOT NULL, 
				  rel VARCHAR(30)
				  )" ); 
endif; 

if ( ! habemusTabla("fonos") ):
	mysql_query( "CREATE TABLE fonos (
				  cnt INT(6) NOT NULL, 
				  fono VARCHAR(30) NOT NULL, 
				  dsc VARCHAR(30)
				  )" ); 
endif; 

if ( ! habemusTabla("email") ):
	mysql_query( "CREATE TABLE email (
				  cnt INT(6) NOT NULL, 
				  email VARCHAR(30) NOT NULL
				  )" ); 
endif; 

if ( ! habemusTabla("dirs") ):
	mysql_query( "CREATE TABLE dirs (
				  cnt INT(6) NOT NULL, 
				  dir VARCHAR(255) NOT NULL,
				  CP VARCHAR(10), 
				  provincia VARCHAR(30) NOT NULL, 
				  pais VARCHAR(30) NOT NULL
				  )" ); 
endif; 

if ( ! habemusTabla("provincias") ):
	mysql_query( "CREATE TABLE provincias (
				  provincia VARCHAR(30) NOT NULL
				  )" ); 
	$provincias= array( "Buenos Aires", "Capital Federal", "Catamarca", "Chaco", "Chubut", 
					    "Córdoba", "Corrientes", "Entre Ríos", "Formosa", 
						"Jujuy", "La Pampa", "La Rioja", "Mendoza", "Misiones", 
						"Neuquén", "Río Negro", "Salta", "San Juan", 
						"San Luis", "Santa Cruz", "Santa Fe", "Santiago del Estero",
						"Tierra del Fuego", "Tucumán"
				  ); 
	for($i= 0; $i < count($provincias); $i++) {
		$qr= "INSERT INTO provincias(provincia) VALUES('".$provincias[$i]."')"; 
		mysql_query($qr); 
	}
endif; 

if ( ! habemusTabla("paises") ):
	mysql_query( "CREATE TABLE paises (
				  pais VARCHAR(30) NOT NULL
				  )" ); 
	$paises= array( "Argentina", "España", "Estados Unidos", "Grecia", "Inglaterra" ); 
	for($i= 0; $i < count($paises); $i++) {
		$qr= "INSERT INTO paises(pais) VALUES('".$paises[$i]."')"; 
		mysql_query($qr); 
	}
endif; 

}

?>