<?php

require 'vendor/autoload.php';
use Slim\Slim;

$app = new \Slim\Slim();
$app->get('/users', 'getUsers');
$app->get('/users/:id', 'getUser');
$app->put('/users/:id', 'updateUser');
$app->delete('/users/:id', 'deleteUser');

$app->post('/add_user', 'addUser');
$app->get('/user', function() use ($app){
	$params = $app->request()->params('username');
	$sql = "select * FROM new_users WHERE email='$params'";
	try {
		$db = getConnection();
		$stmt = $db->query($sql);  
		$wines = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($wines);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
});

$app->run();

function addUser() {
	$request = Slim::getInstance()->request();
	$user = json_decode($request->getBody());
	$sql = "INSERT INTO new_users (full_name, email, password) VALUES (:full_name, :email, :password)";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("full_name", $user->full_name);
		$stmt->bindParam("email", $user->email);
		$stmt->bindParam("password", $user->password);
		$stmt->execute();
		$user->id = $db->lastInsertId();
		$db = null;
		echo json_encode($user); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function getUsers() {
	$sql = "select * FROM new_users ORDER BY id";
	try {
		$db = getConnection();
		$stmt = $db->query($sql);  
		$wines = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($wines);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function getUser($id) {
	$sql = "select * FROM new_users WHERE id=".$id." ORDER BY id";
	try {
		$db = getConnection();
		$stmt = $db->query($sql);  
		$wines = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($wines);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}


function updateUser($id) {
	$request = Slim::getInstance()->request();
	$user = json_decode($request->getBody());
	$sql = "UPDATE new_users SET username=:username, first_name=:first_name, last_name=:last_name, address=:address WHERE id=:id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("username", $user->username);
		$stmt->bindParam("first_name", $user->first_name);
		$stmt->bindParam("last_name", $user->last_name);
		$stmt->bindParam("address", $user->address);
		$stmt->bindParam("id", $id);
		$stmt->execute();
		$db = null;
		echo json_encode($user); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function deleteUser($id) {
	$sql = "DELETE FROM new_users WHERE id=".$id;
	try {
		$db = getConnection();
		$stmt = $db->query($sql);  
		$db = null;
		echo '{"success":'.true.'}'; 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function getConnection() {

	$dbhost="thindocsdb000.chat5m5hti5d.us-west-2.rds.amazonaws.com";
	$dbuser="ben";
	$dbpass="ben!";
	$dbname="dbtuts";	
	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbh;
}

?>