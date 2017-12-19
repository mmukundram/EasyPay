<?php
/*
** AUTHORS: 	Abdul Azharudeen and Mukundram M 
** DESCRIPTION: Adds rows into tables
** USAGE:		http://www.server.com:port/insert_record.php?table=TABLENAME&attr1=ATTR1&attr2=ATTR2&attrn=ATTRN
** NOTE:		Supports any number of arguments and tables
*/

function insert_record($insert_json) {
	$_POST = $insert_json;
	$response = array();
	
	require_once 'db_connect.php';
	
	$sql = "INSERT INTO " . $_POST["table"] . " (";

	$length = count($_POST);
	$count = 0;

	foreach($_POST as $index => $item) {
		if ($index <> "table") {
			$sql = $sql . $index;
			$count = $count + 1;
			
			if ($count < $length -1) {
				$sql = $sql . ', ';
			}
			else {
				$sql = $sql . ') ';
			}
		}
	}

	$sql = $sql . "VALUES('";
	$count = 0;

	foreach($_POST as $index => $item) {
		if ($index <> "table") {
			$sql = $sql . $_POST[$index];
			$count = $count + 1;
			
			if ($count < $length-1) {
				$sql = $sql . "', '";
			}
			else {
				$sql = $sql . "') ";
			}
		}
	}
	$result = mysqli_query($conn, $sql);
	if($result) {
		$response['statusCode'] = 200;
		$response['message'] = "Record added to " . $_POST['table'];
	}
	else {
		$response['status'] = 500;
		$response['message'] = "Could not add record to ". $_POST['table'];
	}
	mysqli_close($conn);
	return $response;
}

?>