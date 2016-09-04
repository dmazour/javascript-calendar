<?php
ini_set("session.cookie_httponly", 1);

//WHERE SHOULD THIS POINT?
$mysqli = new mysqli('localhost', 'mod5admin', 'mod5admin', 'calendar');
 
if($mysqli->connect_errno) {
	printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
} 

session_start();
$postrequestdata = json_decode(file_get_contents('php://input'), true);
$username = htmlentities($_SESSION['username']);
$month = htmlentities($postrequestdata["month"]);
$year = htmlentities($postrequestdata["year"]);
$username = htmlentities((string) $username);
$month = htmlentities((string) $month);
$year = htmlentities((string) $year);

if( !preg_match('/^[\w_\.\-\S]+$/', $username) ){
echo json_encode(array(
"success" => false,
"message" => "Invalid username!"
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


$getAllReturnedData = "";
$retrunedArray = array();

$stmt = $mysqli->prepare("SELECT username, title, time, day, id, category from events where username=? AND month=? AND year=?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
$stmt->bind_param('sss', $username, $month, $year); 
$stmt->execute();
$stmt->bind_result($GotUsername, $GotTitle, $GotTime, $GotDay, $GotId, $GotCategory);
while($stmt->fetch()){
	// printf("\t<h3>%s</h3>\n",
	// 	htmlspecialchars($title)
	// );
	$getAllReturnedData .= $GotTitle;
	// $putResponseinArray = "[" . $GotTime . "," . $GotTitle . "," . $GotDay . "]";
	$eventEntry = $GotTitle . "," . $GotDay . "," . $GotTime . "," . $GotId . "," . $GotUsername . "," . $GotCategory;
	array_push($retrunedArray, $eventEntry);
}
$stmt->close();

	echo json_encode(array(
		"success" => true,
		"username" => $GotUsername,
		"allEvents" => $retrunedArray,
		"category" => $GotCategory
	));
	exit;


?>











