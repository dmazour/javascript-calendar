<?php 


$postrequestdata = json_decode(file_get_contents('php://input'), true);
$username = $postrequestdata["desiredusername"];
$password = $postrequestdata["desiredpassword"];

echo json_encode(array(
		"success" => true,
		"username" => $username,
		"password" => $password
	));
	exit;

?>