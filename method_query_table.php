<?php
/*
** AUTHORS: 	Abdul Azharudeen and Mukundram M 
** DESCRIPTION: Queries from tables
** USAGE:		http://www.server.com:port/query_table.php?table=TABLENAME&attr1=ATTR1&attr2=ATTR2&attrn=ATTRN
** NOTE:		Supports any number of arguments and tables
*/

/*
** REQUEST OBJECT FORMAT
** {
** 		"table": 	"tableName",
**		"select": 	{
** 			"column1": "true",
**			"column2": "true"
**		}
**		"key1":		"value1"
**		"key2": 	"value2"	
** }
** response['value'] = {
**		{
**			"column1": "value1",
**			"column2": "value2"
**		}
**		{
**			"column1": "value3",
**			"column2": "value4"
**		}
** }
*/

function query_table($query_json) {
	$_POST = $query_json;
	$response = array();
	
	require_once 'db_connect.php';
	
	$sql = "SELECT ";
	
	$select_length = count($_POST['select']);
	$select_count = 0;
	foreach($_POST['select'] as $key => $value) {
		if ($select_count > 0) {
			$sql = $sql . ", " . $key;
		}
		else {
			$sql = $sql . $key;
		}
		$select_count++;
	}
	

	$sql = $sql . " FROM " . $_POST['table'];

	$length = count($_POST);
	$count = 0;

	if ($length > 1) {
		$sql = $sql . " WHERE ";

		foreach($_POST as $index => $item) {
			if($index <> "table" && $index <> "select") {
				$sql = $sql . $index;
				$sql = $sql . " = '";
				$sql = $sql . $_POST[$index];
				$sql = $sql . "'";
				$count = $count + 1;
				if($count <> $length-2) {
					$sql = $sql . " AND ";
				}
			}
		}
	}
	$result = mysqli_query($conn, $sql);
	if($result && mysqli_num_rows($result)>0) {
		$response['statusCode'] = 200;
		$response['message'] = "Query successful";
		$response['value'] = array();
		while($row = mysqli_fetch_array($result)) {
			$temp = array();
			$key_switch = 0;
			foreach($row as $key => $value) {
				if ($key_switch === 1) {
					$temp[$key] = $row[$key];
					$key_switch = 0;
				}
				else {
					$key_switch = 1;
				}
			}
			array_push($response['value'], $temp);
		}
	}
	mysqli_close($conn);
	return $response;
}

?>