 <?php 
ini_set("session.cookie_httponly", 1);

$mysqli = new mysqli('localhost', 'mod5admin', 'mod5admin', 'calendar');

if($mysqli->connect_errno) {
	printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
} 

session_start();
$postrequestdata = json_decode(file_get_contents('php://input'), true);
$username = htmlentities($_SESSION['username']);
$eventId = htmlentities($postrequestdata['eventId']);
$eventId = htmlentities((int) $eventId);


// if($_SESSION['token'] !== $postrequestdata['token']){
// 	die("Request forgery detected");
//testtests
// }

$stmt = $mysqli->prepare("DELETE FROM events WHERE username=? AND id=?");
if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
$stmt->bind_param('si', $username, $eventId);
$stmt->execute();
$stmt->close();

echo json_encode(array(
		"success" => true,
		"username" => $username,
		"eventId" => $eventId
));
exit;

?>