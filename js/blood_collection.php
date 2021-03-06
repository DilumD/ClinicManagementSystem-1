<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

$app = new \Slim\App;

$app->get('/blood/{id}',function(Request $request, Response $response){
	header('Content-type: application/json');
	$id = $request->getAttribute('id');
	getBloodData($id);
});

$app->get('/blood',function(Request $request, Response $response){
	getAllData();
});

$app->post('/blood',function(Request $request, Response $response){
	$json = $request->getBody();
	$json = json_decode($json, TRUE);
	updateBloodData($json);
});

function getBloodData($id) {
	$json = array();
	$server_name = "localhost";
	$user_name = "root";
	$password = "Tally456";
	$db_name = "chestclinic";

	$db = new mysqli($server_name, $user_name, $password, $db_name);
	if($db->connect_errno > 0){
		die('Unable to connect to database [' . $db->connect_error . ']');
	}

	$sql_data = 'SELECT spec1 FROM investigation_type WHERE '.
				'patientId = ? AND investigation = ?';
	
	$stmt = $db->prepare($sql_data);
	if($stmt === false) {		
		trigger_error('Wrong SQL: ' . $sql_data . ' Error: ' . $db->error, E_USER_ERROR);
	}

	$sp = "Blood";

	if(!$stmt->bind_param('ss',$id, $sp)) {
		echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	
	if (!$stmt->execute()) {
		echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
	}

	$stmt->bind_result($a);

    while($stmt->fetch()){
    	array_push($json, $a);
    }

    echo(json_encode($json));

	$stmt->close();
}

function updateBloodData($json){
	$server_name = "localhost";
	$user_name = "root";
	$password = "Tally456";
	$db_name = "chestclinic";

	$db = new mysqli($server_name, $user_name, $password, $db_name);
	if($db->connect_errno > 0){
		die('Unable to connect to database [' . $db->connect_error . ']');
	}

	$sql_data = 'UPDATE investigation_type '.
				'SET status = ?,'.
				'sample_index = ? '.
				'WHERE patientId = ? '.
				'AND investigation = ? '.
				'AND spec1 = ?';
	
	$stmt = $db->prepare($sql_data);
	if($stmt === false) {		
		trigger_error('Wrong SQL: ' . $sql_data . ' Error: ' . $db->error, E_USER_ERROR);
	}

	$sp = "Blood";

	if(!$stmt->bind_param('sssss', $json['status'], $json['sample_index'], $json['patientId'], $sp, $json['spec1'])) {
		echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	
	if (!$stmt->execute()) {
		echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
	}

	$stmt->close();
}

function getAllData() {
	$json = array();
	$server_name = "localhost";
	$user_name = "root";
	$password = "Tally456";
	$db_name = "chestclinic";

	$db = new mysqli($server_name, $user_name, $password, $db_name);
	if($db->connect_errno > 0){
		die('Unable to connect to database [' . $db->connect_error . ']');
	}

	$sql_data = 'SELECT DISTINCT investigation.* '.
				'FROM investigation '.
				'JOIN investigation_type '.
				'ON investigation.patientId = investigation_type.patientId '.
				'AND investigation.investigation = investigation_type.investigation '.
				'AND investigation.date = investigation_type.date '.
				'WHERE investigation_type.status = ? '.
				'AND investigation.investigation = ?';
	
	$stmt = $db->prepare($sql_data);
	if($stmt === false) {		
		trigger_error('Wrong SQL: ' . $sql_data . ' Error: ' . $db->error, E_USER_ERROR);
	}

	$pending = "Pending";
	$invest = "Blood";

	if(!$stmt->bind_param('ss', $pending, $invest)) {
		echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	
	if (!$stmt->execute()) {
		echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
	}

	$meta = $stmt->result_metadata();
    while ( $field = $meta->fetch_field() )
    	$parameters[] = &$row[$field->name]; 
 
   call_user_func_array(array($stmt, 'bind_result'), $parameters);

    while ( $stmt->fetch() ) {
        $x = array();
        foreach( $row as $key => $val )
        	$x[$key] = $val;
        $results[] = $x;
    }

    echo json_encode($results);
	$stmt->close();
}

$app->run();

?>