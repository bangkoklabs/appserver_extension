# appserver_extension

Place files in /opt/appserver_extension/

Add
include_once("/opt/appserver_extension/index_extended.php");
to /opt/dpp-appserver/src/restapi/index.html before the last line
Flight::start();

Tada, enjoy extented restapi functions in index_extension.php
