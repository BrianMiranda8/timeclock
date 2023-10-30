<?php
session_start();
include("./models/employee-model.php");
include("./functions/dynamic-insert.php");
include("./functions/dynamic-update.php");
include("./functions/execute-sql.php");
include('./models/employees-model.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/prefs-mysql.php';

$timeclock = new mysqli($server, $username, $password, 'timeclock');

$employees = new Employees($timeclock);

$employee = $employees->getEmployee($_POST['employeeid']);
$employee->SetSession();

switch ($_POST['area']) {
    case "Time Clock":

        header("location: employee-punch.php");
        break;
    case "View My Time":

        header("location: ./employee-views/employee-find-punch.php");
        break;
    case "Manager Area":
        if (!$employee->isManager()) {
            header("index.php?message=Invalid Credentials");
            exit();
        }

        header('location: ./manager-views/manager-login.php');
        break;
    default:
        break;
}
