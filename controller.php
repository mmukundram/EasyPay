<?php
/*
** AUTHORS: 	Abdul Azharudeen and Mukundram M 
** DESCRIPTION: Controller to access various methods
*/

/*
** REQUEST FORMAT
** {
** 		'method': 'methodName',
**		'request_object': 
**			{
**				...
**			}
** }
*/

require_once "method_delete_record.php";
require_once "method_insert_record.php";
require_once "method_query_table.php";
require_once "method_query_user_transaction.php";
require_once "method_query_user_user_transaction.php";
require_once "method_update_record.php";

$response = array();

$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

if (array_key_exists('method',$_POST)) {
	if ($_POST['method'] == 'delete_record') {
		$response = delete_record($_POST['request_object']);
	}
	elseif ($_POST['method'] == 'insert_record') {
		$response = insert_record($_POST['request_object']);
	}
	elseif ($_POST['method'] == 'query_table') {
		$response = query_table($_POST['request_object']);
	}
	elseif ($_POST['method'] == 'query_user_transaction') {
		$response = query_user_transaction($_POST['request_object']);
	}
	elseif ($_POST['method'] == 'query_user_user_transaction') {
		$response = query_user_user_transaction($_POST['request_object']);
	}
	elseif ($_POST['method'] == 'update_record') {
		$response = update_record($_POST['request_object']);
	}
	else {
		$response['statusCode'] = 500;
		$response['message'] = "Unknown request";
	}
}

?>