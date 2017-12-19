<?php

/*
** AUTHORS: 	Abdul Azharudeen and Mukundram M 
** DESCRIPTION: Responds with all transactions of a user with another user, along with the net outgoing and incoming amounts
** NOTE:		Supports any number of arguments and tables
*/

/*
** REQUEST OBJECT FORMAT
** { 
**  	"userPhoneNumber": phoneNumber1,
**		"otherUserPhoneNumber": phoneNumber2
** }
**
** response['value'] = {
**		"incoming_amount": amount1,
**		"outgoing_amount": amount2,
** 		"incoming": {
**			{
**				"amount": amount,
**				"transactionGroupsetID": ID
**			}
**		}
**				
**		"outgoing": {
**			{
**				"amount": amount,
**				"transactionGroupsetID": ID	
**			}
**		}
** }
*/

function query_user_user_transaction($query_json) {
	$_POST = $query_json;
	$response = array();
	
	require_once 'db_connect.php';
	
	$outgoing_transaction_sql = "SELECT amount, transactionGroupsetID FROM transaction WHERE fromUserPhoneNumber=" . $_POST['userPhoneNumber'] . " AND toUserPhoneNumber=" . $_POST['otherUserPhoneNumber'];
	$incoming_transaction_sql = "SELECT amount, transactionGroupsetID FROM transaction WHERE toUserPhoneNumber=" . $_POST['userPhoneNumber'] . " AND fromUserPhoneNumber=" . $_POST['otherUserPhoneNumber'];
	
	$outgoing_transaction = mysqli_query($conn, $outgoing_transaction_sql);
	$incoming_transaction = mysqli_query($conn, $incoming_transaction_sql);
	
	if(($incoming_transaction && mysqli_num_rows($incoming_transaction)>0) || ($outgoing_transaction && mysqli_num_rows($outgoing_transaction)>0)) {
		$response['statusCode'] = 200;
		$response['message'] = "Transactions retrieved";
		$response['value'] = array();
		$response['value']['incoming'] = array();
		$response['value']['outgoing'] = array();
		$incoming_amount = 0;
		$outgoing_amount = 0;
		while($row = mysqli_fetch_array($incoming_transaction)) {
			$key_switch = 0;
			$temp = array();
			foreach($row as $key => $value) {
				if ($key_switch === 1) {
					$temp[$key] = $row[$key];
					$key_switch = 0;
				}
				else {
					$key_switch = 1;
				}
				if($key === "amount") {
					$incoming_amount = $incoming_amount + $row[$key];
				}
			}
			array_push($response['value']['incoming'], $temp);
		}
		while($row = mysqli_fetch_array($outgoing_transaction)) {
			$key_switch = 0;
			$temp = array();
			foreach($row as $key => $value) {
				if($key_switch === 1) {
					$temp[$key] = $row[$key];
					$key_switch = 0;
				}
				else {
					$key_switch = 1;
				}
				if($key === "amount") {
					$outgoing_amount = $outgoing_amount + $row[$key];
				}
			}
			array_push($response['value']['outgoing'], $temp);
		}
		
		$response['value']['incoming_amount'] = $incoming_amount;
		$response['value']['outgoing_amount'] = $outgoing_amount;
	}
	else {
		$response['statusCode'] = 500;
		$response['message'] = "Could not retrieve transactions";
	}
	mysqli_close($conn);
	return $response;
}


?>