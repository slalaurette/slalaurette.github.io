#!./rebol -c
REBOL [Title: "Server Time"]
print "content-type: text/html^/"
print [<HTML><BODY>]
print ["Date/time is:" now]
print [</BODY></HTML>]