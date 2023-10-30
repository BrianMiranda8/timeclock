<?php
session_start();
include("../models/employee-model.php");

require_once $_SERVER['DOCUMENT_ROOT'] . '/prefs-mysql.php';

$timeclock = new mysqli($server, $username, $password, 'timeclock');

$employee = new Employee($_POST['employeeid'], $timeclock);

$employee->SetSession();

$_SESSION['employee']['employeeid'] = $_POST['employeeid'];

header("Location: ../manager-views/manager-edit-employee.php");
