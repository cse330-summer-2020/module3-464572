<!-- 
    Require in other files to connect to a specified MySQL Database.
    The module3-user has all DATA privileges.
 -->
<?php
$mysqli = new mysqli('localhost', 'module3-user', 'module3-password', 'module3');

if($mysqli->connect_errno) {
	printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
}
?>