<? 
session_start(); 
require "func.php"; 
require "tablas.php"; 
checkLogged(); 
connect();
checkTables(); 

/* no funca */ 
$s= '
<script src="shortcut.js" type="text/javascript"></script>
<script type="text/javascript">
function init() {
	shortcut.add("Shift+F1",function() {
		alert("Help Me!");
	});
	shortcut.add("Ctrl+S",function() {
		alert("Saved!");
	});
	shortcut.add("Right",function() {
		alert("Right");
	});
}
addEvent(window,"load",init);
</script>
';


print html( 
			head( 
			      title("Agenda.")
				, css("style.css")
				, javascript($s) 
				), 
			body( 
			      //menu_main(),
			      botonera_main()
				, messages()
				, div( array( 'id' => 'test1', 'style' => 'height:100; overflow:none' ), '...' )
				, div( array( 'id' => 'test2', 'style' => 'height:100; overflow:none' ), '...' )
/*
				, javascript( '
					$.get( "listados.php", function( response ) {
					//document.write( response ); // server response
					$("#test2").html(response) ; 
					});')
*/
				, ajaxform( array( method => 'GET', action => 'listados.php', name => 'test' ), 
				            input( 'submit', 'submit', '[ TEST ]' ) )
			    ) 
		  ); 

?>
