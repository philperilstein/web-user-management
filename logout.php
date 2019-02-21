<?php
include("SessionQuery.php");       //Include MySQL database class
include("Session.php"); 		//Include PHP MySQL sessions
$session = new Session();       //Start a new PHP MySQL session
session_destroy();
echo "Logged out";
?>