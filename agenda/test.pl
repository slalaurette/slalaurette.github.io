#!/usr/bin/perl -wT

#use CGI; 
#use CGI::Ajax; 

# Put this code anywhere in your script.
BEGIN { $SIG{'__DIE__'} = sub { print <<__WARN__ and exit 1 } }
Content-Type: text/html; charset=ISO-8859-1\n
Fatal Error in @{[(caller(2))[1]||__FILE__]} at ${\ scalar localtime }
while responding to request from ${\ $ENV{'REMOTE_ADDR'} || 'localhost
+' }
${\ join("\n",$!,$@,@_) }
__WARN__



print "Hello World!";

 my $pjx = new CGI::Ajax(
    'exported_func' => \&perl_func,
    'skip_header'   => 1,
  );
  
#  $pjx->skip_header(1);
  
  #print $pjx->build_html( $cgi, \&Show_HTML);
  