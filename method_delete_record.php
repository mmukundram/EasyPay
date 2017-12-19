<?php
/*
** AUTHORS: 	Abdul Azharudeen and Mukundram M 
** DESCRIPTION: Delete rows from tables
** USAGE:		http://www.server.com:port/insert_record.php?table=TABLENAME&attr1=ATTR1&attr2=ATTR2&attrn=ATTRN
** NOTE:		Supports any number of arguments and tables
*/

/*
** REQUEST FORMAT
** {
**      'table': 'TableName'
**   	'key1': 'value1'
**		'key2': 'value2'
** }
*/

function delete_record($delete_json) {
	$_POST = $delete_json;
	$response = array();

	require_once 'db_connect.php';

	$sql = "DELETE FROM " . $_POST["table"];

	$length = count($_POST);
	$count = 0;

	if ($length <> 0) {
		$sql = $sql . " WHERE ";
		foreach($_POST as $index => $item) {
			if ($index <> "table") {
				$sql = $sql . $index . "='" . $_POST[$index] . "'";
				$count = $count + 1;
				
				if($count <> $length-1) {
					$sql = $sql . " AND ";
				}
			}
		}
	}

	$result = mysqli_query($conn, $sql);

	if ($result) {
		$response['statusCode'] = 200;
		$response['message'] = "Record deleted from " . $_POST['table'];
		} else {
			$response['statusCode'] = 500;
			$response['message'] = "Could not delete record from " . $_POST['table'];
		}

	mysqli_close($conn);
	return $response;
}
?>