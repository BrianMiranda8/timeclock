<?php
session_start();

$password = file_get_contents("php://input");

$response = array(
	'message' => '',
	'validation' => false
);


if ($password !== $_SESSION['manager']['password']) {
	$response['message'] = "Invalid Password";
	echo json_encode($response);
	$_SESSION['manager_validation'] = $response['validation'];
	exit();
}
$response['validation'] = true;
$_SESSION['manager_validation'] = $response['validation'];

echo json_encode($response);
exit();
