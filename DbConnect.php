<?php

	require_once 'constants.php';

	//creating a new connection object using mysqli 
	$conn = new mysqli(SERVER_NAME, SERVER_USER_NAME, SERVER_USER_PASSWORD, DATABASE);
	 
	//if there is some error connecting to the database
	//with die we will stop the further execution by displaying a message causing the error 
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

?>