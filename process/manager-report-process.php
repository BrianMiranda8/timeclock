<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include('../functions/execute-sql.php');
include('../models/punches-model.php');
include('../models/employee-model.php');

require_once $_SERVER['DOCUMENT_ROOT'] . '/prefs-mysql.php';
$timeclock = new mysqli($server, $username, $password, 'timeclock');

$punchModel = new Punches($timeclock);

$workingEmployees = array();
if ($_POST['employeeid'] == '-1') {
    $workingEmployees = $punchModel->workingEmployees($_POST['startdate'], $_POST['enddate']);
} else {
    $employee = new Employee($_POST['employeeid'], $timeclock);
    $workingEmployees = array($employee->employee());
}

$_SESSION['workingEmployees'] = $workingEmployees;

$params = array('start' => $_POST['startdate'], 'end' => $_POST['enddate']);

$queryString = http_build_query($params);
header("location: ../manager-views/manager-employee-report.php?$queryString");
exit();
