<?php

ini_set("session.cookie_httponly", 1);

$mysqli = new mysqli('localhost', 'mod5admin', 'mod5admin', 'calendar');

if($mysqli->connect_errno) {
	printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
} 

session_start();
$postrequestdata = json_decode(file_get_contents('php://input'), true);
$username = htmlentities($postrequestdata["desiredusername"]);
$password = htmlentities($postrequestdata["desiredpassword"]);
$username = (string) $username;
$password = (string) $password;

// Make sure the username + password is valid (regex)
if( !preg_match('/^[\w_\.\-]+$/', $username) ){
	echo json_encode(array(
		"success" => false,
		"message" => "Invalid username!"
		));
	exit;
}
if( !preg_match('/^[\w_\.\-]+$/', $password) ){
	echo json_encode(array(
		"success" => false,
		"message" => "Invalid password!"
		));
	exit;
}

// Make sure the username isn't taken
$stmt = $mysqli->prepare("select username from users where username=?");


if(!$stmt){
	printf("Query Prep Failed(1): %s\n", $mysqli->error);
	exit;
}
$stmt->bind_param('s', $username);

$stmt->execute();

$stmt->bind_result($result);
$stmt->fetch();


if (is_null($result)){
	// sign-in the user
	$_SESSION['username'] = $username;
	$_SESSION['token'] = substr(md5(rand()), 0, 10); // generate a 10-character random string

	//Add that to the users query
	$stmt = $mysqli->prepare("insert into users (username, password) values (?, ?)");
	if(!$stmt){
		printf("Query Prep Failed(2): %s\n", $mysqli->error);
		exit;
	}
	$cryptPwd = crypt($password);
	$stmt->bind_param('ss', $username, $cryptPwd);
	$stmt->execute();
	$stmt->close();

	echo json_encode(array(
		"success" => true,
		"username" => $username,
		"password" => $password
		));
	exit;
} else {
	echo json_encode(array(
		"success" => false,
		"message" => "Taken Username or Password"
		));
	exit;
}

?>