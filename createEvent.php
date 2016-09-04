<?php

ini_set("session.cookie_httponly", 1);

$mysqli = new mysqli('localhost', 'mod5admin', 'mod5admin', 'calendar');

if($mysqli->connect_errno) {
	printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
} 

session_start();
$postrequestdata = json_decode(file_get_contents('php://input'), true);
$username = $_SESSION['username'];
$title = $postrequestdata["title"];
$time = $postrequestdata["time"];
$day = $postrequestdata["day"];
$month = $postrequestdata["month"];
$year = $postrequestdata["year"];
$sharedWith = $postrequestdata["sharedWith"];
$category = $postrequestdata["eventCategory"];

$username = htmlentities((string) $username);
$title = htmlentities((string) $title);
$time = htmlentities((string) $time);
$day = htmlentities((string) $day);
$month = htmlentities((string) $month);
$year = htmlentities((string) $year);
$sharedWith = htmlentities((string) $sharedWith);
$category = htmlentities((string) $category);

// if($_SESSION['token'] !== $postrequestdata['token']){
// 	echo json_encode(array(
// 		"success" => false,
// 		"message" => "Forgery Detected!"
// 		));
// 	exit;
// }

if( !preg_match('/^[\w_\.\-\S]+$/', $username) ){
	echo json_encode(array(
		"success" => false,
		"message" => "Invalid username!"
		));
	exit;
}
if( !preg_match('/^[\w_\.\-]+$/', $title) ){
	echo json_encode(array(
		"success" => false,
		"message" => "Invalid title!"
		));
	exit;
}
if( !preg_match('/^[\w_\.\-\S]+$/', $time) ){
	echo json_encode(array(
		"success" => false,
		"message" => "Invalid time!"
		));
	exit;
}
if( !preg_match('/^[\w_\.\-\S]+$/', $day) ){
	echo json_encode(array(
		"success" => false,
		"message" => "Invalid day!"
		));
	exit;
}
if( !preg_match('/^[\w_\.\-\S]+$/', $month) ){
	echo json_encode(array(
		"success" => false,
		"message" => "Invalid month!"
		));
	exit;
}
if( !preg_match('/^[\w_\.\-\S]+$/', $year) ){
	echo json_encode(array(
		"success" => false,
		"message" => "Invalid year!"
		));
	exit;
}
if (strlen($sharedWith) !== 0){
	if( !preg_match('/^[\w_\.\-\S]+$/', $sharedWith) ){
		echo json_encode(array(
			"success" => false,
			"message" => "Invalid username to share with!"
			));
		exit;
	}
}
if (strlen($category) !== 0){
	if( !preg_match('/^[\w_\.\-\S]+$/', $category) ){
		echo json_encode(array(
			"success" => false,
			"message" => "Invalid category - no periods,dashes,spaces allowed!"
			));
		exit;
	}
}


if (is_null($username)|| is_null($title) || is_null($time) || is_null($day) || is_null($month) || is_null($year)){
	echo json_encode(array(
		"success" => false,
		"message" => "Null Areas."
		));
	exit;
}



$stmt = $mysqli->prepare("insert into events (username, title, time, day, month, year, sharedWith, category) values (?, ?, ?, ?, ?, ?, ?, ?)");
if(!$stmt){
	printf("Query Prep Failed(2): %s\n", $mysqli->error);
	exit;
}
$stmt->bind_param('ssssssss', $username, $title, $time, $day ,$month, $year, $sharedWith, $category);
$stmt->execute();
$stmt->close();

echo json_encode(array(
	"success" => true,
	"username" => $username,
	"title" => $title,
	"time" => $time,
	"day" => $day,
	"month" => $month,
	"year" => $year,
	"sharedWith" => $sharedWith,
	"category" => $category,
	));
exit;

?>