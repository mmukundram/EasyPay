<?php
/*
** AUTHORS: 	Abdul Azharudeen and Mukundram M 
** DESCRIPTION: Modifies rows in tables
** USAGE:		http://www.server.com:port/insert_record.php?table=TABLENAME&attr1=ATTR1&attr2=ATTR2&attrn=ATTRN
** NOTE:		Supports any number of arguments and tables
**				Only supports equality 
**
*/

/*
** REQUEST FORMAT: 
** {
**	  'table': 'TableName'
**	  'set':
**        'key1': 'new_value1'
**        'key2': 'new_value2'
**    'where':
**        'key1': 'value1'
**        'key2': 'value2'
** }
**
**
*/

function update_record($update_json) {
	$_POST = $update_json;
	$response = array();

	require_once 'db_connect.php';

	$sql = "UPDATE " . $_POST['table'] . " SET ";

	$length = count($_POST["set"]);
	$count = 0;

	foreach($_POST["set"] as $index => $item) {
		$sql = $sql . $index . "='" . $_POST["set"][$index] . "'";
		$count = $count + 1;
		
		if ($count < $length-1) {
			$sql = $sql . ", ";
		}
	}

	$sql = $sql . " WHERE ";
	$length = count($_POST["where"]);
	$count = 0;

	foreach($_POST["where"] as $index => $item) {	
		$sql = $sql . $index . "='" . $_POST["where"][$index] . "'";
		$count = $count + 1;
		
		if($count < $length-1) {
			$sql = $sql . " AND ";
		}	
	}
	$result = mysqli_query($conn, $sql);
	if ($result) {
		$response['statusCode'] = 200;
		$response['message'] = "Record in table " . $_POST['table'] . " updated";
	}
	else {
		$response['statusCode'] = 500;
		$response['message'] = "Unable to update record in table " . $_POST['table'];
	}
	mysqli_close($conn);
	return $response;
}


?>