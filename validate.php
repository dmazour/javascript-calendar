<?php

ini_set("session.cookie_httponly", 1);

$mysqli = new mysqli('localhost', 'mod5admin', 'mod5admin', 'calendar');
 
if($mysqli->connect_errno) {
	printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
} 

session_start();
$postrequestdata = json_decode(file_get_contents('php://input'), true);
$username = htmlentities($postrequestdata["username"]);
$password = htmlentities($postrequestdata["password"]);
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

// $stmt = $mysqli->prepare("select username from users where username=?");
$stmt = $mysqli->prepare("SELECT COUNT(*), username, password from users where username=?");
if(!$stmt){
	printf("Query Prep Failed(1): %s\n", $mysqli->error);
	exit;
}
$stmt->bind_param('s', $username);
$stmt->execute();
$stmt->bind_result($count, $returnUsr, $returnPwd);
$stmt->fetch();
$stmt->close();

//CHANGE THIS:
// $stmt = $mysqli->prepare("select title, storyText, link from stories order by link");
// if(!$stmt){
// 	printf("Query Prep Failed: %s\n", $mysqli->error);
// 	exit;
// }
 
// $stmt->execute();
// $stmt->bind_result($title, $storyText, $link);
// while($stmt->fetch()){
// 	printf("\t<h3>%s</h3>\n",
// 		htmlspecialchars($title)
// 	);
// 	echo "<form method=\"get\" action=\"viewfile.php\"><p><input type=\"hidden\" name=\"link\" value=\"$link\"><input type=\"submit\" value=\"Read\"></p></form>";
// }
// $stmt->close();

if ($count == 1 && crypt($password, $returnPwd)==$returnPwd){
	$_SESSION['username'] = $username;
	$_SESSION['token'] = substr(md5(rand()), 0, 10); // generate a 10-character random string
	
	//SHOULD I PUT THIS TO calendar.html??
	echo json_encode(array(
		"success" => true,
		"username" => $username,
		"password" => $password
	));
	exit;
}else{
	echo json_encode(array(
		"success" => false,
		"message" => "Taken Username or Password"
	));
	exit;
}

?>