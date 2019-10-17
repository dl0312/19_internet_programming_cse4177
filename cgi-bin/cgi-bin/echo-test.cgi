#!/bin/sh
echo Content-type: text/html
echo
echo "<HTML><BODY>"
echo "<H1>Just Echo Twices </H1>"
echo "$QUERY_STRING \n"
echo "$QUERY_STRING"
echo "</BODY></HTML>"
