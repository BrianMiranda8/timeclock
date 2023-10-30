<?php
session_start();
include("../models/employee-model.php");
include("../functions/dynamic-insert.php");
include("../functions/dynamic-update.php");
include("../functions/execute-sql.php");
include('../models/employees-model.php');

require_once $_SERVER['DOCUMENT_ROOT'] . '/prefs-mysql.php';

$_POST['active'] = boolval($_POST['active']);
$conn = new mysqli($server, $username, $password, 'timeclock');
if ($_SESSION['employee']['employeeid'] == '-1') {
    $employees = new Employees($conn);
    $employee = $employees->insertEmployee($_POST);
    //$employee->SetSession();
    header('location: ../manager-views/manager-employee-search.php');
    exit();
}


$employee = new Employee($_SESSION['employee']['employeeid'], $conn);
$response = $employee->updateEmployee($_POST);
//$employee->SetSession();
header("location: ../manager-views/manager-employee-search.php");
exit();
