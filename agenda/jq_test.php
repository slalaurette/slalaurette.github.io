<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Demo</title>
</head>
<body>
    <a href="http://jquery.com/">jQuery * jQuery * jQuery * jQuery * jQuery</a>
    <script src="jquery.js"></script>
    <script>
    $( document ).ready(function() {
 $( "a" ).click(function( event ){
    event.preventDefault();
    $( this ).hide( "slow" );
});    });
    </script>
</body>
</html>