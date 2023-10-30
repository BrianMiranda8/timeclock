<?php
session_start();
include('../models/punches-model.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/prefs-mysql.php';
$data = json_decode(file_get_contents("php://input"), true);

$method = $_SERVER['REQUEST_METHOD'];

$conn = new mysqli($server, $username, $password, 'timeclock');

$punch = new Punches($conn);
$start = date('Y-m-d H:i:s', strtotime($data['in_time']));
$end = ($data['out_time'] == "") ? null : date('Y-m-d H:i:s', strtotime($data['out_time']));

switch ($method) {
    case "POST":
        $punch->addPunch($data['employeeid'], $data['department'], $start, $end);
        break;
    case "PUT":

        $punch->editPunch($data['punchid'], $start, $end, $data['department']);
        break;
    case "DELETE":
        $punch->deletePunch($data['punchid']);
        break;
    default:
        echo "";
}
